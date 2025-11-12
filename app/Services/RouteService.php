<?php

namespace App\Services;

use App\Models\Route;
use App\Models\Bus;
use App\Models\Student;
use Illuminate\Http\Request;

// Import Laravel Facades
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Handles all business logic related to Routes.
 *
 * (Note: We've removed the Repository layer to match other services)
 */
class RouteService
{
    // Note: The old constructor with RouteRepository is removed.
    // We will use the Eloquent Model (Route::) directly.

    /**
     * Validate and create a new route.
     *
     * @param Request $request
     * @return array Service response array
     */
    public function createRoute(Request $request)
    {
        $data = $request->all();
        $validation = $this->validate($data);

        if (!empty($validation['errors'])) {
            return ['success' => false, 'message' => 'Validation failed', 'data' => $validation, 'status_code' => 422];
        }

        try {
            // Use the Route model directly
            Route::create($data); 
            
            return ['success' => true, 'message' => 'Route created successfully.', 'data' => null, 'status_code' => 201];
        
        } catch (\Exception $e) {
            Log::error('Route creation failed', ['error' => $e->getMessage()]);
            // Check for duplicate entry
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                 return ['success' => false, 'message' => 'Database error: A route with this name already exists.', 'data' => null, 'status_code' => 409];
            }
            return ['success' => false, 'message' => 'Database error: Could not create route.', 'data' => null, 'status_code' => 500];
        }
    }

    /**
     * Validate and update an existing route.
     *
     * @param Request $request
     * @return array Service response array
     */
    public function updateRoute(Request $request)
    {
        $data = $request->all();
        $routeId = $data['id']; // 'id' field from the form
        
        $route = Route::find($routeId);
        if (!$route) {
            return ['success' => false, 'message' => 'Route not found', 'data' => null, 'status_code' => 404];
        }

        // We must validate against the route 'name', not its 'id'
        $validation = $this->validate($data, true, $route->name);
        if (!empty($validation['errors'])) {
            // ===== ITHU THAAN ANTHA FIX (=> added) =====
            return ['success' => false, 'message' => 'Validation failed', 'data' => $validation, 'status_code' => 422];
        }

        try {
            // Use the Route model directly
            $route->update($data);
            
            return ['success' => true, 'message' => 'Route updated successfully.', 'data' => null, 'status_code' => 200];
        
        } catch (\Exception $e) {
            Log::error('Route update failed', ['error' => $e->getMessage()]);
            // Check for duplicate entry
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                 return ['success' => false, 'message' => 'Database error: A route with this name already exists.', 'data' => null, 'status_code' => 409];
            }
            return ['success' => false, 'message' => 'Database error: Could not update route.', 'data' => null, 'status_code' => 500];
        }
    }

    /**
     * Delete a route.
     *
     * @param Request $request
     * @return array Service response array
     */
    public function deleteRoute(Request $request)
    {
        $routeId = $request->input('id'); // 'id' field from the form
        $route = Route::find($routeId);

        if (!$route) {
            return ['success' => false, 'message' => 'Route not found', 'status_code' => 404];
        }

        // Check if route is assigned to students
        if (Student::where('route_name', $route->name)->exists()) {
             return ['success' => false, 'message' => 'Cannot delete: Route is still assigned to students.', 'status_code' => 409]; // 409 Conflict
        }

        try {
            // Use the Route model directly
            $route->delete();
            
            return ['success' => true, 'message' => 'Route deleted successfully.', 'status_code' => 200];
        
        } catch (\Exception $e) {
            Log::error('Route deletion failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Error deleting route.', 'status_code' => 500];
        }
    }

    /**
     * Private helper to validate route data.
     *
     * @param array $data
     * @param bool $isUpdate
     * @param string|null $routeName
     * @return array
     */
    private function validate($data, $isUpdate = false, $routeName = null)
    {
        $errors = [];
        
        if (empty($data['name'])) $errors['name'] = 'Route Name is required';
        if (empty($data['start'])) $errors['start'] = 'Start Point is required';
        if (empty($data['end'])) $errors['end'] = 'End Point is required';
        if (empty($data['bus_plate'])) $errors['bus_plate'] = 'Bus is required';

        // Check unique route name
        $query = Route::where('name', $data['name']);
        if ($isUpdate) {
            // On update, we ignore the *current* route's name
            $query->where('name', '!=', $routeName);
        }
        if ($query->exists()) {
            $errors['name'] = 'A route with this name already exists';
        }

        // Check if assigned bus exists
        if (!Bus::where('plate', $data['bus_plate'])->exists()) {
            $errors['bus_plate'] = 'Selected bus does not exist';
        }
        
        return ['errors' => $errors];
    }
}