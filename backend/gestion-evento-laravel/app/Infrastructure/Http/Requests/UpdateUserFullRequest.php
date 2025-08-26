<?php

namespace App\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserFullRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepara los datos antes de validarlos
     * Esto permite que si llegan en form-data con "persona.nombres"
     * se conviertan en un array persona[]
     */
    protected function prepareForValidation()
    {
        if ($this->has('persona.nombres')) {
            $this->merge([
                'persona' => [
                    'nombres' => $this->input('persona.nombres'),
                    'apellidos' => $this->input('persona.apellidos'),
                    'tipoDocumento' => $this->input('persona.tipoDocumento'),
                    'numeroDocumento' => $this->input('persona.numeroDocumento'),
                    'telefono' => $this->input('persona.telefono'),
                    'direccion' => $this->input('persona.direccion'),
                    'fechaNacimiento' => $this->input('persona.fechaNacimiento'),
                ]
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->route('id')), // Ignora el usuario actual
            ],
            'password' => 'sometimes|string|min:6',
            'role' => 'sometimes|string|exists:roles,nombre',

            // Persona
            'persona.nombres' => 'sometimes|string|max:255',
            'persona.apellidos' => 'sometimes|string|max:255',
            'persona.tipoDocumento' => 'sometimes|string|max:50',
            'persona.numeroDocumento' => [
                'sometimes',
                'string',
                'max:50',
                Rule::unique('personas', 'numeroDocumento')->ignore(optional($this->user()->persona)->id),
            ],
            'persona.telefono' => 'sometimes|string|max:20',
            'persona.direccion' => 'sometimes|string|max:255',
            'persona.fechaNacimiento' => 'sometimes|date',

            // Imagen
            'fotoPerfil' => 'sometimes|file|image|max:2048',
        ];
    }
}