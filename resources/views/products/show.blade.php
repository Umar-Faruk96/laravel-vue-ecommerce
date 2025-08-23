<x-app-layout>
    <div x-data="productItem({{ json_encode([
        'id' => $product->id,
        'slug' => $product->slug,
        'image' => $product->image ?? Storage::url('images/no-image-placeholder.png'),
        'title' => $product->title,
        'price' => $product->price,
        'quantity' => $product->quantity,
        'addToCartUrl' => route('cart.add', $product),
    ]) }})" class="container mx-auto">
        <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-5">
            <div class="lg:col-span-3">
                <div x-data="{
                    images: ['{{ $product->images ? $product->images->map(fn($image) => $image->url) : Storage::url('images/no-image-placeholder.png') }}'],
                    activeImage: null,
                    prev() {
                        let index = this.images.indexOf(this.activeImage);
                        if (index === 0)
                            index = this.images.length;
                        this.activeImage = this.images[index - 1];
                    },
                    next() {
                        let index = this.images.indexOf(this.activeImage);
                        if (index === this.images.length - 1)
                            index = -1;
                        this.activeImage = this.images[index + 1];
                    },
                    init() {
                        this.activeImage = this.images.length > 0 ? this.images[0] : null
                    }
                }">
                    <div class="relative dark:bg-gray-800">
                        <template x-for="image in images">
                            <div x-show="activeImage === image" class="aspect-h-2 aspect-w-3">
                                <img :src="image" alt="" class="mx-auto w-auto"/>
                            </div>
                        </template>

                        <a @click.prevent="prev"
                           class="absolute left-0 top-1/2 ml-2 -translate-y-1/2 cursor-pointer rounded-sm bg-black/30 text-white dark:bg-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </a>

                        <a @click.prevent="next"
                           class="absolute right-0 top-1/2 mr-2 -translate-y-1/2 cursor-pointer rounded-sm bg-black/30 text-white dark:bg-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>

                    <div class="flex">
                        <template x-for="image in images">
                            <a @click.prevent="activeImage = image"
                               class="flex h-[80px] w-[80px] cursor-pointer items-center justify-center border border-gray-300 hover:border-purple-500 dark:border-gray-600 dark:bg-gray-700 dark:hover:border-purple-500"
                               :class="{ 'border-purple-600 dark:border-purple-600': activeImage === image }">
                                <img :src="image" alt="" class="max-auto max-h-full w-auto"/>
                            </a>
                        </template>
                    </div>
                </div>
            </div>

            <div class="space-y-6 lg:col-span-2">
                <h1 class="text-lg font-semibold lg:text-2xl dark:text-white">
                    {{ $product->title }}
                </h1>

                @if($product->quantity === 0)
                    <p class="text-red-500 dark:text-red-600 text-lg font-bold">This product is out of stock! 🥺</p>
                @endif

                <p class="text-xl font-bold dark:text-white">${{ $product->price }}</p>

                <div class="flex items-center justify-between">
                    <label for="quantity" class="mr-4 block font-bold dark:text-white">
                        Quantity
                    </label>

                    <input type="number" name="quantity" x-ref="quantityEl" value="1" min="1"
                           class="w-32 rounded focus:border-purple-500 focus:outline-none dark:border-2 dark:focus:border-purple-600"/>
                </div>

                <button @click="addToCart($refs.quantityEl.value)"
                        :disabled="product.quantity === 0"
                        :class="{ 'opacity-50 cursor-not-allowed': product.quantity === 0 }"
                        class="btn-primary flex w-full min-w-0 justify-center py-4 text-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-6 w-6" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Add to Cart
                </button>

                <div x-data="{ expanded: false }">
                    <div x-show="expanded" x-collapse.min.120px class="wysiwyg-content text-gray-300">
                        {!!  $product->description !!}
                    </div>

                    <p class="text-right">
                        <button @click="expanded = !expanded" class="text-purple-500 hover:text-purple-700"
                                x-text="expanded ? 'Read Less' : 'Read More'"></button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
