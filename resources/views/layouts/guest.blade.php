<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Agenda CUCSH') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex">

            {{-- Panel izquierdo — formulario --}}
            <div class="w-full lg:w-1/2 flex items-center justify-center bg-white px-8 py-12 sm:px-12">
                <div class="w-full max-w-sm">
                    {{ $slot }}
                </div>
            </div>

            {{-- Panel derecho — imagen decorativa (solo desktop) --}}
            <div class="hidden lg:block lg:w-1/2 p-4 guest-panel-right">
                <div class="relative h-full rounded-2xl bg-udg-blue overflow-hidden flex items-center justify-center">

                    {{-- Escudo UDG como placeholder (se reemplazara con foto del campus) --}}
                    <img src="{{ asset('images/escudo-udg.png') }}"
                         alt=""
                         aria-hidden="true"
                         class="w-32 h-auto opacity-15 select-none pointer-events-none">

                    {{-- Patron decorativo sutil --}}
                    <div class="absolute inset-0 opacity-[0.03]"
                         style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 32px 32px;">
                    </div>

                    {{-- Overlay glass-morphism con quote institucional --}}
                    <div class="absolute bottom-6 left-6 right-6 backdrop-blur-md bg-white/10 border border-white/20 rounded-xl p-6">
                        <blockquote>
                            <p class="text-white/90 text-sm leading-relaxed font-medium">
                                "Piensa y trabaja"
                            </p>
                            <footer class="mt-3 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white/70" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-white/80 text-xs font-semibold">Universidad de Guadalajara</p>
                                    <p class="text-white/50 text-xs">Centro Universitario de Ciencias Sociales y Humanidades</p>
                                </div>
                            </footer>
                        </blockquote>
                    </div>

                </div>
            </div>

        </div>
    </body>
</html>
