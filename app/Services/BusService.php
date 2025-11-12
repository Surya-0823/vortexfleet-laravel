<?php

namespace App\Services;

use App\Models\Bus;
use App\Models\Driver;
use App\Models\Route; // Added missing import
use Illuminate\Http\Request;
use App\Http\Traits\FileUploadTrait; // Import trait

// Import Laravel Facades
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File; // For deleting files

/**
 * Handles all business logic related to Buses.
 */
class BusService
{
    // Use the trait to get access to $this->handlePhotoUpload()
    use FileUploadTrait;

    // Note: The old constructor that injected a logger is removed.
    // We use the 'Log' facade directly.

    /**
     * Validate and create a new bus.
     *
     * @param Request $request
     * @return array Service response array
     */
    public function createBus(Request $request)
    {
        $data = $request->all();
        $validation = $this->validate($data);

        if (!empty($validation['errors'])) {
            return ['success' => false, 'message' => 'Validation failed', 'data' => $validation, 'status_code' => 422];
        }

        // 1. Handle Photo Upload
        try {
            // Use 'plate' for file name prefix
            $uploadResult = $this->handlePhotoUpload($request, 'uploads/buses', $data['plate']);
            if (isset($uploadResult['error'])) {
                return ['success' => false, 'message' => $uploadResult['error'], 'data' => null, 'status_code' => 400];
            }
            $data['photo_path'] = $uploadResult['path'];
        } catch (\Exception $e) {
            Log::error('Bus photo upload failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'File upload failed: ' . $e->getMessage(), 'data' => null, 'status_code' => 500];
        }

        // 2. Handle Driver Assignment (if provided)
        $driverId = $data['driver_id'] ?? null;
        unset($data['driver_id']); // Not a 'buses' table column

        try {
            DB::beginTransaction();

            // 3. Create Bus
            $bus = Bus::create($data);

            // 4. Assign to Driver
            if ($driverId) {
                $driver = Driver::find($driverId);
                if ($driver) {
                    $driver->bus_plate = $bus->plate;
                    $driver->save();
                } else {
                    // This case should be caught by validation, but good to double-check
                    throw new \Exception("Assigned driver (ID: $driverId) not found.");
                }
            }

            DB::commit();
            return ['success' => true, 'message' => 'Bus created and assigned successfully.', 'data' => null, 'status_code' => 201];
        
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bus creation failed', ['error' => $e->getMessage()]);
            // Check for duplicate entry
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                 return ['success' => false, 'message' => 'Database error: A bus with this plate already exists.', 'data' => null, 'status_code' => 409];
            }
            return ['success' => false, 'message' => 'Database error: Could not create bus.', 'data' => null, 'status_code' => 500];
        }
    }

    /**
     * Validate and update an existing bus.
     *
     * @param Request $request
     * @return array Service response array
     */
    public function updateBus(Request $request)
    {
        $data = $request->all();
        $busId = $data['id']; // 'id' field from the form
        
        $bus = Bus::find($busId);
        if (!$bus) {
            return ['success' => false, 'message' => 'Bus not found', 'data' => null, 'status_code' => 404];
        }

        // We must validate against the bus 'plate', not its 'id'
        $validation = $this->validate($data, true, $bus->plate);
        if (!empty($validation['errors'])) {
            return ['success' => false, 'message' => 'Validation failed', 'data' => $validation, 'status_code' => 422];
        }

        // 1. Handle Photo Upload (if new photo provided)
        if ($request->hasFile('photo')) {
            try {
                $uploadResult = $this->handlePhotoUpload($request, 'uploads/buses', $data['plate'], $bus->photo_path);
                if (isset($uploadResult['error'])) {
                    return ['success' => false, 'message' => $uploadResult['error'], 'data' => null, 'status_code' => 400];
                }
                $data['photo_path'] = $uploadResult['path'];
            } catch (\Exception $e) {
                Log::error('Bus photo update failed', ['error' => $e->getMessage()]);
                return ['success' => false, 'message' => 'File upload failed: ' . $e->getMessage(), 'data' => null, 'status_code' => 500];
            }
        }

        // 2. Handle Driver Assignment
        $newDriverId = $data['driver_id'] ?? null;
        $oldDriver = Driver::where('bus_plate', $bus->plate)->first();
        unset($data['driver_id']); 

        try {
            DB::beginTransaction();

            // 3. Update Bus details
            $bus->update($data);

            // 4. Update Driver assignments
            if ($oldDriver && $oldDriver->id != $newDriverId) {
                // Un-assign old driver
                $oldDriver->bus_plate = null;
                $oldDriver->save();
            }

            if ($newDriverId) {
                $newDriver = Driver::find($newDriverId);
                if ($newDriver) {
                    // Assign new driver
                    $newDriver->bus_plate = $bus->plate;
                    $newDriver->save();
                }
            }
            
            DB::commit();
            return ['success' => true, 'message' => 'Bus updated successfully.', 'data' => null, 'status_code' => 200];
        
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bus update failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Database error: Could not update bus.', 'data' => null, 'status_code' => 500];
        }
    }

    /**
     * Delete a bus and its photo.
     *
     * @param Request $request
     * @return array Service response array
     */
    public function deleteBus(Request $request)
    {
        $busId = $request->input('id'); // 'id' field from the form
        $bus = Bus::find($busId);

        if (!$bus) {
            return ['success' => false, 'message' => 'Bus not found', 'status_code' => 404];
        }

        // Check if bus is assigned to routes or drivers
        if (Route::where('bus_plate', $bus->plate)->exists() || Driver::where('bus_plate', $bus->plate)->exists()) {
             return ['success' => false, 'message' => 'Cannot delete: Bus is still assigned to a route or driver.', 'status_code' => 409]; // 409 Conflict
        }

        try {
            DB::beginTransaction();
            
            // Delete photo file
            if ($bus->photo_path) {
                // Use Laravel's public_path() and File facade
                $server_existing_path = public_path($bus->photo_path);
                if (File::exists($server_existing_path)) {
                    File::delete($server_existing_path);
                }
            }
            
            // Delete from database
            $bus->delete();

            DB::commit();
            return ['success' => true, 'message' => 'Bus deleted successfully.', 'status_code' => 200];
        
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bus deletion failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Error deleting bus.', 'status_code' => 500];
        }
    }

    /**
     * Private helper to validate bus data.
     *
     * @param array $data
     * @param bool $isUpdate
     * @param string|null $busPlate
     * @return array
     */
    private function validate($data, $isUpdate = false, $busPlate = null)
    {
        $errors = [];
        
        if (empty($data['name'])) $errors['name'] = 'Bus Name is required';
        if (empty($data['plate'])) $errors['plate'] = 'Bus Plate is required';
        if (empty($data['capacity'])) $errors['capacity'] = 'Capacity is required';

        if (!is_numeric($data['capacity']) || $data['capacity'] <= 0) {
             $errors['capacity'] = 'Capacity must be a positive number';
        }

        // Check unique plate
        $query = Bus::where('plate', $data['plate']);
        if ($isUpdate) {
            // On update, we ignore the *current* bus's plate
            $query->where('plate', '!=', $busPlate);
        }
        if ($query->exists()) {
            $errors['plate'] = 'A bus with this plate number already exists';
        }

        // Check if assigned driver exists
        if (!empty($data['driver_id'])) {
            if (!Driver::where('id', $data['driver_id'])->exists()) {
                $errors['driver_id'] = 'Selected driver does not exist';
            }
        }
        
        return ['errors' => $errors];
    }
}