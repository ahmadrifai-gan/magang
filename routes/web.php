<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmployeeController;

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

// Home Page
Route::get('/', function () {
    return view('index');
})->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Show Login Form
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    
    // Handle Login Submission
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    // Show Register Form
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    
    // Handle Register Submission
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

// Protected Routes (Require Authentication)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Admin Routes
    Route::prefix('admin')->group(function () {
        Route::get('/pending-approvals', [AdminController::class, 'pendingApprovals'])->name('admin.pending-approvals');
        Route::get('/all-requests', [AdminController::class, 'allRequests'])->name('admin.all-requests');
        Route::get('/view-request/{leaveRequest}', [AdminController::class, 'viewRequest'])->name('admin.view-request');
    });

    // Employee Routes
    Route::prefix('employee')->group(function () {
        Route::get('/my-requests', [EmployeeController::class, 'myRequests'])->name('employee.my-requests');
        Route::get('/create-request', [EmployeeController::class, 'createRequest'])->name('employee.create-request');
        Route::post('/store-request', [EmployeeController::class, 'storeRequest'])->name('employee.store-request');
        Route::get('/view-request/{leaveRequest}', [EmployeeController::class, 'viewRequest'])->name('employee.view-request');
        Route::delete('/cancel-request/{leaveRequest}', [EmployeeController::class, 'cancelRequest'])->name('employee.cancel-request');
    });
});
