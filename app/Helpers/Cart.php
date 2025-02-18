<?php

namespace App\Helpers;

use App\Models\CartItem;
use Illuminate\Support\Collection;

class Cart
{
    public static function getCartItemsCount(): int
    {
        $request = request();
        $user = $request->user();

        if ($user) {
            return CartItem::where('user_id', $user->id)->sum('quantity');
        } else {
            $cartItems = self::getCookieCartItems();

            return array_reduce($cartItems, fn($carry, $item) => $carry + $item['quantity'], 0);
        }
    }

    public static function getCartItems(): Collection|array
    {
        $request = request();
        $user = $request->user();

        if ($user) {
            return CartItem::where('user_id', $user->id)->get()->map(fn($item) => ['product_id' => $item->product_id, 'quantity' => $item->quantity]);
        } else {
            return self::getCookieCartItems();
        }
    }

    public static function getCookieCartItems(): array
    {
        $request = request();
        return json_decode($request->cookie('cart_items'), true) ?? [];
    }

    public static function getCountFromItems(array $cartItems): int
    {
        return array_reduce($cartItems, fn($carry, $item) => $carry + $item['quantity'], 0);
    }

    public static function moveCartItemsIntoDB()
    {
        $request = request();
        $cartItems = self::getCookieCartItems();
        $dbCartItems = CartItem::where('user_id', $request->user()->id)->get()->keyBy('product_id');

        $newCartItems = [];

        foreach ($cartItems as $cartItem) {
            if (isset($dbCartItems[$cartItem['product_id']])) {
                continue;
            }

            $newCartItems[] = [
                'product_id' => $cartItem['product_id'],
                'user_id' => $request->user()->id,
                'quantity' => $cartItem['quantity']
            ];
        }

        if ($newCartItems) {
            CartItem::insert($newCartItems);
        }
    }
}
