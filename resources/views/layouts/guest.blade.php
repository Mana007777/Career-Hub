<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles

        <!-- Custom Styles for Auth Forms -->
        <style>
            select option {
                background-color: #111827; /* gray-900 */
                color: white;
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
                -webkit-text-fill-color: white;
                -webkit-box-shadow: 0 0 0px 1000px rgba(255, 255, 255, 0.1) inset;
                transition: background-color 5000s ease-in-out 0s;
            }
        </style>
    </head>
    <body>
        <div class="font-sans antialiased bg-gray-900 text-gray-100 min-h-screen">
            {{ $slot }}
        </div>

        @livewireScripts
    </body>
</html>
