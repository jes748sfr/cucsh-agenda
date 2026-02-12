<?php

namespace App\Http\Requests;

use App\Models\EventoFecha;
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
            'ubicacion' => ['nullable', 'string', 'max:500'],
            'activo' => ['sometimes', 'boolean'],
            'notas_cta' => ['nullable', 'string', 'max:5000'],
            'notas_servicios' => ['nullable', 'string', 'max:5000'],
            'fechas' => ['required', 'array', 'min:1'],
            'fechas.*.fecha' => ['required', 'date'],
            'fechas.*.hora_inicio' => ['required', 'date_format:H:i'],
            'fechas.*.hora_fin' => ['required', 'date_format:H:i', 'after:fechas.*.hora_inicio'],
        ];
    }

    /**
     * Validación de solapamiento después de las reglas básicas.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $this->validarSolapamiento($validator, null);
            },
        ];
    }

    /**
     * Verificar que no existan eventos solapados en la misma institución.
     */
    protected function validarSolapamiento(Validator $validator, ?int $eventoId): void
    {
        foreach ($this->input('fechas') as $index => $fecha) {
            $query = EventoFecha::where('fecha', $fecha['fecha'])
                ->where(function ($q) use ($fecha) {
                    $q->whereBetween('hora_inicio', [$fecha['hora_inicio'], $fecha['hora_fin']])
                        ->orWhereBetween('hora_fin', [$fecha['hora_inicio'], $fecha['hora_fin']])
                        ->orWhere(function ($q2) use ($fecha) {
                            $q2->where('hora_inicio', '<=', $fecha['hora_inicio'])
                                ->where('hora_fin', '>=', $fecha['hora_fin']);
                        });
                })
                ->whereHas('evento', function ($q) use ($eventoId) {
                    $q->where('institucion_id', $this->input('institucion_id'));

                    if ($eventoId) {
                        $q->where('id', '!=', $eventoId);
                    }
                });

            if ($query->exists()) {
                $validator->errors()->add(
                    "fechas.{$index}",
                    "Conflicto: Ya existe un evento en {$fecha['fecha']} de {$fecha['hora_inicio']} a {$fecha['hora_fin']}."
                );
            }
        }
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
            'ubicacion.max' => 'La ubicación no puede exceder 500 caracteres.',
            'notas_cta.max' => 'Las notas CTA no pueden exceder 5000 caracteres.',
            'notas_servicios.max' => 'Las notas de servicios no pueden exceder 5000 caracteres.',
            'fechas.required' => 'Debe agregar al menos una fecha al evento.',
            'fechas.min' => 'Debe agregar al menos una fecha al evento.',
            'fechas.*.fecha.required' => 'La fecha es obligatoria.',
            'fechas.*.fecha.date' => 'La fecha debe ser una fecha válida.',
            'fechas.*.hora_inicio.required' => 'La hora de inicio es obligatoria.',
            'fechas.*.hora_inicio.date_format' => 'La hora de inicio debe tener formato HH:MM.',
            'fechas.*.hora_fin.required' => 'La hora de fin es obligatoria.',
            'fechas.*.hora_fin.date_format' => 'La hora de fin debe tener formato HH:MM.',
            'fechas.*.hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
        ];
    }
}
