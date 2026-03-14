<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-900">Panel</h2>
    </x-slot>

    <div class="flex flex-col h-full overflow-hidden"
         x-data="calendarFilters()"
         x-init="$watch('activeFilter', () => refetch()); $watch('institucionId', () => refetch());"
    >

        {{-- Cabecera: titulo --}}
        <div class="flex flex-wrap items-center justify-between gap-2 flex-shrink-0 mb-1">
            <h1 class="text-lg font-bold text-gray-900">Calendario de Eventos</h1>
        </div>

        {{-- Filtros del calendario --}}
        <x-calendar-filters :instituciones="$instituciones" :administraciones="$administraciones" :eventos-tipos="$eventosTipos" />

        {{-- Contenedor FullCalendar --}}
        <div class="flex-1 min-h-0 bg-white rounded-xl shadow-sm border border-gray-200 p-3 overflow-hidden flex flex-col">

            {{-- Barra de navegacion del calendario --}}
            <x-calendar-nav-bar :show-new-event="true" :create-route="route('eventos.create')" />

            {{-- Panel lateral de detalle + contenedor del calendario --}}
            <x-calendar-event-panel :show-footer="true" :evento-base-url="url('eventos')" />
        </div>

    </div>

    {{-- Scripts Alpine compartidos (eventPanel, calendarFilters) + carga calendar.js --}}
    @include('partials.calendar-scripts', ['isPublic' => false])

</x-app-layout>
