<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Traits\ReportTrait;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    use ReportTrait;

    public function orders(Request $request): JsonResponse
    {
        $searchDate = $this->searchQueryResults($request);

        $orders = Order::select([DB::raw('CAST(created_at as DATE) AS day'), DB::raw('COUNT(created_at) AS order_placed')])->where('created_at', '>', $searchDate)->groupBy('day')->get();

        $labels = [];
        $now = Carbon::now();
        $searchDate = Carbon::parse($searchDate);
        
        while ($searchDate < $now) {
            $labels[] = $searchDate->format('Y-m-d');
            $searchDate->addDay();
        }

        $data = collect($labels)->map(function ($label) use ($orders) {
            $orderCount = $orders->firstWhere('day', $label);
            return $orderCount ? $orderCount->order_placed : 0;
        });


        return response()->json(['orders' => $orders, 'labels' => $labels, 'dataset' => $data]);
    }

    public function customers()
    {
        // Logic to retrieve customer reports
    }
}
