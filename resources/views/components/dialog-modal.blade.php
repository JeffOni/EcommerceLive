@props(['id' => null, 'maxWidth' => null ,'title' => ''])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="px-2 sm:px-4 py-2 sm:py-3">
        @if($title)
        <div class="text-sm sm:text-base font-medium text-gray-900 dark:text-gray-100">
            {{ $title }}
        </div>
        @endif

        <div
            class="mt-1 sm:mt-2 text-xs sm:text-sm text-gray-600 dark:text-gray-400 max-h-80 sm:max-h-none overflow-y-auto">
            {{ $content }}
        </div>
    </div>

    <div class="flex flex-col sm:flex-row justify-end px-2 sm:px-4 py-2 sm:py-3 bg-gray-100 dark:bg-gray-800 text-end">
        {{ $footer }}
    </div>
</x-modal>