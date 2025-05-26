<div>
    @if ($product)
        <h3>{{ $product->name }}</h3>
        <p>Price: {{ $product->price }}</p>
    @else
        <p>{{ __('Product not found') }}</p>
    @endif
</div>
