<div {{ $attributes->merge(['class' => 'mt-2 bg-red-50 dark:bg-red-100 border border-red-400 dark:border-red-500 text-red-600 dark:text-red-400 px-4 py-3 rounded-md']) }}>
    @if ($errors->any())
        <div class="font-medium">
            {{ __('Whoops! Something went wrong!') }}
        </div>

        <ul class="mt-3 list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
</div>