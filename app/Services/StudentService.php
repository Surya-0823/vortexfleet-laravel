<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Route;
use Illuminate\Http\Request;
use App\Http\Traits\FileUploadTrait; // Import trait
use App\Http\Traits\EmailDispatchTrait; // Import trait
use PHPMailer\PHPMailer\Exception; // For catching email errors

// Import Laravel Facades
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File; // For deleting files
use Illuminate\Support\Carbon;

/**
 * Handles all business logic related to Students.
 */
class StudentService
{
    // Use the traits to get access to $this->handlePhotoUpload() and $this->sendEmail()
    use FileUploadTrait, EmailDispatchTrait;

    // Note: The old constructor that injected a logger is removed.
    // We use the 'Log' facade directly.

    /**
     * Validate and create a new student.
     *
     * @param Request $request
     * @return array Service response array
     */
    public function createStudent(Request $request)
    {
        $data = $request->all();
        $validation = $this->validate($data);

        if (!empty($validation['errors'])) {
            return ['success' => false, 'message' => 'Validation failed', 'data' => $validation, 'status_code' => 422];
        }

        // 1. Handle Photo Upload
        try {
            $uploadResult = $this->handlePhotoUpload($request, 'uploads/students', $data['name']);
            if (isset($uploadResult['error'])) {
                return ['success' => false, 'message' => $uploadResult['error'], 'data' => null, 'status_code' => 400];
            }
            $data['photo_path'] = $uploadResult['path'];
        } catch (\Exception $e) {
            Log::error('Student photo upload failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'File upload failed: ' . $e->getMessage(), 'data' => null, 'status_code' => 500];
        }

        // 2. Create Student
        try {
            Student::create($data); // Assumes 'photo_path' is fillable in Model
            return ['success' => true, 'message' => 'Student created successfully.', 'data' => null, 'status_code' => 201];
        } catch (\Exception $e) {
            Log::error('Student creation failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Database error: Could not create student.', 'data' => null, 'status_code' => 500];
        }
    }

    /**
     * Validate and update an existing student.
     *
     * @param Request $request
     * @return array Service response array
     */
    public function updateStudent(Request $request)
    {
        $data = $request->all();
        $studentId = $data['id'];
        $student = Student::find($studentId);

        if (!$student) {
            return ['success' => false, 'message' => 'Student not found', 'data' => null, 'status_code' => 404];
        }

        $validation = $this->validate($data, true, $studentId);
        if (!empty($validation['errors'])) {
            return ['success' => false, 'message' => 'Validation failed', 'data' => $validation, 'status_code' => 422];
        }

        // 1. Handle Photo Upload (if new photo provided)
        if ($request->hasFile('photo')) {
            try {
                $uploadResult = $this->handlePhotoUpload($request, 'uploads/students', $data['name'], $student->photo_path);
                if (isset($uploadResult['error'])) {
                    return ['success' => false, 'message' => $uploadResult['error'], 'data' => null, 'status_code' => 400];
                }
                $data['photo_path'] = $uploadResult['path'];
            } catch (\Exception $e) {
                Log::error('Student photo update failed', ['error' => $e->getMessage()]);
                return ['success' => false, 'message' => 'File upload failed: ' . $e->getMessage(), 'data' => null, 'status_code' => 500];
            }
        }

        // 2. Update Student
        try {
            if (array_key_exists('app_password', $data) && $data['app_password'] === '') {
                unset($data['app_password']);
            }

            $student->update($data); // Assumes fields are fillable
            return ['success' => true, 'message' => 'Student updated successfully.', 'data' => null, 'status_code' => 200];
        } catch (\Exception $e) {
            Log::error('Student update failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Database error: Could not update student.', 'data' => null, 'status_code' => 500];
        }
    }

    /**
     * Delete a student and their photo.
     *
     * @param Request $request
     * @return array Service response array
     */
    public function deleteStudent(Request $request)
    {
        $studentId = $request->input('id');
        $student = Student::find($studentId);

        if (!$student) {
            return ['success' => false, 'message' => 'Student not found', 'status_code' => 404];
        }

        try {
            // Delete photo file
            if ($student->photo_path) {
                // Use Laravel's public_path() and File facade
                $server_existing_path = public_path($student->photo_path);
                if (File::exists($server_existing_path)) {
                    File::delete($server_existing_path);
                }
            }
            
            // Delete from database
            $student->delete();
            
            return ['success' => true, 'message' => 'Student deleted successfully.', 'status_code' => 200];
        } catch (\Exception $e) {
            Log::error('Student deletion failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Error deleting student.', 'status_code' => 500];
        }
    }

    /**
     * Update a student's verification status (set to Not Verified).
     *
     * @param Request $request
     * @return array Service response array
     */
    public function updateStudentStatus(Request $request)
    {
        $studentId = $request->input('id');
        $newStatus = $request->input('is_verified');

        if ($newStatus !== '0') {
            return ['success' => false, 'message' => 'Invalid status update.', 'status_code' => 400];
        }
        
        $student = Student::find($studentId);
        if ($student) {
            $student->is_verified = 0;
            $student->save();
            return ['success' => true, 'message' => 'Student status updated to Not Verified.', 'status_code' => 200];
        }
        return ['success' => false, 'message' => 'Student not found.', 'status_code' => 404];
    }

    /**
     * Send a verification OTP to the student's email.
     *
     * @param Request $request
     * @return array Service response array
     */
    public function sendStudentOtp(Request $request)
    {
        $studentId = $request->input('student_id');
        $student = Student::find($studentId);
        if (!$student) {
            return ['success' => false, 'message' => 'Student not found.', 'status_code' => 404];
        }

        if ($student->otp_locked_until && strtotime($student->otp_locked_until) > time()) {
            return ['success' => false, 'message' => 'Too many attempts. Please try again after 24 hours.', 'status_code' => 429];
        }

        $otp_code = rand(100000, 999999);
        $otp_expires = now()->addMinutes(3)->toDateTimeString();
        
        $subject = 'Your Verification Code for VortexFleet Student App';
        $body    = 'Hi ' . $student->name . ',<br><br>Your OTP for student app verification is: <b>' . $otp_code . '</b><br>This code is valid for 3 minutes.<br><br>Thanks,<br>Team VortexFleet';
        
        try {
            // Use trait method
            $this->sendEmail($student->email, $student->name, $subject, $body);
            
            $student->otp_code = $otp_code;
            $student->otp_expires_at = $otp_expires;
            $student->otp_attempt_count = 0;
            $student->otp_locked_until = null;
            $student->save();

            return ['success' => true, 'message' => 'OTP sent successfully to ' . $student->email, 'status_code' => 200];

        } catch (Exception $e) {
            Log::error('Failed to send student OTP', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => "Email could not be sent. Mailer Error: {$e->getMessage()}", 'status_code' => 500];
        }
    }

    /**
     * Verify the OTP entered by the student.
     *
     * @param Request $request
     * @return array Service response array
     */
    public function verifyStudentOtp(Request $request)
    {
        $studentId = $request->input('student_id');
        $otp_from_user = $request->input('otp_code');

        $student = Student::find($studentId);
        if (!$student) {
            return ['success' => false, 'message' => 'Student not found.', 'status_code' => 404];
        }

        if ($student->otp_locked_until && strtotime($student->otp_locked_until) > time()) {
            return ['success' => false, 'message' => 'Account locked. Try again after 24 hours.', 'status_code' => 429];
        }

        if (!$student->otp_code || !$student->otp_expires_at) {
            return ['success' => false, 'message' => 'OTP has not been generated. Please request a new code.', 'status_code' => 400];
        }

        if (Carbon::parse($student->otp_expires_at)->isPast()) {
            return ['success' => false, 'message' => 'OTP has expired. Please resend.', 'status_code' => 400];
        }

        if ($student->otp_code == $otp_from_user) {
            $student->is_verified = 1;
            $student->otp_code = null;
            $student->otp_expires_at = null;
            $student->otp_attempt_count = 0;
            $student->otp_locked_until = null;
            $student->save();
            return ['success' => true, 'message' => 'Verification successful!', 'status_code' => 200];
        } else {
            $attempts = $student->otp_attempt_count + 1;
            
            if ($attempts >= 3) {
                $locked_until = now()->addHours(24)->toDateTimeString();
                $student->otp_attempt_count = $attempts;
                $student->otp_locked_until = $locked_until;
                $student->save();
                return ['success' => false, 'message' => 'Invalid OTP. Account locked for 24 hours.', 'status_code' => 429];
            } else {
                $student->otp_attempt_count = $attempts;
                $student->save();
                $attempts_left = 3 - $attempts;
                return ['success' => false, 'message' => 'Invalid OTP. ' . $attempts_left . ' attempt(s) remaining.', 'status_code' => 400];
            }
        }
    }
    
    /**
     * Reset the student's app password.
     *
     * @param Request $request
     * @return array Service response array
     */
    public function resetStudentPassword(Request $request)
    {
        $studentId = $request->input('id');
        $student = Student::find($studentId);
        
        if (!$student) {
            return ['success' => false, 'message' => 'Student not found.', 'data' => null, 'status_code' => 404];
        }

        try {
            $new_password_plain = substr(bin2hex(random_bytes(3)), 0, 6);
            
            $student->app_password = $new_password_plain;
            $student->otp_locked_until = null;
            $student->otp_attempt_count = 0;
            $student->save();
            
            $responseData = ['new_password' => $new_password_plain];
            return ['success' => true, 'message' => 'Password reset successfully!', 'data' => $responseData, 'status_code' => 200];

        } catch (\Exception $e) {
            Log::error('Student password reset failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage(), 'data' => null, 'status_code' => 500];
        }
    }

    /**
     * Private helper to validate student data.
     *
     * @param array $data
     * @param bool $isUpdate
     * @param int|null $studentId
     * @return array
     */
    private function validate($data, $isUpdate = false, $studentId = null)
    {
        $errors = [];
        
        if (empty($data['name'])) $errors['name'] = 'Name is required';
        if (empty($data['email'])) $errors['email'] = 'Email is required';
        if (empty($data['app_username'])) $errors['app_username'] = 'App Username is required';
        if (!$isUpdate && empty($data['app_password'])) $errors['app_password'] = 'App Password is required';
        if (empty($data['route_name'])) $errors['route_name'] = 'Route is required';

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        }

        // Check unique constraints
        $queryEmail = Student::where('email', $data['email']);
        if ($isUpdate) $queryEmail->where('id', '!=', $studentId);
        if ($queryEmail->exists()) $errors['email'] = 'Email already taken';

        $queryUsername = Student::where('app_username', $data['app_username']);
        if ($isUpdate) $queryUsername->where('id', '!=', $studentId);
        if ($queryUsername->exists()) $errors['app_username'] = 'App Username already taken';

        // Check if route exists
        if (!Route::where('name', $data['route_name'])->exists()) {
            $errors['route_name'] = 'Selected route does not exist';
        }
        
        return ['errors' => $errors];
    }
}