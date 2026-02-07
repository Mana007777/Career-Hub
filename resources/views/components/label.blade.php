@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm dark:text-gray-700 text-gray-700 mb-2']) }}>
    {{ $value ?? $slot }}
</label>
