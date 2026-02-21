<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-900">Eventos</h2>
    </x-slot>

    {{-- Cabecera en el área de contenido --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Gestión de Eventos</h1>
        <p class="text-sm text-gray-500 mt-1">Administra los eventos académicos del CUCSH.</p>
    </div>

    {{-- Barra de acciones --}}
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <p class="text-sm font-medium text-gray-700">
            Todos los eventos
            <span class="ml-1 inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">
                {{ $eventos->total() }}
            </span>
        </p>

        @can('eventos.crear')
            <a href="{{ route('eventos.create') }}">
                <x-primary-button>
                    <x-heroicon-o-plus class="h-4 w-4 mr-1.5 -ml-0.5" />
                    Nuevo evento
                </x-primary-button>
            </a>
        @endcan
    </div>

    {{-- Tabla --}}
    <x-table>
        <thead class="bg-gray-50">
            <tr>
                <x-table-header>Evento</x-table-header>
                <x-table-header>Institución</x-table-header>
                <x-table-header>Organizador</x-table-header>
                <x-table-header>Estado</x-table-header>
                <x-table-header>Fechas</x-table-header>
                <x-table-header align="right">Acciones</x-table-header>
            </tr>
        </thead>

        <tbody>
            @forelse ($eventos as $evento)
                <x-table-row striped
                    x-data="{ url: '{{ route('eventos.show', $evento) }}' }"
                    @click="if (!$event.target.closest('a, button')) window.location.href = url"
                    style="cursor: pointer"
                >
                    {{-- Nombre + tipo --}}
                    <x-table-cell>
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 flex-shrink-0 rounded-md bg-primary/10 flex items-center justify-center">
                                <x-heroicon-o-calendar-days class="h-4 w-4 text-primary" />
                            </div>
                            <div class="min-w-0">
                                <a href="{{ route('eventos.show', $evento) }}"
                                   class="font-medium text-gray-900 hover:text-primary transition-colors">
                                    {{ $evento->nombre }}
                                </a>
                                <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $evento->eventoTipo->nombre }}</p>
                            </div>
                        </div>
                    </x-table-cell>

                    {{-- Institución --}}
                    <x-table-cell>
                        <span class="text-sm text-gray-700">{{ $evento->institucion->nombre }}</span>
                    </x-table-cell>

                    {{-- Organizador --}}
                    <x-table-cell>
                        <span class="text-sm text-gray-700">{{ $evento->organizador->nombre }}</span>
                    </x-table-cell>

                    {{-- Estado --}}
                    <x-table-cell>
                        @if ($evento->activo)
                            <x-badge color="success" dot>Activo</x-badge>
                        @else
                            <x-badge color="gray" dot>Inactivo</x-badge>
                        @endif
                    </x-table-cell>

                    {{-- Fechas --}}
                    <x-table-cell>
                        <x-badge color="primary">{{ $evento->fechas_count }}</x-badge>
                    </x-table-cell>

                    {{-- Acciones --}}
                    <x-table-actions>
                        @can('update', $evento)
                            <a href="{{ route('eventos.edit', $evento) }}"
                               class="text-gray-400 hover:text-primary transition"
                               title="Editar">
                                <x-heroicon-o-pencil-square class="h-5 w-5" />
                            </a>
                        @endcan

                        @can('delete', $evento)
                            <button type="button"
                                x-on:click="$dispatch('open-modal', 'delete-evento-{{ $evento->id }}')"
                                class="text-gray-400 hover:text-danger transition"
                                title="Eliminar">
                                <x-heroicon-o-trash class="h-5 w-5" />
                            </button>
                        @endcan
                    </x-table-actions>
                </x-table-row>

                @can('delete', $evento)
                    <x-confirm-delete-modal
                        name="delete-evento-{{ $evento->id }}"
                        :action="route('eventos.destroy', $evento)"
                        title="Eliminar evento"
                        :description="'Se eliminará «' . $evento->nombre . '» y todas sus fechas. Esta acción no se puede deshacer.'"
                    />
                @endcan
            @empty
                <x-table-row>
                    <td colspan="6" class="px-4 py-12 text-center">
                        <x-heroicon-o-calendar-days class="mx-auto h-10 w-10 text-gray-300" />
                        <p class="mt-2 text-sm font-medium text-gray-700">No hay eventos registrados</p>
                        <p class="mt-1 text-xs text-gray-400">Crea el primer evento con el botón de arriba.</p>
                    </td>
                </x-table-row>
            @endforelse
        </tbody>
    </x-table>

    @if ($eventos->hasPages())
        <div class="mt-4">
            {{ $eventos->links() }}
        </div>
    @endif

</x-app-layout>
