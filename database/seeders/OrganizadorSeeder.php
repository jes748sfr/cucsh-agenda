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
                'nombre' => 'Mtra. Patricia Rosas Chávez',
                'tel' => '3336175432',
                'email' => 'patricia.rosas@cucsh.udg.mx',
                'administracion_id' => 1, // Global
                'activo' => true,
            ],
            [
                'nombre' => 'Dr. Carlos Mendoza López',
                'tel' => '3336175433',
                'email' => 'carlos.mendoza@cucsh.udg.mx',
                'administracion_id' => 1, // Global
                'activo' => true,
            ],
            [
                'nombre' => 'Lic. María Elena Gutiérrez',
                'tel' => '3336175434',
                'email' => 'maria.gutierrez@cucsh.udg.mx',
                'administracion_id' => 2, // Administrativo
                'activo' => true,
            ],
            [
                'nombre' => 'Mtro. Roberto Sánchez Vega',
                'tel' => null,
                'email' => 'roberto.sanchez@cucsh.udg.mx',
                'administracion_id' => 3, // Externo
                'activo' => true,
            ],
            [
                'nombre' => 'Dra. Laura Fernández Ríos',
                'tel' => '3336175440',
                'email' => 'laura.fernandez@cucsh.udg.mx',
                'administracion_id' => 2, // Administrativo
                'activo' => false,
            ],
        ];

        foreach ($organizadores as $organizador) {
            Organizador::create($organizador);
        }
    }
}
