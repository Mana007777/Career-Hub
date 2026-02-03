<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-gray-900 via-gray-800 to-gray-700 hover:from-gray-800 hover:via-gray-700 hover:to-gray-900 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-wider shadow-lg shadow-gray-900/40 hover:shadow-gray-800/40 focus:outline-none focus:ring-2 focus:ring-gray-800 focus:ring-offset-2 focus:ring-offset-transparent disabled:opacity-50 transition-all duration-300 transform hover:scale-105']) }}>
    {{ $slot }}
</button>
