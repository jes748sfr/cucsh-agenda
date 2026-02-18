<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3 min-w-0">
                <a href="{{ route('organizadores.index') }}"
                   class="text-gray-400 hover:text-gray-600 transition flex-shrink-0"
                   title="Volver al listado">
                    <x-heroicon-o-arrow-left class="h-5 w-5" />
                </a>
                <h2 class="text-lg font-semibold text-gray-900 truncate">{{ $organizador->nombre }}</h2>
            </div>

            <div class="flex items-center gap-2 ml-4 flex-shrink-0">
                @can('organizadores.editar')
                    <a href="{{ route('organizadores.edit', $organizador) }}">
                        <x-secondary-button>
                            <x-heroicon-o-pencil-square class="h-4 w-4 mr-1.5 -ml-0.5" />
                            Editar
                        </x-secondary-button>
                    </a>
                @endcan

                @can('organizadores.eliminar')
                    <x-danger-button
                        type="button"
                        x-on:click="$dispatch('open-modal', 'delete-organizador')">
                        <x-heroicon-o-trash class="h-4 w-4 mr-1.5 -ml-0.5" />
                        Eliminar
                    </x-danger-button>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <div class="rounded-lg bg-white shadow-sm border border-gray-200 p-6">
            <dl class="divide-y divide-gray-100">

                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $organizador->nombre }}</dd>
                </div>

                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Correo electrónico</dt>
                    <dd class="mt-1 text-sm sm:col-span-2 sm:mt-0">
                        <a href="mailto:{{ $organizador->email }}"
                           class="text-primary hover:underline">
                            {{ $organizador->email }}
                        </a>
                    </dd>
                </div>

                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        @if ($organizador->tel)
                            {{ $organizador->tel }}
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </dd>
                </div>

                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Administración</dt>
                    <dd class="mt-1 text-sm sm:col-span-2 sm:mt-0">
                        <a href="{{ url('administraciones/' . $organizador->administracion->id) }}"
                           class="text-primary hover:underline">
                            {{ $organizador->administracion->nombre }}
                        </a>
                    </dd>
                </div>

                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Estado</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        @if ($organizador->activo)
                            <x-badge color="success" dot>Activo</x-badge>
                        @else
                            <x-badge color="gray" dot>Inactivo</x-badge>
                        @endif
                    </dd>
                </div>

                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Registrado</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        {{ $organizador->created_at->translatedFormat('d M Y, H:i') }}
                    </dd>
                </div>

                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Actualizado</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        {{ $organizador->updated_at->translatedFormat('d M Y, H:i') }}
                    </dd>
                </div>

            </dl>
        </div>
    </div>

    @can('organizadores.eliminar')
        <x-confirm-delete-modal
            name="delete-organizador"
            :action="route('organizadores.destroy', $organizador)"
            title="Eliminar organizador"
            :description="'Se eliminará a «' . $organizador->nombre . '» del sistema. Esta acción no se puede deshacer.'"
        />
    @endcan

</x-app-layout>
