{{--
    Barra de navegacion del calendario: badge de fecha + prev/hoy/next + dropdown vista.
    Compartido entre dashboard y calendario publico.

    Props:
    - $showNewEvent (bool): mostrar boton "Nuevo evento" (default false)
    - $createRoute (string): ruta para crear evento (default '')
--}}

@props([
    'showNewEvent' => false,
    'createRoute' => '',
])

{{-- Nivel 3: Badge de fecha + controles de navegacion + dropdown vista --}}
<div class="flex items-center justify-between gap-3 flex-shrink-0 mb-2"
     x-data="{
         mesCorto: '', diaNum: '', titulo: '', diaSemana: '', fechaISO: '', fechaFinISO: '',
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
         @if($showNewEvent)
         get puedeCrear() {
             const hoy = new Date();
             hoy.setHours(0,0,0,0);
             const fecha = new Date(this.fechaISO + 'T00:00:00');

             if (this.vistaActual === 'timeGridDay') {
                 return fecha >= hoy;
             }
             if (this.vistaActual === 'timeGridWeek') {
                 const finSemana = new Date(this.fechaFinISO + 'T00:00:00');
                 return finSemana >= hoy;
             }
             if (this.vistaActual === 'dayGridMonth') {
                 const finMes = new Date(this.fechaFinISO + 'T00:00:00');
                 return finMes >= hoy;
             }
             return true;
         },
         @endif
         cambiarVista(key) {
             this.vistaActual = key;
             this.vistaOpen = false;
             window.dispatchEvent(new CustomEvent('calendar-change-view', { detail: { view: key } }));
         }
     }"
     @calendar-date-change.window="
         mesCorto = $event.detail.mesCorto;
         diaNum = $event.detail.diaNum;
         let t = $event.detail.titulo.toLowerCase();
         titulo = t.charAt(0).toUpperCase() + t.slice(1);
         diaSemana = $event.detail.diaSemana;
         if ($event.detail.viewType) vistaActual = $event.detail.viewType;
         if ($event.detail.fechaISO) fechaISO = $event.detail.fechaISO;
         if ($event.detail.fechaFinISO) fechaFinISO = $event.detail.fechaFinISO;
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
            <p class="text-lg font-semibold text-gray-900" x-text="titulo"></p>
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

        {{-- Boton nuevo evento (condicional) --}}
        @if($showNewEvent)
            @can('eventos.crear')
                <a :href="vistaActual === 'timeGridDay' && fechaISO
                    ? '{{ $createRoute }}?fecha=' + fechaISO + '&hora_inicio=07:00&from=dashboard&vista=' + vistaActual
                    : '{{ $createRoute }}?from=dashboard&vista=' + vistaActual + (fechaISO ? '&fecha=' + fechaISO : '')"
                   :class="{ 'pointer-events-none opacity-50': !puedeCrear }"
                   x-bind:tabindex="puedeCrear ? 0 : -1"
                >
                    <x-primary-button class="cal-add-event-btn">
                        <svg class="w-4 h-4 mr-1 -ml-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Nuevo evento
                    </x-primary-button>
                </a>
            @endcan
        @endif
    </div>
</div>
