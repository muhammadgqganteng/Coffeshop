<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Payment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-6">Complete Your Payment</h3>

                    <div class="mb-8">
                        <h4 class="text-md font-semibold mb-4">Order Details</h4>
                        <p><strong>Order ID:</strong> #{{ $order->id }}</p>
                        <p><strong>Total:</strong> ${{ number_format($order->total, 2) }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                    </div>

                    <div class="text-center">
                        <button id="pay-button"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-300">
                            Pay Now
                        </button>
                    </div>

                    <div class="mt-6 text-center">
                        <a href="{{ route('orders.index') }}" class="text-blue-600 hover:text-blue-800">Back to
                            Orders</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Midtrans Snap.js -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function () {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function (result) {
                    console.log('success');
                    window.location.href = '{{ route("orders.index") }}?success=1';
                },
                onPending: function (result) {
                    console.log('pending');
                    window.location.href = '{{ route("orders.index") }}?pending=1';
                },
                onError: function (result) {
                    console.log('error');
                    window.location.href = '{{ route("orders.index") }}?error=1';
                },
                onClose: function () {
                    console.log('customer closed the popup without finishing the payment');
                    window.location.href = '{{ route("orders.index") }}';
                }
            });
        };
    </script>
</x-app-layout>