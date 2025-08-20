<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserFullRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'password' => 'sometimes|string|min:6',
            'role' => 'sometimes|string|exists:roles,nombre',

            // Persona
            'persona.nombres' => 'sometimes|string|max:255',
            'persona.apellidos' => 'sometimes|string|max:255',
            'persona.tipoDocumento' => 'sometimes|string|max:50',
            'persona.numeroDocumento' => 'sometimes|string|max:50',
            'persona.telefono' => 'sometimes|string|max:20',
            'persona.direccion' => 'sometimes|string|max:255',
            'persona.fechaNacimiento' => 'sometimes|date',

            // Imagen
            'fotoPerfil' => 'sometimes|file|image|max:2048'
        ];
    }
}
