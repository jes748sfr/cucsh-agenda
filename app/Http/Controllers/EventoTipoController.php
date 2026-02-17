<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventoTipoRequest;
use App\Http\Requests\UpdateEventoTipoRequest;
use App\Models\EventoTipo;

class EventoTipoController extends Controller
{
    /**
     * Listado paginado de tipos de evento.
     */
    public function index()
    {
        $this->authorize('viewAny', EventoTipo::class);

        $eventosTipos = EventoTipo::orderBy('nombre')->paginate(12);

        return view('eventos-tipos.index', compact('eventosTipos'));
    }

    /**
     * Formulario de creacion de tipo de evento.
     */
    public function create()
    {
        $this->authorize('create', EventoTipo::class);

        return view('eventos-tipos.create');
    }

    /**
     * Almacenar nuevo tipo de evento.
     */
    public function store(StoreEventoTipoRequest $request)
    {
        $this->authorize('create', EventoTipo::class);

        EventoTipo::create($request->validated());

        return redirect()->route('eventos-tipos.index')
            ->with('success', __('El tipo de evento se creo correctamente.'));
    }

    /**
     * Detalle de un tipo de evento.
     */
    public function show(EventoTipo $eventoTipo)
    {
        $this->authorize('view', $eventoTipo);

        return view('eventos-tipos.show', compact('eventoTipo'));
    }

    /**
     * Formulario de edicion de tipo de evento.
     */
    public function edit(EventoTipo $eventoTipo)
    {
        $this->authorize('update', $eventoTipo);

        return view('eventos-tipos.edit', compact('eventoTipo'));
    }

    /**
     * Actualizar tipo de evento.
     */
    public function update(UpdateEventoTipoRequest $request, EventoTipo $eventoTipo)
    {
        $this->authorize('update', $eventoTipo);

        $eventoTipo->update($request->validated());

        return redirect()->route('eventos-tipos.show', $eventoTipo)
            ->with('success', __('El tipo de evento se actualizo correctamente.'));
    }

    /**
     * Eliminar tipo de evento.
     */
    public function destroy(EventoTipo $eventoTipo)
    {
        $this->authorize('delete', $eventoTipo);

        if ($eventoTipo->eventos()->exists()) {
            return redirect()->route('eventos-tipos.index')
                ->with('error', __('No se puede eliminar el tipo de evento: existen eventos asociados.'));
        }

        $eventoTipo->delete();

        return redirect()->route('eventos-tipos.index')
            ->with('success', __('El tipo de evento se eliminó correctamente.'));
    }
}
