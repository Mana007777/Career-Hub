@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 dark:text-gray-900 text-gray-900 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 dark:text-gray-500 text-gray-600 dark:hover:text-gray-700 hover:text-gray-900 dark:hover:border-gray-300 hover:border-gray-400 focus:outline-none dark:focus:text-gray-700 focus:text-gray-900 dark:focus:border-gray-300 focus:border-gray-400 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
