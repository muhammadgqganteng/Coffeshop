<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Order History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($orders->isEmpty())
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <p class="text-gray-500 dark:text-gray-400 mb-4">You haven't placed any orders yet.</p>
                        <a href="{{ route('home') }}"
                            class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-2 rounded-lg transition duration-300">
                            Start Shopping
                        </a>
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full table-auto">
                                <thead>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <th class="text-left py-2">Order ID</th>
                                        <th class="text-left py-2">Date</th>
                                        <th class="text-right py-2">Total</th>
                                        <th class="text-center py-2">Status</th>
                                        <th class="text-center py-2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr class="border-b border-gray-200 dark:border-gray-700">
                                            <td class="py-4">#{{ $order->id }}</td>
                                            <td class="py-4">{{ $order->created_at->format('M d, Y') }}</td>
                                            <td class="text-right py-4">${{ number_format($order->total, 2) }}</td>
                                            <td class="text-center py-4">
                                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                                            @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                                                            @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                                            @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                                            @endif">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td class="text-center py-4">
                                                <a href="{{ route('orders.show', $order) }}"
                                                    class="text-amber-600 hover:text-amber-800">View Details</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>