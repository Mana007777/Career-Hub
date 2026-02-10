<div class="min-h-screen dark:bg-black bg-white dark:text-white text-gray-900 pb-24" style="width: 100vw; margin-left: calc(-50vw + 50%); margin-right: calc(-50vw + 50%);">
    <div class="max-w-4xl mx-auto px-4 py-8 animate-pulse">
        <!-- Back button skeleton -->
        <div class="mb-6">
            <div class="h-4 w-24 rounded-full dark:bg-gray-800 bg-gray-200"></div>
        </div>

        <div class="dark:bg-gray-900 bg-white border dark:border-gray-800 border-gray-200 rounded-lg p-6 shadow-2xl space-y-6">
            <!-- Header skeleton -->
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-full dark:bg-gray-800 bg-gray-200"></div>
                <div class="flex-1 space-y-2">
                    <div class="h-4 w-40 rounded-full dark:bg-gray-800 bg-gray-200"></div>
                    <div class="h-3 w-32 rounded-full dark:bg-gray-800 bg-gray-200"></div>
                </div>
                <div class="h-8 w-20 rounded-lg dark:bg-gray-800 bg-gray-200"></div>
            </div>

            <!-- Title & content skeleton -->
            <div class="space-y-3">
                <div class="h-5 w-3/4 rounded-full dark:bg-gray-800 bg-gray-200"></div>
                <div class="h-4 w-40 rounded-full dark:bg-gray-800 bg-gray-200"></div>
                @for ($i = 0; $i < 4; $i++)
                    <div class="h-3 w-full rounded-full dark:bg-gray-800 bg-gray-200"></div>
                @endfor
            </div>

            <!-- Media skeleton -->
            <div class="h-64 w-full rounded-xl dark:bg-gray-900 bg-gray-100"></div>

            <!-- Tags / specialties skeleton -->
            <div class="pt-4 border-t dark:border-gray-800 border-gray-200 flex flex-wrap gap-2">
                @for ($i = 0; $i < 4; $i++)
                    <div class="h-6 w-24 rounded-full dark:bg-gray-800 bg-gray-200"></div>
                @endfor
            </div>

            <!-- Stats skeleton -->
            <div class="flex items-center gap-6 pt-4 border-t dark:border-gray-800 border-gray-200">
                @for ($i = 0; $i < 3; $i++)
                    <div class="h-4 w-16 rounded-full dark:bg-gray-800 bg-gray-200"></div>
                @endfor
            </div>

            <!-- Comments header skeleton -->
            <div class="mt-8 pt-6 border-t dark:border-gray-800 border-gray-200 space-y-4">
                <div class="h-5 w-28 rounded-full dark:bg-gray-800 bg-gray-200"></div>
                @for ($i = 0; $i < 3; $i++)
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-full dark:bg-gray-800 bg-gray-200"></div>
                        <div class="flex-1 space-y-2">
                            <div class="h-3 w-32 rounded-full dark:bg-gray-800 bg-gray-200"></div>
                            <div class="h-3 w-3/4 rounded-full dark:bg-gray-800 bg-gray-200"></div>
                            <div class="h-3 w-2/3 rounded-full dark:bg-gray-800 bg-gray-200"></div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
</div>

