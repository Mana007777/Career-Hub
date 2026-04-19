<div {{ $attributes->merge(['class' => 'w-full']) }}>
    <div
        class="overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-md ring-1 ring-zinc-950/5 dark:border-zinc-800 dark:bg-zinc-900/90 dark:shadow-[0_24px_60px_rgba(0,0,0,0.35)] dark:ring-white/5"
    >
        <header
            class="border-b border-zinc-200/90 bg-gradient-to-br from-zinc-50 via-white to-zinc-100/80 px-6 py-6 sm:px-10 sm:py-7 dark:border-zinc-800 dark:from-zinc-950 dark:via-zinc-900 dark:to-zinc-950"
        >
            <h3 class="text-base font-black uppercase tracking-wide text-zinc-900 sm:text-lg dark:text-white">
                {{ $title }}
            </h3>
            <p class="mt-2 max-w-2xl text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">
                {{ $description }}
            </p>
        </header>

        <div class="px-6 py-8 sm:px-10 sm:py-9">
            <div class="mx-auto w-full max-w-xl space-y-6 text-zinc-900 sm:max-w-2xl dark:text-zinc-100">
                {{ $content }}
            </div>
        </div>
    </div>
</div>
