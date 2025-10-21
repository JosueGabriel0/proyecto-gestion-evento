<?php

namespace App\Infrastructure\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('id'); // Si pasas el ID en la URL

        return [
            // Datos de usuario
            'email' => 'required|email|unique:users,email,' . $userId,
            'password' => 'nullable|min:6',
            'escuela_id' => 'required|integer|exists:escuelas,id',
            'role' => 'nullable|string|exists:roles,nombre',

            // Datos de persona
            'persona.nombres' => 'required|string|max:100',
            'persona.apellidos' => 'required|string|max:100',
            'persona.tipo_documento' => 'required|string|max:20',
            'persona.numero_documento' => 'required|string|max:20|unique:personas,numero_documento,' . $userId . ',user_id',
            'persona.telefono' => 'nullable|string|max:15',
            'persona.direccion' => 'nullable|string|max:255',
            'persona.correo_electronico' => 'required|email',
            'persona.foto_perfil' => 'nullable|string',
            'persona.fecha_nacimiento' => 'required|date',

            // Datos específicos según el rol
            'alumno.codigo_universitario' => 'nullable|string|max:20',
            'jurado.especialidad' => 'nullable|string|max:100',
            'ponente.biografia' => 'nullable|string',
        ];
    }
}