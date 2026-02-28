<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventoRequest;
use App\Http\Requests\UpdateEventoRequest;
use App\Mail\NotaCtaNotificacion;
use App\Models\Administracion;
use App\Models\Evento;
use App\Models\EventoTipo;
use App\Models\Institucion;
use App\Models\Organizador;
use App\Models\Ubicacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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

        /** @var Evento $evento */
        $evento = DB::transaction(function () use ($validated, $request) {
            $evento = Evento::create([
                ...\Arr::except($validated, ['fechas']),
                'usuario_id' => $request->user()->id,
            ]);

            $evento->fechas()->createMany($validated['fechas']);

            return $evento;
        });

        // Notificar al CTA si el evento incluye notas_cta
        if (filled($evento->notas_cta)) {
            $this->enviarNotificacionCta($evento, esActualizacion: false);
        }

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

        // Capturar valor original antes de la transaccion
        $notasCtaOriginal = $evento->notas_cta;

        DB::transaction(function () use ($validated, $evento) {
            $evento->update(\Arr::except($validated, ['fechas']));

            $evento->fechas()->delete();
            $evento->fechas()->createMany($validated['fechas']);
        });

        // Notificar al CTA si notas_cta cambio y tiene contenido
        if (filled($evento->notas_cta) && $evento->notas_cta !== $notasCtaOriginal) {
            $this->enviarNotificacionCta($evento, esActualizacion: filled($notasCtaOriginal));
        }

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
     * Enviar notificacion al CTA con el resumen del evento.
     * Si CTA_EMAIL no esta configurado, se omite con un warning en log.
     */
    private function enviarNotificacionCta(Evento $evento, bool $esActualizacion): void
    {
        $ctaEmail = config('cucsh.cta_email');

        if (blank($ctaEmail)) {
            Log::warning('No se envio notificacion CTA: CTA_EMAIL no configurado.', [
                'evento_id' => $evento->id,
            ]);

            return;
        }

        // Eager load relaciones necesarias para el template
        $evento->load(['eventoTipo', 'institucion', 'organizador.administracion', 'ubicacionRel', 'fechas']);

        try {
            Mail::send(new NotaCtaNotificacion($evento, $esActualizacion));
        } catch (\Throwable $e) {
            Log::error('Error al enviar notificacion CTA.', [
                'evento_id' => $evento->id,
                'error' => $e->getMessage(),
            ]);
        }
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
