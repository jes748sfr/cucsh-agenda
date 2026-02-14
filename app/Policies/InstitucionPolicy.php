<?php

namespace App\Policies;

use App\Models\Institucion;
use App\Models\User;

class InstitucionPolicy
{
    /**
     * Determina si el usuario puede ver el listado de instituciones.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('catalogos.ver');
    }

    /**
     * Determina si el usuario puede ver una institucion.
     */
    public function view(User $user, Institucion $institucion): bool
    {
        return $user->can('catalogos.ver');
    }

    /**
     * Determina si el usuario puede crear instituciones.
     */
    public function create(User $user): bool
    {
        return $user->can('catalogos.crear');
    }

    /**
     * Determina si el usuario puede actualizar una institucion.
     */
    public function update(User $user, Institucion $institucion): bool
    {
        return $user->can('catalogos.editar');
    }

    /**
     * Determina si el usuario puede eliminar una institucion.
     */
    public function delete(User $user, Institucion $institucion): bool
    {
        return $user->can('catalogos.eliminar');
    }
}
