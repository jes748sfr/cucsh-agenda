<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Instituciones</h2>
            @can('catalogos.crear')
                <x-primary-button type="button" x-data @click="$dispatch('catalog-create')">
                    <x-heroicon-o-plus class="h-4 w-4 mr-1.5 -ml-0.5" />
                    Nueva institución
                </x-primary-button>
            @endcan
        </div>
    </x-slot>

    <div x-data="{
            editing: {{ $errors->any() && old('_editing') == '1' ? 'true' : 'false' }},
            editId: '{{ old('_edit_id', '') }}',
            nombre: {{ Js::from(old('nombre', '')) }},
            formAction: '{{ route('instituciones.store') }}',

            openCreate() {
                this.editing = false;
                this.editId = null;
                this.nombre = '';
                this.formAction = '{{ route('instituciones.store') }}';
                $dispatch('open-modal', 'catalog-form');
            },
            openEdit(id, nombre, updateUrl) {
                this.editing = true;
                this.editId = id;
                this.nombre = nombre;
                this.formAction = updateUrl;
                $dispatch('open-modal', 'catalog-form');
            }
        }"
        x-init="
            @if($errors->any())
                if (editing) {
                    formAction = '{{ url('instituciones') }}/' + editId;
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
                @forelse ($instituciones as $institucion)
                    <x-table-row striped>
                        <x-table-cell>
                            <a href="{{ route('instituciones.show', $institucion) }}"
                               class="font-medium text-gray-900 hover:text-primary transition-colors">
                                {{ $institucion->nombre }}
                            </a>
                        </x-table-cell>

                        <x-table-actions>
                            @can('catalogos.editar')
                                <button type="button"
                                    @click="openEdit({{ $institucion->id }}, {{ Js::from($institucion->nombre) }}, '{{ route('instituciones.update', $institucion) }}')"
                                    class="text-gray-400 hover:text-primary transition"
                                    title="Editar">
                                    <x-heroicon-o-pencil-square class="h-5 w-5" />
                                </button>
                            @endcan

                            @can('catalogos.eliminar')
                                <button type="button"
                                    x-on:click="$dispatch('open-modal', 'delete-inst-{{ $institucion->id }}')"
                                    class="text-gray-400 hover:text-danger transition"
                                    title="Eliminar">
                                    <x-heroicon-o-trash class="h-5 w-5" />
                                </button>
                            @endcan
                        </x-table-actions>
                    </x-table-row>

                    @can('catalogos.eliminar')
                        <x-confirm-delete-modal
                            name="delete-inst-{{ $institucion->id }}"
                            :action="route('instituciones.destroy', $institucion)"
                            title="Eliminar institución"
                            :description="'Se eliminará «' . $institucion->nombre . '». Esta acción no se puede deshacer.'"
                        />
                    @endcan
                @empty
                    <x-table-row>
                        <td colspan="2" class="px-4 py-8 text-center">
                            <x-heroicon-o-building-library class="mx-auto h-10 w-10 text-gray-300" />
                            <p class="mt-2 text-sm text-gray-500">No hay instituciones registradas.</p>
                            @can('catalogos.crear')
                                <button type="button"
                                    @click="openCreate()"
                                    class="mt-3 text-sm font-medium text-primary hover:underline">
                                    Crear la primera
                                </button>
                            @endcan
                        </td>
                    </x-table-row>
                @endforelse
            </tbody>

            @if ($instituciones->hasPages())
                <x-slot name="footer">
                    {{ $instituciones->links() }}
                </x-slot>
            @endif
        </x-table>

        <x-catalog-form-modal entity-label="institución" :max-length="255" />
    </div>
</x-app-layout>
