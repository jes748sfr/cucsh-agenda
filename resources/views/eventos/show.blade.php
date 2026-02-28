@php
    $back      = url()->previous();
    $indexPath = rtrim(parse_url(route('eventos.index'), PHP_URL_PATH), '/');
    $backPath  = rtrim(parse_url($back, PHP_URL_PATH), '/');
    // Si viene del index de eventos o del dashboard → volver a esa página
    // Cualquier otra página → volver al index de eventos
    $backUrl = ($backPath === $indexPath || $backPath === rtrim(parse_url(route('dashboard'), PHP_URL_PATH), '/'))
        ? $back
        : route('eventos.index');
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3 min-w-0">
                <a href="{{ $backUrl }}"
                   class="text-gray-400 hover:text-gray-600 transition flex-shrink-0"
                   title="Volver">
                    <x-heroicon-o-arrow-left class="h-5 w-5" />
                </a>
                <h2 class="text-lg font-semibold text-gray-900 truncate">{{ $evento->nombre }}</h2>
            </div>

            <div class="flex items-center gap-2 ml-4 flex-shrink-0">
                @can('update', $evento)
                    <a href="{{ route('eventos.edit', $evento) }}">
                        <x-secondary-button>
                            <x-heroicon-o-pencil-square class="h-4 w-4 mr-1.5 -ml-0.5" />
                            Editar
                        </x-secondary-button>
                    </a>
                @endcan

                @can('delete', $evento)
                    <x-danger-button
                        type="button"
                        x-on:click="$dispatch('open-modal', 'delete-evento')">
                        <x-heroicon-o-trash class="h-4 w-4 mr-1.5 -ml-0.5" />
                        Eliminar
                    </x-danger-button>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl space-y-6">

        {{-- Información general --}}
        <div class="rounded-lg bg-white shadow-sm border border-gray-200 p-6">
            <dl class="divide-y divide-gray-100">

                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 font-medium">{{ $evento->nombre }}</dd>
                </div>

                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Tipo de evento</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $evento->eventoTipo->nombre }}</dd>
                </div>

                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Institución</dt>
                    <dd class="mt-1 text-sm sm:col-span-2 sm:mt-0">
                        <a href="{{ url('instituciones/' . $evento->institucion->id) }}"
                           class="text-primary hover:underline">
                            {{ $evento->institucion->nombre }}
                        </a>
                    </dd>
                </div>

                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Organizador</dt>
                    <dd class="mt-1 text-sm sm:col-span-2 sm:mt-0">
                        <a href="{{ route('organizadores.show', $evento->organizador) }}"
                           class="text-primary hover:underline">
                            {{ $evento->organizador->nombre }}
                        </a>
                        @if ($evento->organizador->administracion)
                            <span class="text-gray-400 ml-1">— {{ $evento->organizador->administracion->nombre }}</span>
                        @endif
                    </dd>
                </div>

                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Ubicación</dt>
                    <dd class="mt-1 text-sm sm:col-span-2 sm:mt-0">
                        @if ($evento->ubicacionRel)
                            <a href="{{ url('ubicaciones/' . $evento->ubicacionRel->id) }}"
                               class="text-primary hover:underline">
                                {{ $evento->ubicacionRel->nombre }}
                            </a>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </dd>
                </div>

                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Estado</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        @if ($evento->activo)
                            <x-badge color="success" dot>Activo</x-badge>
                        @else
                            <x-badge color="gray" dot>Inactivo</x-badge>
                        @endif
                    </dd>
                </div>

                @if ($evento->notas_cta)
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Notas convocatoria</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 whitespace-pre-line">{{ $evento->notas_cta }}</dd>
                    </div>
                @endif

                @if ($evento->notas_servicios)
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">Notas servicios</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 whitespace-pre-line">{{ $evento->notas_servicios }}</dd>
                    </div>
                @endif

                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Registrado</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        {{ $evento->created_at->translatedFormat('d M Y, H:i') }}
                    </dd>
                </div>

                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-gray-500">Actualizado</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        {{ $evento->updated_at->translatedFormat('d M Y, H:i') }}
                    </dd>
                </div>

            </dl>
        </div>

        {{-- Fechas del evento --}}
        <div>
            <h3 class="text-base font-semibold text-gray-900 mb-3">
                Fechas programadas
                <span class="ml-2 inline-flex items-center rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-medium text-primary">
                    {{ $evento->fechas->count() }}
                </span>
            </h3>

            @if ($evento->fechas->isNotEmpty())
                <div class="rounded-lg border border-gray-200 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inicio</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fin</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($evento->fechas->sortBy('fecha') as $fecha)
                                <tr class="{{ $loop->even ? 'bg-gray-50/50' : '' }}">
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $fecha->fecha->translatedFormat('l, d \d\e M \d\e Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700 font-mono">
                                        {{ $fecha->hora_inicio->format('H:i') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700 font-mono">
                                        {{ $fecha->hora_fin->format('H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-sm text-gray-400 italic">No hay fechas registradas para este evento.</p>
            @endif
        </div>

    </div>

    @can('delete', $evento)
        <x-confirm-delete-modal
            name="delete-evento"
            :action="route('eventos.destroy', $evento)"
            title="Eliminar evento"
            :description="'Se eliminará «' . $evento->nombre . '» y todas sus fechas. Esta acción no se puede deshacer.'"
        />
    @endcan

</x-app-layout>
