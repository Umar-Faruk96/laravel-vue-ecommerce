<?php

namespace App\Helpers;

use App\Models\{Product, CartItem};
use Illuminate\{Database\Eloquent\Collection as EloquentCollection, Support\Arr, Support\Collection};

class Cart
{
    public static function getCartItems(): Collection|array
    {
        $user = request()->user();

        if ($user) {
            return CartItem::where('user_id', $user->id)->get()->map(fn($item) => ['product_id' => $item->product_id, 'quantity' => $item->quantity]);
        } else {
            return self::getCookieCartItems();
        }
    }

    public static function getCookieCartItems(): array
    {
        return json_decode(request()->cookie('cart_items', '[]'), true);
    }

    public static function getCartItemsCount(): int
    {
        $user = request()->user();

        if ($user) {
            return CartItem::where('user_id', $user->id)->sum('quantity');
        } else {
            $cartItems = self::getCookieCartItems();

            return self::getCountFromItems($cartItems);
        }
    }

    public static function getCountFromItems(array $cartItems): int
    {
        return array_reduce($cartItems, fn($carry, $item) => $carry + $item['quantity'], 0);
    }

    public static function moveCartItemsIntoDB()
    {
        $cartItems = self::getCookieCartItems();
        $dbCartItems = CartItem::where('user_id', request()->user()->id)->get()->keyBy('product_id');

        $newCartItems = [];

        foreach ($cartItems as $cartItem) {
            if (isset($dbCartItems[$cartItem['product_id']])) {
                continue;
            }

            $newCartItems[] = [
                'product_id' => $cartItem['product_id'],
                'user_id' => request()->user()->id,
                'quantity' => $cartItem['quantity']
            ];
        }

        if ($newCartItems) {
            CartItem::insert($newCartItems);
        }
    }

    public static function getProductsAndCartItems(): array|EloquentCollection
    {
        $cartItems = self::getCartItems();

        // dump($cartItems);

        $cartItems = Arr::keyBy($cartItems, 'product_id');

        $ids = Arr::pluck($cartItems, 'product_id');
        $products = Product::query()->whereIn('id', $ids)->get();

        return [$products, $cartItems];
    }
}
