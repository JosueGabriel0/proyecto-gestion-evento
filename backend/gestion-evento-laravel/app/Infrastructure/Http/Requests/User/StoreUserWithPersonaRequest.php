<?php

namespace App\Infrastructure\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Application\UseCases\User\CreateUserWithPersonaCommand;

class StoreUserWithPersonaRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Aquí puedes meter lógica de policies si corresponde
        return true;
    }

    public function rules(): array
    {
        return [
            // ─── User ───────────────────────────────────────────────
            'email'       => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'    => ['required', 'string', 'min:8'],
            'escuela_id'  => ['required', 'integer', 'exists:escuelas,id'],
            'role_id'     => ['nullable', 'integer', 'exists:roles,id'],

            // ─── Persona ────────────────────────────────────────────
            'nombres'            => ['required', 'string', 'max:255'],
            'apellidos'          => ['required', 'string', 'max:255'],
            'tipo_documento'     => ['required', 'string', 'max:50'], // o enum
            'numero_documento'   => ['required', 'string', 'max:50'],
            'telefono'           => ['nullable', 'string', 'max:50'],
            'direccion'          => ['nullable', 'string', 'max:255'],
            'correo_electronico' => ['nullable', 'email', 'max:255'],
            'foto_perfil'        => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'fecha_nacimiento'   => ['required', 'date_format:Y-m-d'],

            // ─── Opcionales: Ponente / Alumno / Jurado ──────────────
            'biografia'          => ['nullable', 'string'],
            'ponencia_id'        => ['nullable', 'integer', 'exists:ponencias,id'],
            'codigo_universitario' => ['nullable', 'string', 'max:50'],
            'carrera'            => ['nullable', 'string', 'max:255'],
            'ciclo'              => ['nullable', 'string', 'max:50'],
            'especialidad'       => ['nullable', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'escuela_id'        => 'escuela',
            'role_id'           => 'rol',
            'correo_electronico' => 'correo electrónico (persona)',
            'fecha_nacimiento'  => 'fecha de nacimiento',
            'codigo_universitario' => 'código universitario',
        ];
    }

    /**
     * Convierte el request validado a Command (para UseCase).
     */
    public function toCommand(): CreateUserWithPersonaCommand
    {
        $data = $this->validated();

        // Procesar upload de foto si existe
        $fotoUrl = null;
        if ($this->hasFile('foto_perfil')) {
            $fotoUrl = $this->file('foto_perfil')->store('images/usuarios', 'public');
        } else {
            $fotoUrl = $data['foto_perfil'] ?? null;
        }

        return new CreateUserWithPersonaCommand(
            email: $data['email'],
            rawPassword: $data['password'],
            escuelaId: (int) $data['escuela_id'],
            roleId: $data['role_id'] ?? null,

            nombres: $data['nombres'],
            apellidos: $data['apellidos'],
            tipoDocumento: $data['tipo_documento'],
            numeroDocumento: $data['numero_documento'],
            telefono: $data['telefono'] ?? '',
            direccion: $data['direccion'] ?? '',
            correoElectronico: $data['correo_electronico'] ?? '',
            fotoPerfil: $fotoUrl, // aquí ya se resuelve
            fechaNacimientoYmd: $data['fecha_nacimiento'],

            biografia: $data['biografia'] ?? null,
            userId: null,
            ponenciaId: $data['ponencia_id'] ?? null,

            codigoUniversitario: $data['codigo_universitario'] ?? null,
            carrera: $data['carrera'] ?? null,
            ciclo: $data['ciclo'] ?? null,
            especialidad: $data['especialidad'] ?? null,
        );
    }
}
