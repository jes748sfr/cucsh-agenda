<x-public-layout>

    <div class="flex flex-col h-full overflow-hidden"
         x-data="calendarFilters()"
         x-init="$watch('activeFilter', () => refetch()); $watch('institucionId', () => refetch());"
    >

        {{-- Cabecera: titulo + filtros agrupados --}}
        <div class="flex-shrink-0 bg-white rounded-xl shadow-sm border border-gray-200 px-4 py-3 mb-3">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                <div class="flex items-center gap-2.5">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-udg-blue/10 text-udg-blue flex-shrink-0">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                    </div>
                    <div>
                        <h1 class="text-base font-bold text-gray-900 leading-tight">Calendario de Eventos</h1>
                        <p class="text-xs text-gray-500 leading-tight">CUCSH &mdash; Universidad de Guadalajara</p>
                    </div>
                </div>
            </div>

            {{-- Filtros del calendario --}}
            <x-calendar-filters :instituciones="$instituciones" :administraciones="$administraciones" :eventos-tipos="$eventosTipos" />
        </div>

        {{-- Contenedor FullCalendar --}}
        <div class="flex-1 min-h-0 bg-white rounded-xl shadow-sm border border-gray-200 p-3 overflow-hidden flex flex-col">

            {{-- Barra de navegacion del calendario --}}
            <x-calendar-nav-bar />

            {{-- Panel lateral de detalle + contenedor del calendario --}}
            <x-calendar-event-panel />
        </div>

    </div>

    {{-- Scripts Alpine compartidos (eventPanel, calendarFilters) + carga calendar.js --}}
    @include('partials.calendar-scripts', ['isPublic' => true])

</x-public-layout>
