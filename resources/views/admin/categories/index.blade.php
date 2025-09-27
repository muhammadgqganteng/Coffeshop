<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Categories') }}
            </h2>
            <a href="{{ route('admin.categories.create') }}"
                class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Add Category
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($categories->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400">No categories found.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full table-auto">
                                <thead>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <th class="text-left py-2">Name</th>
                                        <th class="text-center py-2">Products Count</th>
                                        <th class="text-center py-2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                        <tr class="border-b border-gray-200 dark:border-gray-700">
                                            <td class="py-2">{{ $category->name }}</td>
                                            <td class="text-center py-2">{{ $category->products_count }}</td>
                                            <td class="text-center py-2">
                                                <a href="{{ route('admin.categories.edit', $category) }}"
                                                    class="text-blue-600 hover:text-blue-800 mr-2">Edit</a>
                                                @if($category->products_count == 0)
                                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                                        class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-800"
                                                            onclick="return confirm('Delete this category?')">Delete</button>
                                                    </form>
                                                @else
                                                    <span class="text-gray-400"
                                                        title="Cannot delete category with products">Delete</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $categories->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>