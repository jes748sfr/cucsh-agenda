<?php

namespace App\Policies;

use App\Models\Ubicacion;
use App\Models\User;

class UbicacionPolicy
{
    /**
     * Determina si el usuario puede ver el listado de ubicaciones.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('catalogos.ver');
    }

    /**
     * Determina si el usuario puede ver una ubicación.
     */
    public function view(User $user, Ubicacion $ubicacion): bool
    {
        return $user->can('catalogos.ver');
    }

    /**
     * Determina si el usuario puede crear ubicaciones.
     */
    public function create(User $user): bool
    {
        return $user->can('catalogos.crear');
    }

    /**
     * Determina si el usuario puede actualizar una ubicación.
     */
    public function update(User $user, Ubicacion $ubicacion): bool
    {
        return $user->can('catalogos.editar');
    }

    /**
     * Determina si el usuario puede eliminar una ubicación.
     */
    public function delete(User $user, Ubicacion $ubicacion): bool
    {
        return $user->can('catalogos.eliminar');
    }
}
