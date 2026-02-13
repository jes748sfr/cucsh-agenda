<?php

namespace Database\Seeders;

use App\Models\EventoFecha;
use Illuminate\Database\Seeder;

class EventoFechaSeeder extends Seeder
{
    public function run(): void
    {
        $fechas = [
            // Evento 1: Conferencia IA — 1 fecha
            [
                'evento_id' => 1,
                'fecha' => '2026-03-10',
                'hora_inicio' => '10:00',
                'hora_fin' => '12:00',
            ],

            // Evento 2: Taller Escritura — 3 fechas consecutivas
            [
                'evento_id' => 2,
                'fecha' => '2026-03-12',
                'hora_inicio' => '09:00',
                'hora_fin' => '11:00',
            ],
            [
                'evento_id' => 2,
                'fecha' => '2026-03-13',
                'hora_inicio' => '09:00',
                'hora_fin' => '11:00',
            ],
            [
                'evento_id' => 2,
                'fecha' => '2026-03-14',
                'hora_inicio' => '09:00',
                'hora_fin' => '11:00',
            ],

            // Evento 3: Seminario Investigación — 2 fechas
            [
                'evento_id' => 3,
                'fecha' => '2026-03-17',
                'hora_inicio' => '16:00',
                'hora_fin' => '18:00',
            ],
            [
                'evento_id' => 3,
                'fecha' => '2026-03-18',
                'hora_inicio' => '16:00',
                'hora_fin' => '18:00',
            ],

            // Evento 4: Ceremonia Graduación — 1 fecha
            [
                'evento_id' => 4,
                'fecha' => '2026-03-20',
                'hora_inicio' => '11:00',
                'hora_fin' => '13:00',
            ],
        ];

        foreach ($fechas as $fecha) {
            EventoFecha::create($fecha);
        }
    }
}
