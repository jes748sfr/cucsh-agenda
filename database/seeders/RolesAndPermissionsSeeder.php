<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar cache de Spatie antes de crear roles/permisos.
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Eventos
        Permission::create(['name' => 'eventos.ver']);
        Permission::create(['name' => 'eventos.crear']);
        Permission::create(['name' => 'eventos.editar']);
        Permission::create(['name' => 'eventos.eliminar']);

        // Catalogos (tipos evento, instituciones, administraciones)
        Permission::create(['name' => 'catalogos.ver']);
        Permission::create(['name' => 'catalogos.crear']);
        Permission::create(['name' => 'catalogos.editar']);
        Permission::create(['name' => 'catalogos.eliminar']);

        // Organizadores
        Permission::create(['name' => 'organizadores.ver']);
        Permission::create(['name' => 'organizadores.crear']);
        Permission::create(['name' => 'organizadores.editar']);
        Permission::create(['name' => 'organizadores.eliminar']);

        // Usuarios
        Permission::create(['name' => 'usuarios.ver']);
        Permission::create(['name' => 'usuarios.crear']);
        Permission::create(['name' => 'usuarios.editar']);
        Permission::create(['name' => 'usuarios.eliminar']);

        // Reportes
        Permission::create(['name' => 'reportes.generar']);

        // Limpiar cache despues de crear permisos.
        // Requerido si se usa WithoutModelEvents.
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Administrador: acceso total
        Role::create(['name' => 'administrador'])
            ->givePermissionTo(Permission::all());

        // Editor: CRUD eventos y organizadores, ver catalogos, reportes
        Role::create(['name' => 'editor'])
            ->givePermissionTo([
                'eventos.ver',
                'eventos.crear',
                'eventos.editar',
                'eventos.eliminar',
                'catalogos.ver',
                'organizadores.ver',
                'organizadores.crear',
                'organizadores.editar',
                'reportes.generar',
            ]);

        // Consultor: solo lectura y reportes
        Role::create(['name' => 'consultor'])
            ->givePermissionTo([
                'eventos.ver',
                'catalogos.ver',
                'organizadores.ver',
                'reportes.generar',
            ]);

        // -------------------------------------------------------
        // Usuario administrador inicial
        // -------------------------------------------------------
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@cucsh.udg.mx',
            'password' => Hash::make('"C4mb14rEnPr0ducc10n!"'),
        ]);

        $admin->assignRole('administrador');
    }
}
