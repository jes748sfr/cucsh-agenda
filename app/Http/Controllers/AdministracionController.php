<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdministracionRequest;
use App\Http\Requests\UpdateAdministracionRequest;
use App\Models\Administracion;

class AdministracionController extends Controller
{
    /**
     * Listado paginado de administraciones.
     */
    public function index()
    {
        $this->authorize('viewAny', Administracion::class);

        $administraciones = Administracion::orderBy('nombre')->paginate(15);

        return view('administraciones.index', compact('administraciones'));
    }

    /**
     * Formulario de creacion de administracion.
     */
    public function create()
    {
        $this->authorize('create', Administracion::class);

        return view('administraciones.create');
    }

    /**
     * Almacenar nueva administracion.
     */
    public function store(StoreAdministracionRequest $request)
    {
        $this->authorize('create', Administracion::class);

        Administracion::create($request->validated());

        return redirect()->route('administraciones.index')
            ->with('success', __('La administracion se creo correctamente.'));
    }

    /**
     * Detalle de una administracion con conteo de organizadores.
     */
    public function show(Administracion $administracion)
    {
        $this->authorize('view', $administracion);

        $administracion->loadCount('organizadores');

        return view('administraciones.show', compact('administracion'));
    }

    /**
     * Formulario de edicion de administracion.
     */
    public function edit(Administracion $administracion)
    {
        $this->authorize('update', $administracion);

        return view('administraciones.edit', compact('administracion'));
    }

    /**
     * Actualizar administracion.
     */
    public function update(UpdateAdministracionRequest $request, Administracion $administracion)
    {
        $this->authorize('update', $administracion);

        $administracion->update($request->validated());

        return redirect()->route('administraciones.show', $administracion)
            ->with('success', __('La administracion se actualizo correctamente.'));
    }

    /**
     * Eliminar administracion.
     */
    public function destroy(Administracion $administracion)
    {
        $this->authorize('delete', $administracion);

        $administracion->delete();

        return redirect()->route('administraciones.index')
            ->with('success', __('La administracion se elimino correctamente.'));
    }
}
