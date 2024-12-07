<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Admins Routes
    Route::middleware('admin')->group(function () {
        Route::apiResource('orders', OrderController::class);
    });

    // User Routes
    Route::apiResource('orders', OrderController::class)->except(['destroy']);
});
