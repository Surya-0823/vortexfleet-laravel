<?php

namespace App\Http\Controllers; 

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\DB; // Use Laravel's DB Facade
use Illuminate\Support\Facades\View; // Use Laravel's View Facade

/**
 * Handles the real-time tracking map page and its data feed.
 */
class TrackingController extends Controller 
{
    /**
     * Constructor.
     * Auth is no longer handled here, it will be handled by web middleware.
     */
    public function __construct()
    {
        // The old 'checkAuth()' logic is REMOVED from here.
        // We will protect this route in 'routes/web.php' using middleware.
    }
    
    /**
     * Display the admin map tracking page.
     *
     * @return \Illuminate\View\View
     */
    public function index() 
    {
        $page_title = "Real-time Tracking Map";
        $page_subtitle = "Monitor all active buses on the map";
        $page_css = '/assets/css/pages/tracking.css'; 
        $page_js = '/assets/js/tracking.js'; 

        // Return the view using the View facade
        return View::make('pages.tracking', [
            'page_title' => $page_title,
            'page_subtitle' => $page_subtitle,
            'page_css' => $page_css,
            'page_js' => $page_js,
        ]);
    }

    /**
     * Provides bus location data to the admin map via AJAX.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAdminBusLocations(Request $request) 
    {
        // Auth check is REMOVED from here.
        // The route '/ajax/admin/bus-locations' must be protected by auth middleware.

        // Fetch bus locations with driver names
        $busLocations = DB::table('buses')
            ->leftJoin('drivers', 'buses.plate', '=', 'drivers.bus_plate')
            ->select(
                'buses.plate',
                'buses.name as bus_name',
                'buses.current_lat',
                'buses.current_lon',
                'drivers.name as driver_name'
            )
            ->get();
            
        // Filter out buses that are not reporting location
        $activeBuses = $busLocations->filter(function ($bus) {
            // Check if coordinates are set and not just 0/null
            return ($bus->current_lat !== null && $bus->current_lon !== null && ($bus->current_lat != 0 || $bus->current_lon != 0));
        })->values(); // Reset keys for JSON array

        if ($activeBuses->isEmpty()) {
            // Use Laravel's response()->json()
            return response()->json(['success' => true, 'message' => 'No active bus locations reported yet.', 'buses' => []], 200);
        }

        // Use Laravel's response()->json()
        return response()->json([
            'success' => true,
            'message' => 'Active bus locations retrieved.',
            'buses' => $activeBuses
        ], 200);
    }
}