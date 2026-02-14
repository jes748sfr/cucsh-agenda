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
            'institucion_id' => ['sometimes', 'integer', 'exists:instituciones,id'],
            'eventos_tipo_id' => ['sometimes', 'integer', 'exists:eventos_tipos,id'],
        ]);

        $fechas = EventoFecha::with([
                'evento.eventoTipo',
                'evento.institucion',
                'evento.organizador.administracion',
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
            })
            ->get();

        $events = $fechas->map(function (EventoFecha $fecha) {
            $evento = $fecha->evento;

            return [
                'id' => $fecha->id,
                'title' => $evento->nombre,
                'start' => $fecha->fecha->format('Y-m-d') . 'T' . $fecha->hora_inicio->format('H:i:s'),
                'end' => $fecha->fecha->format('Y-m-d') . 'T' . $fecha->hora_fin->format('H:i:s'),
                'backgroundColor' => '#202945',
                'borderColor' => '#B12028',
                'extendedProps' => [
                    'institucion' => $evento->institucion->nombre,
                    'tipo' => $evento->eventoTipo->nombre,
                    'organizador' => $evento->organizador->nombre,
                    'ubicacion' => $evento->ubicacion,
                ],
            ];
        });

        return response()->json($events);
    }
}
