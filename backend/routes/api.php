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

        Route::get('/exports', [ExportController::class, 'index']);
        Route::get('/exports/download/{exportId}', [ExportController::class, 'downloadExport']);
        Route::post('/exports', [ExportController::class, 'startExport']);

        Route::get('/docs', function () {
            $path = storage_path('api-docs/api-docs.json');

            if (!file_exists($path)) {
                return response()->json([
                    'error' => 'API documentation not found. Run `php artisan l5-swagger:generate`'
                ], 404);
            }

            return Response::file($path, [
                'Content-Type' => 'application/json'
            ]);
        });
    });

    // User Routes
    Route::apiResource('/orders', OrderController::class)->except(['destroy']);
});
