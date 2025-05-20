<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\{Customer, Product, Order};
use App\Enums\CustomerStatus;
use App\Enums\OrderStatus;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
	public function activeCustomers() : JsonResponse
	{
		$activeCustomers = Customer::where('status', CustomerStatus::Active->value)->count();
		return response()->json(['active_customers' => $activeCustomers]);
	}
	
	public function activeProducts() : JsonResponse
	{
		// TODO: Implement the logic to count active products
		$activeProducts = Product::count('id');
		
		return response()->json(['active_products' => $activeProducts]);
	}
	
	public function paidOrders() : JsonResponse
	{
		$paidOrders = Order::where('status', OrderStatus::Paid->value)->count();
		
		return response()->json(['paid_orders' => $paidOrders]);
	}
	
	public function totalSale() : JsonResponse
	{
		$totalSale = Order::where('status', OrderStatus::Paid->value)->sum('total_price');
		
		return response()->json(['total_sale' => $totalSale]);
	}
}