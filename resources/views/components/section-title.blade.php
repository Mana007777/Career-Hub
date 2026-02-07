<div class="md:col-span-1 flex justify-between">
    <div class="px-4 sm:px-0">
        <h3 class="text-lg font-medium dark:text-gray-900 text-gray-900">{{ $title }}</h3>

        <p class="mt-1 text-sm dark:text-gray-600 text-gray-600">
            {{ $description }}
        </p>
    </div>

    <div class="px-4 sm:px-0">
        {{ $aside ?? '' }}
    </div>
</div>
