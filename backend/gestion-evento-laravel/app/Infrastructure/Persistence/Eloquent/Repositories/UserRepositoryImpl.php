<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Entities\Persona as PersonaEntity;
use App\Domain\Entities\User as UserEntity;
use App\Domain\Entities\Role as RoleEntity;
use App\Domain\Entities\Alumno as AlumnoEntity;
use App\Domain\Entities\Ponente as PonenteEntity;
use App\Domain\Entities\Jurado as JuradoEntity;
use App\Domain\Repositories\UserRepository;
use App\Infrastructure\Persistence\Eloquent\Models\AlumnoModel;
use App\Infrastructure\Persistence\Eloquent\Models\JuradoModel;
use App\Infrastructure\Persistence\Eloquent\Models\UserModel as UserModel;
use App\Infrastructure\Persistence\Eloquent\Models\PersonaModel as PersonaModel;
use App\Infrastructure\Persistence\Eloquent\Models\PonenteModel;
use Illuminate\Support\Facades\DB;

class UserRepositoryImpl implements UserRepository
{
    public function create(UserEntity $user): UserEntity
    {
        return DB::transaction(function () use ($user) {
            if ($user->getId() !== null) {
                throw new \InvalidArgumentException('create() no acepta entidades con ID. Usa update().');
            }

            // User
            $userModel = new UserModel();
            $userModel->email       = $user->getEmail();
            $userModel->password    = $user->getPasswordHash();
            $userModel->escuela_id  = $user->getEscuelaId();
            $userModel->role_id     = $user->getRoleId();
            $userModel->save();

            $user->setId((int)$userModel->id);

            // Persona
            $persona = $user->getPersona();
            if ($persona) {
                $personaModel = new PersonaModel();
                $personaModel->nombres           = $persona->getNombres();
                $personaModel->apellidos         = $persona->getApellidos();
                $personaModel->tipoDocumento     = $persona->getTipoDocumento();
                $personaModel->numeroDocumento   = $persona->getNumeroDocumento();
                $personaModel->telefono          = $persona->getTelefono();
                $personaModel->direccion         = $persona->getDireccion();
                $personaModel->correoElectronico = $persona->getCorreoElectronico();
                $personaModel->fotoPerfil        = $persona->getFotoPerfil();
                $personaModel->fechaNacimiento   = $persona->getFechaNacimiento()->format('Y-m-d');
                $personaModel->user_id           = $userModel->id;
                $personaModel->save();

                $persona->setId((int)$personaModel->id);
                $persona->attachToUser((int)$userModel->id);
            }

            // Ponente
            $ponente = $user->getPonente();
            if ($ponente) {
                $ponenteModel = new PonenteModel();
                $ponenteModel->biografia   = $ponente->getBiografia();
                $ponenteModel->user_id = $userModel->id;
                $ponenteModel->ponencia_id = $ponente->getPonenciaId();
                $ponenteModel->save();

                $ponente->setId((int)$ponenteModel->id);
            }

            // Alumno
            $alumno = $user->getAlumno();
            if ($alumno) {
                $alumnoModel = new AlumnoModel();
                $alumnoModel->user_id = $userModel->id;
                $alumnoModel->codigo_universitario = $alumno->getCodigoUniversitario();
                $alumnoModel->carrera  = $alumno->getCarrera();
                $alumnoModel->ciclo    = $alumno->getCiclo();
                $alumnoModel->save();

                $alumno->setId((int)$alumnoModel->id);
            }

            // Jurado
            $jurado = $user->getJurado();
            if ($jurado) {
                $juradoModel = new JuradoModel();
                $juradoModel->user_id  = $userModel->id;
                $juradoModel->especialidad = $jurado->getEspecialidad();
                $juradoModel->save();

                $jurado->setId((int)$juradoModel->id);
            }

            $userModel = UserModel::with(['persona', 'role', 'ponente', 'alumno', 'jurado'])->findOrFail($userModel->id);

            return $this->mapModelToEntity($userModel);
        });
    }

    public function update(UserEntity $user): UserEntity
    {
        if ($user->getId() === null) {
            throw new \InvalidArgumentException('update() requiere un ID. Usa create() para nuevos registros.');
        }

        return DB::transaction(function () use ($user) {
            /** @var UserModel $userModel */
            $userModel = UserModel::with(['persona', 'ponente', 'alumno', 'jurado'])->findOrFail($user->getId());

            $userModel->email       = $user->getEmail();
            $userModel->password    = $user->getPasswordHash();
            $userModel->escuela_id  = $user->getEscuelaId();
            $userModel->role_id     = $user->getRoleId();
            $userModel->save();

            // Persona
            $persona = $user->getPersona();
            if ($persona) {
                $personaModel = $persona->getId()
                    ? PersonaModel::findOrFail($persona->getId())
                    : ($userModel->persona ?? new PersonaModel());

                $personaModel->nombres           = $persona->getNombres();
                $personaModel->apellidos         = $persona->getApellidos();
                $personaModel->tipoDocumento     = $persona->getTipoDocumento();
                $personaModel->numeroDocumento   = $persona->getNumeroDocumento();
                $personaModel->telefono          = $persona->getTelefono();
                $personaModel->direccion         = $persona->getDireccion();
                $personaModel->correoElectronico = $persona->getCorreoElectronico();
                $personaModel->fotoPerfil        = $persona->getFotoPerfil();
                $personaModel->fechaNacimiento   = $persona->getFechaNacimiento()->format('Y-m-d');
                $personaModel->user_id           = $userModel->id;
                $personaModel->save();

                if ($persona->getId() === null) {
                    $persona->setId((int)$personaModel->id);
                }
                $persona->attachToUser((int)$userModel->id);
            }

            // Ponente
            $ponente = $user->getPonente();
            if ($ponente) {
                $ponenteModel = $ponente->getId()
                    ? PonenteModel::findOrFail($ponente->getId())
                    : ($userModel->ponente ?? new PonenteModel());
                $ponenteModel->biografia   = $ponente->getBiografia();
                $ponenteModel->user_id = $userModel->id;
                $ponenteModel->ponencia_id = $ponente->getPonenciaId();
                $ponenteModel->save();

                if ($ponente->getId() === null) {
                    $ponente->setId((int)$ponenteModel->id);
                }
            }

            // Alumno
            $alumno = $user->getAlumno();
            if ($alumno) {
                $alumnoModel = $alumno->getId()
                    ? AlumnoModel::findOrFail($alumno->getId())
                    : ($userModel->alumno ?? new AlumnoModel());
                $alumnoModel->user_id = $userModel->id;
                $alumnoModel->codigo_universitario = $alumno->getCodigoUniversitario();
                $alumnoModel->carrera   = $alumno->getCarrera();
                $alumnoModel->ciclo     = $alumno->getCiclo();
                $alumnoModel->save();

                if ($alumno->getId() === null) {
                    $alumno->setId((int)$alumnoModel->id);
                }
            }

            // Jurado
            $jurado = $user->getJurado();
            if ($jurado) {
                $juradoModel = $jurado->getId()
                    ? JuradoModel::findOrFail($jurado->getId())
                    : ($userModel->jurado ?? new JuradoModel());
                $juradoModel->user_id  = $userModel->id;
                $juradoModel->especialidad = $jurado->getEspecialidad();
                $juradoModel->save();

                if ($jurado->getId() === null) {
                    $jurado->setId((int)$juradoModel->id);
                }
            }

            return $this->mapModelToEntity($userModel->fresh(['persona', 'ponente', 'alumno', 'jurado']));
        });
    }

    public function findById(int $id): ?UserEntity
    {
        $m = UserModel::with(['persona', 'role', 'ponente', 'alumno', 'jurado'])->find($id);
        if (!$m) return null;

        return $this->mapModelToEntity($m);
    }

    public function findAll(): array
    {
        $models = UserModel::with(['persona', 'role', 'ponente', 'alumno', 'jurado'])->get();

        return $models
            ->map(fn(UserModel $m) => $this->mapModelToEntity($m))
            ->all();
    }

    public function delete(int $id): void
    {
        DB::transaction(function () use ($id) {
            $m = UserModel::with(['persona', 'alumno', 'ponente', 'jurado'])->findOrFail($id);

            if ($m->persona) {
                $m->persona()->delete();
            }
            if ($m->alumno) {
                $m->alumno()->delete();
            }
            if ($m->ponente) {
                $m->ponente()->delete();
            }
            if ($m->jurado) {
                $m->jurado()->delete();
            }

            $m->delete();
        });
    }

    private function mapModelToEntity(UserModel $m): UserEntity
    {
        $persona = $m->persona ? new PersonaEntity(
            id: (int)$m->persona->id,
            nombres: $m->persona->nombres,
            apellidos: $m->persona->apellidos,
            tipoDocumento: $m->persona->tipoDocumento,
            numeroDocumento: $m->persona->numeroDocumento,
            telefono: $m->persona->telefono,
            direccion: $m->persona->direccion,
            correoElectronico: $m->persona->correoElectronico,
            fotoPerfil: $m->persona->fotoPerfil,
            fechaNacimiento: new \DateTime($m->persona->fechaNacimiento),
            userId: (int)$m->id
        ) : null;

        $role = $m->role ? new RoleEntity(
            id: (int)$m->role->id,
            nombre: $m->role->nombre,
            foto: $m->role->foto
        ) : null;

        $alumno = $m->alumno ? new AlumnoEntity(
            id: (int)$m->alumno->id,
            userId: (int)$m->alumno->user_id,
            codigo_universitario: $m->alumno->codigo_universitario,
            carrera: $m->alumno->carrera,
            ciclo: $m->alumno->ciclo
        ) : null;

        $ponente = $m->ponente ? new PonenteEntity(
            id: (int)$m->ponente->id,
            biografia: $m->ponente->biografia,
            userId: (int)$m->ponente->user_id,
            ponenciaId: $m->ponente->ponencia_id
        ) : null;

        $jurado = $m->jurado ? new JuradoEntity(
            id: (int)$m->jurado->id,
            userId: (int)$m->jurado->user_id,
            especialidad: $m->jurado->especialidad
        ) : null;

        return new UserEntity(
            id: (int)$m->id,
            email: $m->email,
            passwordHash: $m->password,
            escuelaId: (int)$m->escuela_id,
            roleId: $m->role_id ? (int)$m->role_id : null,
            emailVerifiedAt: $m->email_verified_at ? new \DateTime($m->email_verified_at) : null,
            rememberToken: $m->remember_token,
            persona: $persona,
            role: $role,
            alumno: $alumno,
            ponente: $ponente,
            jurado: $jurado
        );
    }
}
