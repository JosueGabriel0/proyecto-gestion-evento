<?php

namespace App\Domain\Entities;

use DateTime;

class Persona
{
    public function __construct(
        private ?int $id,
        private string $nombres,
        private string $apellidos,
        private string $tipoDocumento,
        private string $numeroDocumento,
        private string $telefono,
        private string $direccion,
        private string $pais,
        private string $religion,
        private string $correoElectronico,
        private string $correoInstitucional,
        private ?string $fotoPerfil,
        private DateTime $fechaNacimiento,
        private ?int $userId = null,
    ) {}

    // ==== Getters ====
    public function getId(): ?int { return $this->id; }
    public function getUserId(): ?int { return $this->userId; }

    public function getNombres(): string { return $this->nombres; }
    public function getApellidos(): string { return $this->apellidos; }
    public function getTipoDocumento(): string { return $this->tipoDocumento; }
    public function getNumeroDocumento(): string { return $this->numeroDocumento; }
    public function getTelefono(): string { return $this->telefono; }
    public function getDireccion(): string { return $this->direccion; }
    public function getPais(): string { return $this->pais; }
    public function getReligion(): string { return $this->religion; }
    public function getCorreoElectronico(): string { return $this->correoElectronico; }
    public function getCorreoInstitucional(): string { return $this->correoInstitucional; }
    public function getFotoPerfil(): ?string { return $this->fotoPerfil; }
    public function getFechaNacimiento(): DateTime { return $this->fechaNacimiento; }

    // ==== Asociaciones ====
    public function setId(?int $id): void { $this->id = $id; }
    public function attachToUser(int $userId): void { $this->userId = $userId; }

    // ==== Setters (para update) ====
    public function setNombres(string $nombres): void { $this->nombres = $nombres; }
    public function setApellidos(string $apellidos): void { $this->apellidos = $apellidos; }
    public function setTipoDocumento(string $tipoDocumento): void { $this->tipoDocumento = $tipoDocumento; }
    public function setNumeroDocumento(string $numeroDocumento): void { $this->numeroDocumento = $numeroDocumento; }
    public function setTelefono(string $telefono): void { $this->telefono = $telefono; }
    public function setDireccion(string $direccion): void { $this->direccion = $direccion; }
    public function setPais(string $pais): void { $this->pais = $pais; }
    public function setRelgion(string $religion): void { $this->religion = $religion; }
    public function setCorreoElectronico(string $correoElectronico): void { $this->correoElectronico = $correoElectronico; }
    public function setCorreoInstitucional(string $correoInstitucional): void { $this->correoInstitucional = $correoInstitucional; }
    public function setFotoPerfil(?string $foto): void { $this->fotoPerfil = $foto; }
    public function setFechaNacimiento(DateTime $fechaNacimiento): void { $this->fechaNacimiento = $fechaNacimiento; }
}