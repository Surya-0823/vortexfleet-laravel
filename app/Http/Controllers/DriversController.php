<?php

namespace App\Http\Controllers; 

use App\Http\Controllers\Controller; 
use App\Models\Driver; 
use Illuminate\Http\Request; 
use PHPMailer\PHPMailer\Exception; // For catching email errors
use App\Services\DriverService; // We will migrate this service later
use App\Http\Traits\EmailDispatchTrait; // Import the email trait

// Import Laravel Facades
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

/**
 * Handles all logic for the Drivers Management page (CRUD, OTP, Status).
 */
class DriversController extends Controller 
{
    // Use the trait so we can call $this->sendEmail()
    use EmailDispatchTrait;

    /**
     * @var DriverService
     */
    protected $driverService; // Variable to hold the service

    /**
     * Constructor to initialize the service.
     * Auth is no longer handled here.
     */
    public function __construct()
    {
        // We create an instance of the service
        $this->driverService = new DriverService(); 

        // The old 'checkAuth()' logic is REMOVED from here.
        // We will protect this route in 'routes/web.php' using middleware.
    }

    /**
     * Display the drivers management page.
     *
     * @return \Illuminate\View\View
     */
    public function index() 
    {
        try {
            $page_title = "Drivers Management";
            $page_subtitle = "Manage your drivers and their information";
            $page_css = '/assets/css/pages/drivers.css';
            $page_js = '/assets/js/drivers.js';
            
            try {
                $drivers = Driver::all();
                if (is_null($drivers)) {
                    $drivers = [];
                }
            } catch (\Exception $e) {
                Log::error('Database error in DriversController::index()', ['error' => $e->getMessage()]);
                $drivers = [];
            }

            // Use Laravel's View facade
            return View::make('pages.drivers', [
                'page_title' => $page_title,
                'page_subtitle' => $page_subtitle,
                'page_css' => $page_css,
                'page_js' => $page_js,
                'drivers' => $drivers 
            ]);
        } catch (\Exception $e) {
            Log::error('Error in DriversController::index()', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            // Re-throw the exception to let Laravel handle it
            throw $e;
        }
    }
    
    /**
     * Call the service to create a new driver.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request) {
        
        // Call the service to handle the logic
        $result = $this->driverService->createDriver($request);

        // Use Laravel's response()->json()
        return response()->json(
            [
                'success' => $result['success'], 
                'message' => $result['message'], 
                'errors' => $result['data']['errors'] ?? null
            ], 
            $result['status_code']
        );
    }
    
    
    /**
     * Call the service to update an existing driver.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request) {
        
        // Call the service to handle the logic
        $result = $this->driverService->updateDriver($request);

        // Use Laravel's response()->json()
        return response()->json(
            [
                'success' => $result['success'], 
                'message' => $result['message'], 
                'errors' => $result['data']['errors'] ?? null
            ], 
            $result['status_code']
        );
    }
    
    /**
     * Call the service to delete a driver.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request) {
        
        // Call the service to handle the logic
        $result = $this->driverService->deleteDriver($request);

        // Use Laravel's response()->json()
        return response()->json(
            [
                'success' => $result['success'], 
                'message' => $result['message']
            ], 
            $result['status_code']
        );
    }

    /**
     * Update a driver's verification status (set to Not Verified).
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request)
    {
        $driverId = $request->input('id');
        $newStatus = $request->input('is_verified'); 

        // This logic only handles 'de-verifying'
        if ($newStatus !== '0') {
            return response()->json(['success' => false, 'message' => 'Invalid status update.'], 400);
        }

        try {
            $driver = Driver::find($driverId);
            if ($driver) {
                $driver->is_verified = 0;
                $driver->save();

                return response()->json(['success' => true, 'message' => 'Driver status updated to Not Verified.']);
            }
            return response()->json(['success' => false, 'message' => 'Driver not found.'], 404);
        } catch (\Exception $e) {
            Log::error('Failed to update driver status', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Send a verification OTP to the driver's email.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendOtp(Request $request)
    {
        $driverId = $request->input('driver_id');
        $driver = Driver::find($driverId);
        if (!$driver) {
            return response()->json(['success' => false, 'message' => 'Driver not found.'], 404);
        }

        // Check for lock-out
        if ($driver->otp_locked_until && strtotime($driver->otp_locked_until) > time()) {
            return response()->json(['success' => false, 'message' => 'Too many attempts. Please try again after 24 hours.'], 429);
        }

        $otp_code = rand(100000, 999999);
        $otp_expires = now()->addMinutes(3)->toDateTimeString(); // Use Laravel helper
        
        $subject = 'Your Verification Code for VortexFleet Driver App';
        $body    = 'Hi ' . $driver->name . ',<br><br>Your OTP for driver app verification is: <b>' . $otp_code . '</b><br>This code is valid for 3 minutes.<br><br>Thanks,<br>Team VortexFleet';
        
        try {
            // Call the method from EmailDispatchTrait
            $this->sendEmail($driver->email, $driver->name, $subject, $body);
            
            $driver->otp_code = $otp_code;
            $driver->otp_expires_at = $otp_expires;
            $driver->otp_attempt_count = 0;
            $driver->otp_locked_until = null;
            $driver->save();

            return response()->json(['success' => true, 'message' => 'OTP sent successfully to ' . $driver->email]);

        } catch (Exception $e) {
            Log::error('Failed to send driver OTP', ['error' => $e->getMessage()]);
            if (str_contains($e->getMessage(), 'Email Server Error')) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return response()->json(['success' => false, 'message' => "Email could not be sent. Mailer Error: {$e->getMessage()}"], 500);
        }
    }

    /**
     * Verify the OTP entered by the user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyOtp(Request $request)
    {
        $driverId = $request->input('driver_id');
        $otp_from_user = $request->input('otp_code');

        $driver = Driver::find($driverId);
        if (!$driver) {
            return response()->json(['success' => false, 'message' => 'Driver not found.'], 404);
        }

        if ($driver->otp_locked_until && strtotime($driver->otp_locked_until) > time()) {
            return response()->json(['success' => false, 'message' => 'Account locked. Try again after 24 hours.'], 429);
        }

        if (!$driver->otp_code || !$driver->otp_expires_at) {
            return response()->json(['success' => false, 'message' => 'OTP has not been generated. Please request a new code.'], 400);
        }

        if (Carbon::parse($driver->otp_expires_at)->isPast()) {
            return response()->json(['success' => false, 'message' => 'OTP has expired. Please resend.'], 400);
        }

        if ($driver->otp_code == $otp_from_user) {
            $driver->is_verified = 1;
            $driver->otp_code = null;
            $driver->otp_expires_at = null;
            $driver->otp_attempt_count = 0;
            $driver->otp_locked_until = null;
            $driver->save();

            return response()->json(['success' => true, 'message' => 'Verification successful!']);
        
        } else {
            $attempts = $driver->otp_attempt_count + 1;
            
            if ($attempts >= 3) {
                $locked_until = now()->addHours(24)->toDateTimeString(); // Use Laravel helper
                $driver->otp_attempt_count = $attempts;
                $driver->otp_locked_until = $locked_until;
                $driver->save();
                
                return response()->json(['success' => false, 'message' => 'Invalid OTP. Account locked for 24 hours due to 3 failed attempts.'], 429);
            } else {
                $driver->otp_attempt_count = $attempts;
                $driver->save();
                
                $attempts_left = 3 - $attempts;
                return response()->json(['success' => false, 'message' => 'Invalid OTP. ' . $attempts_left . ' attempt(s) remaining.'], 400);
            }
        }
    }
    
    /**
     * Reset the driver's app password.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        $driverId = $request->input('id');
        $driver = Driver::find($driverId);
        
        if (!$driver) {
            return response()->json(['success' => false, 'message' => 'Driver not found.'], 404);
        }

        try {
            $new_password_plain = substr(bin2hex(random_bytes(3)), 0, 6);
            
            $driver->app_password = $new_password_plain;
            $driver->otp_locked_until = null;
            $driver->otp_attempt_count = 0;
            $driver->save();
            
            return response()->json([
                'success' => true, 
                'message' => 'Password reset successfully!',
                'new_password' => $new_password_plain // Send new password back
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to reset driver password', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}