<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-preference" content="{{ auth()->check() ? (auth()->user()->theme_preference ?? 'system') : 'system' }}">

        <title>{{ \App\Support\PageTitle::format($title ?? null) }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <style>[x-cloak]{display:none !important}</style>

        <!-- Inline theme bootstrapping to avoid light flash before JS loads -->
        <script>
            (function () {
                var html = document.documentElement;
                var meta = document.querySelector('meta[name="theme-preference"]');
                var preference = localStorage.getItem('theme-preference') || ((meta && meta.getAttribute('content')) || 'system');
                var effective = preference === 'system'
                    ? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light')
                    : preference;

                html.classList.toggle('dark', effective === 'dark');
                html.classList.toggle('light', effective === 'light');
            })();
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/chat.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-zinc-50 text-zinc-950 selection:bg-emerald-500/25 selection:text-emerald-900 dark:bg-zinc-950 dark:text-zinc-100 dark:selection:bg-emerald-500/30 dark:selection:text-emerald-200" x-data="{ pageLoaded: false }" x-init="setTimeout(() => pageLoaded = true, 50)">
        <div class="min-h-screen bg-zinc-50 relative overflow-hidden dark:bg-zinc-950">
            <!-- Global Background Aesthetic -->
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_0%,rgba(16,185,129,0.05),transparent_50%)]"></div>
            <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-[120px] pointer-events-none animate-pulse"></div>
            <div class="absolute bottom-0 right-1/4 w-[600px] h-[600px] bg-cyan-500/5 rounded-full blur-[150px] pointer-events-none"></div>

            @if(isset($header))
                <header class="bg-white/80 backdrop-blur-2xl border-b border-zinc-200/80 relative z-10 sticky top-0 transition-all duration-500 dark:bg-zinc-950/80 dark:border-zinc-800/50">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                        {{ $header }}
                    </div>
                </header>
            @endif
            
            <!-- Page Content -->
            <main 
                class="relative z-0 pt-4 pb-20"
                x-cloak
                x-show="pageLoaded"
                x-transition:enter="transition ease-out duration-1000"
                x-transition:enter-start="opacity-0 scale-[0.98]"
                x-transition:enter-end="opacity-100 scale-100"
            >
                <div 
                    x-show="pageLoaded"
                    x-transition:enter="transition ease-out duration-1000 delay-100"
                    x-transition:enter-start="opacity-0 translate-y-8"
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
