<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\{AuthController, ProductController};



Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    // Users routes
    Route::get('/user', [AuthController::class, 'getUser']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Products routes
    Route::apiResource('products', ProductController::class);
});

Route::post('/login', [AuthController::class, 'login']);
