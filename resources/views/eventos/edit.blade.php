<x-app-layout>
    @php
        $from = request()->query('from');
        $vista = request()->query('vista');
        if ($from === 'dashboard') {
            $backUrl = route('dashboard') . ($vista ? '?vista=' . urlencode($vista) : '');
        } else {
            $back      = url()->previous();
            $indexPath = rtrim(parse_url(route('eventos.index'), PHP_URL_PATH), '/');
            $backPath  = rtrim(parse_url($back, PHP_URL_PATH), '/');
            $backUrl   = ($backPath === $indexPath) ? $back : route('eventos.show', $evento);
        }
    @endphp

    <x-slot name="header">
        <div class="flex items-center gap-4 min-w-0">
            <a href="{{ $backUrl }}"
               class="text-gray-400 hover:text-gray-600 transition flex-shrink-0"
               title="Volver">
                <x-heroicon-o-arrow-left class="h-5 w-5" />
            </a>
            <div class="min-w-0">
                <h2 class="text-lg font-semibold text-gray-900 truncate">Editar evento</h2>
                <p class="text-xs text-gray-400 truncate">{{ $evento->nombre }}</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl">
        <form
            x-data="eventoForm()"
            @submit.prevent="submitForm($el)"
            action="{{ route('eventos.update', $evento) }}"
            method="POST"
            novalidate
        >
            @csrf
            @method('PUT')

            <div class="space-y-10">

                {{-- Sección 1: Información --}}
                <div class="border-b border-gray-200 pb-10">
                    <h3 class="text-base font-semibold text-gray-900">Información del evento</h3>
                    <p class="mt-1 text-sm text-gray-500">Datos principales de identificación y clasificación del evento.</p>

                    <div class="mt-8 grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-6">

                        {{-- Nombre --}}
                        <div class="col-span-full">
                            <x-input-label for="nombre" value="Nombre *" />
                            <div class="mt-2">
                                <x-text-input
                                    id="nombre"
                                    name="nombre"
                                    type="text"
                                    class="block w-full"
                                    :value="old('nombre', $evento->nombre)"
                                    placeholder="Nombre del evento"
                                    maxlength="255"
                                    autofocus
                                    aria-describedby="nombre-error"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('nombre')" id="nombre-error" />
                            <template x-if="hasError('nombre')">
                                <div data-ajax-error class="mt-1">
                                    <template x-for="msg in getErrors('nombre')" :key="msg">
                                        <p class="text-sm text-red-600" x-text="msg"></p>
                                    </template>
                                </div>
                            </template>
                        </div>

                        {{-- Tipo de evento --}}
                        <div class="sm:col-span-3">
                            <x-input-label for="eventos_tipo_id" value="Tipo de evento *" />
                            <div class="mt-2">
                                <select
                                    id="eventos_tipo_id"
                                    name="eventos_tipo_id"
                                    aria-describedby="tipo-error"
                                    class="block w-full rounded-md border-gray-300 shadow-sm text-sm transition-colors duration-150 focus:outline-none focus:border-udg-gold focus:ring-2 focus:ring-udg-gold/30"
                                >
                                    <option value="">— Seleccionar tipo —</option>
                                    @foreach ($tipos as $tipo)
                                        <option value="{{ $tipo->id }}"
                                            {{ old('eventos_tipo_id', $evento->eventos_tipo_id) == $tipo->id ? 'selected' : '' }}>
                                            {{ $tipo->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <x-input-error :messages="$errors->get('eventos_tipo_id')" id="tipo-error" />
                            <template x-if="hasError('eventos_tipo_id')">
                                <div data-ajax-error class="mt-1">
                                    <template x-for="msg in getErrors('eventos_tipo_id')" :key="msg">
                                        <p class="text-sm text-red-600" x-text="msg"></p>
                                    </template>
                                </div>
                            </template>
                        </div>

                        {{-- Institución --}}
                        <div class="sm:col-span-3">
                            <x-input-label for="institucion_id" value="Institución *" />
                            <div class="mt-2">
                                <select
                                    id="institucion_id"
                                    name="institucion_id"
                                    aria-describedby="institucion-error"
                                    class="block w-full rounded-md border-gray-300 shadow-sm text-sm transition-colors duration-150 focus:outline-none focus:border-udg-gold focus:ring-2 focus:ring-udg-gold/30"
                                >
                                    <option value="">— Seleccionar institución —</option>
                                    @foreach ($instituciones as $inst)
                                        <option value="{{ $inst->id }}"
                                            {{ old('institucion_id', $evento->institucion_id) == $inst->id ? 'selected' : '' }}>
                                            {{ $inst->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <x-input-error :messages="$errors->get('institucion_id')" id="institucion-error" />
                            <template x-if="hasError('institucion_id')">
                                <div data-ajax-error class="mt-1">
                                    <template x-for="msg in getErrors('institucion_id')" :key="msg">
                                        <p class="text-sm text-red-600" x-text="msg"></p>
                                    </template>
                                </div>
                            </template>
                        </div>

                        {{-- Organizador --}}
                        <div
                            class="col-span-full"
                            x-data="{
                                open: false,
                                selectedId: '{{ old('organizador_id', $evento->organizador_id) }}',
                                items: {{ Js::from($organizadores->map(fn($o) => [
                                    'id' => $o->id,
                                    'nombre' => $o->nombre,
                                    'administracion' => $o->administracion?->nombre ?? '',
                                ])) }},
                                get selected() {
                                    if (!this.selectedId) return null;
                                    return this.items.find(i => String(i.id) === String(this.selectedId)) || null;
                                },
                                select(item) {
                                    this.selectedId = String(item.id);
                                    this.open = false;
                                },
                                clear() {
                                    this.selectedId = '';
                                    this.open = false;
                                }
                            }"
                            @organizador-created.window="
                                const org = $event.detail;
                                items.push({ id: org.id, nombre: org.nombre, administracion: org.administracion || '' });
                                selectedId = String(org.id);
                            "
                        >
                            <x-input-label for="organizador_id" value="Organizador *" />
                            <input type="hidden" name="organizador_id" :value="selectedId">
                            <div class="mt-2 flex gap-2">
                                <div class="relative w-full">
                                    {{-- Trigger --}}
                                    <button
                                        type="button"
                                        @click="open = !open"
                                        @keydown.escape.window="open = false"
                                        aria-haspopup="listbox"
                                        :aria-expanded="open"
                                        aria-describedby="organizador-error"
                                        class="relative block w-full rounded-md border border-gray-300 bg-white py-2 pl-3 pr-10 text-left shadow-sm text-sm transition-colors duration-150 focus:outline-none focus:border-udg-gold focus:ring-2 focus:ring-udg-gold/30"
                                    >
                                        <template x-if="selected">
                                            <span class="block">
                                                <span class="block font-medium text-gray-900 truncate" x-text="selected.nombre"></span>
                                                <span class="block text-xs text-gray-500 truncate" x-text="selected.administracion"></span>
                                            </span>
                                        </template>
                                        <template x-if="!selected">
                                            <span class="block text-gray-400">Seleccionar organizador...</span>
                                        </template>
                                        {{-- Chevron --}}
                                        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </button>

                                    {{-- Lista desplegable --}}
                                    <div
                                        x-show="open"
                                        x-cloak
                                        @click.outside="open = false"
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="opacity-0 -translate-y-1"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="opacity-100 translate-y-0"
                                        x-transition:leave-end="opacity-0 -translate-y-1"
                                        class="absolute z-20 mt-1 w-full rounded-md bg-white shadow-lg ring-1 ring-black/5"
                                    >
                                        <ul
                                            role="listbox"
                                            class="max-h-60 overflow-y-auto rounded-md py-1 text-sm focus:outline-none"
                                        >
                                            {{-- Opcion para limpiar seleccion --}}
                                            <li
                                                x-show="selectedId"
                                                @click="clear()"
                                                class="cursor-pointer select-none px-3 py-2 text-gray-400 hover:bg-gray-50"
                                            >
                                                Limpiar seleccion
                                            </li>
                                            <template x-for="item in items" :key="item.id">
                                                <li
                                                    @click="select(item)"
                                                    :class="{
                                                        'bg-primary/5': String(selectedId) === String(item.id)
                                                    }"
                                                    class="cursor-pointer select-none px-3 py-2 hover:bg-gray-50"
                                                    role="option"
                                                    :aria-selected="String(selectedId) === String(item.id)"
                                                >
                                                    <span class="block font-medium text-gray-900" x-text="item.nombre"></span>
                                                    <span class="block text-xs text-gray-500" x-text="item.administracion"></span>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>
                                @can('create', App\Models\Organizador::class)
                                    <button
                                        type="button"
                                        @click="$dispatch('open-modal', 'crear-organizador')"
                                        class="inline-flex items-center gap-1.5 rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-600 shadow-sm hover:bg-gray-50 hover:text-primary transition-colors focus:outline-none focus:ring-2 focus:ring-udg-gold/30 whitespace-nowrap"
                                        title="Crear nuevo organizador"
                                    >
                                        <x-heroicon-o-plus class="h-4 w-4" />
                                        <span class="hidden sm:inline">Nuevo</span>
                                    </button>
                                @endcan
                            </div>
                            <x-input-error :messages="$errors->get('organizador_id')" id="organizador-error" />
                            <template x-if="hasError('organizador_id')">
                                <div data-ajax-error class="mt-1">
                                    <template x-for="msg in getErrors('organizador_id')" :key="msg">
                                        <p class="text-sm text-red-600" x-text="msg"></p>
                                    </template>
                                </div>
                            </template>
                        </div>

                        {{-- Ubicación --}}
                        <div class="col-span-full">
                            <x-input-label for="ubicacion_id" value="Ubicación" />
                            <div class="mt-2">
                                <select
                                    id="ubicacion_id"
                                    name="ubicacion_id"
                                    aria-describedby="ubicacion-error"
                                    class="block w-full rounded-md border-gray-300 shadow-sm text-sm transition-colors duration-150 focus:outline-none focus:border-udg-gold focus:ring-2 focus:ring-udg-gold/30"
                                >
                                    <option value="">— Sin ubicación —</option>
                                    @foreach ($ubicaciones as $ub)
                                        <option value="{{ $ub->id }}"
                                            {{ old('ubicacion_id', $evento->ubicacion_id) == $ub->id ? 'selected' : '' }}>
                                            {{ $ub->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <x-input-error :messages="$errors->get('ubicacion_id')" id="ubicacion-error" />
                            <template x-if="hasError('ubicacion_id')">
                                <div data-ajax-error class="mt-1">
                                    <template x-for="msg in getErrors('ubicacion_id')" :key="msg">
                                        <p class="text-sm text-red-600" x-text="msg"></p>
                                    </template>
                                </div>
                            </template>
                        </div>

                    </div>
                </div>

                {{-- Sección 2: Opciones --}}
                <div class="border-b border-gray-200 pb-10">
                    <h3 class="text-base font-semibold text-gray-900">Opciones</h3>
                    <p class="mt-1 text-sm text-gray-500">Estado del evento y notas adicionales.</p>

                    <div class="mt-8 grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-6">

                        {{-- Activo --}}
                        <div class="col-span-full" x-data="{ activo: {{ old('activo', $evento->activo ? '1' : '0') == '1' ? 'true' : 'false' }} }">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <x-input-label value="Estado" />
                                    <p class="mt-1 text-xs text-gray-500">Define si el evento es visible en el calendario.</p>
                                </div>
                                <div class="flex items-center gap-3 pt-0.5">
                                    <input type="hidden" name="activo" :value="activo ? '1' : '0'">
                                    <button
                                        type="button"
                                        @click="activo = !activo"
                                        :class="activo ? 'bg-primary' : 'bg-gray-200'"
                                        class="relative inline-flex flex-shrink-0 items-center rounded-full transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary/50 focus:ring-offset-2"
                                        style="width: 2.75rem; height: 1.5rem;"
                                        :aria-checked="activo.toString()"
                                        aria-label="Estado del evento"
                                        role="switch"
                                    >
                                        <span
                                            :style="{
                                                width: '1.125rem',
                                                height: '1.125rem',
                                                transform: activo ? 'translateX(1.375rem)' : 'translateX(0.1875rem)'
                                            }"
                                            class="inline-block rounded-full bg-white shadow transition-transform duration-200 ease-in-out"
                                        ></span>
                                    </button>
                                </div>
                            </div>
                            <div class="mt-2">
                                <span
                                    x-show="activo"
                                    class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20"
                                    x-cloak
                                >
                                    <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                    Activo — Visible en el calendario
                                </span>
                                <span
                                    x-show="!activo"
                                    class="inline-flex items-center gap-1.5 rounded-full bg-gray-50 px-2.5 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10"
                                    x-cloak
                                >
                                    <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                    Inactivo — No visible
                                </span>
                            </div>
                        </div>

                        {{-- Notas convocatoria --}}
                        <div class="sm:col-span-full">
                            <x-input-label for="notas_cta" value="Notas convocatoria" />
                            <div class="mt-2">
                                <textarea
                                    id="notas_cta"
                                    name="notas_cta"
                                    rows="4"
                                    aria-describedby="notas-cta-error"
                                    placeholder="Servicios requeridos al CTA: equipo audiovisual, transmisión en vivo, grabación, videoconferencia, etc."
                                    class="block w-full rounded-md border-gray-300 shadow-sm text-sm transition-colors duration-150 focus:outline-none focus:border-udg-gold focus:ring-2 focus:ring-udg-gold/30"
                                >{{ old('notas_cta', $evento->notas_cta) }}</textarea>
                            </div>
                            <x-input-error :messages="$errors->get('notas_cta')" id="notas-cta-error" />
                            <template x-if="hasError('notas_cta')">
                                <div data-ajax-error class="mt-1">
                                    <template x-for="msg in getErrors('notas_cta')" :key="msg">
                                        <p class="text-sm text-red-600" x-text="msg"></p>
                                    </template>
                                </div>
                            </template>
                        </div>

                        {{-- Notas servicios --}}
                        <div class="sm:col-span-full">
                            <x-input-label for="notas_servicios" value="Notas servicios" />
                            <div class="mt-2">
                                <textarea
                                    id="notas_servicios"
                                    name="notas_servicios"
                                    rows="4"
                                    aria-describedby="notas-servicios-error"
                                    placeholder="Requerimientos de servicios (audio, sillas, etc.)..."
                                    class="block w-full rounded-md border-gray-300 shadow-sm text-sm transition-colors duration-150 focus:outline-none focus:border-udg-gold focus:ring-2 focus:ring-udg-gold/30"
                                >{{ old('notas_servicios', $evento->notas_servicios) }}</textarea>
                            </div>
                            <x-input-error :messages="$errors->get('notas_servicios')" id="notas-servicios-error" />
                            <template x-if="hasError('notas_servicios')">
                                <div data-ajax-error class="mt-1">
                                    <template x-for="msg in getErrors('notas_servicios')" :key="msg">
                                        <p class="text-sm text-red-600" x-text="msg"></p>
                                    </template>
                                </div>
                            </template>
                        </div>

                        {{-- Color del evento --}}
                        <div class="col-span-full" x-data="{ color: '{{ old('color', $evento->color) }}' }">
                            <div class="flex items-center gap-3">
                                <x-input-label value="Color del evento" class="!mb-0" />
                                <span class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1 text-xs font-semibold text-white shadow-sm"
                                      :style="'background-color: ' + color">
                                    <span x-text="{
                                        '#7FBCD2': 'Global',
                                        '#FF6868': 'Importante',
                                        '#FFBB64': 'Administrativo',
                                        '#B1C29E': 'Externo'
                                    }[color] || ''"></span>
                                </span>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Define el color de la pill del evento en el calendario. Cada color representa una categoria visual.</p>
                            <input type="hidden" name="color" :value="color">
                            <div class="mt-3 flex flex-wrap gap-3">
                                <button type="button" @click="color = '#7FBCD2'"
                                        class="relative w-10 h-10 rounded-full border-2 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-udg-gold/30"
                                        :class="color === '#7FBCD2' ? 'border-gray-900 ring-2 ring-gray-900/20' : 'border-transparent hover:border-gray-300'"
                                        style="background-color: #7FBCD2"
                                        title="Global">
                                    <span x-show="color === '#7FBCD2'" class="absolute inset-0 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white drop-shadow" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                    </span>
                                </button>
                                <button type="button" @click="color = '#FF6868'"
                                        class="relative w-10 h-10 rounded-full border-2 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-udg-gold/30"
                                        :class="color === '#FF6868' ? 'border-gray-900 ring-2 ring-gray-900/20' : 'border-transparent hover:border-gray-300'"
                                        style="background-color: #FF6868"
                                        title="Importante">
                                    <span x-show="color === '#FF6868'" class="absolute inset-0 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white drop-shadow" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                    </span>
                                </button>
                                <button type="button" @click="color = '#FFBB64'"
                                        class="relative w-10 h-10 rounded-full border-2 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-udg-gold/30"
                                        :class="color === '#FFBB64' ? 'border-gray-900 ring-2 ring-gray-900/20' : 'border-transparent hover:border-gray-300'"
                                        style="background-color: #FFBB64"
                                        title="Administrativo">
                                    <span x-show="color === '#FFBB64'" class="absolute inset-0 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white drop-shadow" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                    </span>
                                </button>
                                <button type="button" @click="color = '#B1C29E'"
                                        class="relative w-10 h-10 rounded-full border-2 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-udg-gold/30"
                                        :class="color === '#B1C29E' ? 'border-gray-900 ring-2 ring-gray-900/20' : 'border-transparent hover:border-gray-300'"
                                        style="background-color: #B1C29E"
                                        title="Externo">
                                    <span x-show="color === '#B1C29E'" class="absolute inset-0 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white drop-shadow" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                    </span>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('color')" id="color-error" />
                            <template x-if="hasError('color')">
                                <div data-ajax-error class="mt-1">
                                    <template x-for="msg in getErrors('color')" :key="msg">
                                        <p class="text-sm text-red-600" x-text="msg"></p>
                                    </template>
                                </div>
                            </template>
                        </div>

                    </div>
                </div>

                {{-- Sección 3: Fechas --}}
                @php
                    $fechasIniciales = old('fechas', $evento->fechas->map(fn($f) => [
                        'fecha'       => $f->fecha->format('Y-m-d'),
                        'hora_inicio' => $f->hora_inicio->format('H:i'),
                        'hora_fin'    => $f->hora_fin->format('H:i'),
                    ])->toArray());
                @endphp

                <div
                    x-data="{ fechas: {{ Js::from($fechasIniciales) }} }"
                    class="border-b border-gray-200 pb-10"
                >
                    <h3 class="text-base font-semibold text-gray-900">Fechas y horarios</h3>
                    <p class="mt-1 text-sm text-gray-500">Modifica las fechas programadas para este evento.</p>

                    <div class="mt-8 space-y-3">

                        <template x-for="(item, i) in fechas" :key="i">
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-8 items-end">

                                {{-- Fecha --}}
                                <div class="sm:col-span-3">
                                    <label :for="'fecha_' + i" class="block text-sm font-medium text-gray-700 mb-1">
                                        Fecha <span x-show="i === 0">*</span>
                                    </label>
                                    <input
                                        type="date"
                                        :id="'fecha_' + i"
                                        :name="'fechas[' + i + '][fecha]'"
                                        x-model="item.fecha"
                                        class="block w-full rounded-md border-gray-300 shadow-sm text-sm transition-colors duration-150 focus:outline-none focus:border-udg-gold focus:ring-2 focus:ring-udg-gold/30"
                                    >
                                </div>

                                {{-- Hora inicio --}}
                                <div class="sm:col-span-2">
                                    <label :for="'hora_inicio_' + i" class="block text-sm font-medium text-gray-700 mb-1">
                                        Inicio <span x-show="i === 0">*</span>
                                    </label>
                                    <select
                                        :id="'hora_inicio_' + i"
                                        :name="'fechas[' + i + '][hora_inicio]'"
                                        x-model="item.hora_inicio"
                                        class="block w-full rounded-md border-gray-300 shadow-sm text-sm transition-colors duration-150 focus:outline-none focus:border-udg-gold focus:ring-2 focus:ring-udg-gold/30"
                                    >
                                        <option value="">--:--</option>
                                        @for ($h = 7; $h <= 22; $h++)
                                            <option value="{{ sprintf('%02d:00', $h) }}">{{ sprintf('%02d:00', $h) }}</option>
                                            @if ($h < 22)
                                                <option value="{{ sprintf('%02d:30', $h) }}">{{ sprintf('%02d:30', $h) }}</option>
                                            @endif
                                        @endfor
                                    </select>
                                </div>

                                {{-- Hora fin --}}
                                <div class="sm:col-span-2">
                                    <label :for="'hora_fin_' + i" class="block text-sm font-medium text-gray-700 mb-1">
                                        Fin <span x-show="i === 0">*</span>
                                    </label>
                                    <select
                                        :id="'hora_fin_' + i"
                                        :name="'fechas[' + i + '][hora_fin]'"
                                        x-model="item.hora_fin"
                                        class="block w-full rounded-md border-gray-300 shadow-sm text-sm transition-colors duration-150 focus:outline-none focus:border-udg-gold focus:ring-2 focus:ring-udg-gold/30"
                                    >
                                        <option value="">--:--</option>
                                        @for ($h = 7; $h <= 22; $h++)
                                            <option value="{{ sprintf('%02d:00', $h) }}">{{ sprintf('%02d:00', $h) }}</option>
                                            @if ($h < 22)
                                                <option value="{{ sprintf('%02d:30', $h) }}">{{ sprintf('%02d:30', $h) }}</option>
                                            @endif
                                        @endfor
                                    </select>
                                </div>

                                {{-- Botón eliminar fila --}}
                                <div class="sm:col-span-1 flex items-end justify-center pb-0.5">
                                    <button
                                        type="button"
                                        @click="if (fechas.length > 1) fechas.splice(i, 1)"
                                        :disabled="fechas.length <= 1"
                                        class="inline-flex items-center gap-1 text-sm text-gray-400 hover:text-danger transition disabled:opacity-30 disabled:cursor-not-allowed"
                                        title="Eliminar fecha"
                                    >
                                        <x-heroicon-o-trash class="h-4 w-4" />
                                        <span class="sm:hidden ml-1">Eliminar</span>
                                    </button>
                                </div>

                            </div>
                        </template>

                        {{-- Errores de fechas (server-side en primera carga + AJAX dinámicos) --}}
                        <div x-show="fechaErrors.length > 0" x-cloak id="fecha-error-box"
                             class="rounded-md bg-red-50 border border-red-200 p-3">
                            <div class="flex gap-2">
                                <svg class="h-5 w-5 text-red-500 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>
                                <div class="text-sm text-red-700 space-y-1">
                                    <template x-for="msg in fechaErrors" :key="msg">
                                        <p x-text="msg"></p>
                                    </template>
                                </div>
                            </div>
                        </div>
                        @if ($errors->hasAny(['fechas', 'fechas.*', 'fechas.*.fecha', 'fechas.*.hora_inicio', 'fechas.*.hora_fin']))
                            {{-- Pre-llenar errores server-side en la variable Alpine --}}
                            <script>
                                document.addEventListener('alpine:init', () => {
                                    window.__serverFechaErrors = @json(
                                        collect($errors->get('fechas'))
                                            ->merge(collect($errors->get('fechas.*'))->flatten())
                                            ->values()
                                    );
                                });
                            </script>
                        @endif

                        {{-- Botones agregar y limpiar fechas --}}
                        <div class="flex items-center gap-4">
                            <button
                                type="button"
                                @click="fechas.push({ fecha: '', hora_inicio: '', hora_fin: '' })"
                                class="mt-2 inline-flex items-center gap-1.5 text-sm font-medium text-primary hover:text-primary-hover transition"
                            >
                                <x-heroicon-o-plus class="h-4 w-4" />
                                Agregar fecha
                            </button>
                            <button
                                type="button"
                                x-show="fechas.length > 1"
                                x-cloak
                                @click="fechas = [{ fecha: '', hora_inicio: '', hora_fin: '' }]"
                                class="mt-2 inline-flex items-center gap-1.5 text-sm font-medium text-danger hover:text-red-700 transition"
                            >
                                <x-heroicon-o-trash class="h-4 w-4" />
                                Limpiar fechas
                            </button>
                        </div>

                    </div>
                </div>

            </div>

            {{-- Botones --}}
            <div class="mt-8 flex items-center justify-end gap-3">
                <a href="{{ $backUrl }}">
                    <x-secondary-button type="button">Cancelar</x-secondary-button>
                </a>

                <button
                    type="submit"
                    :disabled="loading"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-udg-blue focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-60 disabled:cursor-not-allowed"
                >
                    <span x-show="!loading" class="inline-flex items-center gap-1.5">
                        <x-heroicon-o-check class="h-4 w-4" />
                        Guardar cambios
                    </span>
                    <span x-show="loading" x-cloak class="inline-flex items-center gap-1.5">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Guardando...
                    </span>
                </button>
            </div>

        </form>
    </div>

    @can('create', App\Models\Organizador::class)
        <x-organizador-create-modal :administraciones="$administraciones" />
    @endcan

    @push('head-scripts')
        <script>
            /**
             * Formulario de evento con envío AJAX.
             * Maneja errores 422 inline sin recargar la página.
             */
            window.eventoForm = function () {
                return {
                    loading: false,
                    errors: {},
                    fechaErrors: window.__serverFechaErrors || [],

                    getErrors(field) {
                        return this.errors[field] || [];
                    },

                    hasError(field) {
                        return (this.errors[field] && this.errors[field].length > 0);
                    },

                    clearErrors() {
                        this.errors = {};
                        this.fechaErrors = [];
                    },

                    async submitForm(formEl) {
                        if (this.loading) return;
                        this.loading = true;
                        this.clearErrors();

                        const formData = new FormData(formEl);

                        try {
                            const response = await fetch(formEl.action, {
                                method: 'POST',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                },
                                body: formData,
                            });

                            if (response.ok) {
                                const data = await response.json();
                                window.location.href = data.redirect;
                                return;
                            }

                            if (response.status === 422) {
                                const data = await response.json();
                                this.errors = data.errors || {};
                                this.extractFechaErrors();
                                this.loading = false;

                                this.$nextTick(() => {
                                    this.scrollToFirstError(formEl);
                                });
                                return;
                            }

                            this.loading = false;
                            window.location.reload();
                        } catch (e) {
                            this.loading = false;
                            window.location.reload();
                        }
                    },

                    extractFechaErrors() {
                        const msgs = [];
                        for (const [key, values] of Object.entries(this.errors)) {
                            if (key === 'fechas' || key.startsWith('fechas.')) {
                                values.forEach(function (m) { msgs.push(m); });
                            }
                        }
                        this.fechaErrors = msgs;
                    },

                    scrollToFirstError(formEl) {
                        const errorEl = formEl.querySelector('[data-ajax-error]:not([style*="display: none"])');
                        if (errorEl) {
                            errorEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            return;
                        }
                        var fechaBox = document.getElementById('fecha-error-box');
                        if (this.fechaErrors.length > 0 && fechaBox) {
                            fechaBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    }
                };
            };
        </script>
    @endpush

</x-app-layout>
