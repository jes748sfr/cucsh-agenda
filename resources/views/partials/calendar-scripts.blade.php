{{--
    Scripts Alpine compartidos para el calendario.
    Incluido en dashboard y calendario publico via @include.

    Variables requeridas del contexto:
    - $administraciones (Collection): para generar TODOS_ADMIN_IDS
    - $isPublic (bool, opcional): si es modo publico (default false)
--}}

@push('head-scripts')
    <script>
        @if(!empty($isPublic))
            // Modo publico: calendar.js lee este flag para deshabilitar acciones de creacion/edicion
            window.__calendarPublicMode = true;
        @endif

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
                panelMode: 'single', // 'single' | 'list'
                panelEvento: {},
                panelEventos: [],
                expandedIdx: null, // Indice de la tarjeta expandida en modo list

                openPanel(detail) {
                    // Toggle si se clickea el mismo evento
                    if (this.panelOpen && this.panelMode === 'single'
                        && this.panelEvento.evento_id === detail.evento_id) {
                        this.panelOpen = false;
                        return;
                    }
                    this.panelMode = 'single';
                    this.panelEvento = detail;
                    this.panelEventos = [];
                    this.expandedIdx = null;
                    this.panelOpen = true;
                },

                openListPanel(detail) {
                    this.panelMode = 'list';
                    this.panelEventos = detail.eventos || [];
                    this.panelEvento = {};
                    this.expandedIdx = null;
                    this.panelOpen = true;
                },

                toggleAccordion(idx) {
                    this.expandedIdx = this.expandedIdx === idx ? null : idx;
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
