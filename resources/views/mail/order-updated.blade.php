<h1>Your Order Status was changed to "{{ $order->status }}"</h1>

<p>
    <strong>See Your Order:</strong>
    <a href="{{ route('order.show', $order, true) }}">Order #{{ $order->id }}</a>
</p>
