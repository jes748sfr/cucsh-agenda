<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Agenda CUCSH') }} - Calendario</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @stack('head-scripts')
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="h-screen bg-gray-50 overflow-hidden flex flex-col">

            {{-- Header minimal --}}
            <header class="flex h-14 items-center justify-between border-b border-gray-200/80 bg-white/95 backdrop-blur-sm px-4 sm:px-6 flex-shrink-0">
                {{-- Logo + nombre --}}
                <a href="{{ route('calendario.publico') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/escudo-udg.svg') }}" alt="Escudo UDG" class="h-8 w-auto">
                    <span class="text-sm font-semibold text-gray-900">Agenda CUCSH</span>
                </a>

                {{-- Link iniciar sesion --}}
                <a href="{{ route('login') }}"
                   class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-600 hover:text-gray-900 transition">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg>
                    Iniciar sesion
                </a>
            </header>

            {{-- Contenido de pagina --}}
            <main class="flex-1 overflow-y-auto p-4 sm:p-6">
                {{ $slot }}
            </main>
        </div>
        @stack('scripts')
    </body>
</html>
