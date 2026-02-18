<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-sm text-gray-500 min-w-0">
                <a href="{{ route('organizadores.index') }}" class="hover:text-primary transition-colors flex-shrink-0">Organizadores</a>
                <x-heroicon-m-chevron-right class="h-4 w-4 flex-shrink-0" />
                <span class="font-medium text-gray-900 truncate">{{ $organizador->nombre }}</span>
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

    <div class="max-w-2xl space-y-4">

        {{-- Card de detalles --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 divide-y divide-gray-100">

            {{-- Encabezado del perfil --}}
            <div class="px-6 py-5 flex items-center gap-4">
                <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-primary/10 text-lg font-bold text-primary">
                    {{ strtoupper(substr($organizador->nombre, 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-lg font-semibold text-gray-900">{{ $organizador->nombre }}</h1>
                    <p class="text-sm text-gray-500">{{ $organizador->email }}</p>
                </div>
                <div class="ml-auto">
                    @if ($organizador->activo)
                        <x-badge color="success" dot>Activo</x-badge>
                    @else
                        <x-badge color="gray" dot>Inactivo</x-badge>
                    @endif
                </div>
            </div>

            {{-- Datos de contacto --}}
            <div class="px-6 py-5">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Información de contacto</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                    <div>
                        <dt class="text-xs font-medium text-gray-500 flex items-center gap-1.5">
                            <x-heroicon-o-envelope class="h-3.5 w-3.5" />
                            Correo electrónico
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <a href="mailto:{{ $organizador->email }}"
                               class="text-primary hover:underline">
                                {{ $organizador->email }}
                            </a>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-medium text-gray-500 flex items-center gap-1.5">
                            <x-heroicon-o-phone class="h-3.5 w-3.5" />
                            Teléfono
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if ($organizador->tel)
                                <a href="tel:{{ $organizador->tel }}" class="hover:text-primary transition-colors">
                                    {{ $organizador->tel }}
                                </a>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Configuración --}}
            <div class="px-6 py-5">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Configuración</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                    <div>
                        <dt class="text-xs font-medium text-gray-500 flex items-center gap-1.5">
                            <x-heroicon-o-building-office class="h-3.5 w-3.5" />
                            Administración
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <a href="{{ url('administraciones/' . $organizador->administracion->id) }}"
                               class="text-primary hover:underline">
                                {{ $organizador->administracion->nombre }}
                            </a>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-medium text-gray-500 flex items-center gap-1.5">
                            <x-heroicon-o-calendar class="h-3.5 w-3.5" />
                            Registrado
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $organizador->created_at->format('d \d\e F \d\e Y') }}
                        </dd>
                    </div>
                </dl>
            </div>

        </div>

    </div>

    {{-- Modal confirmación eliminación --}}
    @can('organizadores.eliminar')
        <x-confirm-delete-modal
            name="delete-organizador"
            :action="route('organizadores.destroy', $organizador)"
            title="Eliminar organizador"
            :description="'Se eliminará a «' . $organizador->nombre . '» del sistema. Esta acción no se puede deshacer.'"
        />
    @endcan

</x-app-layout>
