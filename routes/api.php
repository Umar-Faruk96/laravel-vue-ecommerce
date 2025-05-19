<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\{AuthController, CustomerController, DashboardController, ProductController, OrderController, UserController};

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
	// Users routes
	Route::get('/user', [AuthController::class, 'getUser']);
	Route::post('/logout', [AuthController::class, 'logout']);

	Route::apiResource('users', UserController::class)->only(['index', 'store', 'show', 'update', 'destroy']);

	// Customers routes
	Route::apiResource('customers', CustomerController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
	Route::get('/countries', [CustomerController::class, 'getCountries']);

	// Products routes
	Route::apiResource('products', ProductController::class);

	// Orders routes
	Route::get('/orders/statuses', [OrderController::class, 'getOrderStatuses']);
	Route::post('/orders/change-status/{order}', [OrderController::class, 'changeOrderStatus']);
	// Route::post('/orders/change-status/{order}/{status}', [OrderController::class, 'changeStatus']);
	Route::apiResource('orders', OrderController::class)->only(['index', 'show']);

	// Dashboard routes
	Route::get('/dashboard/active-customers', [DashboardController::class, 'activeCustomers']);
	Route::get('/dashboard/active-products', [DashboardController::class, 'activeProducts']);
	Route::get('/dashboard/paid-orders', [DashboardController::class, 'paidOrders']);
	Route::get('/dashboard/total-sale', [DashboardController::class, 'totalSale']);
});

Route::post('/login', [AuthController::class, 'login']);
