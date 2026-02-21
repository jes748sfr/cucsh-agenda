<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3 min-w-0">
                <a href="{{ route('users.index') }}"
                   class="text-gray-400 hover:text-gray-600 transition flex-shrink-0"
                   title="Volver al listado">
                    <x-heroicon-o-arrow-left class="h-5 w-5" />
                </a>
                <div class="min-w-0">
                    <h2 class="text-lg font-semibold text-gray-900 truncate">{{ $user->name }}</h2>
                    <p class="text-xs text-gray-400 truncate">{{ $user->email }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2 ml-4 flex-shrink-0">
                @can('usuarios.editar', $user)
                    <a href="{{ route('users.edit', $user) }}">
                        <x-secondary-button>
                            <x-heroicon-o-pencil-square class="h-4 w-4 mr-1.5 -ml-0.5" />
                            Editar
                        </x-secondary-button>
                    </a>
                @endcan

                @if(Auth::id() !== $user->id)
                    @can('usuarios.eliminar', $user)
                        <x-danger-button
                            type="button"
                            x-on:click="$dispatch('open-modal', 'delete-user')">
                            <x-heroicon-o-trash class="h-4 w-4 mr-1.5 -ml-0.5" />
                            Eliminar
                        </x-danger-button>
                    @endcan
                @endif
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <div class="rounded-lg bg-white shadow-sm border border-gray-200 p-6">
            <dl class="divide-y divide-gray-100">

                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $user->name }}</dd>
                </div>

                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Correo electrónico</dt>
                    <dd class="mt-1 text-sm sm:col-span-2 sm:mt-0">
                        <a href="mailto:{{ $user->email }}"
                           class="text-primary hover:underline">
                            {{ $user->email }}
                        </a>
                    </dd>
                </div>

                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Rol</dt>
                    <dd class="mt-1 text-sm sm:col-span-2 sm:mt-0">
                        @php
                            $rolNombre = $user->roles->first()?->name ?? 'sin rol';
                            $badgeColor = match($rolNombre) {
                                'administrador' => 'primary',
                                'editor'        => 'warning',
                                default         => 'gray',
                            };
                        @endphp
                        <x-badge :color="$badgeColor">{{ ucfirst($rolNombre) }}</x-badge>
                    </dd>
                </div>

                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Registrado</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        {{ $user->created_at->translatedFormat('d M Y, H:i') }}
                    </dd>
                </div>

                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Actualizado</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        {{ $user->updated_at->translatedFormat('d M Y, H:i') }}
                    </dd>
                </div>

            </dl>
        </div>
    </div>

    @if(Auth::id() !== $user->id)
        @can('usuarios.eliminar', $user)
            <x-confirm-delete-modal
                name="delete-user"
                :action="route('users.destroy', $user)"
                title="Eliminar usuario"
                :description="'Se eliminará la cuenta de «' . $user->name . '». Esta acción no se puede deshacer.'"
            />
        @endcan
    @endif

</x-app-layout>
