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

class CreateUserWithPersonaUseCase
{
    public function __construct(private UserRepository $users) {}

    public function execute(CreateUserWithPersonaCommand $cmd): User
    {
        // === Ponente ===
        $ponente = null;
        if ($cmd->getBiografia() !== null) {
            $ponente = new Ponente(
                id: null,
                biografia: $cmd->getBiografia(),
                userId: null,
                ponenciaId: $cmd->getPonenciaId()
            );
        }

        // === Alumno ===
        $alumno = null;
        if ($cmd->getCodigoUniversitario() !== null) {
            $alumno = new Alumno(
                id: null,
                userId: null,
                codigo_universitario: $cmd->getCodigoUniversitario(),
                carrera: $cmd->getCarrera(),
                ciclo: $cmd->getCiclo()
            );
        }

        // === Jurado ===
        $jurado = null;
        if ($cmd->getEspecialidad() !== null) {
            $jurado = new Jurado(
                id: null,
                userId: null,
                especialidad: $cmd->getEspecialidad()
            );
        }

        // === Persona ===
        $persona = new Persona(
            id: null,
            nombres: $cmd->getNombres(),
            apellidos: $cmd->getApellidos(),
            tipoDocumento: $cmd->getTipoDocumento(),
            numeroDocumento: $cmd->getNumeroDocumento(),
            telefono: $cmd->getTelefono(),
            direccion: $cmd->getDireccion(),
            correoElectronico: $cmd->getCorreoElectronico(),
            fotoPerfil: $cmd->getFotoPerfil(),
            fechaNacimiento: new DateTime($cmd->getFechaNacimientoYmd()),
            userId: null
        );

        // === User (Aggregate Root) ===
        $user = new User(
            id: null,
            email: $cmd->getEmail(),
            passwordHash: Hash::make($cmd->getRawPassword()),
            escuelaId: $cmd->getEscuelaId(),
            roleId: $cmd->getRoleId(),
            emailVerifiedAt: null,
            rememberToken: null,
            persona: $persona,
            ponente: $ponente,
            alumno: $alumno,
            jurado: $jurado,
            role: null // normalmente se carga después desde el repo
        );

        return $this->users->create($user);
    }
}