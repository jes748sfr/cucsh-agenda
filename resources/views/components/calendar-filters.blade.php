{{--
    Filtros del calendario: institucion + tipo de evento + administraciones + limpiar.
    Compartido entre dashboard y calendario publico.

    Props:
    - $instituciones (Collection): coleccion de instituciones
    - $administraciones (Collection): coleccion de administraciones
    - $eventosTipos (Collection): coleccion de tipos de evento
--}}

@props([
    'instituciones',
    'administraciones',
    'eventosTipos',
])

<div class="flex flex-wrap items-end gap-3 flex-shrink-0 mb-3 mt-1">

    {{-- Filtro: Institucion (dropdown) --}}
    <div class="relative" x-cloak>
        <label class="block text-xs font-medium text-gray-500 mb-1">Institucion</label>
        <button type="button"
                class="inline-flex items-center justify-between gap-2 w-full min-w-[10rem] rounded-md border shadow-sm text-sm px-3 py-[7px] transition-colors duration-150 focus:outline-none focus:border-udg-gold focus:ring-2 focus:ring-udg-gold/30"
                :class="institucionId ? 'border-udg-gold bg-white text-gray-900' : 'border-gray-300 bg-white text-gray-700'"
                @click="institucionOpen = !institucionOpen"
        >
            <span class="truncate" x-text="institucionNombre || 'Todas'"></span>
            <svg class="w-3.5 h-3.5 opacity-50 flex-shrink-0 transition-transform" :class="institucionOpen && 'rotate-180'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
        </button>

        @include('components.calendar-filters-dropdown-instituciones', ['instituciones' => $instituciones])
    </div>

    {{-- Filtro: Tipo de evento (dropdown) --}}
    <div class="relative" x-cloak>
        <label class="block text-xs font-medium text-gray-500 mb-1">Tipo de evento</label>
        <button type="button"
                class="inline-flex items-center justify-between gap-2 w-full min-w-[10rem] rounded-md border shadow-sm text-sm px-3 py-[7px] transition-colors duration-150 focus:outline-none focus:border-udg-gold focus:ring-2 focus:ring-udg-gold/30"
                :class="eventoTipoId ? 'border-udg-gold bg-white text-gray-900' : 'border-gray-300 bg-white text-gray-700'"
                @click="eventoTipoOpen = !eventoTipoOpen"
        >
            <span class="truncate" x-text="eventoTipoNombre || 'Todos'"></span>
            <svg class="w-3.5 h-3.5 opacity-50 flex-shrink-0 transition-transform" :class="eventoTipoOpen && 'rotate-180'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
        </button>

        {{-- Dropdown de tipos de evento --}}
        <div x-show="eventoTipoOpen"
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @click.outside="eventoTipoOpen = false"
             class="absolute z-50 mt-1 w-full min-w-[10rem] bg-white rounded-md shadow-lg border border-gray-200 py-1 max-h-60 overflow-y-auto"
        >
            {{-- Opcion: Todos --}}
            <button type="button"
                    class="block w-full text-left px-3 py-1.5 text-sm transition-colors"
                    :class="!eventoTipoId ? 'bg-udg-blue/5 text-udg-blue font-medium' : 'text-gray-700 hover:bg-gray-50'"
                    @click="selectEventoTipo(null, null)"
            >Todos</button>

            @foreach ($eventosTipos as $tipo)
                <button type="button"
                        class="block w-full text-left px-3 py-1.5 text-sm transition-colors"
                        :class="eventoTipoId === {{ $tipo->id }} ? 'bg-udg-blue/5 text-udg-blue font-medium' : 'text-gray-700 hover:bg-gray-50'"
                        @click="selectEventoTipo({{ $tipo->id }}, {{ Js::from($tipo->nombre) }})"
                >{{ $tipo->nombre }}</button>
            @endforeach
        </div>
    </div>

    {{-- Filtro: Administraciones (dropdown checklist) --}}
    <div class="relative" x-cloak>
        <label class="block text-xs font-medium text-gray-500 mb-1">Administracion</label>
        <button type="button"
                class="inline-flex items-center justify-between gap-2 w-full min-w-[12rem] rounded-md border shadow-sm text-sm px-3 py-[7px] transition-colors duration-150 focus:outline-none focus:border-udg-gold focus:ring-2 focus:ring-udg-gold/30"
                :class="adminFilterActive ? 'border-udg-gold bg-white text-gray-900' : 'border-gray-300 bg-white text-gray-700'"
                @click="adminsOpen = !adminsOpen"
        >
            <span class="truncate" x-text="adminFilterLabel"></span>
            <svg class="w-3.5 h-3.5 opacity-50 flex-shrink-0 transition-transform" :class="adminsOpen && 'rotate-180'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
        </button>

        @include('components.calendar-filters-dropdown-admins', ['administraciones' => $administraciones])
    </div>

    {{-- Boton: Limpiar filtros --}}
    <div class="flex items-center self-center ml-1 pl-3 border-l border-gray-200">
        <button type="button"
                title="Limpiar filtros"
                class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider transition"
                :class="(activeFilter === 'todos' && !adminFilterActive && !eventoTipoId) ? 'text-gray-300 cursor-default' : 'text-danger hover:text-danger/80'"
                :disabled="activeFilter === 'todos' && !adminFilterActive && !eventoTipoId"
                @click="setFilter('todos')"
        >
            <x-heroicon-o-x-mark class="h-3.5 w-3.5" />
            Limpiar
        </button>
    </div>

</div>
