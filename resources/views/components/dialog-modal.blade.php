@props(['id' => null, 'maxWidth' => null])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="px-6 py-4">
        <div class="text-lg font-medium dark:text-gray-900 text-gray-900">
            {{ $title }}
        </div>

        <div class="mt-4 text-sm dark:text-gray-600 text-gray-600">
            {{ $content }}
        </div>
    </div>

    <div class="flex flex-row justify-end px-6 py-4 dark:bg-[#F5EFE7] bg-gray-50 text-end">
        {{ $footer }}
    </div>
</x-modal>
