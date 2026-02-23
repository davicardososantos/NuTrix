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
    </head>
    <body class="font-figtree text-gray-900 antialiased bg-gradient-to-br from-amber-50 via-white to-green-50">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-amber-200 opacity-20 rounded-full blur-3xl -mr-48 -mt-48"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-green-200 opacity-20 rounded-full blur-3xl -ml-48 -mb-48"></div>
            
            <div class="relative z-10">
                <a href="/" class="inline-flex items-center justify-center">
                    <x-application-logo class="text-gray-900" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-8 mx-4 px-8 py-8 bg-white shadow-2xl overflow-hidden sm:rounded-2xl border border-gray-100 relative z-10">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
