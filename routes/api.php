<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\{AuthController, ProductController, OrderController};



Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    // Users routes
    Route::get('/user', [AuthController::class, 'getUser']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Products routes
    Route::apiResource('products', ProductController::class);
    Route::apiResource('orders', OrderController::class)->only(['index', 'show']);
});

Route::post('/login', [AuthController::class, 'login']);
