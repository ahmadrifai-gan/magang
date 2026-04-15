<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LeaveRequestController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentication Routes (No auth required)
Route::prefix('auth')->group(function () {
    // CSRF Cookie endpoint
    Route::get('csrf-cookie', function () {
        return response()->json(['message' => 'CSRF cookie set']);
    });
    
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    
    // Protected auth routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });
});

// Protected Routes (Require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Leave Requests
    Route::prefix('leave-requests')->group(function () {
        Route::get('/', [LeaveRequestController::class, 'index']);
        Route::post('/', [LeaveRequestController::class, 'store']);
        Route::get('{leaveRequest}', [LeaveRequestController::class, 'show']);
        
        // Admin only routes
        Route::post('{leaveRequest}/approve', [LeaveRequestController::class, 'approve']);
        Route::post('{leaveRequest}/reject', [LeaveRequestController::class, 'reject']);
    });

    // Leave Balance
    Route::get('leave-balance', [LeaveRequestController::class, 'getBalance']);

    // User endpoint
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
