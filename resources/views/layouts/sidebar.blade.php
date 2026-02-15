{{-- Sidebar lateral colapsable
     Estrategia: ancho fijo w-64 siempre. Al colapsar, el sidebar se "recorta"
     visualmente usando el margen del contenido principal (sm:ml-16 vs sm:ml-64).
     El overflow-hidden del wrapper oculta el texto que queda fuera del área visible.
     En mobile: translate-x para deslizar fuera de pantalla.
--}}
<aside class="fixed inset-y-0 left-0 z-30 w-64 flex flex-col bg-white border-r border-gray-200 transition-transform duration-300 ease-in-out"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-48 sm:-translate-x-48'">

    {{-- Logo --}}
    <div class="px-5 pt-6 pb-5">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-x-3">
            <img src="{{ asset('images/escudo-udg.png') }}"
                 alt="Escudo Universidad de Guadalajara"
                 class="h-10 w-auto flex-shrink-0">
            <div class="flex flex-col justify-center overflow-hidden transition-opacity duration-300"
                 :class="sidebarOpen ? 'opacity-100' : 'opacity-0'">
                <span class="text-sm font-bold leading-none text-primary tracking-wide whitespace-nowrap">
                    CUCSH
                </span>
                <span class="mt-0.5 text-xs leading-tight text-gray-500 whitespace-nowrap">
                    Agenda universitaria
                </span>
            </div>
        </a>
    </div>

    {{-- Navegación --}}
    <nav class="flex-1 overflow-y-auto overflow-x-hidden px-3 pb-4 space-y-1">
        <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="heroicon-o-home">
            Panel
        </x-sidebar-link>

        @can('eventos.ver')
            <x-sidebar-link :href="route('eventos.index')" :active="request()->routeIs('eventos.*')" icon="heroicon-o-calendar-days">
                Eventos
            </x-sidebar-link>
        @endcan

        @can('organizadores.ver')
            <x-sidebar-link :href="route('organizadores.index')" :active="request()->routeIs('organizadores.*')" icon="heroicon-o-user-group">
                Organizadores
            </x-sidebar-link>
        @endcan

        @can('catalogos.ver')
            <x-sidebar-group label="Catálogos" icon="heroicon-o-rectangle-stack"
                             :active="request()->routeIs('eventos-tipos.*', 'instituciones.*', 'administraciones.*')">
                <x-sidebar-sublink :href="route('eventos-tipos.index')" :active="request()->routeIs('eventos-tipos.*')">
                    Tipos de Evento
                </x-sidebar-sublink>
                <x-sidebar-sublink :href="route('instituciones.index')" :active="request()->routeIs('instituciones.*')">
                    Instituciones
                </x-sidebar-sublink>
                <x-sidebar-sublink :href="route('administraciones.index')" :active="request()->routeIs('administraciones.*')">
                    Administraciones
                </x-sidebar-sublink>
            </x-sidebar-group>
        @endcan

        @can('usuarios.ver')
            <x-sidebar-link :href="route('users.index')" :active="request()->routeIs('users.*')" icon="heroicon-o-users">
                Usuarios
            </x-sidebar-link>
        @endcan
    </nav>

    {{-- Footer: usuario --}}
    <div class="border-t border-gray-100 px-4 py-3">
        <div class="flex items-center gap-3">
            <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-primary text-xs font-semibold text-white">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="min-w-0 overflow-hidden transition-opacity duration-300"
                 :class="sidebarOpen ? 'opacity-100' : 'opacity-0'">
                <p class="truncate text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                <p class="truncate text-xs text-gray-500">{{ Auth::user()->email }}</p>
            </div>
        </div>
    </div>
</aside>