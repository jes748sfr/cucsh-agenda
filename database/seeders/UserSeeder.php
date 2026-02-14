<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Crear usuarios de prueba para cada rol.
     * El usuario administrador se crea en RolesAndPermissionsSeeder.
     */
    public function run(): void
    {
        $editor = User::create([
            'name' => 'Editor CUCSH',
            'email' => 'editor@cucsh.udg.mx',
            'password' => Hash::make('password'),
        ]);
        $editor->assignRole('editor');

        $consultor = User::create([
            'name' => 'Consultor CUCSH',
            'email' => 'consultor@cucsh.udg.mx',
            'password' => Hash::make('password'),
        ]);
        $consultor->assignRole('consultor');
    }
}