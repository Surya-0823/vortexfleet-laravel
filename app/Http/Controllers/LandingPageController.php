<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB; // Use Laravel's DB Facade
use Illuminate\Support\Facades\View; // Use Laravel's View Facade

/**
 * Handles the public-facing landing page.
 */
class LandingPageController extends Controller 
{
    /**
     * Display the landing page with statistics.
     *
     * @return \Illuminate\View\View
     */
    public function index() 
    {
        // Fetch statistics using Laravel's DB facade
        $stats = [
            'drivers' => DB::table('drivers')->count(),
            'buses' => DB::table('buses')->count(),
            'students' => DB::table('students')->count(),
            'routes' => DB::table('routes')->count(),
        ];

        $page_title = "VortexFleet - Bus Management System";
        $page_css = '/assets/css/pages/landing.css';
        $page_js = '/assets/js/landing.js';
        
        // Return the view using the View facade
        return View::make('pages.landing', [
            'page_title' => $page_title,
            'page_css' => $page_css,
            'page_js' => $page_js,
            'stats' => $stats,
        ]);
    }
}