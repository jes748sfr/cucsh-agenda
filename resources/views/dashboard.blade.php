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

        {{-- Tabs de filtro (estilo referencia Nivel 2) --}}
        <div class="flex flex-wrap items-center gap-2 flex-shrink-0 mb-3 mt-1">

            {{-- Tab: Institucion (dropdown) --}}
            <div class="relative" x-cloak>
                <button type="button"
                        class="cal-filter-pill inline-flex items-center gap-1"
                        :class="activeFilter === 'institucion' ? 'cal-filter-pill--active' : 'cal-filter-pill--inactive'"
                        @click="institucionOpen = !institucionOpen"
                >
                    <span x-text="institucionNombre ? ('Institucion: ' + institucionNombre) : 'Institucion'"></span>
                    {{-- Chevron --}}
                    <svg class="w-3.5 h-3.5 opacity-60 transition-transform" :class="institucionOpen && 'rotate-180'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                </button>

                {{-- Dropdown de instituciones --}}
                <div x-show="institucionOpen"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     @click.outside="institucionOpen = false"
                     class="absolute left-0 top-full mt-1 z-50 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-1"
                >
                    {{-- Opcion: Todas --}}
                    <button type="button"
                            class="w-full text-left px-3 py-1.5 text-sm hover:bg-gray-50 transition-colors"
                            :class="!institucionId && 'font-semibold text-udg-blue'"
                            @click="selectInstitucion(null, null); institucionOpen = false"
                    >
                        Todas las instituciones
                    </button>
                    <div class="border-t border-gray-100 my-0.5"></div>
                    @foreach ($instituciones as $inst)
                        <button type="button"
                                class="w-full text-left px-3 py-1.5 text-sm hover:bg-gray-50 transition-colors"
                                :class="institucionId == {{ $inst->id }} && 'font-semibold text-udg-blue'"
                                @click="selectInstitucion({{ $inst->id }}, {{ Js::from($inst->nombre) }}); institucionOpen = false"
                        >
                            {{ $inst->nombre }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Tab: Administraciones (dropdown checklist) --}}
            <div class="relative" x-cloak>
                <button type="button"
                        class="cal-filter-pill inline-flex items-center gap-1"
                        :class="adminFilterActive ? 'cal-filter-pill--active' : 'cal-filter-pill--inactive'"
                        @click="adminsOpen = !adminsOpen"
                >
                    <span x-text="adminFilterLabel"></span>
                    <svg class="w-3.5 h-3.5 opacity-60 transition-transform" :class="adminsOpen && 'rotate-180'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                </button>

                {{-- Dropdown checklist --}}
                <div x-show="adminsOpen"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     @click.outside="adminsOpen = false"
                     class="absolute left-0 top-full mt-1 z-50 w-64 bg-white rounded-lg shadow-lg border border-gray-200 py-2 px-3"
                >
                    {{-- Checkbox: Todas --}}
                    <label class="flex items-center gap-2 py-1.5 cursor-pointer">
                        <input type="checkbox"
                               :checked="allAdminsSelected"
                               @change="toggleAllAdmins($event.target.checked)"
                               class="rounded border-gray-300 text-udg-blue focus:ring-udg-gold/30 h-4 w-4" />
                        <span class="text-sm font-medium text-gray-900">Todas</span>
                    </label>
                    <div class="border-t border-gray-100 my-1"></div>
                    @foreach ($administraciones as $admin)
                        <label class="flex items-center gap-2 py-1 cursor-pointer">
                            <input type="checkbox"
                                   value="{{ $admin->id }}"
                                   :checked="adminSeleccionadas.includes({{ $admin->id }})"
                                   @change="toggleAdmin({{ $admin->id }}, $event.target.checked)"
                                   class="rounded border-gray-300 text-udg-blue focus:ring-udg-gold/30 h-4 w-4" />
                            <span class="text-sm text-gray-700">{{ $admin->nombre }}</span>
                        </label>
                    @endforeach
                    <div class="border-t border-gray-100 mt-2 pt-2">
                        <button type="button"
                                class="w-full text-center text-sm font-medium text-white bg-udg-blue rounded-md py-1.5 hover:bg-udg-blue/90 transition-colors"
                                @click="aplicarAdmins()"
                        >
                            Aplicar
                        </button>
                    </div>
                </div>
            </div>

            {{-- Botón: Limpiar filtros --}}
            <button type="button"
                    title="Limpiar filtros"
                    class="cal-filter-pill inline-flex items-center gap-1.5"
                    :class="(activeFilter === 'todos' && !adminFilterActive) ? 'cal-filter-pill--inactive opacity-50 cursor-default' : 'cal-filter-pill--active'"
                    :disabled="activeFilter === 'todos' && !adminFilterActive"
                    @click="setFilter('todos')"
            >
                <x-heroicon-o-funnel class="w-4 h-4" />
                <span>Limpiar</span>
            </button>

        </div>

        {{-- Contenedor FullCalendar --}}
        <div class="flex-1 min-h-0 bg-white rounded-xl shadow-sm border border-gray-200 p-3 overflow-hidden flex flex-col">

            {{-- Nivel 3: Badge de fecha + controles de navegación + dropdown vista + nuevo evento --}}
            <div class="flex items-center justify-between gap-3 flex-shrink-0 mb-2"
                 x-data="{
                     mesCorto: '', diaNum: '', titulo: '', diaSemana: '', fechaISO: '',
                     vistaActual: new URLSearchParams(window.location.search).get('vista') || 'dayGridMonth',
                     vistaOpen: false,
                     vistas: [
                         { key: 'dayGridMonth', label: 'Mes' },
                         { key: 'timeGridWeek', label: 'Semana' },
                         { key: 'timeGridDay',   label: 'Dia' },
                         { key: 'listWeek',     label: 'Agenda' },
                     ],
                     get vistaLabel() {
                         const v = this.vistas.find(v => v.key === this.vistaActual);
                         return v ? v.label : 'Mes';
                     },
                     cambiarVista(key) {
                         this.vistaActual = key;
                         this.vistaOpen = false;
                         window.dispatchEvent(new CustomEvent('calendar-change-view', { detail: { view: key } }));
                     }
                 }"
                 @calendar-date-change.window="
                     mesCorto = $event.detail.mesCorto;
                     diaNum = $event.detail.diaNum;
                     titulo = $event.detail.titulo;
                     diaSemana = $event.detail.diaSemana;
                     if ($event.detail.viewType) vistaActual = $event.detail.viewType;
                     if ($event.detail.fechaISO) fechaISO = $event.detail.fechaISO;
                 "
            >
                {{-- Izquierda: Badge + texto --}}
                <div class="flex items-center gap-3 min-w-0">
                    {{-- Badge: mes abreviado + dia grande --}}
                    <div class="flex flex-col items-center justify-center w-16 h-16 rounded-lg bg-gray-100 border border-gray-200 flex-shrink-0">
                        <span class="text-[0.65rem] font-bold uppercase leading-none text-gray-500 tracking-wide" x-text="mesCorto"></span>
                        <span class="text-2xl font-bold leading-none text-gray-900 mt-0.5" x-text="diaNum"></span>
                    </div>
                    {{-- Titulo de la vista + dia de la semana --}}
                    <div class="min-w-0">
                        <p class="text-lg font-semibold text-gray-900 capitalize" x-text="titulo"></p>
                        <p class="text-sm text-gray-500 capitalize" x-text="diaSemana"></p>
                    </div>
                </div>

                {{-- Derecha: Controles prev/hoy/next + dropdown vista + nuevo evento --}}
                <div class="flex items-center gap-2 flex-shrink-0">
                    {{-- Grupo prev / hoy / next --}}
                    <div class="flex items-center">
                        <button type="button"
                                title="Anterior"
                                class="cal-nav-btn cal-nav-btn--left"
                                @click="$dispatch('calendar-prev')"
                        >
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                        </button>
                        <button type="button"
                                class="cal-nav-btn cal-nav-btn--center"
                                @click="$dispatch('calendar-today')"
                        >
                            Hoy
                        </button>
                        <button type="button"
                                title="Siguiente"
                                class="cal-nav-btn cal-nav-btn--right"
                                @click="$dispatch('calendar-next')"
                        >
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                        </button>
                    </div>

                    {{-- Dropdown de vista --}}
                    <div class="relative" x-cloak>
                        <button type="button"
                                class="cal-nav-btn cal-view-btn"
                                @click="vistaOpen = !vistaOpen"
                        >
                            <span x-text="vistaLabel"></span>
                            <svg class="w-3.5 h-3.5 opacity-50 transition-transform" :class="vistaOpen && 'rotate-180'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                        </button>

                        <div x-show="vistaOpen"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             @click.outside="vistaOpen = false"
                             class="absolute right-0 top-full mt-1 z-50 w-36 bg-white rounded-lg shadow-lg border border-gray-200 py-1"
                        >
                            <template x-for="v in vistas" :key="v.key">
                                <button type="button"
                                        class="w-full text-left px-3 py-1.5 text-sm hover:bg-gray-50 transition-colors"
                                        :class="vistaActual === v.key && 'font-semibold text-udg-blue'"
                                        @click="cambiarVista(v.key)"
                                        x-text="v.label"
                                ></button>
                            </template>
                        </div>
                    </div>

                    {{-- Boton nuevo evento (pre-llena fecha en vista diaria, preserva vista) --}}
                    @can('eventos.crear')
                        <a :href="vistaActual === 'timeGridDay' && fechaISO
                            ? '{{ route('eventos.create') }}?fecha=' + fechaISO + '&hora_inicio=07:00&from=dashboard&vista=' + vistaActual
                            : '{{ route('eventos.create') }}?from=dashboard&vista=' + vistaActual">
                            <x-primary-button class="cal-add-event-btn">
                                <svg class="w-4 h-4 mr-1 -ml-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                Nuevo evento
                            </x-primary-button>
                        </a>
                    @endcan
                </div>
            </div>

            {{-- Contenedor flex-row: calendario + panel lateral --}}
            <div class="flex-1 min-h-0 flex flex-row overflow-hidden"
                 x-data="eventPanel()"
                 x-on:show-event-panel.window="openPanel($event.detail)"
                 x-on:close-event-panel.window="closePanel()"
                 x-on:calendar-date-change.window="onViewChange($event.detail.viewType)"
            >
                <div id="calendar" class="flex-1 min-h-0 min-w-0"></div>

                {{-- Panel lateral de detalle (solo timeGridDay desktop) --}}
                <div x-show="panelOpen"
                     x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="translate-x-full opacity-0"
                     x-transition:enter-end="translate-x-0 opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="translate-x-0 opacity-100"
                     x-transition:leave-end="translate-x-full opacity-0"
                     class="w-80 flex-shrink-0 border-l border-gray-200 bg-white flex flex-col overflow-hidden"
                >
                    {{-- Cabecera fija --}}
                    <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100 flex-shrink-0">
                        <h3 class="text-sm font-semibold text-gray-900">Detalles del Evento</h3>
                        <button type="button"
                                @click="closePanel()"
                                class="p-1 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors"
                                title="Cerrar panel"
                        >
                            <x-heroicon-o-x-mark class="w-5 h-5" />
                        </button>
                    </div>

                    {{-- Contenido scrollable --}}
                    <div class="flex-1 overflow-y-auto px-5 py-4 space-y-4">

                        {{-- Tipo de evento (subtitulo) --}}
                        <div>
                            <span class="text-xs font-semibold uppercase tracking-wider text-udg-blue/70"
                                  x-text="panelEvento.tipo || 'Evento'"
                            ></span>
                        </div>

                        {{-- Titulo del evento --}}
                        <h4 class="text-lg font-bold text-gray-900 leading-snug -mt-2"
                            x-text="panelEvento.title || ''"
                        ></h4>

                        {{-- Separador --}}
                        <div class="border-t border-gray-100"></div>

                        {{-- Fecha y hora --}}
                        <div class="flex items-start gap-3">
                            <x-heroicon-o-calendar class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" />
                            <div>
                                <p class="text-sm font-medium text-gray-900" x-text="panelEvento.fechaTexto || ''"></p>
                                <p class="text-sm text-gray-500" x-text="panelEvento.horarioTexto || ''"></p>
                            </div>
                        </div>

                        {{-- Ubicacion e institucion --}}
                        <div class="flex items-start gap-3">
                            <x-heroicon-o-building-library class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" />
                            <div>
                                <p class="text-sm font-medium text-gray-900" x-text="panelEvento.ubicacion || 'Sin ubicación registrada'"></p>
                                <p class="text-sm text-gray-500" x-text="panelEvento.institucion || ''"></p>
                            </div>
                        </div>

                        {{-- Organizador --}}
                        <div class="flex items-start gap-3">
                            <x-heroicon-o-user class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" />
                            <p class="text-sm text-gray-700" x-text="panelEvento.organizador || ''"></p>
                        </div>



                        {{-- Notas CTA (condicional — campo no disponible aun en API) --}}
                        <template x-if="panelEvento.notas_cta">
                            <div class="mt-2">
                                <div class="border-t border-gray-100 mb-3"></div>
                                <div class="flex items-start gap-3">
                                    <x-heroicon-o-document-text class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" />
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Notas</p>
                                        <p class="text-sm text-gray-700 whitespace-pre-line" x-text="panelEvento.notas_cta"></p>
                                    </div>
                                </div>
                            </div>
                        </template>

                    </div>

                    {{-- Pie fijo --}}
                    <div class="px-5 py-3 border-t border-gray-100 flex-shrink-0">
                        <a :href="'/eventos/' + panelEvento.evento_id"
                           class="flex items-center justify-center gap-2 w-full px-4 py-2 text-sm font-medium text-white bg-udg-blue rounded-lg hover:bg-udg-blue/90 transition-colors"
                        >
                            <x-heroicon-o-arrow-top-right-on-square class="w-4 h-4" />
                            Ver evento completo
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @push('head-scripts')
        <script>
            /**
             * Panel lateral de detalle de evento — funcion global para Alpine.
             * Se activa solo en vista timeGridDay en desktop (>= 1024px).
             * calendar.js despacha 'show-event-panel' con los datos del evento.
             *
             * Definido en <head> para que Alpine lo encuentre al inicializarse.
             */
            window.eventPanel = function () {
                return {
                    panelOpen: false,
                    panelEvento: {},

                    openPanel(detail) {
                        if (this.panelOpen && this.panelEvento.evento_id === detail.evento_id) {
                            this.panelOpen = false;
                            return;
                        }
                        this.panelEvento = detail;
                        this.panelOpen = true;
                    },

                    closePanel() {
                        this.panelOpen = false;
                    },

                    /**
                     * Cierra el panel cuando el usuario cambia a una vista
                     * diferente de timeGridDay.
                     */
                    onViewChange(viewType) {
                        if (viewType !== 'timeGridDay') {
                            this.panelOpen = false;
                        }
                    }
                };
            };

            /**
             * Filtros del calendario — expuesto como funcion global para Alpine.
             * calendar.js lee window.__calendarFilters para extraParams.
             *
             * Definido en <head> para que Alpine lo encuentre al inicializarse.
             */
            // IDs de todas las administraciones (generado server-side)
            const TODOS_ADMIN_IDS = @json($administraciones->pluck('id')->values());

            window.calendarFilters = function () {
                return {
                    activeFilter: 'todos',
                    institucionId: null,
                    institucionNombre: null,
                    institucionOpen: false,
                    adminsOpen: false,

                    // Estado de administraciones: array de IDs seleccionados
                    // Por defecto todas seleccionadas (= sin filtro)
                    adminSeleccionadas: [...TODOS_ADMIN_IDS],
                    // IDs aplicados (solo cambian al hacer click en "Aplicar")
                    adminAplicadas: [...TODOS_ADMIN_IDS],

                    // Computed: todas seleccionadas?
                    get allAdminsSelected() {
                        return this.adminSeleccionadas.length === TODOS_ADMIN_IDS.length;
                    },

                    // Computed: filtro de administraciones activo?
                    get adminFilterActive() {
                        return this.adminAplicadas.length > 0
                            && this.adminAplicadas.length < TODOS_ADMIN_IDS.length;
                    },

                    // Computed: etiqueta del boton
                    get adminFilterLabel() {
                        if (this.adminFilterActive) {
                            const n = this.adminAplicadas.length;
                            return 'Administraciones (' + n + ')';
                        }
                        return 'Administraciones';
                    },

                    toggleAllAdmins(checked) {
                        this.adminSeleccionadas = checked ? [...TODOS_ADMIN_IDS] : [];
                    },

                    toggleAdmin(id, checked) {
                        if (checked) {
                            if (!this.adminSeleccionadas.includes(id)) {
                                this.adminSeleccionadas.push(id);
                            }
                        } else {
                            this.adminSeleccionadas = this.adminSeleccionadas.filter(i => i !== id);
                        }
                    },

                    aplicarAdmins() {
                        this.adminsOpen = false;
                        this.adminAplicadas = [...this.adminSeleccionadas];
                        this.refetch();
                    },

                    setFilter(tipo) {
                        if (tipo === 'todos') {
                            this.activeFilter = 'todos';
                            this.institucionId = null;
                            this.institucionNombre = null;
                            // Resetear administraciones a todas
                            this.adminSeleccionadas = [...TODOS_ADMIN_IDS];
                            this.adminAplicadas = [...TODOS_ADMIN_IDS];
                        }
                        this.institucionOpen = false;
                        this.adminsOpen = false;
                        this.refetch();
                    },

                    selectInstitucion(id, nombre) {
                        if (id) {
                            this.activeFilter = 'institucion';
                            this.institucionId = id;
                            this.institucionNombre = nombre;
                        } else {
                            // "Todas" seleccionada — quitar filtro institucion
                            this.activeFilter = 'todos';
                            this.institucionId = null;
                            this.institucionNombre = null;
                        }
                    },

                    /**
                     * Devuelve los params para la API segun los filtros activos.
                     * calendar.js llama a esto via window.__calendarFilters.
                     */
                    getApiParams() {
                        const params = {};

                        // Filtro de institucion
                        if (this.activeFilter === 'institucion' && this.institucionId) {
                            params.institucion_id = this.institucionId;
                        }

                        // Filtro de administraciones (solo si no son todas)
                        if (this.adminAplicadas.length > 0
                            && this.adminAplicadas.length < TODOS_ADMIN_IDS.length) {
                            // Enviar como array para que Laravel lo reciba como administracion_ids[]
                            params['administracion_ids[]'] = this.adminAplicadas;
                        }

                        return params;
                    },

                    refetch() {
                        // calendar.js escucha este evento custom
                        window.dispatchEvent(new CustomEvent('calendar-refetch'));
                    },

                    init() {
                        // Exponer referencia para que calendar.js pueda leer los params
                        window.__calendarFilters = this;
                    }
                };
            };
        </script>
    @endpush

    @push('scripts')
        @vite('resources/js/calendar.js')
    @endpush

</x-app-layout>
