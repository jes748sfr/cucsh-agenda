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
        <div x-data="{ sidebarOpen: $persist(true) }" class="min-h-screen flex bg-gray-100">

            {{-- Overlay móvil --}}
            <div x-show="sidebarOpen"
                 x-transition.opacity.duration.300ms
                 @click="sidebarOpen = false"
                 class="fixed inset-0 z-20 bg-gray-900/50 sm:hidden">
            </div>

            {{-- Sidebar --}}
            @include('layouts.sidebar')

            {{-- Contenido principal --}}
            <div class="flex-1 flex flex-col min-h-screen min-w-0 transition-[margin] duration-300 ease-in-out"
                 :class="sidebarOpen ? 'sm:ml-64' : 'sm:ml-16'">

                {{-- Top bar --}}
                <header class="sticky top-0 z-10 flex h-16 items-center gap-4 border-b border-gray-200 bg-white px-4 sm:px-6">
                    {{-- Toggle sidebar --}}
                    <button @click="sidebarOpen = !sidebarOpen"
                            type="button"
                            class="inline-flex items-center justify-center rounded-md p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition"
                            aria-label="Alternar menú lateral">
                        <x-heroicon-o-bars-3 class="h-5 w-5" />
                    </button>

                    {{-- Título de página --}}
                    @isset($header)
                        <div class="flex-1 min-w-0">
                            {{ $header }}
                        </div>
                    @endisset

                    {{-- Dropdown usuario --}}
                    <div class="ml-auto flex items-center">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center gap-2 rounded-md px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition">
                                    <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary text-xs font-semibold text-white sm:hidden">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
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
                <main class="flex-1 p-4 sm:p-6">
                    <x-flash-message />
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
