<x-app-layout>
    <div class="container mx-auto lg:w-2/3 xl:w-2/3">
        <h1 class="mb-6 text-3xl font-bold dark:text-gray-200">Your Cart Items</h1>

        {{-- @dump($cartItems) --}}

        <div x-data="{
            cartItems: {{ json_encode(
                $products->map(
                    fn($product) => [
                        'id' => $product->id,
                        'slug' => $product->slug,
                        'image' => $product->image,
                        'title' => $product->title,
                        'price' => $product->price,
                        'quantity' => $cartItems[$product->id]['quantity'] ?? 1,
                        'href' => route('product.show', $product->slug),
                        'removeUrl' => route('cart.remove', $product),
                        'updateQuantityUrl' => route('cart.update-quantity', $product),
                    ],
                ),
            ) }},
            get cartTotal() {
                return this.cartItems.reduce((accum, next) => accum + next.price * next.quantity, 0).toFixed(2)
            },
        }" class="rounded-lg bg-white p-4 shadow">
            <!-- Product Items -->
            <template x-if="cartItems.length">
                <div>
                    <!-- Product Item -->
                    <template x-for="product of cartItems" :key="product.id">
                        <div x-data="productItem(product)">
                            <div class="flex w-full flex-1 flex-col items-center gap-4 sm:flex-row">
                                <a :href="product.href"
                                    class="flex h-32 w-36 items-center justify-center overflow-hidden">
                                    <img :src="product.image" class="object-cover" alt="" />
                                </a>

                                <div class="flex flex-1 flex-col justify-between">
                                    <div class="mb-3 flex justify-between">
                                        <h3 x-text="product.title"></h3>
                                        <span class="text-lg font-semibold">
                                            ৳<span x-text="product.price"></span>
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            Qty:
                                            <input type="number" min="1" x-model="product.quantity"
                                                @change="changeQuantity()"
                                                class="ml-3 w-16 border-gray-200 py-1 focus:border-purple-600 focus:ring-purple-600" />
                                        </div>

                                        <a href="#" @click.prevent="removeItemFromCart()"
                                            class="text-purple-600 hover:text-purple-500">Remove</a>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-5" />
                        </div>
                    </template>
                    <!--/ Product Item -->

                    <div class="border-t border-gray-300 pt-4">
                        <div class="flex justify-between">
                            <span class="font-semibold">Subtotal</span>
                            <span id="cartTotal" class="text-xl" x-text="`৳${cartTotal}`"></span>
                        </div>

                        <p class="mb-6 text-gray-500">
                            Shipping and taxes calculated at checkout.
                        </p>

                        <form action="{{ route('checkout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-primary w-full py-3 text-lg">
                                Proceed to Checkout
                            </button>
                        </form>
                    </div>
                </div>
                <!--/ Product Items -->
            </template>

            <template x-if="!cartItems.length">
                <div class="py-8 text-center text-gray-500">
                    You don't have any items in cart
                </div>
            </template>

        </div>
    </div>
</x-app-layout>
