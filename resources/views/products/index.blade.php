<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Category Filter -->
            <div class="mb-8">
                <div class="flex space-x-4">
                    <a href="{{ route('products.index') }}" class="px-4 py-2 rounded-full {{ !$categoryId ? 'bg-amber-600 text-white' : 'bg-gray-200 text-gray-700' }} hover:bg-amber-600 hover:text-white transition duration-300">
                        All
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('products.index', ['category' => $category->id]) }}" class="px-4 py-2 rounded-full {{ $categoryId == $category->id ? 'bg-amber-600 text-white' : 'bg-gray-200 text-gray-700' }} hover:bg-amber-600 hover:text-white transition duration-300">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($products as $product)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <span class="text-gray-500 dark:text-gray-400">No Image</span>
                            </div>
                        @endif
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ $product->name }}</h3>
                            <p class="text-gray-600 dark:text-gray-300 mb-4">{{ Str::limit($product->description, 100) }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-2xl font-bold text-amber-600">${{ number_format($product->price, 2) }}</span>
                                <button onclick="addToCart({{ $product->id }}, 1)" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg transition duration-300">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $products->links() }}
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
