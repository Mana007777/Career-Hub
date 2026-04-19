<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center rounded-2xl border border-zinc-300 bg-zinc-100 px-4 py-2 text-xs font-bold uppercase tracking-wide text-zinc-800 shadow-sm transition hover:bg-zinc-200 focus:outline-none focus:ring-2 focus:ring-zinc-400/40 focus:ring-offset-2 focus:ring-offset-white disabled:cursor-not-allowed disabled:opacity-40 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-200 dark:hover:bg-zinc-700 dark:focus:ring-offset-zinc-950']) }}>
    {{ $slot }}
</button>
