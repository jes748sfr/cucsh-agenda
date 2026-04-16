<?php

namespace App\Exports;

use App\Models\Evento;
use App\Models\EventoFecha;
use App\Models\EventoTipo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EventosExport implements FromCollection, WithMapping, WithHeadings
{

    protected $fechaInicio;
    protected $fechaFin;

    public function __construct($request)
    {
        $this->fechaInicio = $request->fecha_inicio;
        $this->fechaFin = $request->fecha_fin;
    }
    public function collection()
    {
        $query = Evento::with(
            'eventoTipo',
            'organizador',
            'institucion',
            'ubicacionRel',
            'usuario',
            'fechas'
        );

        if ($this->fechaInicio) {
            $query->whereHas('fechas', function ($q) {
                $q->whereDate('fecha', '>=', $this->fechaInicio);
            });
        }

        if ($this->fechaFin) {
            $query->whereHas('fechas', function ($q) {
                $q->whereDate('fecha', '<=', $this->fechaFin);
            });
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Tipo de evento',
            'Organizador',
            'Institución',
            'Ubicación',
            'Usuario',
            'Activo',
            'Fechas',
            'Horas'
        ];
    }

    public function map($evento): array
    {
        return [
            $evento->id,
            $evento->nombre,

            // 👇 relación eventoTipo
            $evento->eventoTipo->nombre ?? '',

            // 👇 otras relaciones
            $evento->organizador->nombre ?? '',
            $evento->institucion->nombre ?? '',
            $evento->ubicacionRel->nombre ?? '',
            $evento->usuario->name ?? '',

            $evento->activo ? 'Sí' : 'No',

            // fechas
            $evento->fechas->pluck('fecha')
                ->map(fn($f) => $f->format('d/m/Y'))
                ->join(', '),

            // horas
            $evento->fechas->map(function ($f) {
                return $f->hora_inicio . ' - ' . $f->hora_fin;
            })->join(', ')
        ];
    }
}
