<?php

namespace App\Policies;

use App\Models\Administracion;
use App\Models\User;

class AdministracionPolicy
{
    /**
     * Determina si el usuario puede ver el listado de administraciones.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('catalogos.ver');
    }

    /**
     * Determina si el usuario puede ver una administracion.
     */
    public function view(User $user, Administracion $administracion): bool
    {
        return $user->can('catalogos.ver');
    }

    /**
     * Determina si el usuario puede crear administraciones.
     */
    public function create(User $user): bool
    {
        return $user->can('catalogos.crear');
    }

    /**
     * Determina si el usuario puede actualizar una administracion.
     * Registros protegidos: Global (id=1) y Administrativo (id=2).
     */
    public function update(User $user, Administracion $administracion): bool
    {
        if (in_array($administracion->id, [1, 2])) {
            return false;
        }

        return $user->can('catalogos.editar');
    }

    /**
     * Determina si el usuario puede eliminar una administracion.
     * Registros protegidos: Global (id=1) y Administrativo (id=2).
     */
    public function delete(User $user, Administracion $administracion): bool
    {
        if (in_array($administracion->id, [1, 2])) {
            return false;
        }

        return $user->can('catalogos.eliminar');
    }
}
