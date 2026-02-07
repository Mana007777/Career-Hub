@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'dark:border-gray-700 border-gray-300 dark:bg-gray-800 bg-gray-100 dark:text-white text-gray-900 dark:placeholder-gray-400 placeholder-gray-500 focus:border-blue-500 dark:focus:ring-gray-500 focus:ring-blue-500 focus:ring-2 rounded-md shadow-sm px-4 py-2 transition-all duration-300']) !!}>
