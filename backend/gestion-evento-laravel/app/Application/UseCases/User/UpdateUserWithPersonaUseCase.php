<?php

namespace App\Application\UseCases\User;

use App\Domain\Entities\Persona;
use App\Domain\Entities\Ponente;
use App\Domain\Entities\Alumno;
use App\Domain\Entities\Jurado;
use App\Domain\Entities\User;
use App\Domain\Repositories\UserRepository;
use DateTime;
use Illuminate\Support\Facades\Hash;
use DomainException;

class UpdateUserWithPersonaUseCase
{
    public function __construct(private UserRepository $users) {}

    public function execute(UpdateUserWithPersonaCommand $cmd): User
    {
        // === 1. Buscar usuario existente ===
        $user = $this->users->findById($cmd->getUserId());
        if (!$user) {
            throw new DomainException("Usuario con ID {$cmd->getUserId()} no encontrado");
        }

        // === 2. Actualizar User ===
        if ($cmd->getEmail() !== null) {
            $user->setEmail($cmd->getEmail());
        }

        if ($cmd->getRawPassword() !== null) {
            $user->setPasswordHash(Hash::make($cmd->getRawPassword()));
        }

        if ($cmd->getEscuelaId() !== null) {
            $user->setEscuelaId($cmd->getEscuelaId());
        }

        if ($cmd->getRoleId() !== null) {
            $user->setRoleId($cmd->getRoleId());
        }

        // === 3. Actualizar Persona ===
        $persona = $user->getPersona();
        if ($persona) {
            if ($cmd->getNombres() !== null) {
                $persona->setNombres($cmd->getNombres());
            }
            if ($cmd->getApellidos() !== null) {
                $persona->setApellidos($cmd->getApellidos());
            }
            if ($cmd->getTipoDocumento() !== null) {
                $persona->setTipoDocumento($cmd->getTipoDocumento());
            }
            if ($cmd->getNumeroDocumento() !== null) {
                $persona->setNumeroDocumento($cmd->getNumeroDocumento());
            }
            if ($cmd->getTelefono() !== null) {
                $persona->setTelefono($cmd->getTelefono());
            }
            if ($cmd->getDireccion() !== null) {
                $persona->setDireccion($cmd->getDireccion());
            }
            if ($cmd->getCorreoElectronico() !== null) {
                $persona->setCorreoElectronico($cmd->getCorreoElectronico());
            }
            if ($cmd->getFotoPerfil() !== null) {
                $persona->setFotoPerfil($cmd->getFotoPerfil());
            }
            if ($cmd->getFechaNacimientoYmd() !== null) {
                $persona->setFechaNacimiento(new DateTime($cmd->getFechaNacimientoYmd()));
            }
        }

        // === 4. Actualizar Ponente (si aplica) ===
        if ($cmd->getBiografia() !== null || $cmd->getPonenciaId() !== null) {
            $ponente = $user->getPonente() ?? new Ponente(null, '', $user->getId(), null);
            $ponente->setBiografia($cmd->getBiografia());
            $ponente->setPonenciaId($cmd->getPonenciaId());
            $user->setPonente($ponente);
        }

        // === 5. Actualizar Alumno (si aplica) ===
        if ($cmd->getCodigoUniversitario() !== null || $cmd->getCarrera() !== null || $cmd->getCiclo() !== null) {
            $alumno = $user->getAlumno() ?? new Alumno(null, $user->getId(), '', '', '');
            $alumno->setCodigoUniversitario($cmd->getCodigoUniversitario());
            $alumno->setCarrera($cmd->getCarrera());
            $alumno->setCiclo($cmd->getCiclo());
            $user->setAlumno($alumno);
        }

        // === 6. Actualizar Jurado (si aplica) ===
        if ($cmd->getEspecialidad() !== null) {
            $jurado = $user->getJurado() ?? new Jurado(null, $user->getId(), '');
            $jurado->setEspecialidad($cmd->getEspecialidad());
            $user->setJurado($jurado);
        }

        // === 7. Guardar cambios ===
        return $this->users->update($user);
    }
}