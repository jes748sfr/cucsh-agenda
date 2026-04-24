<?php

namespace Database\Seeders;

use App\Models\Ubicacion;
use Illuminate\Database\Seeder;

class UbicacionSeeder extends Seeder
{
    public function run(): void
    {
        $ubicaciones = [
            // Auditorios
            ['nombre' => 'Auditorio Rosario Castellanos', 'institucion_id' => 1, 'activo' => true, 'color' => '#078418', 'capacidad' => 280],
            ['nombre' => 'Auditorio 1  Ed. H', 'institucion_id' => 1, 'activo' => true, 'color' => '#A3A32A', 'capacidad' => 100],
            ['nombre' => 'Auditorio 2 Ed. H', 'institucion_id' => 1, 'activo' => true, 'color' => '#A3A32A', 'capacidad' => 100],
            ['nombre' => 'Sala Fernándo Carlos Vevia Romero', 'institucion_id' => 1, 'activo' => true, 'color' => '#7A1301', 'capacidad' => 65],
            ['nombre' => 'Sala Margarita Martin Montoro', 'institucion_id' => 1, 'activo' => true, 'color' => '#5C027A', 'capacidad' => 60],
            ['nombre' => 'Sala Fernándo Pozos Ponce', 'institucion_id' => 1, 'activo' => true, 'color' => '#F09282', 'capacidad' => 35],
            ['nombre' => 'Sala Dr. Jesús Gómez Fregoso', 'institucion_id' => 1, 'activo' => true, 'color' => '#6056EF', 'capacidad' => 25],
            ['nombre' => 'Sala de Maestros Piso 3', 'institucion_id' => 1, 'activo' => true, 'color' => '#A3A32C', 'capacidad' => 20],
            ['nombre' => 'Sala Juárez', 'institucion_id' => 1, 'activo' => true, 'color' => '#82D1AD', 'capacidad' => 16],
        ];

        foreach ($ubicaciones as $ubicacion) {
            Ubicacion::create($ubicacion);
        }
    }
}
