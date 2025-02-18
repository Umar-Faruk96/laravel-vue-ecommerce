<x-app-layout>
    <div class="grig-cols-1 grid gap-8 p-5 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @foreach ($products as $product)
            <!-- Product Item -->
            <div class="border-1 rounded-md border border-gray-200 bg-white transition-colors hover:border-purple-600">
                <a href="{{ route('products.show', $product->slug) }}"
                    class="aspect-h-3 aspect-w-2 block overflow-hidden">
                    <img src="{{ $product->image }}" alt=""
                        class="rounded-lg object-cover transition-transform hover:rotate-1 hover:scale-105" />
                </a>
                <div class="p-4">
                    <h3 class="text-lg">
                        <a href="{{ route('products.show', $product->slug) }}">
                            {{ $product->title }}
                        </a>
                    </h3>
                    <h5 class="font-bold">
                        <span class="mr-1 font-sans text-lg font-extrabold">৳</span>{{ $product->price }}
                    </h5>
                </div>
                <div class="flex justify-between px-4 py-3">
                    <button @click="addToWatchlist()"
                        class="border-1 flex h-10 w-10 items-center justify-center rounded-full border border-purple-600 transition-colors hover:bg-purple-600 hover:text-white active:bg-purple-800"
                        :class="isInWatchlist(id) ? 'bg-purple-600 text-white' : 'text-purple-600'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                    <button class="btn-primary" @click="addToCart(id)">
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
