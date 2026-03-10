<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInstitucionRequest;
use App\Http\Requests\UpdateInstitucionRequest;
use App\Models\Institucion;

class InstitucionController extends Controller
{
    /**
     * Listado paginado de instituciones.
     */
    public function index()
    {
        $this->authorize('viewAny', Institucion::class);

        $instituciones = Institucion::orderBy('nombre')->paginate(12);

        return view('instituciones.index', compact('instituciones'));
    }

    /**
     * Formulario de creacion de institucion.
     */
    public function create()
    {
        $this->authorize('create', Institucion::class);

        return view('instituciones.create');
    }

    /**
     * Almacenar nueva institucion.
     */
    public function store(StoreInstitucionRequest $request)
    {
        $this->authorize('create', Institucion::class);

        Institucion::create($request->validated());

        return redirect()->route('instituciones.index')
            ->with('success', __('La institucion se creo correctamente.'));
    }

    /**
     * Detalle de una institucion.
     */
    public function show(Institucion $institucion)
    {
        $this->authorize('view', $institucion);

        return view('instituciones.show', compact('institucion'));
    }

    /**
     * Formulario de edicion de institucion.
     */
    public function edit(Institucion $institucion)
    {
        $this->authorize('update', $institucion);

        return view('instituciones.edit', compact('institucion'));
    }

    /**
     * Actualizar institucion.
     */
    public function update(UpdateInstitucionRequest $request, Institucion $institucion)
    {
        $this->authorize('update', $institucion);

        $institucion->update($request->validated());

        return redirect()->route('instituciones.show', $institucion)
            ->with('success', __('La institucion se actualizo correctamente.'));
    }

    /**
     * Eliminar institucion.
     */
    public function destroy(Institucion $institucion)
    {
        $this->authorize('delete', $institucion);

        if ($institucion->eventos()->exists()) {
            return redirect()->route('instituciones.index')
                ->with('error', __('No se puede eliminar la institución: existen eventos asociados.'));
        }

        $institucion->delete();

        return redirect()->route('instituciones.index')
            ->with('success', __('La institución se eliminó correctamente.'));
    }
}
