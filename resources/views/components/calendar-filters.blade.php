{{--
    Filtros del calendario: institucion + administraciones + limpiar.
    Compartido entre dashboard y calendario publico.

    Props:
    - $instituciones (Collection): coleccion de instituciones
    - $administraciones (Collection): coleccion de administraciones
--}}

@props([
    'instituciones',
    'administraciones',
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
                :class="(activeFilter === 'todos' && !adminFilterActive) ? 'text-gray-300 cursor-default' : 'text-danger hover:text-danger/80'"
                :disabled="activeFilter === 'todos' && !adminFilterActive"
                @click="setFilter('todos')"
        >
            <x-heroicon-o-x-mark class="h-3.5 w-3.5" />
            Limpiar
        </button>
    </div>

</div>
