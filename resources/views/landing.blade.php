<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Agenda CUCSH') }} - Eventos Academicos</title>
        <meta name="description" content="Consulta los eventos académicos del Centro Universitario de Ciencias Sociales y Humanidades de la Universidad de Guadalajara.">

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('images/escudo-cucsh.png') }}" type="image/png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* =============================================
               Landing page — estilos específicos
               ============================================= */

            /* Fondo del hero — imagen de fondo visible completa */
            .landing-hero-bg {
                position: relative;
                overflow: hidden;
            }

            /* Contenedor de imagen de fondo */
            .landing-bg-image {
                position: absolute;
                inset: 0;
                background-image: url('{{ asset('images/landing-bg.webp') }}');
                background-size: cover;
                background-position: center center;
                background-repeat: no-repeat;
                opacity: 0.35;
                pointer-events: none;
            }

            /* Animación suave de entrada */
            .landing-fade-up {
                animation: landingFadeUp 0.7s ease-out both;
            }
            .landing-fade-up-delay-1 { animation-delay: 0.1s; }
            .landing-fade-up-delay-2 { animation-delay: 0.25s; }
            .landing-fade-up-delay-3 { animation-delay: 0.4s; }

            @keyframes landingFadeUp {
                from {
                    opacity: 0;
                    transform: translateY(24px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Sombra elevada para el mockup */
            .landing-mockup-shadow {
                box-shadow:
                    0 20px 60px rgba(32, 41, 69, 0.15),
                    0 8px 24px rgba(32, 41, 69, 0.08);
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-white">
        <div class="min-h-screen flex flex-col" x-data="{ mobileNav: false }">

            {{-- ============================================
                 NAVBAR
                 ============================================ --}}
            <nav class="relative z-50 flex-shrink-0">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="relative flex items-center justify-between h-16 sm:h-18">

                        {{-- Logo + nombre --}}
                        <x-logo-cucsh />

                        {{-- Navegacion central (desktop) — centrada absoluta en el viewport --}}
                        <div class="hidden md:flex items-center gap-1 absolute left-1/2 -translate-x-1/2">
                            <a href="#inicio"
                               class="px-3 py-2 text-sm font-medium text-udg-blue hover:text-udg-blue/70 transition-colors rounded-lg">
                                Inicio
                            </a>
                            <a href="#funciones"
                               class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-udg-blue transition-colors rounded-lg">
                                Funciones
                            </a>
                            <a href="{{ url('/calendario') }}"
                               class="px-3 py-2 text-sm font-medium text-gray-600 hover:text-udg-blue transition-colors rounded-lg">
                                Calendario
                            </a>
                        </div>

                        {{-- Acciones (desktop) --}}
                        <div class="hidden md:flex items-center gap-3">
                            <a href="{{ route('login') }}"
                               class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg>
                                Iniciar sesión
                            </a>
                            <a href="{{ url('/calendario') }}"
                               class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-udg-blue rounded-full hover:bg-udg-blue-light transition-colors shadow-sm">
                                Ver calendario
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 19.5 15-15m0 0H8.25m11.25 0V15"/></svg>
                            </a>
                        </div>

                        {{-- Hamburguesa (movil) --}}
                        <button type="button"
                                class="md:hidden inline-flex items-center justify-center p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition"
                                @click="mobileNav = !mobileNav"
                                aria-label="Abrir menu">
                            <svg x-show="!mobileNav" class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                            <svg x-show="mobileNav" x-cloak class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- Menu movil desplegable --}}
                    <div x-show="mobileNav"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         x-cloak
                         class="md:hidden border-t border-gray-100 pb-4 pt-2 space-y-1">
                        <a href="#inicio" @click="mobileNav = false" class="block px-3 py-2 text-sm font-medium text-udg-blue rounded-lg">Inicio</a>
                        <a href="#funciones" @click="mobileNav = false" class="block px-3 py-2 text-sm font-medium text-gray-600 hover:text-udg-blue rounded-lg">Funciones</a>
                        <a href="{{ url('/calendario') }}" class="block px-3 py-2 text-sm font-medium text-gray-600 hover:text-udg-blue rounded-lg">Calendario</a>
                        <div class="border-t border-gray-100 mt-2 pt-2 flex flex-col gap-2 px-3">
                            <a href="{{ url('/calendario') }}"
                               class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-udg-blue rounded-full hover:bg-udg-blue-light transition-colors">
                                Ver calendario
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 19.5 15-15m0 0H8.25m11.25 0V15"/></svg>
                            </a>
                            <a href="{{ route('login') }}"
                               class="inline-flex items-center justify-center gap-1.5 px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg>
                                Iniciar sesión
                            </a>
                        </div>
                    </div>
                </div>
            </nav>

            {{-- ============================================
                 HERO SECTION
                 ============================================ --}}
            <section id="inicio" class="landing-hero-bg flex-1 flex flex-col">
                {{-- Imagen de fondo decorativa (skyline/cityscape)
                     OPCIONAL - PARA MODIFICACIÓN POSTERIOR
                     ================================================
                     IMAGEN REQUERIDA: public/images/landing-bg.webp
                     Dimensiones recomendadas: 1920x600px o superior
                     Formato: WebP (preferido) o PNG con transparencia
                     Contenido: skyline, cityscape estilizado, o ilustracion arquitectonica
                     La imagen cubre todo el hero desde la zona superior con opacidad 35%
                     ================================================ --}}
                <div class="landing-bg-image"></div>

                {{-- Contenido del hero --}}
                <div class="relative z-10 flex-1 flex flex-col items-center justify-start pt-12 sm:pt-16 lg:pt-20 px-4 sm:px-6">

                    {{-- Titulo principal --}}
                    <h1 class="landing-fade-up landing-fade-up-delay-1 mt-4 sm:mt-5 text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-extrabold text-udg-blue text-center leading-tight tracking-tight max-w-4xl">
                        Tu agenda académica,<br class="hidden sm:inline"> siempre disponible
                    </h1>

                    {{-- Descripcion --}}
                    <p class="landing-fade-up landing-fade-up-delay-2 mt-5 sm:mt-6 text-base sm:text-lg text-gray-600 text-center max-w-xl leading-relaxed">
                        Consulta conferencias, talleres y eventos de tu universidad de Guadalajara CUCSH.
                    </p>

                    {{-- Botones CTA --}}
                    <div class="landing-fade-up landing-fade-up-delay-3 mt-8 sm:mt-10 flex flex-col sm:flex-row items-center gap-3 sm:gap-4">
                        {{-- CTA principal --}}
                        <a href="{{ url('/calendario') }}"
                           class="inline-flex items-center gap-2.5 px-7 py-3.5 text-base font-bold text-white bg-udg-blue rounded-full hover:bg-udg-blue-light transition-all shadow-lg shadow-udg-blue/20 hover:shadow-xl hover:shadow-udg-blue/30 hover:-translate-y-0.5">
                            Ver calendario
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 19.5 15-15m0 0H8.25m11.25 0V15"/></svg>
                        </a>
                    </div>

                    {{-- Mockup del calendario
                         ================================================
                         IMAGEN REQUERIDA: public/images/landing-mockup.webp
                         Dimensiones recomendadas: 1200x750px (o mayor, relacion ~16:10)
                         Formato: WebP (preferido) o PNG
                         Contenido: captura de pantalla del calendario en uso
                         (vista mensual con algunos eventos visibles)
                         ================================================ --}}
                    <div class="landing-fade-up landing-fade-up-delay-3 mt-10 sm:mt-14 w-full max-w-4xl px-2 sm:px-0">
                        <div class="relative rounded-t-xl sm:rounded-t-2xl overflow-hidden landing-mockup-shadow bg-white border border-gray-200/60">
                            <img src="{{ asset('images/landing-mockup.webp') }}"
                                 alt="Vista previa del calendario de eventos CUCSH"
                                 class="w-full h-auto block"
                                 loading="lazy"
                                 onerror="this.parentElement.innerHTML='<div class=\'flex items-center justify-center bg-gray-100 text-gray-400 text-sm py-32 sm:py-44\'><div class=\'text-center\'><svg class=\'w-12 h-12 mx-auto mb-3 text-gray-300\' xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke-width=\'1\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z\'/></svg><p>Imagen del calendario</p><p class=\'text-xs mt-1 text-gray-300\'>public/images/landing-mockup.webp</p></div></div>'">
                        </div>
                    </div>

                </div>
            </section>

            {{-- ============================================
                 Seccion de funciones
                 ============================================ --}}
            <section id="funciones" class="relative z-10 bg-white py-16 sm:py-20 lg:py-24">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                    {{-- Titulo de seccion --}}
                    <div class="text-center max-w-2xl mx-auto mb-12 sm:mb-16">
                        <p class="text-sm font-semibold text-udg-gold uppercase tracking-wider">Funciones</p>
                        <h2 class="mt-2 text-2xl sm:text-3xl lg:text-4xl font-bold text-udg-blue leading-tight">
                            Todo lo que necesitas para estar al dia
                        </h2>
                        <p class="mt-4 text-base text-gray-500 leading-relaxed">
                            Accede a la agenda académica del CUCSH de forma rápida y sencilla, sin necesidad de crear una cuenta.
                        </p>
                    </div>

                    {{-- Grid de features --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">

                        {{-- Feature 1: Acceso libre --}}
                        <div class="group relative bg-gray-50 rounded-2xl p-6 sm:p-7 border border-gray-100 hover:border-gray-200 hover:shadow-lg hover:shadow-gray-100/50 transition-all duration-300">
                            <div class="flex items-center justify-center w-11 h-11 rounded-xl bg-udg-blue/10 text-udg-blue mb-4">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 1 1 9 0v3.75M3.75 21.75h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H3.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                            </div>
                            <h3 class="text-base font-bold text-gray-900 mb-1.5">Acceso libre</h3>
                            <p class="text-sm text-gray-500 leading-relaxed">
                                Consulta todos los eventos sin necesidad de registro ni contraseña.
                            </p>
                        </div>

                        {{-- Feature 2: Multiples vistas --}}
                        <div class="group relative bg-gray-50 rounded-2xl p-6 sm:p-7 border border-gray-100 hover:border-gray-200 hover:shadow-lg hover:shadow-gray-100/50 transition-all duration-300">
                            <div class="flex items-center justify-center w-11 h-11 rounded-xl bg-udg-blue/10 text-udg-blue mb-4">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                            </div>
                            <h3 class="text-base font-bold text-gray-900 mb-1.5">Multiples vistas</h3>
                            <p class="text-sm text-gray-500 leading-relaxed">
                                Vista mensual, semanal, diaria y agenda. Elige la que mejor se adapte a ti.
                            </p>
                        </div>

                        {{-- Feature 3: Filtros inteligentes --}}
                        <div class="group relative bg-gray-50 rounded-2xl p-6 sm:p-7 border border-gray-100 hover:border-gray-200 hover:shadow-lg hover:shadow-gray-100/50 transition-all duration-300">
                            <div class="flex items-center justify-center w-11 h-11 rounded-xl bg-udg-blue/10 text-udg-blue mb-4">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z"/></svg>
                            </div>
                            <h3 class="text-base font-bold text-gray-900 mb-1.5">Filtros inteligentes</h3>
                            <p class="text-sm text-gray-500 leading-relaxed">
                                Filtra por institución o administración para encontrar exactamente lo que buscas.
                            </p>
                        </div>

                        {{-- Feature 4: Siempre actualizado --}}
                        <div class="group relative bg-gray-50 rounded-2xl p-6 sm:p-7 border border-gray-100 hover:border-gray-200 hover:shadow-lg hover:shadow-gray-100/50 transition-all duration-300">
                            <div class="flex items-center justify-center w-11 h-11 rounded-xl bg-udg-blue/10 text-udg-blue mb-4">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182"/></svg>
                            </div>
                            <h3 class="text-base font-bold text-gray-900 mb-1.5">Siempre actualizado</h3>
                            <p class="text-sm text-gray-500 leading-relaxed">
                                Los eventos se actualizan en tiempo real. Información confiable y al dia.
                            </p>
                        </div>

                    </div>
                </div>
            </section>

            {{-- ============================================
                 Banda final
                 ============================================ --}}
            <section class="bg-udg-blue py-14 sm:py-16">
                <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center">
                    <h2 class="text-2xl sm:text-3xl font-bold text-white leading-tight">
                        Explora los eventos del CUCSH
                    </h2>
                    <p class="mt-3 text-base text-white/70 leading-relaxed max-w-lg mx-auto">
                        Conferencias, talleres, seminarios y mas. Todo en un solo lugar.
                    </p>
                    <a href="{{ url('/calendario') }}"
                       class="inline-flex items-center gap-2.5 mt-8 px-7 py-3.5 text-base font-bold text-udg-blue bg-white rounded-full hover:bg-gray-50 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                        Ir al calendario
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                    </a>
                </div>
            </section>

            {{-- ============================================
                 Footer
                 ============================================ --}}
            <footer class="bg-gray-50 border-t border-gray-100 py-8 sm:py-10">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        {{-- Logo y creditos --}}
                        <x-logo-cucsh size="sm" class="opacity-60" />
                        {{-- Copyright --}}
                        <p class="text-xs text-gray-400">
                            &copy; {{ date('Y') }} CUCSH &mdash; Universidad de Guadalajara
                        </p>
                    </div>
                </div>
            </footer>

        </div>
    </body>
</html>
