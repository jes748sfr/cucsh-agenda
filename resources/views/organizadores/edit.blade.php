<x-app-layout>
    @php
        $back        = url()->previous();
        $indexPath   = rtrim(parse_url(route('organizadores.index'), PHP_URL_PATH), '/');
        $backPath    = rtrim(parse_url($back, PHP_URL_PATH), '/');
        $backUrl     = ($backPath === $indexPath) ? $back : route('organizadores.show', $organizador);
    @endphp

    <x-slot name="header">
        <div class="flex items-center gap-4 min-w-0">
            <a href="{{ $backUrl }}"
               class="text-gray-400 hover:text-gray-600 transition flex-shrink-0"
               title="Volver al detalle">
                <x-heroicon-o-arrow-left class="h-5 w-5" />
            </a>
            <div class="min-w-0">
                <h2 class="text-lg font-semibold text-gray-900 truncate">Editar organizador</h2>
                <p class="text-xs text-gray-400 truncate">{{ $organizador->nombre }}</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-xl">
        <form
            x-data="{ loading: false }"
            @submit="loading = true"
            action="{{ route('organizadores.update', $organizador) }}"
            method="POST"
            novalidate
        >
            @csrf
            @method('PUT')

            <div class="space-y-10">

                {{-- Sección: Información de contacto --}}
                <div class="border-b border-gray-200 pb-10">
                    <h3 class="text-base font-semibold text-gray-900">Información de contacto</h3>
                    <p class="mt-1 text-sm text-gray-500">Datos básicos de identificación y comunicación del organizador.</p>

                    <div class="mt-8 grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-6">

                        {{-- Nombre --}}
                        <div class="sm:col-span-3">
                            <x-input-label for="nombre" value="Nombre *" />
                            <div class="mt-2">
                                <x-text-input
                                    id="nombre"
                                    name="nombre"
                                    type="text"
                                    class="block w-full"
                                    :value="old('nombre', $organizador->nombre)"
                                    placeholder="Nombre completo"
                                    maxlength="255"
                                    autofocus
                                    aria-describedby="nombre-error"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('nombre')" id="nombre-error" />
                        </div>

                        {{-- Teléfono --}}
                        <div class="sm:col-span-3">
                            <x-input-label for="tel" value="Teléfono" />
                            <div class="mt-2">
                                <x-text-input
                                    id="tel"
                                    name="tel"
                                    type="tel"
                                    class="block w-full"
                                    :value="old('tel', $organizador->tel)"
                                    placeholder="+52 33 1234 5678"
                                    maxlength="20"
                                    aria-describedby="tel-error"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('tel')" id="tel-error" />
                        </div>

                        {{-- Correo electrónico --}}
                        <div class="sm:col-span-4">
                            <x-input-label for="email" value="Correo electrónico *" />
                            <div class="mt-2">
                                <x-text-input
                                    id="email"
                                    name="email"
                                    type="email"
                                    class="block w-full"
                                    :value="old('email', $organizador->email)"
                                    placeholder="organizador@cucsh.udg.mx"
                                    maxlength="255"
                                    aria-describedby="email-error"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('email')" id="email-error" />
                        </div>

                    </div>
                </div>

                {{-- Sección: Configuración --}}
                <div class="border-b border-gray-200 pb-10">
                    <h3 class="text-base font-semibold text-gray-900">Configuración</h3>
                    <p class="mt-1 text-sm text-gray-500">Área de pertenencia y disponibilidad del organizador en el sistema.</p>

                    <div class="mt-8 grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-6">

                        {{-- Administración --}}
                        <div class="sm:col-span-4">
                            <x-input-label for="administracion_id" value="Administración *" />
                            <div class="mt-2">
                                <select
                                    id="administracion_id"
                                    name="administracion_id"
                                    aria-describedby="administracion-error"
                                    class="block w-full rounded-md border-gray-300 shadow-sm text-sm transition-colors duration-150 focus:outline-none focus:border-udg-gold focus:ring-2 focus:ring-udg-gold/30"
                                >
                                    <option value="">— Seleccionar administración —</option>
                                    @foreach ($administraciones as $adm)
                                        <option value="{{ $adm->id }}"
                                            {{ old('administracion_id', $organizador->administracion_id) == $adm->id ? 'selected' : '' }}>
                                            {{ $adm->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <x-input-error :messages="$errors->get('administracion_id')" id="administracion-error" />
                        </div>

                        {{-- Estado --}}
                        <div class="col-span-full" x-data="{ activo: {{ old('activo', $organizador->activo ? '1' : '0') == '1' ? 'true' : 'false' }} }">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <x-input-label value="Estado" />
                                    <p class="mt-1 text-xs text-gray-500">Define si el organizador puede ser asignado a nuevos eventos.</p>
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
                                        aria-label="Estado del organizador"
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
                                    Activo — Disponible para eventos
                                </span>
                                <span
                                    x-show="!activo"
                                    class="inline-flex items-center gap-1.5 rounded-full bg-gray-50 px-2.5 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10"
                                    x-cloak
                                >
                                    <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                    Inactivo — No disponible
                                </span>
                            </div>
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
                    {{-- Estado normal --}}
                    <span x-show="!loading" class="inline-flex items-center gap-1.5">
                        <x-heroicon-o-check class="h-4 w-4" />
                        Guardar cambios
                    </span>

                    {{-- Estado cargando --}}
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
