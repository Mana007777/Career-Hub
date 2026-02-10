<div class="min-h-screen dark:text-white text-gray-900 pb-24">
    <div class="w-full px-0 sm:px-2 lg:px-0 py-4">
        <!-- Header skeleton -->
        <div class="mb-8">
            <div class="h-8 w-32 rounded-full dark:bg-gray-800 bg-gray-200 mb-4"></div>
            <div class="flex items-center justify-between">
                <div class="h-4 w-40 rounded-full dark:bg-gray-800 bg-gray-200"></div>
                <div class="h-9 w-28 rounded-lg dark:bg-gray-800 bg-gray-200"></div>
            </div>
        </div>

        <!-- Filters skeleton -->
        <div class="mb-6 dark:bg-gray-900 bg-white border dark:border-gray-800 border-gray-200 rounded-xl p-6 shadow-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @for ($i = 0; $i < 4; $i++)
                    <div class="space-y-2">
                        <div class="h-3 w-24 rounded-full dark:bg-gray-800 bg-gray-200"></div>
                        <div class="h-10 w-full rounded-lg dark:bg-gray-800 bg-gray-200"></div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Posts grid skeleton -->
        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @for ($i = 0; $i < 6; $i++)
                <x-skeleton.post-card />
            @endfor
        </div>
    </div>
</div>

