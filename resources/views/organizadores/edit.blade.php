<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3 min-w-0">
            <a href="{{ route('organizadores.show', $organizador) }}"
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

    <div class="max-w-3xl">
        <form action="{{ route('organizadores.update', $organizador) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 divide-y divide-gray-200">

                {{-- Nombre --}}
                <div class="px-6 py-6 sm:grid sm:grid-cols-4 sm:gap-x-4 sm:items-start">
                    <div class="pt-0.5">
                        <x-input-label for="nombre" value="Nombre" class="!text-sm !font-semibold !text-gray-800" />
                        <p class="mt-1.5 text-xs leading-relaxed text-gray-500">Nombre completo del organizador tal como aparecerá en los eventos.</p>
                    </div>
                    <div class="hidden sm:block"></div>
                    <div class="mt-5 sm:col-span-2 sm:mt-0">
                        <x-text-input
                            id="nombre"
                            name="nombre"
                            type="text"
                            class="block w-full"
                            :value="old('nombre', $organizador->nombre)"
                            maxlength="255"
                            required
                            autofocus
                        />
                        <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                    </div>
                </div>

                {{-- Teléfono --}}
                <div class="px-6 py-6 sm:grid sm:grid-cols-4 sm:gap-x-4 sm:items-start">
                    <div class="pt-0.5">
                        <x-input-label for="tel" value="Teléfono" class="!text-sm !font-semibold !text-gray-800" />
                        <p class="mt-1.5 text-xs leading-relaxed text-gray-500">Número de contacto directo. Campo opcional.</p>
                    </div>
                    <div class="hidden sm:block"></div>
                    <div class="mt-5 sm:col-span-2 sm:mt-0">
                        <x-text-input
                            id="tel"
                            name="tel"
                            type="tel"
                            class="block w-full"
                            :value="old('tel', $organizador->tel)"
                            placeholder="+52 33 1234 5678"
                            maxlength="20"
                        />
                        <x-input-error :messages="$errors->get('tel')" class="mt-2" />
                    </div>
                </div>

                {{-- Correo electrónico --}}
                <div class="px-6 py-6 sm:grid sm:grid-cols-4 sm:gap-x-4 sm:items-start">
                    <div class="pt-0.5">
                        <x-input-label for="email" value="Correo electrónico" class="!text-sm !font-semibold !text-gray-800" />
                        <p class="mt-1.5 text-xs leading-relaxed text-gray-500">Dirección de correo institucional. Debe ser única en el sistema.</p>
                    </div>
                    <div class="hidden sm:block"></div>
                    <div class="mt-5 sm:col-span-2 sm:mt-0">
                        <x-text-input
                            id="email"
                            name="email"
                            type="email"
                            class="block w-full"
                            :value="old('email', $organizador->email)"
                            placeholder="organizador@cucsh.udg.mx"
                            maxlength="255"
                            required
                        />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                </div>

                {{-- Administración --}}
                <div class="px-6 py-6 sm:grid sm:grid-cols-4 sm:gap-x-4 sm:items-start">
                    <div class="pt-0.5">
                        <x-input-label for="administracion_id" value="Administración" class="!text-sm !font-semibold !text-gray-800" />
                        <p class="mt-1.5 text-xs leading-relaxed text-gray-500">Área administrativa a la que pertenece este organizador.</p>
                    </div>
                    <div class="hidden sm:block"></div>
                    <div class="mt-5 sm:col-span-2 sm:mt-0">
                        <select id="administracion_id"
                                name="administracion_id"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary text-sm">
                            <option value="">— Seleccionar administración —</option>
                            @foreach ($administraciones as $adm)
                                <option value="{{ $adm->id }}"
                                    {{ old('administracion_id', $organizador->administracion_id) == $adm->id ? 'selected' : '' }}>
                                    {{ $adm->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('administracion_id')" class="mt-2" />
                    </div>
                </div>

                {{-- Estado --}}
                <div class="px-6 py-6 sm:grid sm:grid-cols-4 sm:gap-x-4 sm:items-start"
                     x-data="{ activo: {{ old('activo', $organizador->activo ? '1' : '0') == '1' ? 'true' : 'false' }} }">
                    <div class="pt-0.5">
                        <x-input-label value="Estado" class="!text-sm !font-semibold !text-gray-800" />
                        <p class="mt-1.5 text-xs leading-relaxed text-gray-500">Define si el organizador puede ser asignado a nuevos eventos.</p>
                    </div>
                    <div class="hidden sm:block"></div>
                    <div class="mt-5 sm:col-span-2 sm:mt-0">
                        <input type="hidden" name="activo" :value="activo ? '1' : '0'">
                        <div class="flex items-center gap-4">
                            <button type="button"
                                    @click="activo = !activo"
                                    :class="activo ? 'bg-primary' : 'bg-gray-200'"
                                    class="relative inline-flex flex-shrink-0 items-center rounded-full transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary/50 focus:ring-offset-2"
                                    style="width: 3.5rem; height: 1.75rem;"
                                    :aria-checked="activo.toString()"
                                    role="switch">
                                <span :style="{
                                          width: '1.25rem',
                                          height: '1.25rem',
                                          transform: activo ? 'translateX(2rem)' : 'translateX(0.25rem)'
                                      }"
                                      class="inline-block rounded-full bg-white shadow transition-transform duration-200 ease-in-out">
                                </span>
                            </button>
                            <span class="text-sm font-medium"
                                  :class="activo ? 'text-gray-900' : 'text-gray-400'"
                                  x-text="activo ? 'Activo' : 'Inactivo'">
                            </span>
                            <span x-show="activo"
                                  class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20"
                                  x-cloak>
                                <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                Disponible para eventos
                            </span>
                            <span x-show="!activo"
                                  class="inline-flex items-center gap-1 rounded-full bg-gray-50 px-2 py-0.5 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10"
                                  x-cloak>
                                <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                No disponible
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Botones --}}
                <div class="px-6 py-4 bg-gray-50 rounded-b-xl flex items-center justify-end gap-3">
                    <a href="{{ route('organizadores.show', $organizador) }}">
                        <x-secondary-button type="button">Cancelar</x-secondary-button>
                    </a>
                    <x-primary-button type="submit">
                        <x-heroicon-o-check class="h-4 w-4 mr-1.5 -ml-0.5" />
                        Guardar cambios
                    </x-primary-button>
                </div>

            </div>
        </form>
    </div>

</x-app-layout>
