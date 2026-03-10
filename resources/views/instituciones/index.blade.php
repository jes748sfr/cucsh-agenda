<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-900">Catálogos</h2>
    </x-slot>

    {{-- Cabecera en el área de contenido --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Instituciones</h1>
        <p class="text-sm text-gray-500 mt-1">Planteles universitarios donde se realizan eventos.</p>
    </div>

    {{-- Barra de acciones --}}
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <p class="text-sm font-medium text-gray-700">
            Todas las instituciones
            <span class="ml-1 inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">
                {{ $instituciones->total() }}
            </span>
        </p>

        @can('catalogos.crear')
            <x-primary-button type="button" x-data @click="$dispatch('catalog-create')">
                <x-heroicon-o-plus class="h-4 w-4 mr-1.5 -ml-0.5" />
                Nueva institución
            </x-primary-button>
        @endcan
    </div>

    <div x-data="{
            editing: {{ $errors->any() && old('_editing') == '1' ? 'true' : 'false' }},
            editId: '{{ old('_edit_id', '') }}',
            nombre: {{ Js::from(old('nombre', '')) }},
            formAction: '{{ url('instituciones') }}',

            openCreate() {
                this.editing = false;
                this.editId = null;
                this.nombre = '';
                this.formAction = '{{ url('instituciones') }}';
                $dispatch('open-modal', 'catalog-form');
            },
            openEdit(id, nombre) {
                this.editing = true;
                this.editId = id;
                this.nombre = nombre;
                this.formAction = '{{ url('instituciones') }}/' + id;
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
                    <x-table-row striped
                        @click="if (!$event.target.closest('a, button')) window.location.href = '{{ url('instituciones/' . $institucion->id) }}'"
                        style="cursor: pointer"
                    >
                        <x-table-cell>
                            <a href="{{ url('instituciones/' . $institucion->id) }}"
                               class="font-medium text-gray-900 hover:text-primary transition-colors">
                                {{ $institucion->nombre }}
                            </a>
                        </x-table-cell>

                        <x-table-actions>
                            @can('catalogos.editar')
                                <button type="button"
                                    @click="openEdit({{ $institucion->id }}, {{ Js::from($institucion->nombre) }})"
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
                            :action="url('instituciones/' . $institucion->id)"
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
        </x-table>

        @if ($instituciones->hasPages())
            <div class="mt-4">
                {{ $instituciones->links() }}
            </div>
        @endif

        <x-catalog-form-modal entity-label="institución" :max-length="255" />
    </div>
</x-app-layout>
