@props(['value'])

<label {{ $attributes->merge(['class' => 'mb-2 block text-[11px] font-bold uppercase tracking-wide text-zinc-600 dark:text-zinc-400']) }}>
    {{ $value ?? $slot }}
</label>
