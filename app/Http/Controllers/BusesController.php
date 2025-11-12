<?php

namespace App\Http\Controllers; 

use App\Http\Controllers\Controller; 
use App\Models\Bus;  
use App\Models\Driver; 
use Illuminate\Http\Request; 
use App\Services\BusService; // We will migrate this service later

// Import Laravel Facades
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

/**
 * Handles all logic for the Buses Management page (CRUD).
 */
class BusesController extends Controller 
{
    /**
     * @var BusService
     */
    protected $busService; // Variable to hold the service

    /**
     * Constructor to initialize the service.
     * Auth is no longer handled here.
     */
    public function __construct()
    {
        parent::__construct();
        
        // Initialize the service
        $this->busService = new BusService(); 

        // The old 'checkAuth()' logic is REMOVED from here.
        // We will protect this route in 'routes/web.php' using middleware.
    }

    /**
     * Display the buses management page.
     *
     * @return \Illuminate\View\View
     */
    public function index() 
    {
        try {
            $page_title = "Bus Management";
            $page_subtitle = "Register and manage your bus fleet";
            $page_css = '/assets/css/pages/drivers.css'; // Uses drivers.css
            $page_js = '/assets/js/buses.js';
            
            try {
                // Fetch buses with related route and driver data
                $buses = DB::table('buses')
                            ->leftJoin('routes', 'buses.plate', '=', 'routes.bus_plate')
                            ->leftJoin('drivers', 'buses.plate', '=', 'drivers.bus_plate') 
                            ->select('buses.*', 'routes.start', 'routes.end', 'drivers.name as driver_name', 'drivers.id as driver_id') 
                            ->get();
                if (is_null($buses)) {
                    $buses = [];
                }
            } catch (\Exception $e) {
                Log::error('Database error in BusesController::index() (buses)', ['error' => $e->getMessage()]);
                $buses = []; 
            }
                    
            try {
                // Fetch drivers who are not assigned to a bus
                $available_drivers = Driver::whereNull('bus_plate')->orWhere('bus_plate', '')->get();
                if (is_null($available_drivers)) {
                    $available_drivers = [];
                }
            } catch (\Exception $e) {
                Log::error('Database error loading drivers in BusesController::index()', ['error' => $e->getMessage()]);
                $available_drivers = []; 
            }

            // Use Laravel's View facade
            return View::make('pages.buses', [
                'page_title' => $page_title,
                'page_subtitle' => $page_subtitle,
                'page_css' => $page_css,
                'page_js' => $page_js,
                'buses' => $buses,
                'available_drivers' => $available_drivers 
            ]);
        } catch (\Exception $e) {
            Log::error('Error in BusesController::index()', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            // Re-throw the exception to let Laravel handle it
            throw $e;
        }
    }
    
    /**
     * Call the service to create a new bus.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request) 
    {
        // Call the service to handle the logic
        $result = $this->busService->createBus($request);

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
     * Call the service to update an existing bus.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request) 
    {
        // Call the service to handle the logic
        $result = $this->busService->updateBus($request);
        
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
     * Call the service to delete a bus.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request) 
    {
        // Call the service to handle the logic
        $result = $this->busService->deleteBus($request);
        
        // Use Laravel's response()->json()
        return response()->json(
            [
                'success' => $result['success'], 
                'message' => $result['message']
            ], 
            $result['status_code']
        );
    }
}