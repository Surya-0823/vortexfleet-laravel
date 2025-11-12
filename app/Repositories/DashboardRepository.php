<?php

namespace App\Repositories;

// Use Laravel's DB Facade
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // For logging errors

/**
 * Repository for fetching data for the admin dashboard.
 */
class DashboardRepository
{
    /**
     * Get the total count of drivers.
     *
     * @return int
     */
    public function getDriverCount()
    {
        try {
            return DB::table('drivers')->count();
        } catch (\Exception $e) {
            Log::error('Failed to get driver count', ['error' => $e->getMessage()]);
            return 0; // Return 0 on error
        }
    }

    /**
     * Get the total count of buses.
     *
     * @return int
     */
    public function getBusCount()
    {
        try {
            return DB::table('buses')->count();
        } catch (\Exception $e) {
            Log::error('Failed to get bus count', ['error' => $e->getMessage()]);
            return 0; // Return 0 on error
        }
    }

    /**
     * Get the total count of students.
     *
     * @return int
     */
    public function getStudentCount()
    {
        try {
            return DB::table('students')->count();
        } catch (\Exception $e) {
            Log::error('Failed to get student count', ['error' => $e->getMessage()]);
            return 0; // Return 0 on error
        }
    }
}