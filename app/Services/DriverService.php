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
            Driver::create($data); // Assumes 'photo_path' is fillable in Model
            return ['success' => true, 'message' => 'Driver created successfully.', 'data' => null, 'status_code' => 201];
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
            if (array_key_exists('app_password', $data) && $data['app_password'] === '') {
                unset($data['app_password']);
            }

            $driver->update($data); // Assumes fields are fillable
            return ['success' => true, 'message' => 'Driver updated successfully.', 'data' => null, 'status_code' => 200];
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
        if (empty($data['app_username'])) $errors['app_username'] = 'App Username is required';
        if (!$isUpdate && empty($data['app_password'])) $errors['app_password'] = 'App Password is required';

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        }

        // Check unique constraints
        $queryEmail = Driver::where('email', $data['email']);
        if ($isUpdate) $queryEmail->where('id', '!=', $driverId);
        if ($queryEmail->exists()) $errors['email'] = 'Email already taken';

        $queryUsername = Driver::where('app_username', $data['app_username']);
        if ($isUpdate) $queryUsername->where('id', '!=', $driverId);
        if ($queryUsername->exists()) $errors['app_username'] = 'App Username already taken';
        
        return ['errors' => $errors];
    }
}