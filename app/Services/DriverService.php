<?php

namespace App\Services;

use App\Models\Driver;
use Illuminate\Http\Request;
use App\Http\Traits\FileUploadTrait; // Import trait

// Import Laravel Facades
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File; // For deleting files

/**
 * Handles all business logic related to Drivers.
 */
class DriverService
{
    // Use the trait to get access to $this->handlePhotoUpload()
    use FileUploadTrait;

    // Note: The old constructor and duplicated file upload methods are removed.
    // We use the 'Log' facade and 'FileUploadTrait'.

    /**
     * Validate and create a new driver.
     *
     * @param Request $request
     * @return array Service response array
     */
    public function createDriver(Request $request)
    {
        $data = $request->all();
        $validation = $this->validate($data);

        if (!empty($validation['errors'])) {
            return ['success' => false, 'message' => 'Validation failed', 'data' => $validation, 'status_code' => 422];
        }
        
        // PUTHU MAATRAM: Generate App Username and Password automatically.
        // App Username will be the driver's email.
        $data['app_username'] = $data['email'];
        
        // Use the new helper function to generate the password
        $data['app_password'] = $this->generateDriverPassword($data['name']);
        
        // 1. Handle Photo Upload
        try {
            $uploadResult = $this->handlePhotoUpload($request, 'uploads/drivers', $data['name']);
            if (isset($uploadResult['error'])) {
                return ['success' => false, 'message' => $uploadResult['error'], 'data' => null, 'status_code' => 400];
            }
            $data['photo_path'] = $uploadResult['path'];
        } catch (\Exception $e) {
            Log::error('Driver photo upload failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'File upload failed: ' . $e->getMessage(), 'data' => null, 'status_code' => 500];
        }

        // 2. Create Driver
        try {
            // NOTE: The 'app_password' field is assumed to be handled by the Driver Model's mutator 
            // (e.g., setAppPasswordAttribute) to be hashed before saving to the database.
            Driver::create($data); // Assumes 'photo_path' is fillable in Model
            return ['success' => true, 'message' => 'Driver created successfully. App credentials auto-generated.', 'data' => null, 'status_code' => 201];
        } catch (\Exception $e) {
            Log::error('Driver creation failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Database error: Could not create driver.', 'data' => null, 'status_code' => 500];
        }
    }

    /**
     * Validate and update an existing driver.
     *
     * @param Request $request
     * @return array Service response array
     */
    public function updateDriver(Request $request)
    {
        $data = $request->all();
        $driverId = $data['id'];
        $driver = Driver::find($driverId);

        if (!$driver) {
            return ['success' => false, 'message' => 'Driver not found', 'data' => null, 'status_code' => 404];
        }

        $validation = $this->validate($data, true, $driverId);
        if (!empty($validation['errors'])) {
            return ['success' => false, 'message' => 'Validation failed', 'data' => $validation, 'status_code' => 422];
        }

        // 1. Handle Photo Upload (if new photo provided)
        if ($request->hasFile('photo')) {
            try {
                $uploadResult = $this->handlePhotoUpload($request, 'uploads/drivers', $data['name'], $driver->photo_path);
                if (isset($uploadResult['error'])) {
                    return ['success' => false, 'message' => $uploadResult['error'], 'data' => null, 'status_code' => 400];
                }
                $data['photo_path'] = $uploadResult['path'];
            } catch (\Exception $e) {
                Log::error('Driver photo update failed', ['error' => $e->getMessage()]);
                return ['success' => false, 'message' => 'File upload failed: ' . $e->getMessage(), 'data' => null, 'status_code' => 500];
            }
        }

        // 2. Update Driver
        try {
            // PUTHU MAATRAM: Logic to handle conditional verification reset
            
            // Password update is not done here
            unset($data['app_password']);

            // Fill the model with new data
            $driver->fill($data);
            
            $message = ''; // Message container

            // Check if email was changed
            if ($driver->isDirty('email')) {
                // Email changed: Update username and reset verification
                $driver->app_username = $driver->email;
                $driver->is_verified = false;
                $driver->otp_code = null;
                $driver->otp_expires_at = null;
                $driver->otp_attempt_count = 0;
                $driver->otp_sent_count = 0;
                $driver->otp_last_sent_at = null;
                $driver->otp_locked_until = null;
                
                $message = 'Driver updated. Email changed, verification is required.';
            
            } else if (!$driver->isDirty() && !$driver->isDirty('photo_path')) {
                // No changes were made at all
                return ['success' => true, 'message' => 'No changes detected.', 'data' => null, 'status_code' => 200];
            
            } else {
                // Other fields (name, phone, photo) changed, but email did not.
                // Do NOT reset verification.
                $message = 'Driver updated successfully.';
            }

            $driver->save();

            return ['success' => true, 'message' => $message, 'data' => null, 'status_code' => 200];
            
        } catch (\Exception $e) {
            Log::error('Driver update failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Database error: Could not update driver.', 'data' => null, 'status_code' => 500];
        }
    }

    /**
     * Delete a driver and their photo.
     *
     * @param Request $request
     * @return array Service response array
     */
    public function deleteDriver(Request $request)
    {
        $driverId = $request->input('id');
        $driver = Driver::find($driverId);

        if (!$driver) {
            return ['success' => false, 'message' => 'Driver not found', 'status_code' => 404];
        }
        
        // Check if driver is assigned to a bus
        if (!empty($driver->bus_plate)) {
             return ['success' => false, 'message' => 'Cannot delete: Driver is still assigned to a bus (' . $driver->bus_plate . ').', 'status_code' => 409]; // 409 Conflict
        }

        try {
            // Delete photo file
            if ($driver->photo_path) {
                // Use Laravel's public_path() and File facade
                $server_existing_path = public_path($driver->photo_path);
                if (File::exists($server_existing_path)) {
                    File::delete($server_existing_path);
                }
            }
            
            // Delete from database
            $driver->delete();
            
            return ['success' => true, 'message' => 'Driver deleted successfully.', 'status_code' => 200];
        } catch (\Exception $e) {
            Log::error('Driver deletion failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Error deleting driver.', 'status_code' => 500];
        }
    }

    /**
     * Reset a driver's password and set them as unverified.
     *
     * @param Request $request
     * @return array Service response array
     */
    public function resetPassword(Request $request)
    {
        $driverId = $request->input('id');
        $driver = Driver::find($driverId);

        if (!$driver) {
            return ['success' => false, 'message' => 'Driver not found', 'status_code' => 404];
        }

        try {
            // Generate a new random password
            $newPassword = $this->generateDriverPassword($driver->name);
            
            // Save the new password (it will be auto-hashed by the model)
            $driver->app_password = $newPassword;
            
            // Set as unverified and clear all OTP data
            $driver->is_verified = false;
            $driver->otp_code = null;
            $driver->otp_expires_at = null;
            $driver->otp_attempt_count = 0;
            $driver->otp_sent_count = 0;
            $driver->otp_last_sent_at = null;
            $driver->otp_locked_until = null;
            
            $driver->save();

            // Store the plain-text password in the session for the email step.
            // THIS IS TEMPORARY. We'll use this in the AuthController.
            // Note: In a real-world queue-based email system, we'd pass this differently.
            session(['temp_plain_password_for_' . $driver->id => $newPassword]);

            return [
                'success' => true, 
                'message' => 'Password reset successfully. Driver is unverified and requires OTP activation.', 
                'status_code' => 200
            ];

        } catch (\Exception $e) {
            Log::error('Driver password reset failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Error resetting password.', 'status_code' => 500];
        }
    }

    /**
     * Private helper to validate driver data.
     *
     * @param array $data
     * @param bool $isUpdate
     * @param int|null $driverId
     * @return array
     */
    private function validate($data, $isUpdate = false, $driverId = null)
    {
        $errors = [];
        
        if (empty($data['name'])) $errors['name'] = 'Name is required';
        if (empty($data['email'])) $errors['email'] = 'Email is required';
        
        // PUTHU MAATRAM: App Username and App Password required validation are removed
        // because they are now auto-generated in createDriver().
        /*
        if (empty($data['app_username'])) $errors['app_username'] = 'App Username is required';
        if (!$isUpdate && empty($data['app_password'])) $errors['app_password'] = 'App Password is required';
        */

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        }

        // Check unique constraints
        $queryEmail = Driver::where('email', $data['email']);
        if ($isUpdate) $queryEmail->where('id', '!=', $driverId);
        if ($queryEmail->exists()) $errors['email'] = 'Email already taken';

        // PUTHU MAATRAM: app_username is now the email, so a separate unique check
        // for app_username is not needed, as email unique check handles it.
        /*
        $queryUsername = Driver::where('app_username', $data['app_username']);
        if ($isUpdate) $queryUsername->where('id', '!=', $driverId);
        if ($queryUsername->exists()) $errors['app_username'] = 'App Username already taken';
        */
        
        return ['errors' => $errors];
    }

    /**
     * Generate a new random password for a driver.
     * Format: Name (no spaces) + @ + 4 random digits.
     *
     * @param string $driverName The driver's full name.
     * @return string The plain-text generated password.
     */
    private function generateDriverPassword(string $driverName): string
    {
        // Remove spaces and special characters from name
        $name_part = preg_replace('/[^a-zA-Z0.9]/', '', str_replace(' ', '', $driverName));
        if (empty($name_part)) {
            $name_part = 'Driver';
        }
        
        // Generate 4 random digits
        $random_digits = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        return $name_part . '@' . $random_digits;
    }
}