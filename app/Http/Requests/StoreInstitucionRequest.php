<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInstitucionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('catalogos.crear');
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255', 'unique:instituciones,nombre'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la institución es obligatorio.',
            'nombre.max' => 'El nombre no puede exceder 255 caracteres.',
            'nombre.unique' => 'Ya existe una institución con este nombre.',
        ];
    }
}
