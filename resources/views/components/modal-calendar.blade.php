<x-modal name="agenda-belenes" id="modal-agenda" :show="false" maxWidth="2xl">
    <div x-data="{
        fecha: null,
        events: [],
        loading: true,

        exportarDia() {
            if (!this.fecha) return;

            const form = document.getElementById('exportForm');

            form.fecha_inicio.value = this.fecha;
            form.fecha_fin.value = this.fecha;

            form.submit();
        },

        formatDate(dateString) {
            const date = new Date(dateString);
            const day = date.toLocaleDateString('es-MX', { weekday: 'long' });
            return day.charAt(0).toUpperCase() + day.slice(1);
        },

        formatTime(dateString) {
            const date = new Date(dateString);
            return date.toLocaleTimeString('es-MX', {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
        }
    }"

    x-on:modal-data-agenda-belenes.window="
        fecha = $event.detail.fecha;
        events = $event.detail.events ?? [];
        loading = $event.detail.loading ?? false;
    ">

        <!-- HEADER -->
        <div class="flex justify-end p-4">
            <button
                type="button"
                @click="$dispatch('close-modal', 'agenda-belenes'); clearBody(); limpiar();"
                class="text-gray-500 hover:text-gray-700"
            >
                ✕
            </button>
        </div>

        <!-- TITULO -->
        <div class="bg-cyan-500 text-white text-center py-2">
            <div class="flex justify-between items-center px-4 py-2">
                <h5 class="text-lg font-semibold">
                    Eventos del día <span x-text="fecha"></span>
                </h5>

                @can('reportes.generar')
                <x-primary-button type="button" x-data @click="exportarDia()" class="bg-white text-cyan-600 transform transition hover:scale-105 hover:bg-white hover:text-cyan-600">
                    Exportar eventos
                </x-primary-button>
                @endcan
            </div>
        </div>

        <!-- CONTENIDO -->
        <div class="p-4">

            <!-- 🔄 LOADING -->
            <template x-if="loading">
                <div class="flex flex-col items-center justify-center py-10 text-gray-500">
                    <svg class="animate-spin h-8 w-8 mb-3" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    </svg>

                    Cargando eventos...
                </div>
            </template>

            <!-- 📭 EMPTY STATE -->
            <template x-if="!loading && events.length === 0">
                <div class="text-center text-gray-500 py-10">
                    No hay eventos para este día
                </div>
            </template>

            <!-- 📦 EVENTS LIST -->
            <template x-if="!loading && events.length > 0">
                <div class="space-y-3">

                    <template x-for="event in events" :key="event.id">
                        <div
                            class="flex border rounded-lg shadow-sm overflow-hidden cursor-pointer hover:bg-gray-50"
                            @click="
                                $dispatch('close-modal', 'agenda-belenes');

                                window.dispatchEvent(new CustomEvent('modal-data-ver-evento', {
                                    detail: { event: event }
                                }));

                                setTimeout(() => {
                                    window.dispatchEvent(new CustomEvent('open-modal', {
                                        detail: 'ver-evento'
                                    }));
                                }, 100);
                            "
                        >

                            <!-- COLOR BAR -->
                            <div class="w-2"
                                 :style="`background-color: ${event.backgroundColor}`">
                            </div>

                            <!-- CONTENT -->
                            <div class="p-3 flex-1">

                                <div class="font-semibold text-gray-800" x-text="event.title"></div>

                                <div class="text-sm text-gray-600">
                                    <span x-text="formatDate(event.start)"></span>
                                    <span x-text="formatTime(event.start)"></span>
                                    -
                                    <span x-text="formatTime(event.end)"></span>
                                </div>

                                <div class="text-xs text-gray-500 mt-1">
                                    <div>Institución: <span x-text="event.extendedProps.institucion"></span></div>
                                    <div>Tipo: <span x-text="event.extendedProps.tipo"></span></div>
                                    <div>Organizador: <span x-text="event.extendedProps.organizador"></span></div>
                                    <div>Ubicación: <span x-text="event.extendedProps.ubicacion"></span></div>
                                </div>

                            </div>
                        </div>
                    </template>

                </div>
            </template>

        </div>

    </div>

    <!-- FORM EXPORT -->
    <form id="exportForm" method="POST" action="/eventos-exportando">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="fecha_inicio" x-model="fecha">
        <input type="hidden" name="fecha_fin" x-model="fecha">
    </form>
</x-modal>
