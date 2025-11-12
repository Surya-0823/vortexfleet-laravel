<?php

namespace App\Http\Controllers; 

use App\Http\Controllers\Controller; 
use App\Models\Route;   
use App\Models\Bus;     
use Illuminate\Http\Request; 
use App\Services\RouteService; // We will migrate this service later

// Import Laravel Facades
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

/**
 * Handles all logic for the Routes Management page (CRUD).
 */
class RoutesController extends Controller 
{
    /**
     * @var RouteService
     */
    protected $routeService; // Variable to hold the service

    /**
     * Constructor to initialize the service.
     * Auth is no longer handled here.
     */
    public function __construct()
    {
        parent::__construct();
        
        // Initialize the service
        $this->routeService = new RouteService(); 

        // The old 'checkAuth()' logic is REMOVED from here.
        // We will protect this route in 'routes/web.php' using middleware.
    }
    
    /**
     * Display the routes management page.
     *
     * @return \Illuminate\View\View
     */
    public function index() 
    {
        try {
            $page_title = "Route Management";
            $page_subtitle = "Register and manage all bus routes";
            $page_css = '/assets/css/pages/drivers.css'; // Uses drivers.css
            $page_js = '/assets/js/routes.js';

            try {
                $routes = Route::all();
                if (is_null($routes)) {
                    $routes = [];
                }
            } catch (\Exception $e) {
                Log::error('Database error in RoutesController::index() (routes)', ['error' => $e->getMessage()]);
                $routes = []; 
            }
            
            try {
                // Fetch buses for the dropdown
                $buses = Bus::all(['plate', 'name']);
                if (is_null($buses)) {
                    $buses = [];
                }
            } catch (\Exception $e) {
                Log::error('Database error loading buses in RoutesController::index()', ['error' => $e->getMessage()]);
                $buses = []; 
            }

            // Use Laravel's View facade
            return View::make('pages.routes', [
                'page_title' => $page_title,
                'page_subtitle' => $page_subtitle,
                'page_css' => $page_css,
                'page_js' => $page_js,
                'routes' => $routes, 
                'buses' => $buses   
            ]);
        } catch (\Exception $e) {
            Log::error('Error in RoutesController::index()', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            // Re-throw the exception to let Laravel handle it
            throw $e;
        }
    }

    /**
     * Call the service to create a new route.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request) 
    {
        // Call the service to handle the logic
        $result = $this->routeService->createRoute($request);

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
     * Call the service to update an existing route.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request) 
    {
        // Call the service to handle the logic
        $result = $this->routeService->updateRoute($request);
        
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
     * Call the service to delete a route.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request) 
    {
        // Call the service to handle the logic
        $result = $this->routeService->deleteRoute($request);
        
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