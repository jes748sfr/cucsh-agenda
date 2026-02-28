<?php

namespace Database\Seeders;

use App\Models\Evento;
use Illuminate\Database\Seeder;

class EventoSeeder extends Seeder
{
    public function run(): void
    {
        $eventos = [
            [
                'nombre' => 'Conferencia de Inteligencia Artificial',
                'eventos_tipo_id' => 1,  // Conferencia
                'organizador_id' => 1,   // Mtra. Patricia Rosas Chávez
                'ubicacion_id' => 1,     // Auditorio Carlos Ramírez Ladewig
                'activo' => true,
                'notas_cta' => 'Requiere proyector y sistema de audio.',
                'notas_servicios' => null,
                'institucion_id' => 1,   // Belenes
                'usuario_id' => 1,       // admin
            ],
            [
                'nombre' => 'Taller de Escritura Académica',
                'eventos_tipo_id' => 2,  // Taller
                'organizador_id' => 2,   // Dr. Carlos Mendoza López
                'ubicacion_id' => 5,     // Aula 1 Edificio A
                'activo' => true,
                'notas_cta' => null,
                'notas_servicios' => 'Coffee break para 30 personas.',
                'institucion_id' => 1,   // Belenes
                'usuario_id' => 1,       // admin
            ],
            [
                'nombre' => 'Seminario de Investigación Social',
                'eventos_tipo_id' => 3,  // Seminario
                'organizador_id' => 1,   // Mtra. Patricia Rosas Chávez
                'ubicacion_id' => 8,     // Sala de Juntas Rectoría
                'activo' => true,
                'notas_cta' => 'Sesión con cupo limitado a 20 asistentes.',
                'notas_servicios' => null,
                'institucion_id' => 1,   // Belenes
                'usuario_id' => 1,       // admin
            ],
            [
                'nombre' => 'Ceremonia de Graduación Marzo 2026',
                'eventos_tipo_id' => 10, // Ceremonia
                'organizador_id' => 3,   // Lic. María Elena Gutiérrez
                'ubicacion_id' => 1,     // Auditorio Carlos Ramírez Ladewig
                'activo' => true,
                'notas_cta' => 'Coordinación con Rectoría para protocolo.',
                'notas_servicios' => 'Montaje de escenario y sistema de sonido.',
                'institucion_id' => 1,   // Belenes
                'usuario_id' => 1,       // admin
            ],
        ];

        foreach ($eventos as $evento) {
            Evento::create($evento);
        }
    }
}
