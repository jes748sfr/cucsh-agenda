<?php

namespace Database\Seeders;

use App\Models\EventoTipo;
use Illuminate\Database\Seeder;

class EventoTipoSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            'Conferencia',
            'Taller',
            'Seminario',
            'Congreso',
            'Coloquio',
            'Foro',
            'Simposío',
            'Curso',
            'Diplomado',
            'Ceremonia',
            'Reunión',
            'Exposición',
            'Presentación de libro',
            'Examen profesional',
        ];

        foreach ($tipos as $tipo) {
            EventoTipo::create(['nombre' => $tipo]);
        }
    }
}
