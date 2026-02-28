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
            ['nombre' => 'Auditorio Carlos Ramírez Ladewig', 'institucion_id' => 1, 'activo' => true],
            ['nombre' => 'Auditorio Adalberto Navarro Sánchez', 'institucion_id' => 1, 'activo' => true],
            ['nombre' => 'Auditorio Salvador Allende', 'institucion_id' => 1, 'activo' => true],

            // Aulas
            ['nombre' => 'Aula Magna', 'institucion_id' => 1, 'activo' => true],
            ['nombre' => 'Aula 1 Edificio A', 'institucion_id' => 1, 'activo' => true],
            ['nombre' => 'Aula 2 Edificio A', 'institucion_id' => 1, 'activo' => true],
            ['nombre' => 'Aula 1 Edificio B', 'institucion_id' => 1, 'activo' => true],

            // Espacios comunes
            ['nombre' => 'Sala de Juntas Rectoría', 'institucion_id' => 1, 'activo' => true],
            ['nombre' => 'Sala de Videoconferencias', 'institucion_id' => 1, 'activo' => true],
            ['nombre' => 'Explanada Principal', 'institucion_id' => 1, 'activo' => true],
            ['nombre' => 'Biblioteca Central', 'institucion_id' => 1, 'activo' => true],

            // Inactiva para pruebas
            ['nombre' => 'Salón de Usos Múltiples (en remodelación)', 'institucion_id' => 1, 'activo' => false],
        ];

        foreach ($ubicaciones as $ubicacion) {
            Ubicacion::create($ubicacion);
        }
    }
}
