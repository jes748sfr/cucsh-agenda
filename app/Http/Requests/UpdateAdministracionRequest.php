<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdministracionRequest extends FormRequest
{
    /**
     * Proteger registros del sistema (Global id=1, Administrativo id=2).
     */
    public function authorize(): bool
    {
        if (in_array($this->route('administracion')?->id, [1, 2])) {
            return false;
        }

        return $this->user()->can('catalogos.editar');
    }

    public function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:150',
                Rule::unique('administraciones', 'nombre')->ignore($this->route('administracion')),
            ],
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
