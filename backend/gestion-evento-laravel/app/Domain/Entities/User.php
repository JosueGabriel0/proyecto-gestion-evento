<?php

namespace App\Domain\Entities;

use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Password;

class User
{
    private ?int $id;   // 👈 puede ser null si aún no está persistido
    private string $name;
    private Email $email;
    private Password $password;
    private int $roleId;

    public function __construct(?int $id, string $name, Email $email, Password $password, int $roleId)
    {
        $this->id = $id;
        $this->setName($name);
        $this->email = $email;
        $this->password = $password;
        $this->roleId = $roleId;
    }

    // Getters
    public function getId(): ?int { return $this->id; }   // 👈 ahora existe
    public function getName(): string { return $this->name; }
    public function getEmail(): Email { return $this->email; }
    public function getPassword(): Password { return $this->password; }
    public function getRoleId(): int { return $this->roleId; }

    // Reglas de negocio simples
    private function setName(string $name): void
    {
        if (strlen($name) < 3) {
            throw new \InvalidArgumentException("El nombre debe tener al menos 3 caracteres.");
        }
        $this->name = $name;
    }
}