<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order Details') }} #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium mb-4">{{ __('Order') }} #{{ $order->id }}</h3>
                    <div class="grid grid-cols-1 gap-4">
                        <p><strong>{{ __('Customer Name') }}:</strong> {{ $order->customer?->name ?? 'Guest' }}</p>
                        <p><strong>{{ __('Email') }}:</strong> {{ $order->customer?->email ?? 'N/A' }}</p>
                        <p><strong>{{ __('Phone') }}:</strong> {{ $order->customer?->phone ?? 'N/A' }}</p>
                        <p><strong>{{ __('Status') }}:</strong> {{ $order->status->translatedLabel() }}</p>
                        <p><strong>{{ __('Total Quantity') }}:</strong> {{ $order->total_qty }}</p>
                        <p><strong>{{ __('Total Cost') }}:</strong> {{ number_format($order->total_cost, 2) }}</p>
                    </div>                    

                    <div class="mt-6 flex space-x-4">
                        <a href="{{ route('admin.orders.edit', $order) }}"
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            {{ __('Edit Order') }}
                        </a>
                        <a href="{{ route('admin.orders') }}"
                            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                            {{ __('Back to Orders') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
