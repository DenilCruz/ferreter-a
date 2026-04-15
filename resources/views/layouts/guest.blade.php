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
    <body class="font-sans text-slate-800 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-slate-200 via-slate-100 to-sky-100 px-4">
            <div class="w-full sm:max-w-md mt-2 mb-2 text-center">
                <a href="{{ url('/') }}" class="text-sm font-semibold text-teal-700 hover:text-teal-600 transition">
                    ← {{ __('Volver al inventario') }}
                </a>
            </div>

            <div>
                <a href="{{ url('/') }}" class="inline-block">
                    <x-application-logo class="w-20 h-20 fill-current text-teal-700" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white/95 backdrop-blur-sm border border-slate-200/80 shadow-lg overflow-hidden sm:rounded-xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
