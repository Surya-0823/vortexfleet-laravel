<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; 
use App\Models\Student; 
use App\Models\Route;   
use App\Models\Bus;     
use Illuminate\Http\Request; 
use App\Services\StudentService; // We will migrate this service later

// Import Laravel Facades
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection; // For type hinting

/**
 * Handles all logic for the Students Management page (CRUD, OTP, Status).
 */
class StudentsController extends Controller 
{
    /**
     * @var StudentService
     */
    protected $studentService; // Variable to hold the service

    /**
     * Constructor to initialize the service.
     * Auth is no longer handled here.
     */
    public function __construct()
    {
        // Initialize the service
        $this->studentService = new StudentService(); 

        // The old 'checkAuth()' logic is REMOVED from here.
        // We will protect this route in 'routes/web.php' using middleware.
    }

    /**
     * Display the students management page.
     *
     * @return \Illuminate\View\View
     */
    public function index() 
    {
        try {
            $page_title = "Student Management";
            $page_subtitle = "Register students with OTP verification";
            $page_css = '/assets/css/pages/drivers.css'; // Uses drivers.css
            $page_js = '/assets/js/students.js';

            try {
                // Get students and their related route
                $students = Student::with('route')->get(); 
                if (is_null($students)) {
                    $students = [];
                }
            } catch (\Exception $e) {
                // Use Laravel's Log facade
                Log::error('Database error in StudentsController::index() (students)', ['error' => $e->getMessage()]);
                $students = []; 
            }
            
            try {
                // Get bus names for routes dropdown
                $bus_plate_map = Bus::pluck('name', 'plate')->toArray();
                $routes_raw = Route::all(); 

                if ($routes_raw instanceof Collection) { 
                    $routes = $routes_raw->map(function ($route) use ($bus_plate_map) {
                        $plate = $route->bus_plate;
                        $route->bus_name = $bus_plate_map[$plate] ?? 'N/A';
                        return $route;
                    });
                } else {
                    $routes = [];
                }
            } catch (\Exception $e) {
                // Use Laravel's Log facade
                Log::error('Database error in StudentsController::index() (routes)', ['error' => $e->getMessage()]);
                $routes = []; 
            }

            // Use Laravel's View facade
            return View::make('pages.students', [
                'page_title' => $page_title,
                'page_subtitle' => $page_subtitle,
                'page_css' => $page_css,
                'page_js' => $page_js,
                'students' => $students, 
                'routes' => $routes     
            ]);
        } catch (\Exception $e) {
            Log::error('Error in StudentsController::index()', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            // Re-throw the exception to let Laravel handle it
            throw $e;
        }
    }
    
    /**
     * Call the service to create a new student.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request) 
    {
        // Call the service to handle the logic
        $result = $this->studentService->createStudent($request);

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
     * Call the service to update an existing student.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request) 
    {
        // Call the service to handle the logic
        $result = $this->studentService->updateStudent($request);
        
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
     * Call the service to delete a student.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request) 
    {
        // Call the service to handle the logic
        $result = $this->studentService->deleteStudent($request);
        
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
     * Call the service to update a student's verification status.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request)
    {
        // Call the service to handle the logic
        $result = $this->studentService->updateStudentStatus($request);
        
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
     * Call the service to send an OTP to a student.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendOtp(Request $request)
    {
        // Call the service to handle the logic
        $result = $this->studentService->sendStudentOtp($request);
        
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
     * Call the service to verify a student's OTP.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyOtp(Request $request)
    {
        // Call the service to handle the logic
        $result = $this->studentService->verifyStudentOtp($request);
        
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
     * Call the service to reset a student's password.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        // Call the service to handle the logic
        $result = $this->studentService->resetStudentPassword($request);
        
        // Use Laravel's response()->json()
        return response()->json(
            [
                'success' => $result['success'], 
                'message' => $result['message'],
                // This specific response includes the new password
                'new_password' => $result['data']['new_password'] ?? null 
            ], 
            $result['status_code']
        );
    }
}