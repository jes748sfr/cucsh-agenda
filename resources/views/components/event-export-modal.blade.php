@props([
    'name' => 'event-export',
    'entityLabel' => 'exportar',
    'maxLength' => 255,
])

<x-modal :name="$name" maxWidth="md" focusable>
    <form method="GET" action="{{ route('eventos.export') }}" class="p-6" x-data="{ fechaInicio: '', fechaFin: '' }" @submit="$dispatch('close')">
        <div class="p-6 space-y-4">

            <h2 class="text-lg font-semibold text-gray-900">
                Exportar eventos
            </h2>

            {{-- Fecha inicio --}}
            <div>
                <label class="text-sm text-gray-600">Fecha inicio</label>

                <div class="flex gap-2">
                    <div class="flex-1">
                        <input type="date" name="fecha_inicio"
                            x-model="fechaInicio"
                            class="w-full rounded-md border-gray-300 text-sm">
                    </div>

                    <div class="flex items-center w-20 rounded-md border border-gray-300">
                        <button type="button"
                            class="w-full h-full text-xs text-gray-500 text-center"
                            @click="fechaInicio = ''">
                            Limpiar inicio
                        </button>
                    </div>
                </div>
            </div>

            {{-- Fecha fin --}}
            <div>
                <label class="text-sm text-gray-600">Fecha fin</label>

                <div class="flex gap-2">
                    <div class="flex-1">
                        <input type="date" name="fecha_fin"
                            x-model="fechaFin"
                            class="w-full rounded-md border-gray-300 text-sm">
                    </div>

                    <div class="flex items-center w-20 rounded-md border border-gray-300">
                        <button type="button"
                            class="w-full h-full text-xs text-gray-500 text-center"
                            @click="fechaFin = ''">
                            Limpiar fin
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <div class="px-6 pb-6 flex justify-end gap-3">
            <x-secondary-button type="button" x-on:click="$dispatch('close')">
                Cancelar
            </x-secondary-button>

            <x-primary-button type="submit">
                Exportar
            </x-primary-button>
        </div>
    </form>
</x-modal>
