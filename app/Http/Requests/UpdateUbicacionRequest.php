<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUbicacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('catalogos.editar');
    }

    public function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('ubicaciones', 'nombre')->ignore($this->route('ubicacion')),
            ],
            'institucion_id' => ['nullable', 'integer', 'exists:instituciones,id'],
            'activo' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la ubicación es obligatorio.',
            'nombre.max' => 'El nombre no puede exceder 255 caracteres.',
            'nombre.unique' => 'Ya existe una ubicación con este nombre.',
            'institucion_id.exists' => 'La institución seleccionada no existe.',
        ];
    }
}
