<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-preference" content="{{ auth()->check() ? (auth()->user()->theme_preference ?? 'system') : 'system' }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <style>[x-cloak]{display:none !important}</style>

        <!-- Inline theme bootstrapping to avoid light flash before JS loads -->
        <script>
            (function () {
                try {
                    var html = document.documentElement;
                    var meta = document.querySelector('meta[name="theme-preference"]');
                    var preference = meta ? (meta.getAttribute('content') || 'system') : 'system';

                    if (!meta) {
                        try {
                            var stored = localStorage.getItem('theme-preference');
                            if (stored) preference = stored;
                        } catch (e) {}
                    }

                    var effectiveTheme = preference;
                    if (preference === 'system') {
                        var isDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                        effectiveTheme = isDark ? 'dark' : 'light';
                    }

                    if (effectiveTheme === 'dark') {
                        html.classList.add('dark');
                        html.classList.remove('light');
                    } else {
                        html.classList.add('light');
                        html.classList.remove('dark');
                    }
                } catch (e) {
                    // Fail silently - main theme.js will handle it later
                }
            })();
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/chat.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased dark:bg-gray-950 dark:text-white bg-gray-50 text-gray-900" x-data="{ pageLoaded: false }" x-init="setTimeout(() => pageLoaded = true, 50)">
        <div class="min-h-screen dark:bg-gradient-to-b dark:from-gray-950 dark:via-gray-900 dark:to-gray-950 bg-gradient-to-b from-gray-50 via-white to-gray-50">
            @if(isset($header))
                <header class="dark:bg-gray-900 bg-white border-b-2 dark:border-gray-800 border-gray-200 shadow-sm">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif
            
            <!-- Page Content -->
            <main 
                class="pt-4 pb-10"
                x-show="pageLoaded"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
            >
                <div 
                    x-show="pageLoaded"
                    x-transition:enter="transition ease-out duration-700"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                >
                    {{ $slot }}
                </div>
            </main>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
