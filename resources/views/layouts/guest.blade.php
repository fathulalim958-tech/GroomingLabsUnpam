@props(['wide' => false])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LPKKS GROOMING LAB') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

        <!-- Anti-FOUC Theme Switcher Script (Light Default) -->
        <script>
            if (localStorage.theme === 'dark') {
                document.documentElement.classList.add('dark');
                document.documentElement.classList.remove('light');
            } else {
                document.documentElement.classList.remove('dark');
                document.documentElement.classList.add('light');
            }
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-[#14243A] bg-[#F7FAFE] dark:bg-slate-950 dark:text-slate-100 antialiased min-h-screen flex items-center justify-center p-4 sm:p-6 md:p-8 lg:p-12 selection:bg-[#1769AA] selection:text-white relative overflow-x-hidden transition-colors duration-200">
        <!-- Theme Toggle Button -->
        <div class="fixed top-4 right-4 z-50">
            <x-theme-toggle />
        </div>

        @if ($wide)
            <div class="w-full max-w-[980px] my-auto flex flex-col items-center justify-center">
                {{ $slot }}
            </div>
        @else
            <div class="w-full max-w-md my-auto">
                <div class="flex justify-center mb-6">
                    <a href="/" class="transition transform hover:scale-105 duration-200">
                        <x-application-logo />
                    </a>
                </div>

                <div class="w-full bg-white dark:bg-slate-900 border border-[#E2EAF4] dark:border-slate-800 shadow-xl dark:shadow-2xl rounded-3xl p-6 sm:p-8 text-[#14243A] dark:text-slate-100">
                    {{ $slot }}
                </div>
            </div>
        @endif
    </body>
</html>
