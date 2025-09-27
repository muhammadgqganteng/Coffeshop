<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Order Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Order #{{ $order->id }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Order Date</p>
                                <p class="font-medium">{{ $order->created_at->format('M d, Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Status</p>
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                                    @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                    @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Total</p>
                                <p class="font-medium">${{ number_format($order->total, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-md font-semibold mb-4">Items</h4>
                        <div class="space-y-4">
                            @foreach($order->orderItems as $item)
                                <div
                                    class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center">
                                        @if($item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}"
                                                alt="{{ $item->product->name }}" class="w-12 h-12 object-cover rounded mr-4">
                                        @endif
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-gray-100">
                                                {{ $item->product->name }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Qty: {{ $item->quantity }} ×
                                                ${{ number_format($item->price, 2) }}</p>
                                        </div>
                                    </div>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">
                                        ${{ number_format($item->quantity * $item->price, 2) }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('orders.index') }}"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition duration-300">
                            Back to Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>