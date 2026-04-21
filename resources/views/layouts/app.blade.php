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
        <style>
            .tree-table {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
                margin-top: 12px;
                font-size: 0.9rem;
                border-radius: 8px;
                overflow: hidden;
            }

            .tree-table th {
                background: #334155;
                color: #fff;
                padding: 12px 16px;
                text-align: left;
                font-weight: 600;
                white-space: nowrap;
            }

            .tree-table td {
                border-bottom: 1px solid #e2e8f0;
                padding: 12px 16px;
                background: #fafafa;
                vertical-align: middle;
            }

            /* Precio alineado a la derecha */
            .tree-table td:nth-child(3),
            .tree-table th:nth-child(3) {
                text-align: right;
                white-space: nowrap;
            }

            /* Stock centrado */
            .tree-table td:nth-child(4),
            .tree-table th:nth-child(4) {
                text-align: center;
            }
        </style>
    </head>
    <body class="font-sans antialiased text-slate-800">
        <div class="min-h-screen bg-gradient-to-br from-slate-200 via-slate-100 to-sky-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white/90 backdrop-blur-sm border-b border-slate-200/80 shadow-sm">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
