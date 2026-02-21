<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-900">Calendario</h2>
    </x-slot>

    {{-- Cabecera de contenido --}}
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Calendario de Eventos</h1>
            <p class="text-sm text-gray-500 mt-1">CUCSH — Centro Universitario de Ciencias Sociales y Humanidades</p>
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
    <div class="mb-4 flex flex-wrap items-center gap-3">
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

    {{-- Contenedor FullCalendar --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <div id="calendar"></div>
    </div>

    {{-- Drawer lateral de detalle --}}
    <div
        x-data="{
            open: false,
            ev: {
                evento_id: null,
                title: '',
                start: null,
                end: null,
                institucion: '',
                tipo: '',
                organizador: '',
                ubicacion: ''
            },
            formatFecha(date) {
                if (!date) return '';
                return new Intl.DateTimeFormat('es-MX', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                }).format(new Date(date));
            },
            formatHora(date) {
                if (!date) return '';
                return new Intl.DateTimeFormat('es-MX', {
                    hour: '2-digit',
                    minute: '2-digit'
                }).format(new Date(date));
            }
        }"
        @show-evento.window="ev = $event.detail; open = true"
        x-cloak
    >
        {{-- Overlay --}}
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-30 bg-gray-900/20"
            @click="open = false"
        ></div>

        {{-- Panel desde la derecha --}}
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="fixed right-0 top-0 h-full w-full sm:w-96 z-40 bg-white shadow-xl border-l border-gray-200 flex flex-col"
        >
            {{-- Header del panel --}}
            <div class="flex items-start justify-between gap-3 px-5 py-4 border-b border-gray-200 bg-gray-50">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1" x-text="ev.tipo"></p>
                    <h3 class="text-base font-semibold text-gray-900 leading-snug" x-text="ev.title"></h3>
                </div>
                <button
                    type="button"
                    @click="open = false"
                    class="flex-shrink-0 mt-0.5 rounded-md p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition"
                    aria-label="Cerrar panel"
                >
                    <x-heroicon-o-x-mark class="h-5 w-5" />
                </button>
            </div>

            {{-- Cuerpo del panel --}}
            <div class="flex-1 overflow-y-auto px-5 py-5 space-y-5">

                {{-- Fecha y hora --}}
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 mt-0.5">
                        <x-heroicon-o-calendar-days class="h-5 w-5 text-gray-400" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800 capitalize" x-text="formatFecha(ev.start)"></p>
                        <p class="text-sm text-gray-500 mt-0.5">
                            <span x-text="formatHora(ev.start)"></span>
                            <template x-if="ev.end">
                                <span> — <span x-text="formatHora(ev.end)"></span></span>
                            </template>
                        </p>
                    </div>
                </div>

                {{-- Institución --}}
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 mt-0.5">
                        <x-heroicon-o-building-office-2 class="h-5 w-5 text-gray-400" />
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider font-medium mb-0.5">Institución</p>
                        <p class="text-sm text-gray-800" x-text="ev.institucion"></p>
                    </div>
                </div>

                {{-- Organizador --}}
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 mt-0.5">
                        <x-heroicon-o-user-circle class="h-5 w-5 text-gray-400" />
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider font-medium mb-0.5">Organizador</p>
                        <p class="text-sm text-gray-800" x-text="ev.organizador"></p>
                    </div>
                </div>

                {{-- Ubicación (condicional) --}}
                <template x-if="ev.ubicacion">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-0.5">
                            <x-heroicon-o-map-pin class="h-5 w-5 text-gray-400" />
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider font-medium mb-0.5">Ubicación</p>
                            <p class="text-sm text-gray-800" x-text="ev.ubicacion"></p>
                        </div>
                    </div>
                </template>

            </div>

            {{-- Footer del panel --}}
            <div class="px-5 py-4 border-t border-gray-200 bg-gray-50">
                <a
                    :href="'/eventos/' + ev.evento_id"
                    class="flex w-full items-center justify-center gap-2 rounded-md bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-hover transition"
                >
                    <x-heroicon-o-eye class="h-4 w-4" />
                    Ver evento completo
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/calendar.js')
    @endpush

</x-app-layout>
