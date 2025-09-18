<?php

namespace App\Infrastructure\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Application\UseCases\User\CreateUserWithPersonaCommand;
use App\Application\UseCases\User\UpdateUserWithPersonaCommand;

class UpdateUserWithPersonaRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Aquí puedes meter lógica de policies si corresponde
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('id') ?? $this->route('user');
        // Ajusta según cómo definas la ruta: /users/{id}

        return [
            // ─── User ───────────────────────────────────────────────
            'email'       => ['required', 'email', 'max:255', "unique:users,email,{$userId}"],
            'password'    => ['nullable', 'string', 'min:8'],
            'escuela_id'  => ['required', 'integer', 'exists:escuelas,id'],
            'role_id'     => ['nullable', 'integer', 'exists:roles,id'],

            // ─── Persona ────────────────────────────────────────────
            'nombres'            => ['required', 'string', 'max:255'],
            'apellidos'          => ['required', 'string', 'max:255'],
            'tipo_documento'     => ['required', 'string', 'max:50'],
            'numero_documento'   => ['required', 'string', 'max:50'],
            'telefono'           => ['nullable', 'string', 'max:50'],
            'direccion'          => ['nullable', 'string', 'max:255'],
            'correo_electronico' => ['nullable', 'email', 'max:255'],
            'foto_perfil'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
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
            'escuela_id'         => 'escuela',
            'role_id'            => 'rol',
            'correo_electronico' => 'correo electrónico (persona)',
            'fecha_nacimiento'   => 'fecha de nacimiento',
            'codigo_universitario' => 'código universitario',
        ];
    }

    public function toCommand(int $id): UpdateUserWithPersonaCommand
    {
        $data = $this->validated();

        $fotoUrl = null;
        if ($this->hasFile('foto_perfil')) {
            $fotoUrl = $this->file('foto_perfil')->store('images/usuarios', 'public');
        } else {
            $fotoUrl = $data['foto_perfil'] ?? null;
        }

        return new UpdateUserWithPersonaCommand(
            userId: $id, // 🚀 el id viene del path
            email: $data['email'] ?? null,
            rawPassword: $data['password'] ?? null,
            escuelaId: $data['escuela_id'] ?? null,
            roleId: $data['role_id'] ?? null,
            nombres: $data['nombres'] ?? null,
            apellidos: $data['apellidos'] ?? null,
            tipoDocumento: $data['tipo_documento'] ?? null,
            numeroDocumento: $data['numero_documento'] ?? null,
            telefono: $data['telefono'] ?? null,
            direccion: $data['direccion'] ?? null,
            correoElectronico: $data['correo_electronico'] ?? null,
            fotoPerfil: $fotoUrl,
            fechaNacimientoYmd: $data['fecha_nacimiento'] ?? null,
            biografia: $data['biografia'] ?? null,
            ponenciaId: $data['ponencia_id'] ?? null,
            codigoUniversitario: $data['codigo_universitario'] ?? null,
            carrera: $data['carrera'] ?? null,
            ciclo: $data['ciclo'] ?? null,
            especialidad: $data['especialidad'] ?? null,
        );
    }
}
