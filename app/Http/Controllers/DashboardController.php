<?php

namespace App\Http\Controllers; 

use App\Http\Controllers\Controller; 
use App\Repositories\DashboardRepository; // We will migrate this file later
use Illuminate\Support\Facades\View; // Use Laravel's View Facade
use Illuminate\Support\Facades\Session; // Use Laravel's Session Facade

/**
 * Handles loading the main admin dashboard page.
 */
class DashboardController extends Controller 
{
    /**
     * @var DashboardRepository
     */
    protected $dashboardRepository;

    /**
     * Constructor to initialize the repository.
     * Auth is no longer handled here, it will be handled by web middleware.
     */
    public function __construct()
    {
        // We create an instance of the repository
        $this->dashboardRepository = new DashboardRepository(); 
        
        // The old 'checkAuth()' logic is REMOVED from here.
        // We will protect this route in 'routes/web.php' using middleware.
    }
    
    /**
     * Display the dashboard page with counts.
     *
     * @return \Illuminate\View\View
     */
    public function index() 
    {
        $page_title = "Dashboard";
        // Get user name from Laravel Session
        $page_subtitle = "Welcome back, " . (Session::get('user_name') ?? 'User') . "! Here's your overview";
        $page_css = '/assets/css/pages/dashboard.css';
        
        // Fetch data using the repository
        $driver_count = $this->dashboardRepository->getDriverCount();
        $bus_count = $this->dashboardRepository->getBusCount();
        $student_count = $this->dashboardRepository->getStudentCount();
        
        // Return the view using the View facade
        return View::make('pages.dashboard', [
            'page_title' => $page_title,
            'page_subtitle' => $page_subtitle,
            'page_css' => $page_css,
            'driver_count' => $driver_count,
            'bus_count' => $bus_count,
            'student_count' => $student_count,
        ]);
    }
}