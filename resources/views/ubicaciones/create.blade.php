<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('ubicaciones.index') }}" class="hover:text-primary transition-colors">Ubicaciones</a>
            <x-heroicon-m-chevron-right class="h-4 w-4 flex-shrink-0" />
            <span class="font-medium text-gray-900">Nueva ubicación</span>
        </div>
    </x-slot>

    <div class="max-w-xl">
        <form
            x-data="{ loading: false }"
            @submit="loading = true"
            action="{{ route('ubicaciones.store') }}"
            method="POST"
            novalidate
        >
            @csrf

            <div class="space-y-10">

                {{-- Sección: Datos de la ubicación --}}
                <div class="border-b border-gray-200 pb-10">
                    <h3 class="text-base font-semibold text-gray-900">Datos de la ubicación</h3>
                    <p class="mt-1 text-sm text-gray-500">Nombre del espacio y la institución a la que pertenece.</p>

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
                                    :value="old('nombre')"
                                    placeholder="Auditorio Carlos Ramírez Ladewig"
                                    maxlength="255"
                                    autofocus
                                    aria-describedby="nombre-error"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('nombre')" id="nombre-error" />
                        </div>

                        {{-- Institución --}}
                        <div class="sm:col-span-4">
                            <x-input-label for="institucion_id" value="Sede" />
                            <div class="mt-2">
                                <select
                                    id="institucion_id"
                                    name="institucion_id"
                                    aria-describedby="institucion-error"
                                    class="block w-full rounded-md border-gray-300 shadow-sm text-sm transition-colors duration-150 focus:outline-none focus:border-udg-gold focus:ring-2 focus:ring-udg-gold/30"
                                >
                                    <option value="">— Sin asignar —</option>
                                    @foreach ($instituciones as $inst)
                                        <option value="{{ $inst->id }}"
                                            {{ old('institucion_id') == $inst->id ? 'selected' : '' }}>
                                            {{ $inst->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="mt-1 text-xs text-gray-400">Opcional. Asocia esta ubicación a una institución específica.</p>
                            <x-input-error :messages="$errors->get('institucion_id')" id="institucion-error" />
                        </div>

                    </div>
                </div>

                {{-- Sección: Estado --}}
                <div class="border-b border-gray-200 pb-10">
                    <h3 class="text-base font-semibold text-gray-900">Estado</h3>
                    <p class="mt-1 text-sm text-gray-500">Disponibilidad de la ubicación en el sistema.</p>

                    <div class="mt-8">

                        <div class="col-span-full" x-data="{ activo: {{ old('activo', '1') == '1' ? 'true' : 'false' }} }">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <x-input-label value="Disponible" />
                                    <p class="mt-1 text-xs text-gray-500">Define si la ubicación aparece como opción al registrar eventos.</p>
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
                                        aria-label="Estado de la ubicación"
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
                                    Activa — Disponible para eventos
                                </span>
                                <span
                                    x-show="!activo"
                                    class="inline-flex items-center gap-1.5 rounded-full bg-gray-50 px-2.5 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10"
                                    x-cloak
                                >
                                    <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                    Inactiva — No disponible
                                </span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            {{-- Botones --}}
            <div class="mt-8 flex items-center justify-end gap-3">
                <a href="{{ route('ubicaciones.index') }}">
                    <x-secondary-button type="button">Cancelar</x-secondary-button>
                </a>

                <button
                    type="submit"
                    :disabled="loading"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-udg-blue focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-60 disabled:cursor-not-allowed"
                >
                    <span x-show="!loading" class="inline-flex items-center gap-1.5">
                        <x-heroicon-o-map-pin class="h-4 w-4" />
                        Crear ubicación
                    </span>
                    <span x-show="loading" x-cloak class="inline-flex items-center gap-1.5">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Creando...
                    </span>
                </button>
            </div>

        </form>
    </div>

</x-app-layout>
