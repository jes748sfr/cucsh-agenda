<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Ubicaciones</h2>
            @can('catalogos.crear')
                <a href="{{ route('ubicaciones.create') }}">
                    <x-primary-button>
                        <x-heroicon-o-plus class="h-4 w-4 mr-1.5 -ml-0.5" />
                        Nueva ubicación
                    </x-primary-button>
                </a>
            @endcan
        </div>
    </x-slot>

    <x-table>
        <thead class="bg-gray-50">
            <tr>
                <x-table-header>Nombre</x-table-header>
                <x-table-header>Institución</x-table-header>
                <x-table-header align="center">Estado</x-table-header>
                <x-table-header align="center">Eventos</x-table-header>
                <x-table-header align="right">Acciones</x-table-header>
            </tr>
        </thead>

        <tbody>
            @forelse ($ubicaciones as $ubicacion)
                <x-table-row striped
                    @click="if (!$event.target.closest('a, button')) window.location.href = '{{ url('ubicaciones/' . $ubicacion->id) }}'"
                    style="cursor: pointer"
                >
                    <x-table-cell>
                        <a href="{{ url('ubicaciones/' . $ubicacion->id) }}"
                           class="font-medium text-gray-900 hover:text-primary transition-colors">
                            {{ $ubicacion->nombre }}
                        </a>
                    </x-table-cell>

                    <x-table-cell>
                        @if ($ubicacion->institucion)
                            <span class="text-sm text-gray-600">{{ $ubicacion->institucion->nombre }}</span>
                        @else
                            <span class="text-sm text-gray-400">—</span>
                        @endif
                    </x-table-cell>

                    <x-table-cell align="center">
                        @if ($ubicacion->activo)
                            <x-badge color="success" dot>Activa</x-badge>
                        @else
                            <x-badge color="gray" dot>Inactiva</x-badge>
                        @endif
                    </x-table-cell>

                    <x-table-cell align="center">
                        <x-badge color="gray">{{ $ubicacion->eventos_count }}</x-badge>
                    </x-table-cell>

                    <x-table-actions>
                        @can('catalogos.editar')
                            <a href="{{ url('ubicaciones/' . $ubicacion->id . '/edit') }}"
                               class="text-gray-400 hover:text-primary transition"
                               title="Editar">
                                <x-heroicon-o-pencil-square class="h-5 w-5" />
                            </a>
                        @endcan

                        @can('catalogos.eliminar')
                            <button type="button"
                                x-on:click="$dispatch('open-modal', 'delete-ubicacion-{{ $ubicacion->id }}')"
                                class="text-gray-400 hover:text-danger transition"
                                title="Eliminar">
                                <x-heroicon-o-trash class="h-5 w-5" />
                            </button>
                        @endcan
                    </x-table-actions>
                </x-table-row>

                @can('catalogos.eliminar')
                    <x-confirm-delete-modal
                        name="delete-ubicacion-{{ $ubicacion->id }}"
                        :action="url('ubicaciones/' . $ubicacion->id)"
                        title="Eliminar ubicación"
                        :description="'Se eliminará «' . $ubicacion->nombre . '». Esta acción no se puede deshacer.'"
                    />
                @endcan
            @empty
                <x-table-row>
                    <td colspan="5" class="px-4 py-12 text-center">
                        <x-heroicon-o-map-pin class="mx-auto h-10 w-10 text-gray-300" />
                        <p class="mt-2 text-sm font-medium text-gray-700">No hay ubicaciones registradas</p>
                        @can('catalogos.crear')
                            <a href="{{ route('ubicaciones.create') }}"
                               class="mt-3 inline-block text-sm font-medium text-primary hover:underline">
                                Crear la primera
                            </a>
                        @endcan
                    </td>
                </x-table-row>
            @endforelse
        </tbody>
    </x-table>

    @if ($ubicaciones->hasPages())
        <div class="mt-4">
            {{ $ubicaciones->links() }}
        </div>
    @endif

</x-app-layout>
