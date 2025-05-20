<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\ApiAuthController;
use App\Http\Controllers\Api\DocumentRequestController;
use App\Http\Controllers\Api\ComplaintController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Mobile API Routes
Route::post('/mobile/login', [ApiAuthController::class, 'login']);
Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/documents', [DocumentRequestController::class, 'store']);

// Complaint Routes
Route::get('/complaints', [ComplaintController::class, 'index']);
Route::post('/complaints', [ComplaintController::class, 'store']);
Route::post('/complaints/track', [ComplaintController::class, 'track']);

Route::get('/test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'API is working'
    ]);
});