<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Driver; 
use App\Models\Student; 
use App\Models\ApiToken; // This model is crucial for API auth
use Illuminate\Http\Request;
// Import Laravel Facades
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

/**
 * Handles all authentication for web (admin) and mobile apps (driver/student).
 */
class AuthController extends Controller
{
    /**
     * Display the admin login page.
     *
     * @return \Illuminate\View\View
     */
    public function showLogin()
    {
        $page_title = "Login - VortexFleet";
        $page_css = '/assets/css/pages/auth.css';
        
        return View::make('pages.login', [
            'page_title' => $page_title,
            'page_css' => $page_css,
        ]);
    }
    
    /**
     * Display the admin registration page.
     *
     * @return \Illuminate\View\View
     */
    public function showRegister()
    {
        $page_title = "Register - VortexFleet";
        $page_css = '/assets/css/pages/auth.css';
        
        return View::make('pages.register', [
            'page_title' => $page_title,
            'page_css' => $page_css,
        ]);
    }
    
    /**
     * Display the subscription pricing page.
     *
     * @return \Illuminate\View\View
     */
    public function showPricing()
    {
        $page_title = "Pricing - VortexFleet";
        $page_css = '/assets/css/pages/pricing.css';
        
        return View::make('pages.pricing', [
            'page_title' => $page_title,
            'page_css' => $page_css,
        ]);
    }
    
    /**
     * Process the admin registration form.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $data = $request->all();
        
        if (empty($data['name']) || empty($data['email']) || empty($data['password']) || empty($data['subscription_plan'])) {
            return Redirect::to('/register')->with('error', 'All fields are required');
        }
        
        // We assume the User model has this static method
        $existingUser = User::findByEmail($data['email']);
        if ($existingUser) {
            return Redirect::to('/register')->with('error', 'Email already registered');
        }
        
        $students = intval($data['students'] ?? 0);
        $buses = intval($data['buses'] ?? 1);
        $subscriptionType = $data['subscription_type'] ?? 'monthly';
        
        $dailyRate = $students * 1.75;
        $monthlyAmount = $dailyRate * 30;
        
        if ($subscriptionType === 'yearly') {
            $paymentAmount = $monthlyAmount * 11;
        } else {
            $paymentAmount = $monthlyAmount;
        }
        
        // Note: Password will be hashed by the User::createUser method (as per old logic)
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'], // Hashing should be handled in model
            'phone' => $data['phone'] ?? '',
            'institution_name' => $data['institution_name'] ?? '',
            'subscription_plan' => $data['subscription_plan'],
            'subscription_type' => $subscriptionType,
            'payment_amount' => $paymentAmount,
        ];
        
        // We assume the User model has this static method
        $userId = User::createUser($userData);
        
        // Use Laravel Session
        Session::put('registering_user_id', $userId);
        Session::put('payment_amount', $paymentAmount);
        Session::put('subscription_type', $subscriptionType);
        
        return Redirect::to('/payment?user_id=' . $userId);
    }
    
    /**
     * Process the admin login form.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        
        if (empty($email) || empty($password)) {
            return Redirect::to('/login')->with('error', 'Email and password are required');
        }
        
        // We assume the User model has this static method
        $user = User::findByEmail($email);
        
        // Use Laravel's Hash facade
        if (!$user || !Hash::check($password, $user->password)) {
            return Redirect::to('/login')->with('error', 'Invalid email or password');
        }
        
        if ($user->payment_status !== 'completed') {
            $url = '/payment?user_id=' . $user->id;
            return Redirect::to($url)->with('error', 'Please complete payment to access dashboard');
        }
        
        if ($user->status !== 'active') {
            return Redirect::to('/login')->with('error', 'Your account is not active');
        }
        
        // Regenerate session
        Session::regenerate(true); 
        
        // Store user data in Laravel session
        Session::put('user_id', $user->id);
        Session::put('user_name', $user->name);
        Session::put('user_email', $user->email);
        
        return Redirect::to('/dashboard');
    }

    /**
     * Generate and save a new API token for a user (Driver or Student).
     *
     * @param \Illuminate\Database\Eloquent\Model $userModel (e.g., Driver or Student)
     * @return string The new API token.
     */
    private function generateAndSaveApiToken($userModel)
    {
        $token = bin2hex(random_bytes(32)); 
        // Use Laravel's 'now()' helper
        $expires_at = now()->addDays(30)->toDateTimeString(); 

        // Delete old tokens for this user
        ApiToken::where('tokenable_id', $userModel->id)
                ->where('tokenable_type', get_class($userModel))
                ->delete();

        // Create new token in the api_tokens table
        ApiToken::create([
            'token' => $token,
            'tokenable_id' => $userModel->id,
            'tokenable_type' => get_class($userModel), // Stores 'App\Models\Driver' or 'App\Models\Student'
            'expires_at' => $expires_at
        ]);
        
        return $token;
    }

    /**
     * Process the Driver App login.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function appDriverLogin(Request $request)
    {
        $email = $request->input('app_username'); 
        $password = $request->input('password');
        
        if (empty($email) || empty($password)) {
            return response()->json(['success' => false, 'message' => 'Username and password are required.'], 400);
        }

        $user = Driver::where('app_username', $email)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Invalid username or password.'], 401);
        }

        // Use Laravel's Hash facade
        if (!Hash::check($password, $user->app_password)) {
            return response()->json(['success' => false, 'message' => 'Invalid username or password.'], 401);
        }

        if ($user->is_verified != 1) {
            return response()->json(['success' => false, 'message' => 'Your account is pending verification. Please verify via OTP.'], 403);
        }

        // Use the new token generation function
        $apiToken = $this->generateAndSaveApiToken($user);

        return response()->json([
            'success' => true,
            'message' => 'Login successful!',
            'api_token' => $apiToken, 
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => 'driver',
            ]
        ], 200);
    }
    
    /**
     * Process the Student App login.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function appStudentLogin(Request $request)
    {
        $email = $request->input('app_username'); 
        $password = $request->input('password');
        
        if (empty($email) || empty($password)) {
            return response()->json(['success' => false, 'message' => 'Username and password are required.'], 400);
        }

        $user = Student::where('app_username', $email)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Invalid username or password.'], 401);
        }

        // Use Laravel's Hash facade
        if (!Hash::check($password, $user->app_password)) {
            return response()->json(['success' => false, 'message' => 'Invalid username or password.'], 401);
        }

        if ($user->is_verified != 1) {
             return response()->json(['success' => false, 'message' => 'Your account is pending verification. Please verify via OTP.'], 403);
        }

        // Use the new token generation function
        $apiToken = $this->generateAndSaveApiToken($user);

        return response()->json([
            'success' => true,
            'message' => 'Login successful!',
            'api_token' => $apiToken, 
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => 'student',
            ]
        ], 200);
    }
    
    /**
     * Display the payment page for registration.
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showPayment(Request $request)
    {
        // Get user_id from request or session
        $userId = $request->input('user_id', Session::get('registering_user_id'));
        
        if (!$userId) {
            return Redirect::to('/register');
        }
        
        // We assume the User model has this static method
        $user = User::findById($userId);
        if (!$user) {
            return Redirect::to('/register');
        }
        
        $page_title = "Payment - VortexFleet";
        $page_css = '/assets/css/pages/auth.css';
        
        return View::make('pages.payment', [
            'page_title' => $page_title,
            'page_css' => $page_css,
            'user' => $user,
        ]);
    }
    
    /**
     * Process the payment form (simulation).
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processPayment(Request $request)
    {
        $userId = $request->input('user_id');
        $amount = $request->input('amount');
        $subscriptionType = $request->input('subscription_type');
        $paymentMethod = $request->input('payment_method');
        
        if (!$userId || !$amount) {
            $url = '/payment?user_id=' . $userId;
            return Redirect::to($url)->with('error', 'Invalid payment data');
        }
        
        $paymentData = [
            'amount' => $amount,
            'subscription_type' => $subscriptionType,
        ];
        
        // We assume the User model has this static method
        $result = User::updatePayment($userId, $paymentData);
        
        if ($result) {
            // Clear registration session data
            Session::forget('registering_user_id');
            Session::forget('payment_amount');
            Session::forget('subscription_type');
            
            // We assume the User model has this static method
            $user = User::findById($userId);
            
            // Log the user in
            Session::regenerate(true);
            Session::put('user_id', $user->id);
            Session::put('user_name', $user->name);
            Session::put('user_email', $user->email);
            
            return Redirect::to('/dashboard');
        }
        
        $url = '/payment?user_id=' . $userId;
        return Redirect::to($url)->with('error', 'Payment processing failed');
    }
    
    /**
     * Log the admin user out.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        // Clear the session
        Session::flush();
        return Redirect::to('/');
    }
}