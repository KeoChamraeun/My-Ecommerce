<div>
    <section class="relative py-10 bg-gray-100">
        <div class="mt-64 lg:mt-0 py-16 bg-white">
            <div class="mx-auto px-4">
                <div class="flex items-end justify-end">
                    <div class="w-full lg:w-3/5 lg:pl-20 lg:ml-auto">
                        <h2 class="mb-8 text-5xl font-bold font-heading">{{ __('Thank you') }}
                            @if (!empty($order->user))
                                {{ $order->user->fullName }}
                            @endif
                        </h2>

                        <p class="mb-12 text-gray-500">{{ __('Your order is processing') }}</p>
                        <div class="flex flex-wrap mb-12">
                            <div class="mr-20">
                                <h3 class="text-gray-600">{{ __('Order Number') }}</h3>
                                <p class="text-blue-300 font-bold font-heading">{{ $order->reference }}</p>
                            </div>
                            <div class="mr-auto">
                                <h3 class="text-gray-600">{{ __('Date') }}</h3>
                                <p class="text-blue-300 font-bold font-heading">
                                    {{ $order->created_at->format('d/m/Y') }}
                                </p>
                            </div>
                            <a class="inline-flex mt-6 md:mt-0 w-full lg:w-auto justify-center items-center py-4 px-6 border hover:border-gray-500 rounded-md font-bold font-heading"
                                href="#">
                                <!-- SVG icon here -->
                                <span class="ml-4">{{ __('View Invoice') }}</span>
                            </a>
                        </div>

                        <div class="mb-6 p-10 shadow-xl">
                            <div class="flex flex-wrap items-center -mx-4">
                                @if ($order->products && $order->products->count())
                                    @foreach ($order->products as $product)
                                        <div class="w-full lg:w-2/6 px-4 mb-8 lg:mb-0">
                                            <img class="w-full h-32 object-contain"
                                                src="{{ asset('images/products/' . $product->image) }}" alt="">
                                        </div>
                                        <div class="w-full lg:w-4/6 px-4">
                                            <div class="flex">
                                                <div class="mr-auto">
                                                    <h3 class="text-xl font-bold font-heading">{{ $product->name }}</h3>
                                                    <p class="text-gray-500">{!! $product->description !!}</p>
                                                    <p class="text-gray-500">
                                                        <span>{{ __('Quantity') }}:</span>
                                                        <span class="text-gray-900 font-bold font-heading">
                                                            {{ $product->pivot->qty ?? 'N/A' }}
                                                        </span>
                                                    </p>
                                                </div>
                                                <span class="text-2xl font-bold font-heading text-blue-300">
                                                    {{ $product->pivot->price ?? $product->price }} DH
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p>{{ __('No products found for this order.') }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="mb-10">
                            <div class="py-3 px-10 bg-gray-100 rounded-full">
                                <div class="flex justify-between">
                                    <span class="font-medium">{{ __('Subtotal') }}</span>
                                    <span class="font-bold font-heading">
                                        {{ $order->subtotal }} DH
                                    </span>
                                </div>
                            </div>
                            <div class="py-3 px-10 rounded-full">
                                <div class="flex justify-between">
                                    <span class="font-medium">{{ __('Shipping') }}</span>
                                    <span class="font-bold font-heading">
                                        {{ $order->shipping_cost ?? 'N/A' }} DH
                                    </span>
                                </div>
                            </div>
                            <div class="py-3 px-10 bg-gray-100 rounded-full">
                                <div class="flex justify-between">
                                    <span class="font-medium">{{ __('Tax') }}</span>
                                    <span class="font-bold font-heading">
                                        {{ $order->tax }} DH
                                    </span>
                                </div>
                            </div>
                            <div class="py-3 px-10 rounded-full">
                                <div class="flex justify-between">
                                    <span
                                        class="text-base md:text-xl font-bold font-heading">{{ __('Order Total') }}</span>
                                    <span class="font-bold font-heading">
                                        {{ $order->total }} DH
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-10 py-10 px-4 bg-gray-100">
                            <div class="flex flex-wrap justify-around -mx-4">
                                <div class="w-full md:w-auto px-4 mb-6 md:mb-0">
                                    <h4 class="mb-6 font-bold font-heading">{{ __('Delivery Address') }}</h4>
                                    @if($order->address)
                                        <p class="text-gray-500">
                                            {{ $order->address->address }}
                                        </p>
                                        <p class="text-gray-500">{{ $order->address->city }} -
                                            {{ $order->address->country }}
                                        </p>
                                    @else
                                        <p class="text-gray-500">{{ __('No address found.') }}</p>
                                    @endif
                                </div>
                                <div class="w-full md:w-auto px-4 mb-6 md:mb-0">
                                    <h4 class="mb-6 font-bold font-heading">{{ __('Contact Information') }}</h4>
                                    <p class="text-gray-500">{{ $order->shipping_email }}</p>
                                    <p class="text-gray-500">{{ $order->shipping_phone }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
