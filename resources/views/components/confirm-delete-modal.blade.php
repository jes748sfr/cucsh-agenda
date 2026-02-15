@props([
    'name',
    'action' => '',
    'title' => '¿Eliminar este registro?',
    'description' => 'Esta acción no se puede deshacer.',
    'maxWidth' => 'md',
])

<x-modal :name="$name" :maxWidth="$maxWidth" focusable>
    <form method="POST" action="{{ $action }}">
        @csrf
        @method('DELETE')

        <div class="p-6">
            {{-- Icono --}}
            <div class="mb-4 inline-flex items-center justify-center h-10 w-10 rounded-full bg-red-50">
                <x-heroicon-o-trash class="h-5 w-5 text-danger" aria-hidden="true" />
            </div>

            {{-- Título --}}
            <h3 class="text-lg font-semibold text-gray-900">
                {{ $title }}
            </h3>

            {{-- Descripción --}}
            <p class="mt-1 text-sm text-gray-500">
                {{ $description }}
            </p>

            {{-- Botones --}}
            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Cancelar
                </x-secondary-button>

                <x-danger-button>
                    Eliminar
                </x-danger-button>
            </div>
        </div>
    </form>
</x-modal>
