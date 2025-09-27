<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-6">Order Summary</h3>

                    <div class="mb-8">
                        @foreach($products as $product)
                            <div class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded mr-4">
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ $product->name }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Qty: {{ $product->quantity }}</p>
                                    </div>
                                </div>
                                <p class="font-medium text-gray-900 dark:text-gray-100">${{ number_format($product->subtotal, 2) }}</p>
                            </div>
                        @endforeach
                        <div class="flex justify-between items-center py-4 font-bold text-lg">
                            <span>Total:</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <h4 class="text-md font-semibold mb-4">Shipping Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="address" :value="__('Address')" />
                                    <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" required />
                                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h4 class="text-md font-semibold mb-4">Payment Method</h4>
                            <div>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="payment_method" value="cash" checked class="form-radio">
                                    <span class="ml-2">Cash on Delivery</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <a href="{{ route('cart.index') }}" class="mr-4 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition duration-300">
                                Back to Cart
                            </a>
                            <x-primary-button>
                                Place Order
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
