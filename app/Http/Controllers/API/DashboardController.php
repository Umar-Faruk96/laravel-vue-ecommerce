<?php

namespace App\Http\Controllers\API;

use App\Enums\AddressType;
use App\Enums\OrderStatus;
use App\Enums\CustomerStatus;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\{Customer, Product, Order};

class DashboardController extends Controller
{
	public function activeCustomers(Request $request) : JsonResponse
	{
		$activeCustomers = Customer::where('status', '=', CustomerStatus::Active->value)->count();
		
		return response()->json(['active_customers' => $activeCustomers]);
	}
	
	public function activeProducts() : JsonResponse
	{
		// TODO: Implement the logic to count active products
		$activeProducts = Product::count('id');
		
		return response()->json(['active_products' => $activeProducts]);
	}
	
	public function paidOrders(Request $request) : JsonResponse
	{
		$paidOrders = Order::where([
			['status', '=', OrderStatus::Paid->value],
			['created_at', '>=', $this->searchQueryResults($request)]
		])->count();
		
		return response()->json(['paid_orders' => $paidOrders]);
	}
	
	public function totalSale(Request $request) : JsonResponse
	{
		$totalSale = Order::where([
			['status', OrderStatus::Paid->value],
			['created_at', '>=', $this->searchQueryResults($request)]
		])->sum('total_price');
		
		return response()->json(['total_sale' => $totalSale]);
	}
	
	public function ordersByCountry(Request $request) : JsonResponse
	{
		$orders = Order::query()
			->join('users', 'created_by', '=', 'users.id')
			->join('customer_addresses AS ca', 'ca.customer_id', '=', 'users.id')
			->join('countries AS c', 'ca.country_code', '=', 'c.code')
			->selectRaw('c.name AS countryName, count(orders.id) AS count')
			->groupBy('countryName')
			->whereIn('status', [OrderStatus::Paid->value, OrderStatus::Shipped->value, OrderStatus::Completed->value])
			->where([
				['ca.type', AddressType::Shipping->value],
				['orders.created_at', '>=', $this->searchQueryResults($request)]
			])
			->get();
		
		return response()->json(['orders_by_country' => $orders]);
	}
	
	public function latestCustomers() : JsonResponse
	{
		$latestCustomers = Customer::where('status', CustomerStatus::Active->value)->orderBy('created_at', 'desc')->limit(5)->get(['user_id', DB::raw('CONCAT_WS(" ", first_name, last_name) AS full_name'), 'email', 'phone', 'created_at']);
		
		return response()->json(['latest_customers' => $latestCustomers]);
	}
	
	public function latestOrders() : JsonResponse
	{
		$latestOrders = Order::with(['items', 'user'])->whereIn('status', [OrderStatus::Paid->value, OrderStatus::Shipped->value, OrderStatus::Completed->value])->orderBy('created_at', 'desc')->limit(10)->get();
		
		$latestOrders->transform(function($order) {
			return [
				'id' => $order->id,
				'created_at' => $order->created_at->diffForHumans(),
				'total_price' => $order->total_price,
				'items_count' => $order->items->count(),
				'full_name' => $order->user->name
			];
		});
		
		return response()->json(['latest_orders' => $latestOrders]);
	}
	
	private function searchQueryResults(Request $request)
	{
		$queryValue = $request->get('dateQuery') ?? 'all';
		
		$queryOptions = [
			'1d' => Carbon::now()->subDays(),
			'1w' => Carbon::now()->subWeek(),
			'2w' => Carbon::now()->subWeeks(2),
			'1m' => Carbon::now()->subMonth(),
			'3m' => Carbon::now()->subMonths(3),
			'6m' => Carbon::now()->subMonths(6),
			'1y' => Carbon::now()->subYear(),
			'all' => '0000-00-00'
		];
		
		return $queryOptions[$queryValue];
	}
}