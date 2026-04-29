<?php

namespace App\Policies;

use App\Models\EventoVal;
use App\Models\User;

class EventoPolicy
{
    /**
     * Determina si el usuario puede ver el listado de eventos.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('eventosVal.ver');
    }

    /**
     * Determina si el usuario puede ver un evento específico.
     */
    public function view(User $user, EventoVal $evento): bool
    {
        return $user->can('eventosVal.ver');
    }

    /**
     * Determina si el usuario puede crear eventos.
     */
    public function create(User $user): bool
    {
        return $user->can('eventosVal.crear');
    }

    /**
     * Determina si el usuario puede actualizar el evento.
     * Requiere permiso y ser el creador del evento.
     */
    public function update(User $user, EventoVal $evento): bool
    {
        return $user->can('eventosVal.editar')
            && $user->id === $evento->usuario_id;
    }

    /**
     * Determina si el usuario puede eliminar el evento.
     * Requiere permiso y ser el creador del evento.
     */
    public function delete(User $user, EventoVal $evento): bool
    {
        return $user->can('eventosVal.eliminar')
            && $user->id === $evento->usuario_id;
    }
}
