<div class="md:col-span-1 flex justify-between">
    <div class="px-4 sm:px-0">
        <h3 class="text-lg font-black uppercase tracking-tight text-zinc-900 dark:text-white">
            {{ $title }}
        </h3>

        <p class="mt-2 text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">
            {{ $description }}
        </p>
    </div>

    <div class="px-4 sm:px-0">
        {{ $aside ?? '' }}
    </div>
</div>
