<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determina si el usuario puede ver el listado de usuarios.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('usuarios.ver');
    }

    /**
     * Determina si el usuario puede ver un usuario.
     */
    public function view(User $user, User $model): bool
    {
        return $user->can('usuarios.ver');
    }

    /**
     * Determina si el usuario puede crear usuarios.
     */
    public function create(User $user): bool
    {
        return $user->can('usuarios.crear');
    }

    /**
     * Determina si el usuario puede actualizar un usuario.
     * Cuenta raiz (id=1) no puede ser modificada.
     */
    public function update(User $user, User $model): bool
    {
        return $user->can('usuarios.editar')
            && $model->id !== 1;
    }

    /**
     * Determina si el usuario puede eliminar un usuario.
     * Cuenta raiz (id=1) no puede ser eliminada.
     * Un usuario no puede eliminarse a si mismo.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->can('usuarios.eliminar')
            && $model->id !== 1
            && $user->id !== $model->id;
    }
}
