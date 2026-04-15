@props([
    'name' => 'catalog-form',
    'entityLabel' => 'registro',
    'maxLength' => 255,
])

<x-modal :name="$name" maxWidth="md" focusable>
    <form :action="formAction" method="POST" class="p-6">
        @csrf
        <template x-if="editing">
            <input type="hidden" name="_method" value="PUT">
        </template>
        <input type="hidden" name="_editing" :value="editing ? '1' : '0'">
        <input type="hidden" name="_edit_id" :value="editId || ''">

        {{-- Icono --}}
        <div class="mb-4 inline-flex items-center justify-center h-10 w-10 rounded-full bg-primary/10">
            <x-heroicon-o-tag class="h-5 w-5 text-primary" aria-hidden="true" />
        </div>

        {{-- Título dinámico --}}
        <h3 class="text-lg font-semibold text-gray-900"
            x-text="editing ? 'Editar {{ $entityLabel }}' : 'Nuevo(a) {{ $entityLabel }}'">
        </h3>

        {{-- Campo nombre --}}
        <div class="mt-4">
            <x-input-label for="catalog-nombre" value="Nombre" />
            <x-text-input
                id="catalog-nombre"
                name="nombre"
                type="text"
                class="mt-1 block w-full"
                x-model="nombre"
                maxlength="{{ $maxLength }}"
                required
            />
            <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
        </div>

        {{-- Botones --}}
        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button" x-on:click="$dispatch('close')">
                Cancelar
            </x-secondary-button>
            <x-primary-button x-text="editing ? 'Actualizar' : 'Crear'">
                Crear
            </x-primary-button>
        </div>
    </form>
</x-modal>
