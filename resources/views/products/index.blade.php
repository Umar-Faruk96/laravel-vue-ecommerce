<x-app-layout>
    <div
        class="grid grig-cols-1 gap-8 p-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 text-gray-700 dark:text-gray-300">
        @if ($products->isEmpty())
            <div class="col-span-full text-center">
                <h2 class="text-xl sm:text-3xl font-bold">No Products Found</h2>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Try searching for something else.</p>
            </div>
        @else
            <div
                class="col-span-full flex flex-col md:flex-row items-center justify-center md:justify-between text-center md:text-left">
                <div>
                    <h2 class="text-xl sm:text-3xl font-bold">Newly Added Products</h2>
                    <p class="mt-2 text-gray-600 dark:text-gray-400 md:hidden">Explore our latest products.</p>
                </div>
                <button type="button" title="See Products" class="mt-4 md:mt-0">
                    <a href="{{ route('home') }}" class="btn-primary">
                        View All Products
                    </a>
                </button>
            </div>
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
                    class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 border-1 rounded-md border border-gray-200 dark:border-gray-800 transition-colors hover:border-purple-600">
                    <a href="{{ route('product.show', $product->slug) }}"
                        class="aspect-h-2 aspect-w-2 block overflow-hidden">
                        <img src="{{ $product->image }}" alt=""
                            class="rounded-t-lg object-cover transition-transform hover:rotate-1 hover:scale-105" />
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
                        <button type="button" title="Add To Cart" class="btn-primary" @click="addToCart()">
                            Add to Cart
                        </button>
                    </div>
                </div>
                <!--/ Product Item -->
            @endforeach
        @endif
    </div>

    {{-- Product Pagination --}}
    {{ $products->links() }}
</x-app-layout>
