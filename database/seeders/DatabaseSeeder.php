<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            EventoTipoSeeder::class,
            InstitucionSeeder::class,
            AdministracionSeeder::class,
        ]);

        // Roles, permisos y usuario administrador
        $this->call(RolesAndPermissionsSeeder::class);

        // Usuarios de prueba (editor, consultor)
        $this->call(UserSeeder::class);

        // Tablas con dependencias
        $this->call([
            OrganizadorSeeder::class,
            EventoSeeder::class,
            EventoFechaSeeder::class,
        ]);
    }
}
