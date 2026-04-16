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
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles

        <!-- Custom Styles for Auth Forms -->
        <style>
            select option {
                background-color: #111827; /* gray-900 */
                color: white;
            }

            html.light select option {
                background-color: #f8fafc;
                color: #0f172a;
            }
            
            select:focus option:checked {
                background-color: #374151; /* gray-700 */
            }

            input::placeholder {
                color: rgba(156, 163, 175, 0.6);
            }

            input:-webkit-autofill,
            input:-webkit-autofill:hover,
            input:-webkit-autofill:focus {
                -webkit-text-fill-color: #0f172a;
                -webkit-box-shadow: 0 0 0px 1000px #f8fafc inset;
                transition: background-color 5000s ease-in-out 0s;
            }

            html.dark input:-webkit-autofill,
            html.dark input:-webkit-autofill:hover,
            html.dark input:-webkit-autofill:focus {
                -webkit-text-fill-color: #ffffff;
                -webkit-box-shadow: 0 0 0px 1000px rgba(15, 23, 42, 0.9) inset;
            }
        </style>
    </head>
    <body>
        <div class="font-sans antialiased bg-zinc-950 text-zinc-100 min-h-screen">
            {{ $slot }}
        </div>

        @livewireScripts
    </body>
</html>
