<?php

namespace App\Infrastructure\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Cambia según tus políticas si es necesario
    }

    public function rules(): array
    {
        $userId = $this->route('id'); // Asumiendo que pasas el ID del usuario en la ruta

        return [
            // 🧩 Datos del usuario
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => 'nullable|string|min:6',
            'escuela_id' => 'required|integer|exists:escuelas,id',
            'role' => 'required|string|exists:roles,nombre',

            // 👤 Datos personales
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'tipo_documento' => [
                'required',
                'string',
                'max:20'
            ],
            'numero_documento' => [
                'required',
                'string',
                'max:20',
                Rule::unique('personas', 'numeroDocumento')->ignore($userId, 'user_id'),
            ],
            'telefono' => 'nullable|string|max:15',
            'direccion' => 'nullable|string|max:255',
            'pais' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:255',
            'correo_electronico' => [
                'required',
                'email',
                Rule::unique('personas', 'correoElectronico')->ignore($userId, 'user_id'),
            ],
            'correo_institucional' => [
                'required',
                'email',
                Rule::unique('personas', 'correoInstitucional')->ignore($userId, 'user_id'),
            ],
            'fecha_nacimiento' => [
                'required',
                'date'
            ],

            // 🖼️ Imagen
            'foto_perfil' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',

            // 🎓 Datos específicos opcionales
            'codigo_universitario' => 'nullable|string|max:20',
            'especialidad' => 'nullable|string|max:100',
            'biografia' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'El correo es obligatorio.',
            'email.unique' => 'El correo ya está registrado.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'nombres.required' => 'El nombre es obligatorio.',
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'tipo_documento.required' => 'El tipo de documento es obligatorio.',
            'numero_documento.required' => 'El número de documento es obligatorio.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
        ];
    }
}
