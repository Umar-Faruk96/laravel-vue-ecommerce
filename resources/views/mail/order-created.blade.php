<h1>Congrats! New Order has been created</h1>

<table>
    <tr>
        <th>Order ID</th>
        <td>
            <a
                href="{{ $admin ? config('app.admin_url') . 'app/orders/' . $order->id : route('order.show', $order, true) }}">#{{ $order->id }}</a>
        </td>
    </tr>
    <tr>
        <th>Order Status</th>
        <td>{{ $order->status }}</td>
    </tr>
    <tr>
        <th>Order Price</th>
        <td>${{ $order->total_price }}</td>
    </tr>
    <tr>
        <th>Order Date</th>
        <td>{{ $order->created_at }}</td>
    </tr>
</table>
<table>
    <tr>
        <th>Image</th>
        <th>Title</th>
        <th>Price</th>
        <th>Quantity</th>
    </tr>

    @foreach ($order->items as $item)
        <tr>
            <td>
                <img src="{{ $item->product->image }}" alt="{{ $item->product->title }}" width="50" height="50">
            </td>
            <td>
                <a
                    href="{{ $admin ? config('app.admin_url') . 'app/products/' . $item->product->id : route('product.show', $item->product, true) }}">{{ $item->product->title }}</a>
            </td>
            <td>${{ $item->unit_price * $item->quantity }}</td>
            <td>{{ $item->quantity }}</td>
        </tr>
    @endforeach
</table>
