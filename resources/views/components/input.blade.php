@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-700 bg-gray-800 text-white placeholder-gray-400 focus:border-gray-400 focus:ring-gray-500 focus:ring-2 rounded-md shadow-sm px-4 py-2 transition-all duration-300']) !!}>
