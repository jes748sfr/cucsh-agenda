<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Administraciones</h2>
            @can('catalogos.crear')
                <x-primary-button type="button" x-data @click="$dispatch('catalog-create')">
                    <x-heroicon-o-plus class="h-4 w-4 mr-1.5 -ml-0.5" />
                    Nueva administración
                </x-primary-button>
            @endcan
        </div>
    </x-slot>

    <div x-data="{
            editing: {{ $errors->any() && old('_editing') == '1' ? 'true' : 'false' }},
            editId: '{{ old('_edit_id', '') }}',
            nombre: {{ Js::from(old('nombre', '')) }},
            formAction: '{{ url('administraciones') }}',

            openCreate() {
                this.editing = false;
                this.editId = null;
                this.nombre = '';
                this.formAction = '{{ url('administraciones') }}';
                $dispatch('open-modal', 'catalog-form');
            },
            openEdit(id, nombre) {
                this.editing = true;
                this.editId = id;
                this.nombre = nombre;
                this.formAction = '{{ url('administraciones') }}/' + id;
                $dispatch('open-modal', 'catalog-form');
            }
        }"
        x-init="
            @if($errors->any())
                if (editing) {
                    formAction = '{{ url('administraciones') }}/' + editId;
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
                    <x-table-header align="center">Organizadores</x-table-header>
                    <x-table-header align="right">Acciones</x-table-header>
                </tr>
            </thead>

            <tbody>
                @forelse ($administraciones as $admin)
                    <x-table-row striped>
                        <x-table-cell>
                            <a href="{{ url('administraciones/' . $admin->id) }}"
                               class="font-medium text-gray-900 hover:text-primary transition-colors">
                                {{ $admin->nombre }}
                            </a>
                        </x-table-cell>

                        <x-table-cell align="center">
                            <x-badge color="primary" size="sm">{{ $admin->organizadores_count }}</x-badge>
                        </x-table-cell>

                        <x-table-actions>
                            @if (in_array($admin->id, [1, 2]))
                                <x-badge color="gray" size="sm">
                                    <span class="flex items-center gap-1">
                                        <x-heroicon-o-lock-closed class="h-3 w-3" />
                                        Protegido
                                    </span>
                                </x-badge>
                            @else
                                @can('catalogos.editar')
                                    <button type="button"
                                        @click="openEdit({{ $admin->id }}, {{ Js::from($admin->nombre) }})"
                                        class="text-gray-400 hover:text-primary transition"
                                        title="Editar">
                                        <x-heroicon-o-pencil-square class="h-5 w-5" />
                                    </button>
                                @endcan

                                @can('catalogos.eliminar')
                                    <button type="button"
                                        x-on:click="$dispatch('open-modal', 'delete-admin-{{ $admin->id }}')"
                                        class="text-gray-400 hover:text-danger transition"
                                        title="Eliminar">
                                        <x-heroicon-o-trash class="h-5 w-5" />
                                    </button>
                                @endcan
                            @endif
                        </x-table-actions>
                    </x-table-row>

                    @if (!in_array($admin->id, [1, 2]))
                        @can('catalogos.eliminar')
                            <x-confirm-delete-modal
                                name="delete-admin-{{ $admin->id }}"
                                :action="url('administraciones/' . $admin->id)"
                                title="Eliminar administración"
                                :description="'Se eliminará «' . $admin->nombre . '». Esta acción no se puede deshacer.'"
                            />
                        @endcan
                    @endif
                @empty
                    <x-table-row>
                        <td colspan="3" class="px-4 py-8 text-center">
                            <x-heroicon-o-building-office class="mx-auto h-10 w-10 text-gray-300" />
                            <p class="mt-2 text-sm text-gray-500">No hay administraciones registradas.</p>
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

        @if ($administraciones->hasPages())
            <div class="mt-4">
                {{ $administraciones->links() }}
            </div>
        @endif

        <x-catalog-form-modal entity-label="administración" :max-length="150" />
    </div>
</x-app-layout>
