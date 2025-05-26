<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('Order Confirmation for ') }} {{ $user->first_name }}</title>
</head>

<body>
    <h1>{{ __('Order Confirmation for ') }} {{ $user->first_name }}</h1>
    <p>{{ __('Thank you for your order!') }}</p>
    <p>{{ __('Here are the details of your order:') }}</p>
    <ul>
        <li>{{ __('Order Number') }}: {{ $order->id }}</li>
        <li>{{ __('Order Date') }}: {{ $order->created_at->format('d M Y H:i') }}</li>
        <li>{{ __('Shipping Method') }}: {{ $order->delivery_method }}</li>
        <li>{{ __('Payment Method') }}: {{ $order->payment_method }}</li>
        <li>{{ __('Shipping Address') }}: {{ $order->shipping_address }}</li>
        <li>{{ __('Billing Address') }}: {{ $order->address ?? $order->shipping_address }}</li>
        <li>{{ __('Items Ordered') }}:</li>
        <ul>
            @if ($order->order_products->isEmpty())
                <li>{{ __('No items in this order') }}</li>
            @else
                @foreach ($order->order_products as $order_product)
                    <li>
                        {{ $order_product->product->name ?? 'Unknown Product' }} x {{ $order_product->qty }}
                        ({{ $order_product->price }} each, Total: {{ $order_product->total }})
                    </li>
                @endforeach
            @endif
        </ul>
        <li>{{ __('Subtotal') }}: {{ $order->subtotal ?? $order->order_products->sum('total') }}</li>
        <li>{{ __('Tax') }}: {{ $order->tax ?? 0 }}</li>
        <li>{{ __('Shipping') }}: {{ $order->shipping_cost ?? 0 }}</li>
        <li>{{ __('Total') }}: {{ $order->total }}</li>
    </ul>
    <p>{{ __('Your order has been received and will be processed soon. You will receive an email with tracking information once shipped.') }}
    </p>
    <p>{{ __('Thank you for shopping with us!') }}</p>
</body>

</html>
