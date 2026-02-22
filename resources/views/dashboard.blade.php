<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-900">Calendario</h2>
    </x-slot>

    <div class="flex flex-col h-full overflow-hidden">

        {{-- Cabecera compacta --}}
        <div class="flex flex-wrap items-center justify-between gap-2 flex-shrink-0 mb-2">
            <div>
                <h1 class="text-lg font-bold text-gray-900">Calendario de Eventos</h1>
                <p class="text-xs text-gray-500 mt-0.5">CUCSH — Centro Universitario de Ciencias Sociales y Humanidades</p>
            </div>
            @can('eventos.crear')
                <a href="{{ route('eventos.create') }}">
                    <x-primary-button>
                        <x-heroicon-o-plus class="h-4 w-4 mr-1.5 -ml-0.5" />
                        Nuevo evento
                    </x-primary-button>
                </a>
            @endcan
        </div>

        {{-- Filtro por institución --}}
        <div class="flex flex-wrap items-center gap-3 flex-shrink-0 mb-3">
            <label for="filtro-institucion" class="text-sm font-medium text-gray-600">Institución:</label>
            <select
                id="filtro-institucion"
                class="rounded-md border-gray-300 text-sm shadow-sm transition-colors duration-150 focus:outline-none focus:border-udg-gold focus:ring-2 focus:ring-udg-gold/30"
            >
                <option value="">Todas las instituciones</option>
                @foreach ($instituciones as $institucion)
                    <option value="{{ $institucion->id }}">{{ $institucion->nombre }}</option>
                @endforeach
            </select>
        </div>

        {{-- Contenedor FullCalendar — flex-1 para ocupar espacio restante --}}
        <div class="flex-1 min-h-0 bg-white rounded-xl shadow-sm border border-gray-200 p-3 overflow-hidden">
            <div id="calendar" class="h-full"></div>
        </div>

    </div>

    @push('scripts')
        @vite('resources/js/calendar.js')
    @endpush

</x-app-layout>
