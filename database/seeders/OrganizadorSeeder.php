<?php

namespace Database\Seeders;

use App\Models\Organizador;
use Illuminate\Database\Seeder;

class OrganizadorSeeder extends Seeder
{
    public function run(): void
    {
        $organizadores = [
            [
                'nombre' => 'Coordinación de Investigación',
                'tel' => '3336175432',
                'email' => 'investigacion@cucsh.udg.mx',
                'administracion_id' => 1, // Global
                'activo' => true,
            ],
            [
                'nombre' => 'Coordinación Académica',
                'tel' => '3336175433',
                'email' => 'academica@cucsh.udg.mx',
                'administracion_id' => 1, // Global
                'activo' => true,
            ],
            [
                'nombre' => 'Secretaría Administrativa',
                'tel' => '3336175434',
                'email' => 'administrativa@cucsh.udg.mx',
                'administracion_id' => 2, // Administrativo
                'activo' => true,
            ],
            [
                'nombre' => 'Asociación de Estudiantes',
                'tel' => null,
                'email' => 'estudiantes@cucsh.udg.mx',
                'administracion_id' => 3, // Externo
                'activo' => true,
            ],
        ];

        foreach ($organizadores as $organizador) {
            Organizador::create($organizador);
        }
    }
}
