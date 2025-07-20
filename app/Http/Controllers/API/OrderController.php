<?php

namespace App\Http\Controllers\API;

use App\Mail\OrderUpdated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\{Http\Request, Http\JsonResponse};
use App\{Models\Order, Http\Controllers\Controller, Http\Resources\OrderListResource, Http\Resources\OrderResource, Enums\OrderStatus};

class OrderController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $sortBy = $request->get('sort_by', 'created_at');
        $sortTo = $request->get('sort_to', 'desc');
        $query = Order::query()->withCount('items')->with('user.customer.shippingAddress.country', 'user.customer.billingAddress.country')->orderBy($sortBy, $sortTo);

        if ($search) {
            $query->where('id', 'like', "%$search%");
        }

        return OrderListResource::collection($query->paginate($perPage));
    }

    public function show(Order $order): JsonResource
    {
        $order = $order->load([
            'items.product',
            'user.customer.shippingAddress.country',
            'user.customer.billingAddress.country'
        ]);
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

        Mail::to($order->user)->send(new OrderUpdated($order));

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
