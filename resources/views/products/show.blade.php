<x-app-layout>
    <div x-data="productItem({{ json_encode([
        'id' => $product->id,
        'slug' => $product->slug,
        'image' => $product->image,
        'title' => $product->title,
        'price' => $product->price,
        'addToCartUrl' => route('cart.add', $product),
    ]) }})" class="container mx-auto">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-5">
            <div class="lg:col-span-3">
                <div x-data="{
                    images: ['{{ $product->image }}'],
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
                    <div class="relative">
                        <template x-for="image in images">
                            <div x-show="activeImage === image" class="aspect-h-2 aspect-w-3">
                                <img :src="image" alt="" class="mx-auto w-auto" />
                            </div>
                        </template>
                        <a @click.prevent="prev"
                            class="absolute left-0 top-1/2 -translate-y-1/2 cursor-pointer bg-black/30 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                        <a @click.prevent="next"
                            class="absolute right-0 top-1/2 -translate-y-1/2 cursor-pointer bg-black/30 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                    <div class="flex">
                        <template x-for="image in images">
                            <a @click.prevent="activeImage = image"
                                class="flex h-[80px] w-[80px] cursor-pointer items-center justify-center border border-gray-300 hover:border-purple-500"
                                :class="{ 'border-purple-600': activeImage === image }">
                                <img :src="image" alt="" class="max-auto max-h-full w-auto" />
                            </a>
                        </template>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-2">
                <h1 class="text-lg font-semibold">
                    {{ $product->title }}
                </h1>
                <div class="mb-6 text-xl font-bold">${{ $product->price }}</div>
                <div class="mb-5 flex items-center justify-between">
                    <label for="quantity" class="mr-4 block font-bold">
                        Quantity
                    </label>
                    <input type="number" name="quantity" x-ref="quantityEl" value="1" min="1"
                        class="w-32 rounded focus:border-purple-500 focus:outline-none" />
                </div>
                <button @click="addToCart($refs.quantityEl.value)"
                    class="btn-primary mb-6 flex w-full min-w-0 justify-center py-4 text-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Add to Cart
                </button>
                <div class="mb-6" x-data="{ expanded: false }">
                    <div x-show="expanded" x-collapse.min.120px class="wysiwyg-content text-gray-500">
                        {{ $product->description }}
                    </div>
                    <p class="text-right">
                        <a @click="expanded = !expanded" href="javascript:void(0)"
                            class="text-purple-500 hover:text-purple-700"
                            x-text="expanded ? 'Read Less' : 'Read More'"></a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
