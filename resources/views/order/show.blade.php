<x-app-layout>

    <div class="container mx-auto p-5 lg:w-2/3">
        <h1 class="mb-6 text-3xl font-bold dark:text-gray-200">Order #{{ $order->id }}</h1>
        <div class="rounded-lg bg-white p-3">
            <table>
                <tbody>
                    <tr>
                        <td class="px-2 py-1 font-bold">Order #</td>
                        <td>{{ $order->id }}</td>
                    </tr>

                    <tr>
                        <td class="px-2 py-1 font-bold">Order Date</td>
                        <td>{{ $order->created_at }}</td>
                    </tr>

                    <tr>
                        <td class="px-2 py-1 font-bold">Order Status</td>

                        <td>
                            <span
                                class="{{ $order->isPaid() ? 'bg-emerald-500' : 'bg-gray-400' }} rounded px-2 py-1 text-white">
                                {{ $order->status }}
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td class="px-2 py-1 font-bold">SubTotal</td>
                        <td>${{ $order->total_price }}</td>
                    </tr>
                </tbody>
            </table>

            <hr class="my-5" />

            @foreach ($order->items()->with('product')->get() as $item)
                <!-- Order Item -->
                <div class="flex flex-col items-center gap-4 sm:flex-row">
                    <a href="{{ route('product.show', $item->product) }}"
                        class="flex h-32 w-36 items-center justify-center overflow-hidden">
                        <img src="{{ $item->product->image }}" class="object-cover" alt="" />
                    </a>

                    <div class="flex flex-col justify-between">
                        <div class="mb-3 flex justify-between">
                            <h3>
                                {{ $item->product->title }}
                            </h3>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">Qty: {{ $item->quantity }}</div>

                            <span class="text-lg font-semibold"> ${{ $item->unit_price }} </span>
                        </div>
                    </div>
                </div>
                <!--/ Order Item -->
                <hr class="my-3" />
            @endforeach

            @if (!$order->isPaid() && $order->status !== 'shipped' && $order->status !== 'completed')
                <form action="{{ route('cart.checkout-order', $order) }}" method="POST">
                    @csrf
                    <button class="btn-primary mt-3 flex w-full items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Make a Payment
                    </button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
