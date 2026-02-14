<?php

namespace App\Policies;

use App\Models\EventoTipo;
use App\Models\User;

class EventoTipoPolicy
{
    /**
     * Determina si el usuario puede ver el listado de tipos de evento.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('catalogos.ver');
    }

    /**
     * Determina si el usuario puede ver un tipo de evento.
     */
    public function view(User $user, EventoTipo $eventoTipo): bool
    {
        return $user->can('catalogos.ver');
    }

    /**
     * Determina si el usuario puede crear tipos de evento.
     */
    public function create(User $user): bool
    {
        return $user->can('catalogos.crear');
    }

    /**
     * Determina si el usuario puede actualizar un tipo de evento.
     */
    public function update(User $user, EventoTipo $eventoTipo): bool
    {
        return $user->can('catalogos.editar');
    }

    /**
     * Determina si el usuario puede eliminar un tipo de evento.
     */
    public function delete(User $user, EventoTipo $eventoTipo): bool
    {
        return $user->can('catalogos.eliminar');
    }
}
