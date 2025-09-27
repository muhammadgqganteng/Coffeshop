<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Product Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row">
                        <div class="md:w-1/2 mb-6 md:mb-0">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    class="w-full h-64 object-cover rounded-lg">
                            @else
                                <div
                                    class="w-full h-64 bg-gray-200 dark:bg-gray-700 flex items-center justify-center rounded-lg">
                                    <span class="text-gray-500 dark:text-gray-400">No Image</span>
                                </div>
                            @endif
                        </div>
                        <div class="md:w-1/2 md:pl-8">
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">{{ $product->name }}
                            </h1>
                            <p class="text-gray-600 dark:text-gray-300 mb-6">{{ $product->description }}</p>
                            <div class="mb-6">
                                <span
                                    class="text-3xl font-bold text-amber-600">${{ number_format($product->price, 2) }}</span>
                            </div>
                            <div class="flex items-center space-x-4">
                                <input type="number" id="quantity" value="1" min="1"
                                    class="w-20 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500">
                                <button
                                    onclick="addToCart({{ $product->id }}, parseInt(document.getElementById('quantity').value))"
                                    class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-2 rounded-lg transition duration-300">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function addToCart(productId, quantity) {
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ product_id: productId, quantity: quantity })
            })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>
</x-app-layout>