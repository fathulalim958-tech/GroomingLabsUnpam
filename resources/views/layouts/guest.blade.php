<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Grooming Lab & MUA') }}</title>

        <!-- Anti-FOUC Theme Switcher Script -->
        <script>
            if (localStorage.theme === 'light') {
                document.documentElement.classList.remove('dark');
                document.documentElement.classList.add('light');
            } else {
                document.documentElement.classList.add('dark');
                document.documentElement.classList.remove('light');
            }
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 bg-slate-50 dark:bg-slate-950 dark:text-slate-100 antialiased min-h-screen flex flex-col justify-center items-center py-8 px-4 selection:bg-amber-500 selection:text-white relative overflow-x-hidden transition-colors duration-200">
        <!-- Theme Toggle Button -->
        <div class="fixed top-4 right-4 z-50">
            <x-theme-toggle />
        </div>

        <!-- Background decorative ambient circles -->
        <div class="fixed top-0 left-1/4 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none -z-10"></div>
        <div class="fixed bottom-0 right-1/4 w-96 h-96 bg-yellow-500/10 rounded-full blur-3xl pointer-events-none -z-10"></div>

        <div class="w-full max-w-md">
            <div class="flex justify-center mb-6">
                <a href="/" class="transition transform hover:scale-105 duration-200">
                    <x-application-logo />
                </a>
            </div>

            <div class="w-full bg-white dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800 backdrop-blur-xl shadow-xl dark:shadow-2xl rounded-2xl p-6 sm:p-8 text-slate-900 dark:text-slate-100">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
