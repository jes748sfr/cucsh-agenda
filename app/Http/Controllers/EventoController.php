<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventoRequest;
use App\Http\Requests\UpdateEventoRequest;
use App\Models\Administracion;
use App\Models\Evento;
use App\Models\EventoTipo;
use App\Models\Institucion;
use App\Models\Organizador;
use App\Models\Ubicacion;
use Illuminate\Support\Facades\DB;

class EventoController extends Controller
{
    /**
     * Listado paginado de eventos.
     */
    public function index()
    {
        $this->authorize('viewAny', Evento::class);

        $eventos = Evento::with(['eventoTipo', 'institucion', 'organizador.administracion', 'ubicacionRel'])
            ->withCount('fechas')
            ->latest()
            ->paginate(15);

        return view('eventos.index', compact('eventos'));
    }

    /**
     * Formulario de creación de evento.
     * Acepta query params opcionales: ?fecha=YYYY-MM-DD&hora_inicio=HH:mm
     */
    public function create()
    {
        $this->authorize('create', Evento::class);

        return view('eventos.create', [
            ...$this->catalogos(),
            'prefillFecha' => request()->query('fecha'),
            'prefillHoraInicio' => request()->query('hora_inicio'),
            'from' => request()->query('from'),
        ]);
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

        $vista = $request->input('vista');
        $redirectUrl = $request->input('from') === 'dashboard'
            ? route('dashboard') . ($vista ? '?vista=' . urlencode($vista) : '')
            : route('eventos.index');

        if ($request->expectsJson()) {
            return response()->json(['redirect' => $redirectUrl]);
        }

        return redirect($redirectUrl)
            ->with('success', __('El evento se creó correctamente.'));
    }

    /**
     * Detalle de un evento con sus fechas.
     */
    public function show(Evento $evento)
    {
        $this->authorize('view', $evento);

        $evento->load(['eventoTipo', 'institucion', 'organizador.administracion', 'ubicacionRel', 'fechas']);

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

        $redirectUrl = route('eventos.show', $evento);

        if ($request->expectsJson()) {
            return response()->json(['redirect' => $redirectUrl]);
        }

        return redirect($redirectUrl)
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
            'administraciones' => Administracion::orderBy('nombre')->get(),
            'ubicaciones' => Ubicacion::where('activo', true)
                ->orderBy('nombre')
                ->get(),
        ];
    }
}
