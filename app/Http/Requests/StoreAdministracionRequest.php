<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdministracionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('catalogos.crear');
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:150', 'unique:administraciones,nombre'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la administración es obligatorio.',
            'nombre.max' => 'El nombre no puede exceder 150 caracteres.',
            'nombre.unique' => 'Ya existe una administración con este nombre.',
        ];
    }
}
