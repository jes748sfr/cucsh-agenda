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
    <body class="font-sans antialiased">
        <div x-data="{ mobileMenu: false }" class="h-screen bg-gray-50 overflow-hidden">

            {{-- Overlay móvil --}}
            <div x-show="mobileMenu"
                 x-transition.opacity.duration.200ms
                 @click="mobileMenu = false"
                 class="fixed inset-0 z-20 bg-gray-900/60 backdrop-blur-sm lg:hidden"
                 x-cloak>
            </div>

            {{-- Sidebar --}}
            @include('layouts.sidebar')

            {{-- Contenido principal --}}
            <div class="lg:pl-64 flex flex-col h-screen">

                {{-- Top bar --}}
                <header class="sticky top-0 z-10 flex h-14 items-center gap-4 border-b border-gray-200/80 bg-white/95 backdrop-blur-sm px-4 sm:px-6">
                    {{-- Botón menú móvil --}}
                    <button @click="mobileMenu = true"
                            type="button"
                            class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition lg:hidden"
                            aria-label="Abrir menú">
                        <x-heroicon-o-bars-3 class="h-5 w-5" />
                    </button>

                    {{-- Título de página --}}
                    @isset($header)
                        <div class="flex-1 min-w-0">
                            {{ $header }}
                        </div>
                    @endisset

                    {{-- Dropdown usuario (solo en pantallas grandes) --}}
                    <div class="ml-auto hidden lg:flex items-center">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center gap-2 rounded-lg px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition">
                                    <div class="flex h-7 w-7 items-center justify-center rounded-full bg-primary/10 text-xs font-semibold text-primary">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <span>{{ Auth::user()->name }}</span>
                                    <x-heroicon-m-chevron-down class="h-3.5 w-3.5 text-gray-400" />
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    Perfil
                                </x-dropdown-link>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault(); this.closest('form').submit();">
                                        Cerrar sesión
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </header>

                {{-- Contenido de página --}}
                <main class="flex-1 overflow-y-hidden p-4 sm:p-6">
                    <x-flash-message />
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
