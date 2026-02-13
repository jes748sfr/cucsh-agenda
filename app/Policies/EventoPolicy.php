<?php

namespace App\Policies;

use App\Models\Evento;
use App\Models\User;

class EventoPolicy
{
    /**
     * Determina si el usuario puede ver el listado de eventos.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('eventos.ver');
    }

    /**
     * Determina si el usuario puede ver un evento específico.
     */
    public function view(User $user, Evento $evento): bool
    {
        return $user->can('eventos.ver');
    }

    /**
     * Determina si el usuario puede crear eventos.
     */
    public function create(User $user): bool
    {
        return $user->can('eventos.crear');
    }

    /**
     * Determina si el usuario puede actualizar el evento.
     * Requiere permiso y ser el creador del evento.
     */
    public function update(User $user, Evento $evento): bool
    {
        return $user->can('eventos.editar')
            && $user->id === $evento->usuario_id;
    }

    /**
     * Determina si el usuario puede eliminar el evento.
     * Requiere permiso y ser el creador del evento.
     */
    public function delete(User $user, Evento $evento): bool
    {
        return $user->can('eventos.eliminar')
            && $user->id === $evento->usuario_id;
    }
}
