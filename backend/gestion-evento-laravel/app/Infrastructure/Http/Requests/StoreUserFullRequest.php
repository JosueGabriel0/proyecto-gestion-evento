<?php

namespace App\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserFullRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // o valida según el usuario autenticado
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|string|exists:roles,nombre', // debe existir en tabla roles

            // Datos de persona
            'persona.nombres' => 'required|string|max:255',
            'persona.apellidos' => 'required|string|max:255',
            'persona.tipoDocumento' => 'required|string|max:20',
            'persona.numeroDocumento' => 'required|string|max:20|unique:personas,numeroDocumento',
            'persona.telefono' => 'required|string|max:20',
            'persona.direccion' => 'required|string|max:255',
            'fotoPerfil' => 'sometimes|file|image|max:2048',
            'persona.fechaNacimiento' => 'required|date',
        ];
    }
}