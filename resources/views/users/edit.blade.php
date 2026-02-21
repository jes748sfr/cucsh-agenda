<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4 min-w-0">
            <a href="{{ route('users.show', $user) }}"
               class="text-gray-400 hover:text-gray-600 transition flex-shrink-0"
               title="Volver al detalle">
                <x-heroicon-o-arrow-left class="h-5 w-5" />
            </a>
            <div class="min-w-0">
                <h2 class="text-lg font-semibold text-gray-900 truncate">Editar usuario</h2>
                <p class="text-xs text-gray-400 truncate">{{ $user->name }}</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-xl">
        <form
            x-data="{ loading: false }"
            @submit="loading = true"
            action="{{ route('users.update', $user) }}"
            method="POST"
            novalidate
        >
            @csrf
            @method('PUT')

            <div class="space-y-10">

                {{-- Sección 1: Información de la cuenta --}}
                <div class="border-b border-gray-200 pb-10">
                    <h3 class="text-base font-semibold text-gray-900">Información de la cuenta</h3>
                    <p class="mt-1 text-sm text-gray-500">Datos de identificación para el acceso al sistema.</p>

                    <div class="mt-8 grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-6">

                        {{-- Nombre --}}
                        <div class="sm:col-span-4">
                            <x-input-label for="name" value="Nombre *" />
                            <div class="mt-2">
                                <x-text-input
                                    id="name"
                                    name="name"
                                    type="text"
                                    class="block w-full"
                                    :value="old('name', $user->name)"
                                    placeholder="Nombre completo"
                                    maxlength="255"
                                    autofocus
                                    aria-describedby="name-error"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('name')" id="name-error" />
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
                                    :value="old('email', $user->email)"
                                    placeholder="usuario@cucsh.udg.mx"
                                    maxlength="255"
                                    aria-describedby="email-error"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('email')" id="email-error" />
                        </div>

                    </div>
                </div>

                {{-- Sección 2: Contraseña --}}
                <div class="border-b border-gray-200 pb-10">
                    <h3 class="text-base font-semibold text-gray-900">Contraseña</h3>
                    <p class="mt-1 text-sm text-gray-500">Deja en blanco para mantener la contraseña actual.</p>

                    <div class="mt-8 grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-6">

                        {{-- Contraseña --}}
                        <div class="sm:col-span-4">
                            <x-input-label for="password" value="Nueva contraseña" />
                            <div class="mt-2">
                                <x-text-input
                                    id="password"
                                    name="password"
                                    type="password"
                                    class="block w-full"
                                    placeholder="••••••••"
                                    autocomplete="new-password"
                                    aria-describedby="password-error"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('password')" id="password-error" />
                        </div>

                        {{-- Confirmar contraseña --}}
                        <div class="sm:col-span-4">
                            <x-input-label for="password_confirmation" value="Confirmar nueva contraseña" />
                            <div class="mt-2">
                                <x-text-input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    class="block w-full"
                                    placeholder="••••••••"
                                    autocomplete="new-password"
                                />
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Sección 3: Permisos --}}
                <div class="border-b border-gray-200 pb-10">
                    <h3 class="text-base font-semibold text-gray-900">Permisos</h3>
                    <p class="mt-1 text-sm text-gray-500">Define las acciones que el usuario puede realizar en el sistema.</p>

                    <div class="mt-8 grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-6">

                        {{-- Rol --}}
                        <div class="sm:col-span-3">
                            <x-input-label for="role" value="Rol *" />
                            <div class="mt-2">
                                @php
                                    $rolActual = old('role', $user->getRoleNames()->first() ?? '');
                                @endphp
                                <select
                                    id="role"
                                    name="role"
                                    aria-describedby="role-error"
                                    class="block w-full rounded-md border-gray-300 shadow-sm text-sm transition-colors duration-150 focus:outline-none focus:border-udg-gold focus:ring-2 focus:ring-udg-gold/30"
                                >
                                    <option value="">— Seleccionar rol —</option>
                                    @foreach ($roles as $rol)
                                        <option value="{{ $rol }}" {{ $rolActual === $rol ? 'selected' : '' }}>
                                            {{ ucfirst($rol) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <x-input-error :messages="$errors->get('role')" id="role-error" />
                        </div>

                    </div>
                </div>

            </div>

            {{-- Botones --}}
            <div class="mt-8 flex items-center justify-end gap-3">
                <a href="{{ route('users.show', $user) }}">
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
