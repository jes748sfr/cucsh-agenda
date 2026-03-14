<x-app-layout>
    <x-slot name="header">
        <x-breadcrumb :items="[
            ['label' => 'Instituciones', 'url' => route('instituciones.index')],
            ['label' => $institucion->nombre],
        ]" />
    </x-slot>

    <div class="max-w-3xl"
         x-data="{
            editing: true,
            editId: '{{ $institucion->id }}',
            nombre: {{ Js::from($institucion->nombre) }},
            formAction: '{{ url('instituciones/' . $institucion->id) }}'
         }"
         @if($errors->any())
         x-init="$nextTick(() => $dispatch('open-modal', 'catalog-form'))"
         @endif
    >
        <div class="rounded-lg bg-white p-6 shadow-sm border border-gray-200">
            <dl class="divide-y divide-gray-100">
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $institucion->nombre }}</dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Creado</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $institucion->created_at->translatedFormat('d M Y, H:i') }}</dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Actualizado</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $institucion->updated_at->translatedFormat('d M Y, H:i') }}</dd>
                </div>
            </dl>

            <div class="mt-6 flex items-center gap-3 border-t border-gray-100 pt-6">
                @can('catalogos.editar')
                    <x-primary-button type="button"
                        @click="$dispatch('open-modal', 'catalog-form')">
                        <x-heroicon-o-pencil-square class="h-4 w-4 mr-1.5" />
                        Editar
                    </x-primary-button>
                @endcan

                @can('catalogos.eliminar')
                    <x-danger-button type="button"
                        x-on:click="$dispatch('open-modal', 'delete-record')">
                        <x-heroicon-o-trash class="h-4 w-4 mr-1.5" />
                        Eliminar
                    </x-danger-button>
                @endcan
            </div>
        </div>

        @can('catalogos.editar')
            <x-catalog-form-modal entity-label="institución" :max-length="255" />
        @endcan

        @can('catalogos.eliminar')
            <x-confirm-delete-modal
                name="delete-record"
                :action="url('instituciones/' . $institucion->id)"
                title="Eliminar institución"
                :description="'Se eliminará «' . $institucion->nombre . '». Esta acción no se puede deshacer.'"
            />
        @endcan
    </div>
</x-app-layout>
