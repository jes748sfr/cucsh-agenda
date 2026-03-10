{{-- Sidebar fijo en desktop, deslizable en móvil --}}
<aside class="fixed inset-y-0 left-0 z-30 w-64 flex flex-col bg-white border-r border-gray-200/80"
       :class="mobileMenu ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
       x-transition:enter="transition ease-out duration-200"
       x-transition:enter-start="-translate-x-full"
       x-transition:enter-end="translate-x-0"
       x-transition:leave="transition ease-in duration-150"
       x-transition:leave-start="translate-x-0"
       x-transition:leave-end="-translate-x-full">

    {{-- Encabezado: Logo + Cerrar (móvil) --}}
    <div class="flex items-center justify-between h-14 px-5 border-b border-gray-100">
        <x-logo-cucsh size="sm" class="sm:flex" />

        {{-- Cerrar en móvil --}}
        <button @click="mobileMenu = false"
                type="button"
                class="rounded-md p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition lg:hidden"
                aria-label="Cerrar menú">
            <x-heroicon-o-x-mark class="h-5 w-5" />
        </button>
    </div>

    {{-- Navegación principal --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4">
        {{-- Sección: General --}}
        <div class="mb-6">
            <p class="px-3 mb-2 text-[11px] font-semibold uppercase tracking-wider text-gray-400">General</p>
            <div class="space-y-0.5">
                <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="heroicon-o-squares-2x2">
                    Panel
                </x-sidebar-link>

                @can('eventos.ver')
                    <x-sidebar-link :href="route('eventos.index')" :active="request()->routeIs('eventos.*')" icon="heroicon-o-calendar-days">
                        Eventos
                    </x-sidebar-link>
                @endcan
            </div>
        </div>

        {{-- Sección: Gestión --}}
        @canany(['organizadores.ver', 'catalogos.ver'])
        <div class="mb-6">
            <p class="px-3 mb-2 text-[11px] font-semibold uppercase tracking-wider text-gray-400">Gestión</p>
            <div class="space-y-0.5">
                @can('organizadores.ver')
                    <x-sidebar-link :href="route('organizadores.index')" :active="request()->routeIs('organizadores.*')" icon="heroicon-o-user-group">
                        Organizadores
                    </x-sidebar-link>
                @endcan

                @can('catalogos.ver')
                    <x-sidebar-group label="Catálogos" icon="heroicon-o-rectangle-stack"
                                     :active="request()->routeIs('eventos-tipos.*', 'instituciones.*', 'administraciones.*', 'ubicaciones.*')">
                        <x-sidebar-sublink :href="route('eventos-tipos.index')" :active="request()->routeIs('eventos-tipos.*')">
                            Tipos de Evento
                        </x-sidebar-sublink>
                        <x-sidebar-sublink :href="route('instituciones.index')" :active="request()->routeIs('instituciones.*')">
                            Instituciones
                        </x-sidebar-sublink>
                        <x-sidebar-sublink :href="route('administraciones.index')" :active="request()->routeIs('administraciones.*')">
                            Administraciones
                        </x-sidebar-sublink>
                        <x-sidebar-sublink :href="route('ubicaciones.index')" :active="request()->routeIs('ubicaciones.*')">
                            Ubicaciones
                        </x-sidebar-sublink>
                    </x-sidebar-group>
                @endcan
            </div>
        </div>
        @endcanany

        {{-- Sección: Sistema --}}
        @can('usuarios.ver')
        <div class="mb-6">
            <p class="px-3 mb-2 text-[11px] font-semibold uppercase tracking-wider text-gray-400">Sistema</p>
            <div class="space-y-0.5">
                <x-sidebar-link :href="route('users.index')" :active="request()->routeIs('users.*')" icon="heroicon-o-cog-6-tooth">
                    Usuarios
                </x-sidebar-link>
            </div>
        </div>
        @endcan
    </nav>

    {{-- Footer: usuario --}}
    <div class="border-t border-gray-100 px-4 py-3">
        <div class="flex items-center gap-3">
            <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-primary/10 text-xs font-bold text-primary">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-medium text-gray-800">{{ Auth::user()->name }}</p>
                <p class="truncate text-xs text-gray-400">{{ Auth::user()->getRoleNames()->first() ?? 'Sin rol' }}</p>
            </div>
            {{-- Cerrar sesión --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="rounded-md p-1.5 text-gray-400 hover:text-danger hover:bg-red-50 transition"
                        title="Cerrar sesión">
                    <x-heroicon-o-arrow-right-start-on-rectangle class="h-4.5 w-4.5" />
                </button>
            </form>
        </div>
    </div>
</aside>
