<?php

namespace App\Http\Controllers; 

use App\Http\Controllers\Controller; 
use App\Repositories\DashboardRepository;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use App\Models\User; // <-- ITHA PUTHUSA ADD PANNANUM

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
     */
    public function __construct(DashboardRepository $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository; 
        // Middleware will handle auth
    }

    /**
     * Display the dashboard page with counts.
     *
     * @return \Illuminate\View\View
     */
    public function index() 
    {
        // --- ITHU THAAN PUDHU FIX ---
        // Session-la irunthu user ID-ah edukkalam (Login appo set aagirukkum)
        $userId = Session::get('user_id');

        // Antha ID-ah vechi user model-ah edukkalam
        $user = User::find($userId);

        // User illana, safety-kku login-ku anuppidalam
        if (!$user) {
            return redirect('/login')->with('error', 'Session invalid. Please log in again.');
        }
        // --- FIX MUDINJATHU ---

        $page_title = "Dashboard";
        // Ippo $user object-la irunthu name edukkalam
        $page_subtitle = "Welcome back, " . ($user->name ?? 'User') . "! Here's your overview";
        $page_css = '/assets/css/pages/dashboard.css';

        // Ippo $user object-ah repository-ku anuppalam (ithu null-ah irukkathu)
        $data = $this->dashboardRepository->getDashboardData($user);

        // View-ku data-voda serthu title, css ellathayum anuppalam
        $viewData = array_merge(
            [
                'page_title' => $page_title,
                'page_subtitle' => $page_subtitle,
                'page_css' => $page_css,
            ],
            $data // Repository-la irunthu vantha ella key/value-vum add aagidum
        );

        return View::make('pages.dashboard', $viewData);
    }
}