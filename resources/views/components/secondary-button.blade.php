<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 dark:bg-white dark:text-gray-700 bg-gray-800 text-white border dark:border-gray-300 border-gray-700 rounded-md font-semibold text-xs uppercase tracking-widest shadow-sm dark:hover:bg-gray-50 hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
