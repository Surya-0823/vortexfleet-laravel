<?php

namespace App\Http\Controllers; 

use App\Http\Controllers\Controller; 
use App\Models\Driver;
use App\Models\Student;
use App\Models\Route;
use App\Models\Bus;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\DB; // Use Laravel's DB Facade
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * Handles all API calls for the mobile applications (Driver & Student).
 *
 * This controller relies on ApiAuthMiddleware to be configured
 * to authenticate users and pass user data via $request->attributes.
 */
class AppApiController extends Controller 
{
    
    // Note: The __construct() and checkApiAuth() methods were
    // intentionally removed. Authentication is handled by ApiAuthMiddleware.

    /**
     * Get the assigned status and details for the authenticated driver.
     * (IDOR-FIXED)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDriverStatus(Request $request) 
    {
        // Get user data injected by ApiAuthMiddleware
        $authenticatedUser = $request->attributes->get('authenticatedUser');
        $userRole = $request->attributes->get('userRole');

        // 1. Authorize role
        if ($userRole !== 'driver') {
            return response()->json(['success' => false, 'message' => 'Forbidden: Only drivers can access this.'], 403);
        }

        // 2. Use the authenticated user's ID
        $driverId = $authenticatedUser->id; 

        // 3. Fetch driver data
        $driverData = DB::table('drivers')
            ->where('drivers.id', $driverId)
            ->leftJoin('buses', 'drivers.bus_plate', '=', 'buses.plate')
            ->leftJoin('routes', 'buses.plate', '=', 'routes.bus_plate')
            ->select(
                'drivers.id as driver_id',
                'drivers.name as driver_name',
                'drivers.phone',
                'drivers.photo_path',
                'drivers.bus_plate',
                'drivers.is_verified',
                'buses.name as bus_name',
                'buses.capacity',
                'buses.current_lat', 
                'buses.current_lon',
                'routes.name as route_name',
                'routes.start as route_start',
                'routes.end as route_end'
            )
            ->first();

        if (!$driverData) {
            return response()->json(['success' => false, 'message' => 'Driver not found.'], 404);
        }
        
        $location = [
            'latitude' => $driverData->current_lat ?? 0, 
            'longitude' => $driverData->current_lon ?? 0
        ];

        $response = [
            'success' => true,
            'message' => 'Driver status retrieved.',
            'profile' => [
                'id' => $driverData->driver_id,
                'name' => $driverData->driver_name,
                'phone' => $driverData->phone,
                'photo_url' => $driverData->photo_path,
                'is_verified' => (bool)$driverData->is_verified,
            ],
            'assignment' => [
                'bus_plate' => $driverData->bus_plate ?? 'N/A',
                'bus_name' => $driverData->bus_name ?? 'Unassigned',
                'capacity' => $driverData->capacity ?? 0,
                'route_name' => $driverData->route_name ?? 'No Route',
                'route_start' => $driverData->route_start ?? '',
                'route_end' => $driverData->route_end ?? '',
            ],
            'bus_location' => $location 
        ];

        return response()->json($response, 200);
    }
    
    /**
     * Update the bus location for the authenticated driver.
     * (IDOR-FIXED)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateLocation(Request $request) 
    {
        // Get user data injected by ApiAuthMiddleware
        $authenticatedUser = $request->attributes->get('authenticatedUser');
        $userRole = $request->attributes->get('userRole');

        // 1. Authorize role
        if ($userRole !== 'driver') {
            return response()->json(['success' => false, 'message' => 'Forbidden: Only drivers can update location.'], 403);
        }

        // 2. Validate inputs
        $validator = Validator::make($request->all(), [
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid location data.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();
        $latitude = (float) $validated['latitude'];
        $longitude = (float) $validated['longitude'];

        // 3. Get authenticated driver
        $driver = $authenticatedUser;

        if (!$driver || empty($driver->bus_plate)) {
            return response()->json(['success' => false, 'message' => 'Driver not assigned to a bus.'], 404);
        }

        // 4. Update the bus table
        try {
            $updated = DB::table('buses')
                ->where('plate', $driver->bus_plate)
                ->update([
                    'current_lat' => $latitude,
                    'current_lon' => $longitude,
                ]);

            if ($updated === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Assigned bus not found for location update.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Location updated successfully.',
                'bus_plate' => $driver->bus_plate
            ], 200);

        } catch (\Exception $e) {
            // Log the error in Laravel
            Log::error('Failed to update location', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Database error during location update.'], 500);
        }
    }
        
    /**
     * Get tracking details (bus location, driver info) for the authenticated student.
     * (IDOR-FIXED)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStudentTracking(Request $request) 
    {
        // Get user data injected by ApiAuthMiddleware
        $authenticatedUser = $request->attributes->get('authenticatedUser');
        $userRole = $request->attributes->get('userRole');

        // 1. Authorize role
        if ($userRole !== 'student') {
            return response()->json(['success' => false, 'message' => 'Forbidden: Only students can access this.'], 403);
        }

        // 2. Use the authenticated user's ID
        $studentId = $authenticatedUser->id; 
        
        // 3. Fetch student and related tracking data
        $studentData = DB::table('students')
            ->where('students.id', $studentId)
            ->leftJoin('routes', 'students.route_name', '=', 'routes.name')
            ->leftJoin('buses', 'routes.bus_plate', '=', 'buses.plate')
            ->leftJoin('drivers', 'buses.plate', '=', 'drivers.bus_plate')
            ->select(
                'students.name as student_name',
                'students.route_name',
                'routes.bus_plate',
                'buses.name as bus_name',
                'buses.current_lat', 
                'buses.current_lon', 
                'drivers.name as driver_name',
                'drivers.phone as driver_phone'
            )
            ->first();
            
        if (!$studentData) {
            return response()->json(['success' => false, 'message' => 'Student not found or unassigned.'], 404);
        }
        
        $location = [
            'latitude' => $studentData->current_lat ?? 0, 
            'longitude' => $studentData->current_lon ?? 0
        ];

        $response = [
            'success' => true,
            'message' => 'Student tracking details retrieved.',
            'student' => [
                'name' => $studentData->student_name,
                'route' => $studentData->route_name ?? 'N/A',
            ],
            'bus_info' => [
                'plate' => $studentData->bus_plate ?? 'N/A',
                'name' => $studentData->bus_name ?? 'Unassigned Bus',
            ],
            'driver_info' => [
                'name' => $studentData->driver_name ?? 'Unassigned',
                'phone' => $studentData->driver_phone ?? 'N/A',
            ],
            'current_location' => $location 
        ];

        return response()->json($response, 200);
    }
}