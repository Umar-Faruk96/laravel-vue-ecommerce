<?php

namespace App\Http\Controllers;

use App\Helpers\Cart;
use Illuminate\Support\Facades\Redirect;
use App\Enums\{OrderStatus, PaymentStatus};
use App\Models\{Order, Payment, CartItem, OrderItem};
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\{View\View, Http\Request, Routing\Redirector, Http\RedirectResponse, Support\Facades\Session};

class CheckoutController extends Controller
{
    public function checkout(Request $request): RedirectResponse|Redirector
    {
        $user = $request->user();

        // unique transection id for every transection
        $tran_id = "test" . rand(1111111, 9999999);

        // aamarPay support Two type of currency USD & BDT  
        $currency = "BDT";

        // 10 taka is the minimum amount for show card option in aamarPay payment gateway
        $amount = "10";

        // aamarPay demo store id
        $store_id = config('aamarpay.store_id');

        // aamarPay demo signature key
        $signature_key = config('aamarpay.signature_key');

        // aamarPay payment gateway url
        $url = "https://​sandbox​.aamarpay.com/jsonpost.php";

        [$products, $cartItems] = Cart::getProductsAndCartItems();

        $orderItems = [];
        $totalPrice = 0;

        // dump($products);

        foreach ($products as $product) {

            $quantity = $cartItems[$product->id]['quantity'];
            $totalPrice += $product->price * $quantity;

            $orderItems[] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $product->price
            ];
        }


        $curl = curl_init();

        // initial setup for aamarPay payment gateway
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([
                'store_id' => $store_id,
                'tran_id' => $tran_id,
                'success_url' => route('checkout.success', [], true),
                'fail_url' => route('checkout.fail', [], true),
                'cancel_url' => route('checkout.cancel', [], true),
                'amount' => $totalPrice,
                'currency' => $currency,
                'signature_key' => $signature_key,
                'desc' => 'Payment for ' . $user->customer->first_name . ' ' . $user->customer->last_name,
                'cus_name' => $user->customer->first_name . ' ' . $user->customer->last_name,
                'cus_email' => $user->customer->email,
                'cus_add1' => $user->customer->shippingAddress->present_address,
                'cus_add2' => $user->customer->billingAddress->present_address,
                'cus_city' => $user->customer->shippingAddress->city,
                'cus_state' => $user->customer->shippingAddress->state,
                'cus_postcode' => $user->customer->shippingAddress->zip_code,
                'cus_country' => $user->customer->shippingAddress->country_code,
                'cus_phone' => $user->customer->phone,
                'type' => 'json'
            ]),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        // dump($response);

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
            'type' => 'cc',
            'gateway' => 'aamarpay',
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'session_id' => $tran_id
        ];
        Payment::create($paymentData);

        CartItem::where(['user_id' => $user->id])->delete();

        $responseObj = json_decode($response);

        // dd($responseObj);

        if (!isset($responseObj->payment_url) && empty($responseObj->payment_url)) {
            echo $response;
        }

        // dd($responseObj->payment_url);

        return redirect()->away($responseObj->payment_url);
    }

    public function success(Request $request): View
    {
        dd($request);

        $user = $request->user();

        try {
            $tran_id = $request->get('session_id');

            if (!$tran_id) {
                return view('checkout.failure', ['message' => 'Invalid Session ID']);
            }

            $payment = Payment::query()
                ->where(['session_id' => $tran_id])
                ->whereIn('status', [PaymentStatus::Pending, PaymentStatus::Paid])
                ->first();

            if (!$payment) {
                throw new NotFoundHttpException();
            }

            if ($payment->status === PaymentStatus::Pending->value) {
                $this->updateOrderAndSession($payment);
            }


            return view('checkout.success', compact('customer'));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (\Exception $e) {
            return view('checkout.failure', ['message' => $e->getMessage()]);
        }
    }

    public function cancel(Request $request)
    {
        return $request;
    }
    public function fail(Request $request)
    {
        return view('checkout.failure', ['message' => ""]);
    }

    public function checkoutOrder(Order $order, Request $request)
    {
        $lineItems = [];

        foreach ($order->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item->product->title,
                        //                        'images' => [$product->image]
                    ],
                    'unit_amount' => $item->unit_price * 100,
                ],
                'quantity' => $item->quantity,
            ];
        }

        $session = Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel', [], true),
        ]);

        $order->payment->session_id = $session->id;
        $order->payment->save();


        return redirect($session->url);
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
