<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;

// Public Routes
Route::post('/customers/register', [CustomerController::class, 'register']);
Route::post('/customers/activate', [CustomerController::class, 'activate']);
Route::post('/login', [AuthController::class, 'login']);


//Protected Routes (Requires a valid Sanctum API token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/dashboard/points', [DashboardController::class, 'points']);
    Route::get('/dashboard/orders', [DashboardController::class, 'orders']);
    Route::post('/logout', [AuthController::class, 'logout']);
});