<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductController::class, 'index']);

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login')->middleware('guest');

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/user', 'user');
        Route::post('/logout', 'logout');
    });
});

Route::middleware(['auth:sanctum'])->group(function () {
    // Admins Routes
    Route::middleware('admin')->group(function () {
        Route::delete('/orders/{order}', [OrderController::class, 'destroy']);

        Route::post('/exports', [ExportController::class, 'startExport']);
        Route::get('/exports/download/{exportId}', [ExportController::class, 'downloadExport']);
    });

    // User Routes
    Route::apiResource('/orders', OrderController::class)->except(['destroy']);
});
