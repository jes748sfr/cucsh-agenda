<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Agenda CUCSH') }} - Calendario</title>

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('images/escudo-cucsh.png') }}" type="image/png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @stack('head-scripts')
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="h-screen bg-gray-200/70 overflow-hidden flex flex-col">

            {{-- Header --}}
            <header class="flex h-14 items-center justify-between bg-udg-blue px-6 sm:px-8 lg:px-10 flex-shrink-0">
                {{-- Logo + nombre --}}
                <a href="{{ url('/') }}" class="flex items-center gap-3 flex-shrink-0">
                    <span class="w-8 h-8 rounded overflow-hidden flex-shrink-0">
                        <img src="{{ asset('images/escudo-cucsh.png') }}" alt="CUCSH" class="w-full h-full object-cover">
                    </span>
                    <div class="hidden sm:block">
                        <span class="text-sm font-bold text-white leading-none">Agenda CUCSH</span>
                        <span class="block text-[0.65rem] text-white/60 leading-tight">Universidad de Guadalajara</span>
                    </div>
                </a>

                {{-- Acciones --}}
                <div class="flex items-center gap-1">
                    {{-- Volver a la landing --}}
                    <a href="{{ url('/inicio') }}"
                       class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-sm font-medium text-white/70 hover:text-white hover:bg-white/10 transition"
                       title="Volver al inicio">
                        <x-heroicon-o-arrow-left class="h-4 w-4" />
                        <span class="hidden sm:inline">Inicio</span>
                    </a>

                    <div class="h-4 w-px bg-white/20"></div>

                    {{-- Link iniciar sesion --}}
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-sm font-medium text-white/70 hover:text-white hover:bg-white/10 transition">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg>
                        <span class="hidden sm:inline">Iniciar sesion</span>
                    </a>
                </div>
            </header>

            {{-- Contenido de pagina --}}
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>
        @stack('scripts')
    </body>
</html>
