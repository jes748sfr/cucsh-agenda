<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrganizadorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('organizadores.editar');
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'tel' => ['nullable', 'string', 'max:20'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('organizadores', 'email')->ignore($this->route('organizador')),
            ],
            'administracion_id' => ['required', 'integer', 'exists:administraciones,id'],
            'activo' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del organizador es obligatorio.',
            'nombre.max' => 'El nombre no puede exceder 255 caracteres.',
            'tel.max' => 'El teléfono no puede exceder 20 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección válida.',
            'email.unique' => 'Ya existe un organizador con este correo electrónico.',
            'administracion_id.required' => 'La administración es obligatoria.',
            'administracion_id.exists' => 'La administración seleccionada no existe.',
        ];
    }
}
