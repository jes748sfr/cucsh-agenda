<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInstitucionRequest extends FormRequest
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
                Rule::unique('instituciones', 'nombre')->ignore($this->route('institucion')),
            ],
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
