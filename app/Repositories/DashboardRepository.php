<?php

namespace App\Repositories;

use App\Models\User; // User model-ah import pannikonga
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // For logging errors

/**
 * Repository for fetching data for the admin dashboard.
 */
class DashboardRepository
{
    /**
     * Get all necessary dashboard data for a specific user.
     *
     * @param User $user
     * @return array
     */
    public function getDashboardData(User $user)
    {
        try {
            // 1. Subscription Limits
            $max_buses = $user->max_buses ?? 5; // Default 5 vechikalam
            $max_students = $max_buses * 40; // Oru bus-ku 40 students limit

            // 2. Current Counts
            $current_drivers = DB::table('drivers')->count();
            $current_buses = DB::table('buses')->count();
            $current_students = DB::table('students')->count();
            $current_routes = DB::table('routes')->count();

            // 3. Action Items (Intha logic-ah simple-ah vechikalam)

            // Pending drivers (status != 'active')
            $pending_drivers = DB::table('drivers')
                                ->where('status', '!=', 'active')
                                ->count();

            // Pending students (status != 'active')
            $pending_students = DB::table('students')
                                ->where('status', '!=', 'active')
                                ->count();

            // Buses without drivers
            $buses_without_driver = DB::table('buses')
                                    ->whereNull('driver_id')
                                    ->count();

            // Routes without buses
            $routes_without_bus = DB::table('routes')
                                    ->whereNull('bus_id')
                                    ->count();


            // Ella data-vayum ore array-la anupparom
            return [
                'max_buses' => $max_buses,
                'max_students' => $max_students,
                'current_drivers' => $current_drivers,
                'current_buses' => $current_buses,
                'current_students' => $current_students,
                'current_routes' => $current_routes,
                'pending_drivers' => $pending_drivers,
                'pending_students' => $pending_students,
                'buses_without_driver' => $buses_without_driver,
                'routes_without_bus' => $routes_without_bus,
            ];

        } catch (\Exception $e) {
            Log::error('Failed to get dashboard data', ['error' => $e->getMessage()]);
            // Error aanaalum, view crash aaga koodathu
            return [
                'max_buses' => 0,
                'max_students' => 0,
                'current_drivers' => 0,
                'current_buses' => 0,
                'current_students' => 0,
                'current_routes' => 0,
                'pending_drivers' => 0,
                'pending_students' => 0,
                'buses_without_driver' => 0,
                'routes_without_bus' => 0,
            ];
        }
    }
}
