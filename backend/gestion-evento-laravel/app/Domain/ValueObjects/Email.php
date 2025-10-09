<?php

namespace App\Domain\ValueObjects;

class Email
{
    private string $value;

    public function __construct(string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Invalid email format");
        }
        $this->value = $value;
    }

    // Devuelve el email en formato string
    public function value(): string
    {
        return $this->value;
    }

    // Comparación entre dos objetos Email
    public function equals(Email $other): bool
    {
        return $this->value === $other->value();
    }

    // Convierte a string directamente
    public function __toString(): string
    {
        return $this->value;
    }
}