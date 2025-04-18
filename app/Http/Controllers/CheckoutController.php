<?php

namespace App\Http\Controllers;

use Stripe\{Customer, StripeClient};
use App\Helpers\Cart;
use App\Models\{Order, Payment, CartItem, OrderItem};
use Illuminate\{View\View, Http\Request, Routing\Redirector, Http\RedirectResponse};
use App\Enums\{OrderStatus, PaymentStatus};
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckoutController extends Controller
{
    public function checkout(Request $request): RedirectResponse|Redirector
    {
        $user = $request->user();

        $stripe = new StripeClient(config('stripe.stripe_sk'));

        [$products, $cartItems] = Cart::getProductsAndCartItems();

        $orderItems = [];
        $lineItems = [];
        $totalPrice = 0;

        foreach ($products as $product) {
            $quantity = $cartItems[$product->id]['quantity'];
            $totalPrice += $product->price * $quantity;

            // initial setup for stripe
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $product->title,
                        // 'images' => [$product->image]
                    ],
                    'unit_amount' => $product->price * 100,
                ],
                'quantity' => $quantity,
            ];

            $orderItems[] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $product->price
            ];
        }

        // stripe setup
        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'customer_email' => $user->email,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
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

        // Create Order Items
        foreach ($orderItems as $orderItem) {
            $orderItem['order_id'] = $order->id;
            OrderItem::create($orderItem);
        }

        // Create Payment
        $paymentData = [
            'order_id' => $order->id,
            'amount' => $totalPrice,
            'status' => PaymentStatus::Pending,
            'gateway' => 'stripe',
            'type' => 'cc',
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'session_id' => $checkout_session->id
        ];
        Payment::create($paymentData);

        CartItem::where(['user_id' => $user->id])->delete();

        return redirect($checkout_session->url);
    }

    public function success(Request $request): View
    {
        // dd($request->all());

        $user = $request->user();
        $stripe = new StripeClient(config('stripe.stripe_sk'));

        try {
            $session_id = $request->get('session_id');
            $session = $stripe->checkout->sessions->retrieve($session_id);
            if (!$session) {
                return view('checkout.failure', ['message' => 'Invalid Session ID']);
            }

            $payment = Payment::query()
                ->where(['session_id' => $session_id])
                ->whereIn('status', [PaymentStatus::Pending, PaymentStatus::Paid])
                ->first();

            if (!$payment) {
                // dd($payment);
                throw new NotFoundHttpException();
            }

            if ($payment->status === PaymentStatus::Pending->value) {
                $this->updateOrderAndSession($payment);
            }

            // dd($session->customer_details->name);
            // $customer = $stripe->customers->retrieve($session->customer);
            $customer = $session->customer_details;
            // dd($customer);

            return view('checkout.success', compact('customer'));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Exception $e) {
            return view('checkout.failure', ['message' => $e->getMessage()]);
        }
    }

    public function cancel(Request $request): View
    {
        return view('checkout.failure', ['message' => ""]);
    }

    public function checkoutOrder(Order $order, Request $request)
    {
        $stripe = new StripeClient(config('stripe.stripe_sk'));

        $lineItems = [];

        foreach ($order->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item->product->title,
                    ],
                    'unit_amount' => $item->unit_price * 100,
                ],
                'quantity' => $item->quantity,
            ];
        }

        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel', [], true),
        ]);

        $order->payment->session_id = $checkout_session->id;
        $order->payment->save();


        return redirect($checkout_session->url);
    }

    private function updateOrderAndSession(Payment $payment)
    {
        $payment->status = PaymentStatus::Paid;
        $payment->update();

        $order = $payment->order;

        $order->status = OrderStatus::Paid;
        $order->update();
    }
}
