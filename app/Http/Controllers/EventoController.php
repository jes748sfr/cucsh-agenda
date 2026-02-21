<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventoRequest;
use App\Http\Requests\UpdateEventoRequest;
use App\Models\Evento;
use App\Models\EventoTipo;
use App\Models\Institucion;
use App\Models\Organizador;
use Illuminate\Support\Facades\DB;

class EventoController extends Controller
{
    /**
     * Listado paginado de eventos.
     */
    public function index()
    {
        $this->authorize('viewAny', Evento::class);

        $eventos = Evento::with(['eventoTipo', 'institucion', 'organizador.administracion'])
            ->withCount('fechas')
            ->latest()
            ->paginate(15);

        return view('eventos.index', compact('eventos'));
    }

    /**
     * Formulario de creación de evento.
     */
    public function create()
    {
        $this->authorize('create', Evento::class);

        return view('eventos.create', $this->catalogos());
    }

    /**
     * Almacenar nuevo evento con sus fechas.
     */
    public function store(StoreEventoRequest $request)
    {
        $this->authorize('create', Evento::class);

        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request) {
            $evento = Evento::create([
                ...\Arr::except($validated, ['fechas']),
                'usuario_id' => $request->user()->id,
            ]);

            $evento->fechas()->createMany($validated['fechas']);
        });

        return redirect()->route('eventos.index')
            ->with('success', __('El evento se creó correctamente.'));
    }

    /**
     * Detalle de un evento con sus fechas.
     */
    public function show(Evento $evento)
    {
        $this->authorize('view', $evento);

        $evento->load(['eventoTipo', 'institucion', 'organizador.administracion', 'fechas']);

        return view('eventos.show', compact('evento'));
    }

    /**
     * Formulario de edición de evento.
     */
    public function edit(Evento $evento)
    {
        $this->authorize('update', $evento);

        $evento->load('fechas');

        return view('eventos.edit', [
            'evento' => $evento,
            ...$this->catalogos(),
        ]);
    }

    /**
     * Actualizar evento y reemplazar sus fechas.
     */
    public function update(UpdateEventoRequest $request, Evento $evento)
    {
        $this->authorize('update', $evento);

        $validated = $request->validated();

        DB::transaction(function () use ($validated, $evento) {
            $evento->update(\Arr::except($validated, ['fechas']));

            $evento->fechas()->delete();
            $evento->fechas()->createMany($validated['fechas']);
        });

        return redirect()->route('eventos.show', $evento)
            ->with('success', __('El evento se actualizó correctamente.'));
    }

    /**
     * Eliminar evento (cascade elimina fechas).
     */
    public function destroy(Evento $evento)
    {
        $this->authorize('delete', $evento);

        $evento->delete();

        return redirect()->route('eventos.index')
            ->with('success', __('El evento se eliminó correctamente.'));
    }

    /**
     * Catálogos para los dropdowns de create/edit.
     */
    private function catalogos(): array
    {
        return [
            'tipos' => EventoTipo::orderBy('nombre')->get(),
            'instituciones' => Institucion::orderBy('nombre')->get(),
            'organizadores' => Organizador::with('administracion')
                ->where('activo', true)
                ->orderBy('nombre')
                ->get(),
        ];
    }
}
