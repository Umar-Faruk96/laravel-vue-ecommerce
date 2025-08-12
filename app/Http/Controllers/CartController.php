<?php

namespace App\Http\Controllers;

use App\{Models\CartItem, Models\Product, Helpers\Cart};
use Illuminate\{Support\Facades\Cookie, Http\Request, Http\Response, Support\Str};

class CartController extends Controller
{
    public function index()
    {
        [$products, $cartItems] = Cart::getProductsAndCartItems();
        $total = 0;

        foreach ($products as $product) {
            $total += $product->price * $cartItems[$product->id]['quantity'];
        }

        return view('cart.index', compact('cartItems', 'products', 'total'));
    }

    public function add(Request $request, Product $product): Response
    {
        // dd($request, $product);

        $quantity = $request->post('quantity', 1);
        $user = $request->user();

        if ($user) {
//            User is authenticated
            $cartItem = CartItem::where(['user_id' => $user->id, 'product_id' => $product->id])->first();

            if ($cartItem) {
//                User already has item(s) in the cart
                $totalQuantity = $cartItem->quantity += $quantity;
                if ($totalQuantity > $product->quantity) {
                    return $this->showErrorMsg($product);
                }

                $cartItem->update();
            } else {
//                User does not have item(s) in the cart
                $data = [
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                ];

                if ($quantity > $product->quantity) {
                    return $this->showErrorMsg($product);
                }

                CartItem::create($data);
            }

            return response([
                'count' => Cart::getCartItemsCount()
            ]);
        } else {
//            User is not authenticated
            $cartItems = Cart::getCookieCartItems();

            $productFound = false;

            foreach ($cartItems as &$item) {
                if ($item['product_id'] === $product->id && ($item['quantity'] + $quantity) <= $product->quantity) {
                    $item['quantity'] += $quantity;
                    $productFound = true;
                    break;
                }
            }

            if (!$productFound) {
                if ($quantity > $product->quantity) {
                    return $this->showErrorMsg($product);
                }

                $cartItems[] = [
                    'user_id' => null,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price
                ];
            }

            Cookie::queue('cart_items', json_encode($cartItems), 60 * 24 * 30);

            return response(['count' => Cart::getCountFromItems($cartItems)]);
        }
    }

    public function remove(Request $request, Product $product)
    {
        $user = $request->user();

        if ($user) {
            $cartItem = CartItem::query()->where(['user_id' => $user->id, 'product_id' => $product->id])->first();
            if ($cartItem) {
                $cartItem->delete();
            }

            return response([
                'count' => Cart::getCartItemsCount(),
            ]);
        } else {
            $cartItems = Cart::getCookieCartItems();

            foreach ($cartItems as $i => &$item) {
                if ($item['product_id'] === $product->id) {
                    array_splice($cartItems, $i, 1);
                    break;
                }
            }

            Cookie::queue('cart_items', json_encode($cartItems), 60 * 24 * 30);

            return response(['count' => Cart::getCountFromItems($cartItems)]);
        }
    }

    public function updateQuantity(Request $request, Product $product)
    {
        $quantity = (int)$request->post('quantity');
        $user = $request->user();

        if ($user) {
//            User is authenticated
            if ($quantity > $product->quantity) {
                return $this->showErrorMsg($product);
            }

            CartItem::where(['user_id' => $request->user()->id, 'product_id' => $product->id])->update(['quantity' => $quantity]);

            return response([
                'count' => Cart::getCartItemsCount(),
            ]);
        } else {
//            User is not authenticated
            $cartItems = Cart::getCookieCartItems();

            foreach ($cartItems as &$item) {
                if ($item['product_id'] === $product->id && $quantity <= $product->quantity) {
                    $item['quantity'] = $quantity;
                    break;
                } else {
                    return $this->showErrorMsg($product);
                }
            }

            Cookie::queue('cart_items', json_encode($cartItems), 60 * 24 * 30);

            return response(['count' => Cart::getCountFromItems($cartItems)]);
        }
    }

    private function showErrorMsg(Product $product): Response
    {
        return response(['message' => match ($product->quantity) {
            0, null => 'The "' . $product->title . '" product is out of stock',
            1 => 'Only 1 item left in stock for the "' . $product->title . '" product',
            default => 'You can buy only ' . $product->quantity . ' ' . Str::plural('item', $product->quantity) . ' left in stock for the "' . $product->title . '" product',
        }], 422);
    }
}
