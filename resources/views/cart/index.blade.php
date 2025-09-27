<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Shopping Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($products->isEmpty())
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <p class="text-gray-500 dark:text-gray-400 mb-4">Your cart is empty.</p>
                        <a href="{{ route('home') }}" class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-2 rounded-lg transition duration-300">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <table class="w-full table-auto">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <th class="text-left py-2">Product</th>
                                    <th class="text-center py-2">Quantity</th>
                                    <th class="text-right py-2">Price</th>
                                    <th class="text-right py-2">Subtotal</th>
                                    <th class="text-center py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="py-4">
                                            <div class="flex items-center">
                                                @if($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover rounded">
                                                @else
                                                    <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center">
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">No Image</span>
                                                    </div>
                                                @endif
                                                <div class="ml-4">
                                                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ $product->name }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center py-4">
                                            <form action="{{ route('cart.update', $product->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="number" name="quantity" value="{{ $product->quantity }}" min="1" class="w-16 px-2 py-1 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500">
                                                <button type="submit" class="ml-2 text-amber-600 hover:text-amber-800">Update</button>
                                            </form>
                                        </td>
                                        <td class="text-right py-4">${{ number_format($product->price, 2) }}</td>
                                        <td class="text-right py-4">${{ number_format($product->subtotal, 2) }}</td>
                                        <td class="text-center py-4">
                                            <form action="{{ route('cart.remove', $product->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Remove this item?')">Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-8 flex justify-between items-center">
                            <div>
                                <button onclick="clearCart()" class="text-red-600 hover:text-red-800 mr-4">Clear Cart</button>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">Total: ${{ number_format($total, 2) }}</p>
                                <a href="{{ route('orders.create') }}" class="mt-4 inline-block bg-amber-600 hover:bg-amber-700 text-white px-6 py-2 rounded-lg transition duration-300">
                                    Proceed to Checkout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function clearCart() {
            if (confirm('Clear all items from cart?')) {
                fetch('/cart', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(() => {
                    location.reload();
                });
            }
        }
    </script>
</x-app-layout>
