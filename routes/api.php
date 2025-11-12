<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Namma palaya API controller-ah import panrom
use App\Http\Controllers\AppApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Inga namma palaya project-la iruntha Mobile App API routes-ah
| Laravel 11 format-ku maathipottom.
| Intha routes-ku automatically '/api' prefix add aagidum.
|
*/

// Driver status API (GET /api/driver/status)
Route::get('/driver/status', [AppApiController::class, 'getDriverStatus']);

// Student Tracking API (GET /api/student/tracking)
Route::get('/student/tracking', [AppApiController::class, 'getStudentTracking']);

// Driver Real-time Location Update API (POST /api/driver/update-location)
Route::post('/driver/update-location', [AppApiController::class, 'updateLocation']);