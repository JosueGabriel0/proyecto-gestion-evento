<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Entities\User;
use App\Domain\Repositories\UserRepository;
use App\Infrastructure\Persistence\Eloquent\Models\UserModel;
use App\Infrastructure\Persistence\Eloquent\Models\RoleModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class UserRepositoryImpl implements UserRepository
{
    /**
     * Obtener todos los usuarios (opcionalmente filtrados por rol)
     */
    public function all(?string $role = null): array
    {
        $query = UserModel::with(['role', 'persona', 'alumno', 'jurado', 'ponente']);

        if ($role) {
            $query->whereHas('role', fn($q) => $q->where('nombre', $role));
        }

        return $query->get()
            ->map(fn($model) => $model->toDomain())
            ->toArray();
    }

    /**
     * Buscar un usuario por ID
     */
    public function find(int $id): ?User
    {
        $model = UserModel::with(['role', 'persona', 'alumno', 'jurado', 'ponente'])->find($id);
        return $model ? $model->toDomain() : null;
    }

    /**
     * Crear un usuario con rol asignado
     */
    public function create(User $user, string $role): User
    {
        return DB::transaction(function () use ($user, $role) {
            $roleModel = RoleModel::where('nombre', $role)->firstOrFail();

            // Crear usuario base
            $model = UserModel::create([
                'email' => $user->getEmail(),
                'password' => bcrypt($user->getPassword()),
                'escuela_id' => $user->getEscuelaId(),
                'role_id' => $roleModel->id,
            ]);

            // ✅ Crear persona asociada
            if ($user->getPersona()) {
                $persona = $user->getPersona();
                $model->persona()->create([
                    'nombres' => $persona->getNombres(),
                    'apellidos' => $persona->getApellidos(),
                    'tipoDocumento' => $persona->getTipoDocumento(),
                    'numeroDocumento' => $persona->getNumeroDocumento(),
                    'telefono' => $persona->getTelefono(),
                    'direccion' => $persona->getDireccion(),
                    'pais' => $persona->getPais(),
                    'religion' => $persona->getReligion(),
                    'correoElectronico' => $persona->getCorreoElectronico(),
                    'correoInstitucional' => $persona->getCorreoInstitucional(),
                    'fotoPerfil' => $persona->getFotoPerfil(),
                    'fechaNacimiento' => $persona->getFechaNacimiento()->format('Y-m-d'),
                ]);
            }

            // Crear relaciones según el rol
            switch ($role) {
                case 'ROLE_ESTUDIANTE':
                    if ($user->getAlumno()) {
                        $model->alumno()->create([
                            'codigo_universitario' => $user->getAlumno()->getCodigoUniversitario(),
                        ]);
                    }
                    break;

                case 'ROLE_JURADO':
                    if ($user->getJurado()) {
                        $model->jurado()->create([
                            'especialidad' => $user->getJurado()->getEspecialidad(),
                        ]);
                    }
                    break;

                case 'ROLE_PONENTE':
                    if ($user->getPonente()) {
                        $model->ponente()->create([
                            'biografia' => $user->getPonente()->getBiografia(),
                            'ponencia_id' => $user->getPonente()->getPonenciaId(), // ✅
                        ]);
                    }
                    break;
            }

            return $model->load(['role', 'persona', 'alumno', 'jurado', 'ponente', 'escuela'])->toDomain();
        });
    }

    /**
     * Actualizar un usuario y sus relaciones
     */
    public function update(User $user, ?string $role = null): User
    {
        return DB::transaction(function () use ($user, $role) {
            $model = UserModel::findOrFail($user->getId());

            $data = [
                'email' => $user->getEmail(),
                'escuela_id' => $user->getEscuelaId(),
            ];

            if ($user->getPassword()) {
                $data['password'] = bcrypt($user->getPassword());
            }

            $model->update($data);

            if ($role) {
                $roleModel = RoleModel::where('nombre', $role)->firstOrFail();
                $model->update(['role_id' => $roleModel->id]);
            }

            $currentRole = $role ?? $model->role->nombre;

            // ✅ Actualizar o crear persona
            if ($user->getPersona()) {
                $persona = $user->getPersona();
                $model->persona()->updateOrCreate(
                    ['user_id' => $model->id],
                    [
                        'nombres' => $persona->getNombres(),
                        'apellidos' => $persona->getApellidos(),
                        'tipoDocumento' => $persona->getTipoDocumento(),
                        'numeroDocumento' => $persona->getNumeroDocumento(),
                        'telefono' => $persona->getTelefono(),
                        'direccion' => $persona->getDireccion(),
                        'pais' => $persona->getPais(),
                        'religion' => $persona->getReligion(),
                        'correoElectronico' => $persona->getCorreoElectronico(),
                        'correoInstitucional' => $persona->getCorreoElectronico(),
                        'fotoPerfil' => $persona->getFotoPerfil(),
                        'fechaNacimiento' => $persona->getFechaNacimiento()->format('Y-m-d'),
                    ]
                );
            }

            // Actualizar relaciones según el rol
            switch ($currentRole) {
                case 'ROLE_ESTUDIANTE':
                    if ($user->getAlumno()) {
                        $model->alumno()->updateOrCreate(
                            ['user_id' => $model->id],
                            ['codigo_universitario' => $user->getAlumno()->getCodigoUniversitario()]
                        );
                    }
                    break;

                case 'ROLE_JURADO':
                    if ($user->getJurado()) {
                        $model->jurado()->updateOrCreate(
                            ['user_id' => $model->id],
                            ['especialidad' => $user->getJurado()->getEspecialidad()]
                        );
                    }
                    break;

                case 'ROLE_PONENTE':
                    if ($user->getPonente()) {
                        $model->ponente()->updateOrCreate(
                            ['user_id' => $model->id],
                            [
                                'biografia' => $user->getPonente()->getBiografia(),
                                'ponencia_id' => $user->getPonente()->getPonenciaId(), // ✅
                            ]
                        );
                    }
                    break;
            }

            return $model->load(['role', 'persona', 'alumno', 'jurado', 'ponente', 'escuela'])->toDomain();
        });
    }

    /**
     * Eliminar usuario y sus relaciones
     */
    public function delete(int $id): void
    {
        $model = UserModel::find($id);

        if (!$model) {
            throw new ModelNotFoundException("Usuario no encontrado con ID: {$id}");
        }

        DB::transaction(function () use ($model) {
            $model->alumno()?->delete();
            $model->jurado()?->delete();
            $model->ponente()?->delete();
            $model->delete();
        });
    }

    /**
     * Listado paginado (filtrado por rol si aplica)
     */
    public function getUsersPaginated(?string $role = null, int $page = 1, int $perPage = 10): LengthAwarePaginator
    {
        $query = UserModel::with(['role', 'persona', 'alumno', 'jurado', 'ponente']);

        if ($role) {
            $query->whereHas('role', fn($q) => $q->where('nombre', $role));
        }

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Buscar usuarios por nombre, correo o rol
     */
    public function searchUsers(string $term, ?string $role = null, int $perPage = 10): LengthAwarePaginator
    {
        $query = UserModel::with(['role', 'persona'])
            ->where(function ($q) use ($term) {
                $q->where('email', 'like', "%{$term}%")
                    ->orWhereHas('persona', function ($sub) use ($term) {
                        $sub->where('nombres', 'like', "%{$term}%")
                            ->orWhere('apellidos', 'like', "%{$term}%");
                    });
            });

        if ($role) {
            $query->whereHas('role', fn($q) => $q->where('nombre', $role));
        }

        return $query->paginate($perPage);
    }
}
