<?php

namespace Database\Seeders;

use App\Models\Administracion;
use Illuminate\Database\Seeder;

class AdministracionSeeder extends Seeder
{
    public function run(): void
    {
        $administraciones = [
            ['id' => 1, 'nombre' => 'Global'],
            ['id' => 2, 'nombre' => 'Administrativo'],
            ['id' => 3, 'nombre' => 'Externo'],
        ];

        foreach ($administraciones as $admin) {
            Administracion::create($admin);
        }
    }
}
