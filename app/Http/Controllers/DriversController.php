<?php

namespace App\Http\Controllers; 

use App\Http\Controllers\Controller; 
use App\Models\Driver; 
use Illuminate\Http\Request; 
use PHPMailer\PHPMailer\Exception; // For catching email errors
use App\Services\DriverService; // Use the Driver Service
use App\Http\Traits\EmailDispatchTrait; // Import the email trait

// Import Laravel Facades
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

/**
 * Handles all logic for the Drivers Management page (CRUD, OTP, Status).
 * GNU Standard Comments
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
     * Auth is handled by middleware in routes/web.php.
     */
    public function __construct()
    {
        // We create an instance of the service
        $this->driverService = new DriverService(); 
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
                // Get all drivers ordered by creation date
                $drivers = Driver::orderBy('created_at', 'desc')->get();
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
     * (Called via AJAX)
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
     * (Called via AJAX)
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
     * (Called via AJAX)
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
     * (Called via AJAX)
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
                $driver->otp_code = null;
                $driver->otp_expires_at = null;
                $driver->otp_attempt_count = 0;
                $driver->otp_sent_count = 0;
                $driver->otp_last_sent_at = null;
                $driver->otp_locked_until = null;
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
     * (Called via AJAX)
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

        $now = Carbon::now();

        // Check for lock-out
        if ($driver->otp_locked_until && Carbon::parse($driver->otp_locked_until)->isFuture()) {
            return response()->json(['success' => false, 'message' => 'Too many attempts. Please try again after 24 hours.'], 429);
        }

        // Reset daily counter if a new day has started
        if ($driver->otp_last_sent_at && !Carbon::parse($driver->otp_last_sent_at)->isSameDay($now)) {
            $driver->otp_sent_count = 0;
        }

        if ($driver->otp_sent_count >= 3) {
            $driver->otp_locked_until = $now->copy()->addDay();
            $driver->save();

            return response()->json(['success' => false, 'message' => 'OTP request limit reached. Account locked for 24 hours.'], 429);
        }

        $otp_code = rand(100000, 999999);
        $otp_expires = $now->copy()->addMinute()->toDateTimeString();
        
        $subject = 'Your Verification Code for VortexFleet Driver App';
        $body    = 'Hi ' . $driver->name . ',<br><br>Your OTP for driver app verification is: <b>' . $otp_code . '</b><br>This code is valid for 1 minute.<br><br>Thanks,<br>Team VortexFleet';
        
        try {
            // Call the method from EmailDispatchTrait
            $this->sendEmail($driver->email, $driver->name, $subject, $body);
            
            $driver->otp_code = $otp_code;
            $driver->otp_expires_at = $otp_expires;
            $driver->otp_attempt_count = 0;
            $driver->otp_sent_count = $driver->otp_sent_count + 1;
            $driver->otp_last_sent_at = $now;
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
     * On success, send credentials email if a plain-text pass is in session.
     * (Called via AJAX)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyOtp(Request $request)
    {
        $driverId = $request->input('driver_id');
        $otp_from_user = $request->input('otp_code');
        
        // PUTHU MAATRAM: Password-ah session-ku bathila request-la irunthu vaangurom
        $plainPassword = $request->input('plain_password');

        $driver = Driver::find($driverId);
        if (!$driver) {
            return response()->json(['success' => false, 'message' => 'Driver not found.'], 404);
        }

        if ($driver->otp_locked_until && Carbon::parse($driver->otp_locked_until)->isFuture()) {
            return response()->json(['success' => false, 'message' => 'Account locked. Try again after 24 hours.'], 429);
        }

        if (!$driver->otp_code || !$driver->otp_expires_at) {
            return response()->json(['success' => false, 'message' => 'OTP has not been generated. Please request a new code.'], 400);
        }

        if (Carbon::parse($driver->otp_expires_at)->isPast()) {
            return response()->json(['success' => false, 'message' => 'OTP has expired. Please resend.'], 400);
        }

        if ($driver->otp_code == $otp_from_user) {
            // OTP is correct. Verify the driver.
            $driver->is_verified = 1;
            $driver->otp_code = null;
            $driver->otp_expires_at = null;
            $driver->otp_attempt_count = 0;
            $driver->otp_sent_count = 0;
            $driver->otp_last_sent_at = null;
            $driver->otp_locked_until = null;
            $driver->save();

            // PUTHU MAATRAM: Check if a plain-text password was passed from JS
            if ($plainPassword) {
                try {
                    $subject = 'VortexFleet App Credentials';
                    $body = 'Hi ' . $driver->name . ',<br><br>Your account is verified. You can now log in to the driver app.<br><br>' .
                            '<b>Username:</b> ' . $driver->app_username . '<br>' .
                            '<b>Password:</b> ' . $plainPassword . '<br><br>' . // Use the password from request
                            'Thanks,<br>Team VortexFleet';
                    
                    // Use the trait function
                    $this->sendEmail($driver->email, $driver->name, $subject, $body);

                    // PUTHU MAATRAM: Session logic removed
                    // session()->forget('temp_plain_password_for_'G . $driverId);

                    return response()->json(['success' => true, 'message' => 'Verification successful! Credentials sent to driver.']);

                } catch (Exception $e) {
                    Log::error('Failed to send driver credentials email', ['error' => $e->getMessage()]);
                    // Verification was successful, but email failed.
                    return response()->json([
                        'success' => true, 
                        'message' => 'Verification successful, but failed to send credentials email. Please notify driver manually.'
                    ]);
                }
            }
            // End PUTHU MAATRAM

            return response()->json(['success' => true, 'message' => 'Verification successful!']);
        
        } else {
            // OTP is incorrect
            $attempts = $driver->otp_attempt_count + 1;
            
            if ($attempts >= 3) {
                $locked_until = Carbon::now()->addHours(24)->toDateTimeString();
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
     * (Called via AJAX)
     *
     * This method now calls the DriverService to perform the reset logic.
     * The service generates the new password (Name@1234), saves it,
     * sets the driver to 'unverified', and stores the plain-text
     * password in the session to be emailed after OTP verification.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        // Call the service to handle the logic
        $response = $this->driverService->resetPassword($request);
        
        // Return the response from the service
        return response()->json($response, $response['status_code']);
    }
}