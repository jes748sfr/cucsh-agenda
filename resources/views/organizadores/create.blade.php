<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('organizadores.index') }}" class="hover:text-primary transition-colors">Organizadores</a>
            <x-heroicon-m-chevron-right class="h-4 w-4 flex-shrink-0" />
            <span class="font-medium text-gray-900">Nuevo organizador</span>
        </div>
    </x-slot>

    <div class="max-w-2xl">
        <form action="{{ route('organizadores.store') }}" method="POST">
            @csrf

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 divide-y divide-gray-100">

                {{-- Sección: Información de contacto --}}
                <div class="px-6 py-5">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">
                        Información de contacto
                    </h3>

                    {{-- Nombre + Teléfono en grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="nombre" value="Nombre *" />
                            <x-text-input
                                id="nombre"
                                name="nombre"
                                type="text"
                                class="mt-1 block w-full"
                                :value="old('nombre')"
                                placeholder="Nombre completo"
                                maxlength="255"
                                required
                                autofocus
                            />
                            <x-input-error :messages="$errors->get('nombre')" class="mt-1.5" />
                        </div>

                        <div>
                            <x-input-label for="tel" value="Teléfono" />
                            <x-text-input
                                id="tel"
                                name="tel"
                                type="tel"
                                class="mt-1 block w-full"
                                :value="old('tel')"
                                placeholder="+52 33 1234 5678"
                                maxlength="20"
                            />
                            <x-input-error :messages="$errors->get('tel')" class="mt-1.5" />
                        </div>
                    </div>

                    {{-- Correo electrónico --}}
                    <div class="mt-4">
                        <x-input-label for="email" value="Correo electrónico *" />
                        <x-text-input
                            id="email"
                            name="email"
                            type="email"
                            class="mt-1 block w-full"
                            :value="old('email')"
                            placeholder="organizador@cucsh.udg.mx"
                            maxlength="255"
                            required
                        />
                        <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                    </div>
                </div>

                {{-- Sección: Configuración --}}
                <div class="px-6 py-5">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">
                        Configuración
                    </h3>

                    {{-- Administración --}}
                    <div>
                        <x-input-label for="administracion_id" value="Administración *" />
                        <select id="administracion_id"
                                name="administracion_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                            <option value="">— Seleccionar administración —</option>
                            @foreach ($administraciones as $adm)
                                <option value="{{ $adm->id }}"
                                    {{ old('administracion_id') == $adm->id ? 'selected' : '' }}>
                                    {{ $adm->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('administracion_id')" class="mt-1.5" />
                    </div>

                    {{-- Toggle activo --}}
                    <div class="mt-5"
                         x-data="{ activo: {{ old('activo', '1') == '1' ? 'true' : 'false' }} }">
                        <input type="hidden" name="activo" :value="activo ? '1' : '0'">
                        <x-input-label value="Estado" />
                        <div class="mt-1.5 flex items-center gap-3">
                            <button type="button"
                                    @click="activo = !activo"
                                    :class="activo ? 'bg-primary' : 'bg-gray-200'"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 items-center rounded-full transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary/50 focus:ring-offset-2"
                                    :aria-checked="activo.toString()"
                                    role="switch">
                                <span :class="activo ? 'translate-x-6' : 'translate-x-1'"
                                      class="inline-block h-4 w-4 rounded-full bg-white shadow-sm transition-transform duration-200 ease-in-out">
                                </span>
                            </button>
                            <span class="text-sm text-gray-700"
                                  x-text="activo ? 'Activo' : 'Inactivo'">
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Botones --}}
                <div class="px-6 py-4 bg-gray-50 rounded-b-lg flex items-center justify-end gap-3">
                    <a href="{{ route('organizadores.index') }}">
                        <x-secondary-button type="button">
                            Cancelar
                        </x-secondary-button>
                    </a>
                    <x-primary-button type="submit">
                        <x-heroicon-o-user-plus class="h-4 w-4 mr-1.5 -ml-0.5" />
                        Crear organizador
                    </x-primary-button>
                </div>

            </div>
        </form>
    </div>

</x-app-layout>
