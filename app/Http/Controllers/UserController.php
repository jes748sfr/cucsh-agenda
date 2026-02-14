<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Listado paginado de usuarios con sus roles.
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);

        $users = User::with('roles')
            ->orderBy('name')
            ->paginate(15);

        return view('users.index', compact('users'));
    }

    /**
     * Formulario de creacion de usuario.
     */
    public function create()
    {
        $this->authorize('create', User::class);

        $roles = ['editor', 'consultor'];

        return view('users.create', compact('roles'));
    }

    /**
     * Almacenar nuevo usuario y asignar rol.
     */
    public function store(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('users.index')
            ->with('success', __('El usuario se creo correctamente.'));
    }

    /**
     * Detalle de un usuario con sus roles.
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        $user->load('roles');

        return view('users.show', compact('user'));
    }

    /**
     * Formulario de edicion de usuario.
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        $roles = ['editor', 'consultor'];

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Actualizar usuario y sincronizar rol.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $validated = $request->validated();

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        $user->syncRoles([$validated['role']]);

        return redirect()->route('users.show', $user)
            ->with('success', __('El usuario se actualizo correctamente.'));
    }

    /**
     * Eliminar usuario.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', __('El usuario se elimino correctamente.'));
    }
}
