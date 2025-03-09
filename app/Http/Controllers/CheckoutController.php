<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use App\Helpers\Cart;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use Stripe\Checkout\Session;
use Stripe\Customer;

class CheckoutController extends Controller
{
    public function checkout(Request $request): RedirectResponse|Redirector
    {
        $user = $request->user();

        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        [$products, $cartItems] = Cart::getProductsAndCartItems();

        $lineItems = [];

        foreach ($products as $product) {
            $quantity = $cartItems[$product->id]['quantity'];
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
