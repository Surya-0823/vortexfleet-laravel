<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriversController;
use App\Http\Controllers\BusesController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\RoutesController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\LandingPageController;
use App\Http\Middleware\AdminAuthMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Landing Page
Route::get('/', [LandingPageController::class, 'index']);
Route::get('/pricing', [LandingPageController::class, 'pricing']);
Route::post('/submit-contact', [LandingPageController::class, 'submitContact']);

// Auth Routes
// FIX: showLoginForm -> showLogin
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/payment', [AuthController::class, 'payment']);
Route::get('/payment-success', [AuthController::class, 'paymentSuccess']);

// Authenticated Admin Routes
Route::middleware([AdminAuthMiddleware::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Drivers
    Route::get('/drivers', [DriversController::class, 'index']);
    Route::post('/drivers/create', [DriversController::class, 'create']);
    Route::post('/drivers/update', [DriversController::class, 'update']);
    Route::post('/drivers/delete', [DriversController::class, 'delete']);
    Route::post('/drivers/update-status', [DriversController::class, 'updateStatus']);
    Route::post('/drivers/send-otp', [DriversController::class, 'sendOtp']);
    Route::post('/drivers/verify-otp', [DriversController::class, 'verifyOtp']);

    // PUTHU MAATRAM: Puthu route for resetting password
    Route::post('/drivers/reset-password', [DriversController::class, 'resetPassword']);

    // Buses
    Route::get('/buses', [BusesController::class, 'index']);
    Route::post('/buses/create', [BusesController::class, 'create']);
    Route::post('/buses/update', [BusesController::class, 'update']);
    Route::post('/buses/delete', [BusesController::class, 'delete']);
    Route::get('/buses/search-driver', [BusesController::class, 'searchDriver']);
    Route::get('/buses/search-student', [BusesController::class, 'searchStudent']);

    // Students
    Route::get('/students', [StudentsController::class, 'index']);
    Route::post('/students/create', [StudentsController::class, 'create']);
    Route::post('/students/update', [StudentsController::class, 'update']);
    Route::post('/students/delete', [StudentsController::class, 'delete']);
    Route::post('/students/update-status', [StudentsController::class, 'updateStatus']);
    Route::post('/students/send-otp', [StudentsController::class, 'sendOtp']);
    Route::post('/students/verify-otp', [StudentsController::class, 'verifyOtp']);

    // Routes
    Route::get('/routes', [RoutesController::class, 'index']);
    Route::get('/routes/get-route-data', [RoutesController::class, 'getRouteData']);
    Route::post('/routes/save-route', [RoutesController::class, 'saveRoute']);
    Route::post('/routes/delete-route', [RoutesController::class, 'deleteRoute']);
});