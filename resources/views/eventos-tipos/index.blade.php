<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-900">Catálogos</h2>
    </x-slot>

    {{-- Cabecera en el área de contenido --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Tipos de Evento</h1>
        <p class="text-sm text-gray-500 mt-1">Categorías para clasificar los eventos académicos.</p>
    </div>

    {{-- Barra de acciones --}}
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <p class="text-sm font-medium text-gray-700">
            Todos los tipos
            <span class="ml-1 inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">
                {{ $eventosTipos->total() }}
            </span>
        </p>

        @can('catalogos.crear')
            <x-primary-button type="button" x-data @click="$dispatch('catalog-create')">
                <x-heroicon-o-plus class="h-4 w-4 mr-1.5 -ml-0.5" />
                Nuevo tipo
            </x-primary-button>
        @endcan
    </div>

    <div x-data="{
            editing: {{ $errors->any() && old('_editing') == '1' ? 'true' : 'false' }},
            editId: '{{ old('_edit_id', '') }}',
            nombre: {{ Js::from(old('nombre', '')) }},
            formAction: '{{ url('eventos-tipos') }}',

            openCreate() {
                this.editing = false;
                this.editId = null;
                this.nombre = '';
                this.formAction = '{{ url('eventos-tipos') }}';
                $dispatch('open-modal', 'catalog-form');
            },
            openEdit(id, nombre) {
                this.editing = true;
                this.editId = id;
                this.nombre = nombre;
                this.formAction = '{{ url('eventos-tipos') }}/' + id;
                $dispatch('open-modal', 'catalog-form');
            }
        }"
        x-init="
            @if($errors->any())
                if (editing) {
                    formAction = '{{ url('eventos-tipos') }}/' + editId;
                }
                $nextTick(() => $dispatch('open-modal', 'catalog-form'));
            @endif
        "
        @catalog-create.window="openCreate()"
    >
        <x-table>
            <thead class="bg-gray-50">
                <tr>
                    <x-table-header>Nombre</x-table-header>
                    <x-table-header align="right">Acciones</x-table-header>
                </tr>
            </thead>

            <tbody>
                @forelse ($eventosTipos as $tipo)
                    <x-table-row striped
                        @click="if (!$event.target.closest('a, button')) window.location.href = '{{ url('eventos-tipos/' . $tipo->id) }}'"
                        style="cursor: pointer"
                    >
                        <x-table-cell>
                            <a href="{{ url('eventos-tipos/' . $tipo->id) }}"
                               class="font-medium text-gray-900 hover:text-primary transition-colors">
                                {{ $tipo->nombre }}
                            </a>
                        </x-table-cell>

                        <x-table-actions>
                            @can('catalogos.editar')
                                <button type="button"
                                    @click="openEdit({{ $tipo->id }}, {{ Js::from($tipo->nombre) }})"
                                    class="text-gray-400 hover:text-primary transition"
                                    title="Editar">
                                    <x-heroicon-o-pencil-square class="h-5 w-5" />
                                </button>
                            @endcan

                            @can('catalogos.eliminar')
                                <button type="button"
                                    x-on:click="$dispatch('open-modal', 'delete-tipo-{{ $tipo->id }}')"
                                    class="text-gray-400 hover:text-danger transition"
                                    title="Eliminar">
                                    <x-heroicon-o-trash class="h-5 w-5" />
                                </button>
                            @endcan
                        </x-table-actions>
                    </x-table-row>

                    @can('catalogos.eliminar')
                        <x-confirm-delete-modal
                            name="delete-tipo-{{ $tipo->id }}"
                            :action="url('eventos-tipos/' . $tipo->id)"
                            title="Eliminar tipo de evento"
                            :description="'Se eliminará «' . $tipo->nombre . '». Esta acción no se puede deshacer.'"
                        />
                    @endcan
                @empty
                    <x-table-row>
                        <td colspan="2" class="px-4 py-8 text-center">
                            <x-heroicon-o-tag class="mx-auto h-10 w-10 text-gray-300" />
                            <p class="mt-2 text-sm text-gray-500">No hay tipos de evento registrados.</p>
                            @can('catalogos.crear')
                                <button type="button"
                                    @click="openCreate()"
                                    class="mt-3 text-sm font-medium text-primary hover:underline">
                                    Crear el primero
                                </button>
                            @endcan
                        </td>
                    </x-table-row>
                @endforelse
            </tbody>
        </x-table>

        @if ($eventosTipos->hasPages())
            <div class="mt-4">
                {{ $eventosTipos->links() }}
            </div>
        @endif

        <x-catalog-form-modal entity-label="tipo de evento" :max-length="100" />
    </div>
</x-app-layout>
