<x-app-layout>
    <div class="grig-cols-1 grid gap-8 p-5 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @foreach ($products as $product)
            <!-- Product Item -->
            <div x-data="productItem({{ json_encode([
                'id' => $product->id,
                'slug' => $product->slug,
                'image' => $product->image,
                'title' => $product->title,
                'price' => $product->price,
                'addToCartUrl' => route('cart.add', $product),
            ]) }})"
                class="border-1 rounded-md border border-gray-200 bg-white transition-colors hover:border-purple-600">
                <a href="{{ route('product.show', $product->slug) }}"
                    class="aspect-h-3 aspect-w-2 block overflow-hidden">
                    <img src="{{ $product->image }}" alt=""
                        class="rounded-lg object-cover transition-transform hover:rotate-1 hover:scale-105" />
                </a>
                <div class="p-4">
                    <h3 class="text-lg">
                        <a href="{{ route('product.show', $product->slug) }}">
                            {{ $product->title }}
                        </a>
                    </h3>
                    <h5 class="font-bold">
                        <span class="mr-1 font-sans text-lg font-extrabold">৳</span>{{ $product->price }}
                    </h5>
                </div>
                <div class="flex justify-between px-4 py-3">
                    <button class="btn-primary" @click="addToCart()">
                        Add to Cart
                    </button>
                </div>
            </div>
            <!--/ Product Item -->
        @endforeach
    </div>
    {{-- Product Pagination --}}
    {{ $products->links() }}
</x-app-layout>
