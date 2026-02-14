<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrganizadorRequest;
use App\Http\Requests\UpdateOrganizadorRequest;
use App\Models\Administracion;
use App\Models\Organizador;

class OrganizadorController extends Controller
{
    /**
     * Listado paginado de organizadores.
     */
    public function index()
    {
        $this->authorize('viewAny', Organizador::class);

        $organizadores = Organizador::with('administracion')
            ->orderBy('nombre')
            ->paginate(15);

        return view('organizadores.index', compact('organizadores'));
    }

    /**
     * Formulario de creacion de organizador.
     */
    public function create()
    {
        $this->authorize('create', Organizador::class);

        $administraciones = Administracion::orderBy('nombre')->get();

        return view('organizadores.create', compact('administraciones'));
    }

    /**
     * Almacenar nuevo organizador.
     */
    public function store(StoreOrganizadorRequest $request)
    {
        $this->authorize('create', Organizador::class);

        Organizador::create($request->validated());

        return redirect()->route('organizadores.index')
            ->with('success', __('El organizador se creo correctamente.'));
    }

    /**
     * Detalle de un organizador.
     */
    public function show(Organizador $organizador)
    {
        $this->authorize('view', $organizador);

        $organizador->load('administracion');

        return view('organizadores.show', compact('organizador'));
    }

    /**
     * Formulario de edicion de organizador.
     */
    public function edit(Organizador $organizador)
    {
        $this->authorize('update', $organizador);

        $administraciones = Administracion::orderBy('nombre')->get();

        return view('organizadores.edit', compact('organizador', 'administraciones'));
    }

    /**
     * Actualizar organizador.
     */
    public function update(UpdateOrganizadorRequest $request, Organizador $organizador)
    {
        $this->authorize('update', $organizador);

        $organizador->update($request->validated());

        return redirect()->route('organizadores.show', $organizador)
            ->with('success', __('El organizador se actualizo correctamente.'));
    }

    /**
     * Eliminar organizador.
     */
    public function destroy(Organizador $organizador)
    {
        $this->authorize('delete', $organizador);

        $organizador->delete();

        return redirect()->route('organizadores.index')
            ->with('success', __('El organizador se elimino correctamente.'));
    }
}
