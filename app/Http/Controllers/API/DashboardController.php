<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\{Customer, Product, Order};
use App\Enums\CustomerStatus;
use App\Enums\OrderStatus;

class DashboardController extends Controller
{
    public function activeCustomers()
    {
        $activeCustomers = Customer::where('status', CustomerStatus::Active->value)->count();
        return response()->json(['active_customers' => $activeCustomers]);
    }

    public function activeProducts()
    {
        // TODO: Implement the logic to count active products
        $activeProducts = Product::count();

        return response()->json(['active_products' => $activeProducts]);
    }

    public function paidOrders()
    {
        $paidOrders = Order::where('status', OrderStatus::Paid->value)->count();

        return response()->json(['paid_orders' => $paidOrders]);
    }

    public function totalSale()
    {
        $totalSale = Order::where('status', OrderStatus::Paid->value)->sum('total_price');

        return response()->json(['total_sale' => $totalSale]);
    }
}
