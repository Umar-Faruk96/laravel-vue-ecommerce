<?php

namespace App\Http\Controllers\API;

use App\Mail\UpdateOrderEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\{Http\Request, Http\JsonResponse};
use App\{Models\Order, Http\Controllers\Controller, Http\Resources\OrderListResource, Http\Resources\OrderResource, Enums\OrderStatus};

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

    public function getOrderStatuses(): JsonResponse
    {
        return response()->json([
            'statuses' => OrderStatus::getStatuses(),
        ]);
    }

    public function changeOrderStatus(Order $order, Request $request): JsonResponse
    {
       $validatedStatus = $request->validate([
            'status' => 'required|in:' . implode(',', OrderStatus::getStatuses()),
        ]);

        $order->update(['status' => $validatedStatus['status']]);

        Mail::to($order->user)->send(new UpdateOrderEmail($order));

        return response()->json([
            'message' => "Order status updated successfully to '{$validatedStatus['status']}'",
        ]);
    }

    // public function changeStatus(Order $order, $status)
    // {
    //     $order->status = $status;
    //     $order->save();

    //     Mail::to($order->user)->send(new OrderUpdateEmail($order));

    //     return response('', 200);
    // }
}
