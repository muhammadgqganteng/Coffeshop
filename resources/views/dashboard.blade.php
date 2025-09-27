<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(auth()->user()->role === 'admin')
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        {{ __("Welcome to Admin Dashboard!") }}
                        <a href="{{ route('admin.dashboard') }}"
                            class="mt-4 inline-block bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg transition duration-300">
                            Go to Admin Panel
                        </a>
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        {{ __("You're logged in!") }}
                        <div class="mt-4 space-x-4">
                            <a href="{{ route('home') }}"
                                class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg transition duration-300">
                                Browse Products
                            </a>
                            <a href="{{ route('orders.index') }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-300">
                                View Orders
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>