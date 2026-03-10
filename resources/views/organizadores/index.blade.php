<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-900">Organizadores</h2>
    </x-slot>

    {{-- Cabecera en el área de contenido --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Organizadores</h1>
        <p class="text-sm text-gray-500 mt-1">Personas y entidades responsables de organizar eventos.</p>
    </div>

    {{-- Barra de acciones --}}
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <p class="text-sm font-medium text-gray-700">
            Todos los organizadores
            <span class="ml-1 inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">
                {{ $organizadores->total() }}
            </span>
        </p>

        @can('organizadores.crear')
            <a href="{{ route('organizadores.create') }}">
                <x-primary-button>
                    <x-heroicon-o-plus class="h-4 w-4 mr-1.5 -ml-0.5" />
                    Nuevo organizador
                </x-primary-button>
            </a>
        @endcan
    </div>

    <x-table>
        <thead class="bg-gray-50">
            <tr>
                <x-table-header>Organizador</x-table-header>
                <x-table-header>Administración</x-table-header>
                <x-table-header align="center">Estado</x-table-header>
                <x-table-header align="right">Acciones</x-table-header>
            </tr>
        </thead>

        <tbody>
            @forelse ($organizadores as $org)
                <x-table-row striped
                    x-data="{ url: '{{ route('organizadores.show', $org) }}' }"
                    @click="if (!$event.target.closest('a, button')) window.location.href = url"
                    style="cursor: pointer"
                >
                    {{-- Nombre + email --}}
                    <x-table-cell>
                        <div>
                            <a href="{{ route('organizadores.show', $org) }}"
                               class="font-medium text-gray-900 hover:text-primary transition-colors">
                                {{ $org->nombre }}
                            </a>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $org->email }}</p>
                        </div>
                    </x-table-cell>

                    {{-- Administración --}}
                    <x-table-cell>
                        <span class="text-sm text-gray-600">{{ $org->administracion->nombre }}</span>
                    </x-table-cell>

                    {{-- Estado --}}
                    <x-table-cell align="center">
                        @if ($org->activo)
                            <x-badge color="success" dot>Activo</x-badge>
                        @else
                            <x-badge color="gray" dot>Inactivo</x-badge>
                        @endif
                    </x-table-cell>

                    {{-- Acciones --}}
                    <x-table-actions>
                        @can('organizadores.editar')
                            <a href="{{ route('organizadores.edit', $org) }}"
                               class="text-gray-400 hover:text-primary transition"
                               title="Editar">
                                <x-heroicon-o-pencil-square class="h-5 w-5" />
                            </a>
                        @endcan

                        @can('organizadores.eliminar')
                            <button type="button"
                                x-on:click="$dispatch('open-modal', 'delete-org-{{ $org->id }}')"
                                class="text-gray-400 hover:text-danger transition"
                                title="Eliminar">
                                <x-heroicon-o-trash class="h-5 w-5" />
                            </button>
                        @endcan
                    </x-table-actions>
                </x-table-row>

                @can('organizadores.eliminar')
                    <x-confirm-delete-modal
                        name="delete-org-{{ $org->id }}"
                        :action="route('organizadores.destroy', $org)"
                        title="Eliminar organizador"
                        :description="'Se eliminará a «' . $org->nombre . '». Esta acción no se puede deshacer.'"
                    />
                @endcan
            @empty
                <x-table-row>
                    <td colspan="4" class="px-4 py-12 text-center">
                        <x-heroicon-o-user-group class="mx-auto h-10 w-10 text-gray-300" />
                        <p class="mt-2 text-sm font-medium text-gray-700">No hay organizadores registrados</p>
                        <p class="mt-1 text-xs text-gray-400">Los organizadores se registran al crear un evento.</p>
                    </td>
                </x-table-row>
            @endforelse
        </tbody>
    </x-table>

    @if ($organizadores->hasPages())
        <div class="mt-4">
            {{ $organizadores->links() }}
        </div>
    @endif

</x-app-layout>
