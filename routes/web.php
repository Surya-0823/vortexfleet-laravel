<?php

use Illuminate\Support\Facades\Route;

// Namma controllers-oda full path (palaya project-la irunthu)
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriversController;
use App\Http\Controllers\BusesController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\RoutesController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TrackingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Inga namma palaya project-la iruntha ella web routes-ah yum
| Laravel 11 format-ku (Route::) maathipottom.
|
*/

// Landing Page
Route::get('/', [LandingPageController::class, 'index']);
Route::get('/home', [LandingPageController::class, 'index']);
Route::get('/landing', [LandingPageController::class, 'index']);

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/pricing', [AuthController::class, 'showPricing']);
Route::get('/payment', [AuthController::class, 'showPayment']);
Route::post('/payment/process', [AuthController::class, 'processPayment']);
Route::get('/logout', [AuthController::class, 'logout']);

// Mobile App Login Routes
Route::post('/app/driver/login', [AuthController::class, 'appDriverLogin']); 
Route::post('/app/student/login', [AuthController::class, 'appStudentLogin']); 


// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/tracking', [TrackingController::class, 'index']);

// Admin tracking map-oda AJAX route
Route::get('/ajax/admin/bus-locations', [TrackingController::class, 'getAdminBusLocations']);


// --- Drivers Routes ---
Route::get('/drivers', [DriversController::class, 'index']);
Route::post('/drivers/create', [DriversController::class, 'create']);
Route::post('/drivers/update', [DriversController::class, 'update']);
Route::post('/drivers/delete', [DriversController::class, 'delete']);
Route::post('/drivers/update-status', [DriversController::class, 'updateStatus']);
Route::post('/drivers/send-otp', [DriversController::class, 'sendOtp']);
Route::post('/drivers/verify-otp', [DriversController::class, 'verifyOtp']);
Route::post('/drivers/reset-password', [DriversController::class, 'resetPassword']);


// --- Buses Routes ---
Route::get('/buses', [BusesController::class, 'index']);
Route::post('/buses/create', [BusesController::class, 'create']);
Route::post('/buses/update', [BusesController::class, 'update']);
Route::post('/buses/delete', [BusesController::class, 'delete']); 

// --- Students Routes ---
Route::get('/students', [StudentsController::class, 'index']);
Route::post('/students/create', [StudentsController::class, 'create']);
Route::post('/students/update', [StudentsController::class, 'update']);
Route::post('/students/delete', [StudentsController::class, 'delete']); 
Route::post('/students/update-status', [StudentsController::class, 'updateStatus']);
Route::post('/students/send-otp', [StudentsController::class, 'sendOtp']);
Route::post('/students/verify-otp', [StudentsController::class, 'verifyOtp']);
Route::post('/students/reset-password', [StudentsController::class, 'resetPassword']);

// --- Routes Routes ---
Route::get('/routes', [RoutesController::class, 'index']);
Route::post('/routes/create', [RoutesController::class, 'create']);
Route::post('/routes/update', [RoutesController::class, 'update']);
Route::post('/routes/delete', [RoutesController::class, 'delete']);