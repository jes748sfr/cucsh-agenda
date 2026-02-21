<x-app-layout>
    @php
        $back      = url()->previous();
        $indexPath = rtrim(parse_url(route('eventos.index'), PHP_URL_PATH), '/');
        $backPath  = rtrim(parse_url($back, PHP_URL_PATH), '/');
        $backUrl   = ($backPath === $indexPath) ? $back : route('eventos.show', $evento);
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
            x-data="{ loading: false }"
            @submit="loading = true"
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
                        <div class="sm:col-span-4">
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
                        </div>

                        {{-- Organizador --}}
                        <div class="sm:col-span-4">
                            <x-input-label for="organizador_id" value="Organizador *" />
                            <div class="mt-2">
                                <select
                                    id="organizador_id"
                                    name="organizador_id"
                                    aria-describedby="organizador-error"
                                    class="block w-full rounded-md border-gray-300 shadow-sm text-sm transition-colors duration-150 focus:outline-none focus:border-udg-gold focus:ring-2 focus:ring-udg-gold/30"
                                >
                                    <option value="">— Seleccionar organizador —</option>
                                    @foreach ($organizadores as $org)
                                        <option value="{{ $org->id }}"
                                            {{ old('organizador_id', $evento->organizador_id) == $org->id ? 'selected' : '' }}>
                                            {{ $org->nombre }}
                                            @if ($org->administracion)
                                                — {{ $org->administracion->nombre }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <x-input-error :messages="$errors->get('organizador_id')" id="organizador-error" />
                        </div>

                        {{-- Ubicación --}}
                        <div class="sm:col-span-4">
                            <x-input-label for="ubicacion" value="Ubicación" />
                            <div class="mt-2">
                                <x-text-input
                                    id="ubicacion"
                                    name="ubicacion"
                                    type="text"
                                    class="block w-full"
                                    :value="old('ubicacion', $evento->ubicacion)"
                                    placeholder="Auditorio, salón, edificio..."
                                    maxlength="255"
                                    aria-describedby="ubicacion-error"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('ubicacion')" id="ubicacion-error" />
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
                                    placeholder="Información relevante para la convocatoria..."
                                    class="block w-full rounded-md border-gray-300 shadow-sm text-sm transition-colors duration-150 focus:outline-none focus:border-udg-gold focus:ring-2 focus:ring-udg-gold/30"
                                >{{ old('notas_cta', $evento->notas_cta) }}</textarea>
                            </div>
                            <x-input-error :messages="$errors->get('notas_cta')" id="notas-cta-error" />
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
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-7 items-end">

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
                                    <input
                                        type="time"
                                        :id="'hora_inicio_' + i"
                                        :name="'fechas[' + i + '][hora_inicio]'"
                                        x-model="item.hora_inicio"
                                        min="07:00"
                                        max="22:00"
                                        class="block w-full rounded-md border-gray-300 shadow-sm text-sm transition-colors duration-150 focus:outline-none focus:border-udg-gold focus:ring-2 focus:ring-udg-gold/30"
                                    >
                                </div>

                                {{-- Hora fin --}}
                                <div class="sm:col-span-2">
                                    <label :for="'hora_fin_' + i" class="block text-sm font-medium text-gray-700 mb-1">
                                        Fin <span x-show="i === 0">*</span>
                                    </label>
                                    <input
                                        type="time"
                                        :id="'hora_fin_' + i"
                                        :name="'fechas[' + i + '][hora_fin]'"
                                        x-model="item.hora_fin"
                                        min="07:00"
                                        max="22:00"
                                        class="block w-full rounded-md border-gray-300 shadow-sm text-sm transition-colors duration-150 focus:outline-none focus:border-udg-gold focus:ring-2 focus:ring-udg-gold/30"
                                    >
                                </div>

                                {{-- Botón eliminar fila --}}
                                <div class="sm:col-span-full sm:flex sm:justify-end">
                                    <button
                                        type="button"
                                        @click="if (fechas.length > 1) fechas.splice(i, 1)"
                                        :disabled="fechas.length <= 1"
                                        class="inline-flex items-center gap-1 text-sm text-gray-400 hover:text-danger transition disabled:opacity-30 disabled:cursor-not-allowed"
                                        title="Eliminar fecha"
                                    >
                                        <x-heroicon-o-trash class="h-4 w-4" />
                                        <span class="sm:hidden">Eliminar fecha</span>
                                    </button>
                                </div>

                            </div>
                        </template>

                        {{-- Errores de fechas --}}
                        @if ($errors->has('fechas') || $errors->has('fechas.*') || $errors->has('fechas.*.fecha') || $errors->has('fechas.*.hora_inicio') || $errors->has('fechas.*.hora_fin'))
                            <div class="rounded-md bg-red-50 border border-red-200 p-3">
                                <div class="flex gap-2">
                                    <x-heroicon-o-exclamation-triangle class="h-5 w-5 text-red-500 flex-shrink-0 mt-0.5" />
                                    <div class="text-sm text-red-700 space-y-1">
                                        @foreach ($errors->get('fechas') as $msg)
                                            <p>{{ $msg }}</p>
                                        @endforeach
                                        @foreach ($errors->get('fechas.*') as $msgs)
                                            @foreach ($msgs as $msg)
                                                <p>{{ $msg }}</p>
                                            @endforeach
                                        @endforeach
                                        @foreach ($errors->get('fechas.*.fecha') as $msgs)
                                            @foreach ($msgs as $msg)
                                                <p>{{ $msg }}</p>
                                            @endforeach
                                        @endforeach
                                        @foreach ($errors->get('fechas.*.hora_inicio') as $msgs)
                                            @foreach ($msgs as $msg)
                                                <p>{{ $msg }}</p>
                                            @endforeach
                                        @endforeach
                                        @foreach ($errors->get('fechas.*.hora_fin') as $msgs)
                                            @foreach ($msgs as $msg)
                                                <p>{{ $msg }}</p>
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Botón agregar fecha --}}
                        <button
                            type="button"
                            @click="fechas.push({ fecha: '', hora_inicio: '', hora_fin: '' })"
                            class="mt-2 inline-flex items-center gap-1.5 text-sm font-medium text-primary hover:text-primary-hover transition"
                        >
                            <x-heroicon-o-plus class="h-4 w-4" />
                            Agregar fecha
                        </button>

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

</x-app-layout>
