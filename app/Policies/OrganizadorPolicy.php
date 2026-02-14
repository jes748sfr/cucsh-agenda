<?php

namespace App\Policies;

use App\Models\Organizador;
use App\Models\User;

class OrganizadorPolicy
{
    /**
     * Determina si el usuario puede ver el listado de organizadores.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('organizadores.ver');
    }

    /**
     * Determina si el usuario puede ver un organizador.
     */
    public function view(User $user, Organizador $organizador): bool
    {
        return $user->can('organizadores.ver');
    }

    /**
     * Determina si el usuario puede crear organizadores.
     */
    public function create(User $user): bool
    {
        return $user->can('organizadores.crear');
    }

    /**
     * Determina si el usuario puede actualizar un organizador.
     */
    public function update(User $user, Organizador $organizador): bool
    {
        return $user->can('organizadores.editar');
    }

    /**
     * Determina si el usuario puede eliminar un organizador.
     */
    public function delete(User $user, Organizador $organizador): bool
    {
        return $user->can('organizadores.eliminar');
    }
}
