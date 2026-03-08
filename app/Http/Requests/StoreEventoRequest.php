<?php

namespace App\Http\Requests;

use App\Models\EventoFecha;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreEventoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('eventos.crear');
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'eventos_tipo_id' => ['required', 'integer', 'exists:eventos_tipos,id'],
            'organizador_id' => ['required', 'integer', 'exists:organizadores,id'],
            'institucion_id' => ['required', 'integer', 'exists:instituciones,id'],
            'ubicacion_id' => ['nullable', 'integer', 'exists:ubicaciones,id'],
            'activo' => ['sometimes', 'boolean'],
            'color' => ['required', 'string', 'in:#7FBCD2,#FF6868,#FFBB64,#B1C29E'],
            'notas_cta' => ['nullable', 'string', 'max:5000'],
            'notas_servicios' => ['nullable', 'string', 'max:5000'],
            'fechas' => ['required', 'array', 'min:1'],
            'fechas.*.fecha' => ['required', 'date', 'after_or_equal:today'],
            'fechas.*.hora_inicio' => ['required', 'date_format:H:i'],
            'fechas.*.hora_fin' => ['required', 'date_format:H:i', 'after:fechas.*.hora_inicio'],
        ];
    }

    /**
     * Validación de solapamiento por ubicación.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            // Validar que la hora de inicio no haya pasado si la fecha es hoy
            $fechas = $this->input('fechas', []);
            $hoy = Carbon::today('America/Mexico_City');
            $horaActual = Carbon::now('America/Mexico_City')->format('H:i');

            foreach ($fechas as $i => $fecha) {
                if (empty($fecha['fecha']) || empty($fecha['hora_inicio'])) {
                    continue;
                }

                // Solo validar si la fecha es hoy
                if (Carbon::parse($fecha['fecha'])->isSameDay($hoy) && $fecha['hora_inicio'] < $horaActual) {
                    $validator->errors()->add(
                        "fechas.{$i}.hora_inicio",
                        'La hora de inicio ya pasó para la fecha de hoy.'
                    );
                }
            }

            $ubicacionId = $this->input('ubicacion_id');

            if (! $ubicacionId || empty($fechas) || $validator->errors()->hasAny(['fechas', 'ubicacion_id'])) {
                return;
            }

            foreach ($fechas as $i => $fecha) {
                if (empty($fecha['fecha']) || empty($fecha['hora_inicio']) || empty($fecha['hora_fin'])) {
                    continue;
                }

                $solapado = EventoFecha::where('fecha', $fecha['fecha'])
                    ->where(function ($q) use ($fecha) {
                        $q->where(function ($q2) use ($fecha) {
                            $q2->where('hora_inicio', '<', $fecha['hora_fin'])
                               ->where('hora_fin', '>', $fecha['hora_inicio']);
                        });
                    })
                    ->whereHas('evento', function ($q) use ($ubicacionId) {
                        $q->where('ubicacion_id', $ubicacionId);
                    })
                    ->exists();

                if ($solapado) {
                    $validator->errors()->add(
                        "fechas.{$i}.fecha",
                        "Ya existe un evento en esta ubicación en la fecha {$fecha['fecha']} con horario superpuesto."
                    );
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del evento es obligatorio.',
            'nombre.max' => 'El nombre no puede exceder 255 caracteres.',
            'eventos_tipo_id.required' => 'El tipo de evento es obligatorio.',
            'eventos_tipo_id.exists' => 'El tipo de evento seleccionado no existe.',
            'organizador_id.required' => 'El organizador es obligatorio.',
            'organizador_id.exists' => 'El organizador seleccionado no existe.',
            'institucion_id.required' => 'La institución es obligatoria.',
            'institucion_id.exists' => 'La institución seleccionada no existe.',
            'ubicacion_id.exists' => 'La ubicación seleccionada no existe.',
            'color.in' => 'El color seleccionado no es válido.',
            'notas_cta.max' => 'Las notas CTA no pueden exceder 5000 caracteres.',
            'notas_servicios.max' => 'Las notas de servicios no pueden exceder 5000 caracteres.',
            'fechas.required' => 'Debe agregar al menos una fecha al evento.',
            'fechas.min' => 'Debe agregar al menos una fecha al evento.',
            'fechas.*.fecha.required' => 'La fecha es obligatoria.',
            'fechas.*.fecha.date' => 'La fecha debe ser una fecha válida.',
            'fechas.*.fecha.after_or_equal' => 'La fecha no puede ser anterior a hoy.',
            'fechas.*.hora_inicio.required' => 'La hora de inicio es obligatoria.',
            'fechas.*.hora_inicio.date_format' => 'La hora de inicio debe tener formato HH:MM.',
            'fechas.*.hora_fin.required' => 'La hora de fin es obligatoria.',
            'fechas.*.hora_fin.date_format' => 'La hora de fin debe tener formato HH:MM.',
            'fechas.*.hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
        ];
    }
}
