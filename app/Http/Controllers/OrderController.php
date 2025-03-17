<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::query()
            ->where(['created_by' => $request->user()->id])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('order.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->created_by !== request()->user()->id) {
            return response("You don't have permission to view this order", 403);
        }

        return view('order.show', compact('order'));
    }
}
