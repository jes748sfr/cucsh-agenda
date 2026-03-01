<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EventoFecha;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventoApiController extends Controller
{
    /**
     * Listado de eventos para FullCalendar.
     * Endpoint publico con throttle del middleware api.
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'start' => ['required', 'date'],
            'end' => ['required', 'date'],
            'institucion_id' => ['nullable', 'integer', 'exists:instituciones,id'],
            'eventos_tipo_id' => ['nullable', 'integer', 'exists:eventos_tipos,id'],
            'administracion_id' => ['nullable', 'integer', 'exists:administraciones,id'],
            'administracion_ids' => ['nullable', 'array'],
            'administracion_ids.*' => ['integer', 'exists:administraciones,id'],
        ]);

        $fechas = EventoFecha::with([
                'evento.eventoTipo',
                'evento.institucion',
                'evento.organizador.administracion',
                'evento.ubicacionRel',
            ])
            ->whereBetween('fecha', [$request->date('start'), $request->date('end')])
            ->whereHas('evento', function ($query) use ($request) {
                $query->where('activo', true);

                if ($request->filled('institucion_id')) {
                    $query->where('institucion_id', $request->integer('institucion_id'));
                }

                if ($request->filled('eventos_tipo_id')) {
                    $query->where('eventos_tipo_id', $request->integer('eventos_tipo_id'));
                }

                // Filtrar por administracion(es) del organizador
                if ($request->filled('administracion_ids')) {
                    $ids = $request->input('administracion_ids');
                    $query->whereHas('organizador', function ($q) use ($ids) {
                        $q->whereIn('administracion_id', $ids);
                    });
                } elseif ($request->filled('administracion_id')) {
                    $query->whereHas('organizador', function ($q) use ($request) {
                        $q->where('administracion_id', $request->integer('administracion_id'));
                    });
                }
            })
            ->get();

        $events = $fechas->map(function (EventoFecha $fecha) {
            $evento = $fecha->evento;

            return [
                'id' => $fecha->id,
                'title' => $evento->nombre,
                'start' => $fecha->fecha->format('Y-m-d') . 'T' . $fecha->hora_inicio->format('H:i:s'),
                'end' => $fecha->fecha->format('Y-m-d') . 'T' . $fecha->hora_fin->format('H:i:s'),
                // Futuro: $evento->color cuando se implemente el campo en el formulario
                // 'backgroundColor' => $evento->color,
                'extendedProps' => [
                    'evento_id' => $evento->id,
                    'institucion' => $evento->institucion->nombre,
                    'tipo' => $evento->eventoTipo->nombre,
                    'organizador' => $evento->organizador->nombre,
                    'administracion' => $evento->organizador->administracion?->nombre,
                    'ubicacion' => $evento->ubicacionRel?->nombre,
                    'notas_cta' => $evento->notas_cta,
                ],
            ];
        });

        return response()->json($events);
    }
}
