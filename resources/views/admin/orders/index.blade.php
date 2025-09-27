<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($orders->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400">No orders found.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full table-auto">
                                <thead>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <th class="text-left py-2">Order ID</th>
                                        <th class="text-left py-2">Customer</th>
                                        <th class="text-right py-2">Total</th>
                                        <th class="text-center py-2">Status</th>
                                        <th class="text-left py-2">Date</th>
                                        <th class="text-center py-2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr class="border-b border-gray-200 dark:border-gray-700">
                                            <td class="py-2">#{{ $order->id }}</td>
                                            <td class="py-2">{{ $order->user->name }}</td>
                                            <td class="text-right py-2">${{ number_format($order->total, 2) }}</td>
                                            <td class="text-center py-2">
                                                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="status" onchange="this.form.submit()"
                                                        class="border border-gray-300 dark:border-gray-600 rounded px-2 py-1 text-sm">
                                                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                                    </select>
                                                </form>
                                            </td>
                                            <td class="py-2">{{ $order->created_at->format('M d, Y') }}</td>
                                            <td class="text-center py-2">
                                                <a href="{{ route('admin.orders.show', $order) }}"
                                                    class="text-blue-600 hover:text-blue-800">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>