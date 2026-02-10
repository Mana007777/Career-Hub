<div class="min-h-screen dark:text-white text-gray-900 pb-24">
    <div class="max-w-4xl mx-auto px-4 py-8 animate-pulse">
        <!-- Header skeleton -->
        <div class="mb-8 space-y-3">
            <div class="h-4 w-24 rounded-full dark:bg-gray-800 bg-gray-200"></div>
            <div class="h-8 w-48 rounded-full dark:bg-gray-800 bg-gray-200"></div>
            <div class="h-3 w-64 rounded-full dark:bg-gray-800 bg-gray-200"></div>
        </div>

        <!-- Card list skeleton -->
        <div class="space-y-4">
            @for ($i = 0; $i < 3; $i++)
                <div class="dark:bg-gray-900 bg-white border dark:border-gray-800 border-gray-200 rounded-lg p-6 shadow-lg space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full dark:bg-gray-800 bg-gray-200"></div>
                        <div class="flex-1 space-y-2">
                            <div class="h-3 w-32 rounded-full dark:bg-gray-800 bg-gray-200"></div>
                            <div class="h-3 w-24 rounded-full dark:bg-gray-800 bg-gray-200"></div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="h-3 w-full rounded-full dark:bg-gray-800 bg-gray-200"></div>
                        <div class="h-3 w-5/6 rounded-full dark:bg-gray-800 bg-gray-200"></div>
                        <div class="h-3 w-2/3 rounded-full dark:bg-gray-800 bg-gray-200"></div>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t dark:border-gray-800 border-gray-200">
                        <div class="h-3 w-24 rounded-full dark:bg-gray-800 bg-gray-200"></div>
                        <div class="h-3 w-20 rounded-full dark:bg-gray-800 bg-gray-200"></div>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</div>

