<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\Customer;
use App\Helpers\Cart;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\View\View;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;

class CheckoutController extends Controller
{
    public function checkout(Request $request): RedirectResponse|Redirector
    {
        $user = $request->user();

        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        [$products, $cartItems] = Cart::getProductsAndCartItems();

        $lineItems = [];
        $totalPrice = 0;

        foreach ($products as $product) {
            $quantity = $cartItems[$product->id]['quantity'];
            $totalPrice += $product->price * $quantity;
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $product->title,
                        'images' => [$product->image]
                    ],
                    'unit_amount' => $product->price * 100,
                ],
                'quantity' => $quantity,
            ];
        }

        $session = Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true),
            'cancel_url' => route('checkout.cancel', [], true),
        ]);

        // Create Order
        $orderData = [
            'total_price' => $totalPrice,
            'status' => OrderStatus::Unpaid,
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ];
        $order = Order::create($orderData);

        // Create Payment
        $paymentData = [
            'order_id' => $order->id,
            'amount' => $totalPrice,
            'status' => PaymentStatus::Pending,
            'type' => 'cc',
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'session_id' => $session->id
        ];
        Payment::create($paymentData);

        return redirect($session->url);
    }

    public function success(Request $request): View
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        try {
            $session = Session::retrieve($request->get('session_id'));

            if (!$session) {
                return view('checkout.failure');
            }

            $customer = Customer::retrieve($session->customer);

            return view('checkout.success', compact('customer'));
        } catch (\Exception $exception) {
            return view('checkout.failure');
        }
    }

    public function cancel(Request $request): void
    {
        dd($request->all());
    }
}
