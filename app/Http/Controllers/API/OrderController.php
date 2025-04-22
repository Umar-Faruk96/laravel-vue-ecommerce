<?php

namespace App\Http\Controllers\API;

use Illuminate\{Http\Request};
use App\{Models\Order, Http\Controllers\Controller, Http\Resources\OrderListResource, Http\Resources\OrderResource};

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $sortBy = $request->get('sort_by', 'created_at');
        $sortTo = $request->get('sort_to', 'desc');
        $query = Order::query()->orderBy($sortBy, $sortTo);

        if ($search) {
            $query->where('id', 'like', "%{$search}%");
        }

        return OrderListResource::collection($query->paginate($perPage));
    }

    public function show(Order $order)
    {
        return OrderResource::make($order);
    }
}
