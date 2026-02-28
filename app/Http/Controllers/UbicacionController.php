<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUbicacionRequest;
use App\Http\Requests\UpdateUbicacionRequest;
use App\Models\Institucion;
use App\Models\Ubicacion;

class UbicacionController extends Controller
{
    /**
     * Listado paginado de ubicaciones.
     */
    public function index()
    {
        $this->authorize('viewAny', Ubicacion::class);

        $ubicaciones = Ubicacion::with('institucion')
            ->withCount('eventos')
            ->orderBy('nombre')
            ->paginate(15);

        return view('ubicaciones.index', compact('ubicaciones'));
    }

    /**
     * Formulario de creación de ubicación.
     */
    public function create()
    {
        $this->authorize('create', Ubicacion::class);

        $instituciones = Institucion::orderBy('nombre')->get();

        return view('ubicaciones.create', compact('instituciones'));
    }

    /**
     * Almacenar nueva ubicación.
     */
    public function store(StoreUbicacionRequest $request)
    {
        $this->authorize('create', Ubicacion::class);

        Ubicacion::create($request->validated());

        return redirect()->route('ubicaciones.index')
            ->with('success', __('La ubicación se creó correctamente.'));
    }

    /**
     * Detalle de una ubicación.
     */
    public function show(Ubicacion $ubicacion)
    {
        $this->authorize('view', $ubicacion);

        $ubicacion->load('institucion');

        return view('ubicaciones.show', compact('ubicacion'));
    }

    /**
     * Formulario de edición de ubicación.
     */
    public function edit(Ubicacion $ubicacion)
    {
        $this->authorize('update', $ubicacion);

        $instituciones = Institucion::orderBy('nombre')->get();

        return view('ubicaciones.edit', compact('ubicacion', 'instituciones'));
    }

    /**
     * Actualizar ubicación.
     */
    public function update(UpdateUbicacionRequest $request, Ubicacion $ubicacion)
    {
        $this->authorize('update', $ubicacion);

        $ubicacion->update($request->validated());

        return redirect()->route('ubicaciones.show', $ubicacion)
            ->with('success', __('La ubicación se actualizó correctamente.'));
    }

    /**
     * Eliminar ubicación.
     */
    public function destroy(Ubicacion $ubicacion)
    {
        $this->authorize('delete', $ubicacion);

        if ($ubicacion->eventos()->exists()) {
            return redirect()->route('ubicaciones.index')
                ->with('error', __('No se puede eliminar la ubicación: existen eventos asociados.'));
        }

        $ubicacion->delete();

        return redirect()->route('ubicaciones.index')
            ->with('success', __('La ubicación se eliminó correctamente.'));
    }
}
