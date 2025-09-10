<?php

namespace App\Domain\Entities;

class Jurado
{
    private ?int $id;
    private ?int $userId;
    private string $especialidad;

    /**
     * Constructor
     */
    public function __construct(?int $id, ?int $userId, ?string $especialidad)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->especialidad = $especialidad;
    }

    // 🔹 Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getEspecialidad(): string
    {
        return $this->especialidad;
    }

    // 🔹 Setters
    public function setId(?int $id): void
    {
        $this->id = $id;
    }
    
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function setEspecialidad(string $especialidad): void
    {
        $this->especialidad = $especialidad;
    }

    // 🔹 Relaciones (opcionalmente puedes agregarlas como propiedades inyectadas desde el repository)
    private ?object $user = null;
    private array $evaluaciones = [];

    public function setUser(object $user): void
    {
        $this->user = $user;
    }

    public function getUser(): ?object
    {
        return $this->user;
    }

    public function setEvaluaciones(array $evaluaciones): void
    {
        $this->evaluaciones = $evaluaciones;
    }

    public function getEvaluaciones(): array
    {
        return $this->evaluaciones;
    }
}