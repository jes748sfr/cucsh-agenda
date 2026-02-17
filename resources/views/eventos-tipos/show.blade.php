<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('eventos-tipos.index') }}"
               class="text-gray-400 hover:text-gray-600 transition"
               title="Volver al listado">
                <x-heroicon-o-arrow-left class="h-5 w-5" />
            </a>
            <h2 class="text-lg font-semibold text-gray-900">{{ $eventoTipo->nombre }}</h2>
        </div>
    </x-slot>

    <div class="max-w-3xl"
         x-data="{
            editing: true,
            editId: '{{ $eventoTipo->id }}',
            nombre: {{ Js::from($eventoTipo->nombre) }},
            formAction: '{{ url('eventos-tipos/' . $eventoTipo->id) }}'
         }"
         @if($errors->any())
         x-init="$nextTick(() => $dispatch('open-modal', 'catalog-form'))"
         @endif
    >
        <div class="rounded-lg bg-white p-6 shadow-sm border border-gray-200">
            <dl class="divide-y divide-gray-100">
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $eventoTipo->nombre }}</dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Creado</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $eventoTipo->created_at->translatedFormat('d M Y, H:i') }}</dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Actualizado</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $eventoTipo->updated_at->translatedFormat('d M Y, H:i') }}</dd>
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
            <x-catalog-form-modal entity-label="tipo de evento" :max-length="100" />
        @endcan

        @can('catalogos.eliminar')
            <x-confirm-delete-modal
                name="delete-record"
                :action="url('eventos-tipos/' . $eventoTipo->id)"
                title="Eliminar tipo de evento"
                :description="'Se eliminará «' . $eventoTipo->nombre . '». Esta acción no se puede deshacer.'"
            />
        @endcan
    </div>
</x-app-layout>
