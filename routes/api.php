<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\ApiAuthController;
use App\Http\Controllers\Api\DocumentRequestController;
use App\Http\Controllers\Api\ComplaintController;
use App\Http\Controllers\DashboardController;

// Mobile API Routes
Route::post('/mobile/login', [ApiAuthController::class, 'login']);
Route::post('/register', [ApiAuthController::class, 'register']);




// Protected API Routes
Route::middleware('auth:sanctum')->group(function () {
    // Document Request Routes

    Route::post('/documents', [DocumentRequestController::class, 'store']);
   
    Route::get('/my-requests', [DocumentRequestController::class, 'myRequests']);

    // Complaint Routes
    Route::get('/complaints', [ComplaintController::class, 'index']);
    Route::post('/complaints', [ComplaintController::class, 'store']);
    Route::post('/complaints/track', [ComplaintController::class, 'track']);

    // Profile Routes
    Route::get('/profile', [ApiAuthController::class, 'viewProfile']);
    Route::put('/profile', [ApiAuthController::class, 'editProfile']);
    Route::post('/logout', [ApiAuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

// Dashboard API Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard/statistics', [DashboardController::class, 'getStatistics']);
    Route::get('/dashboard/charts', [DashboardController::class, 'getCharts']);
});

Route::get('/test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'API is working'
    ]);
});