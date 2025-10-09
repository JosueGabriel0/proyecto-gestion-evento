<?php

namespace App\Domain\ValueObjects;

class Password
{
    private string $value;

    public function __construct(string $value)
    {
        if (strlen($value) < 8) {
            throw new \InvalidArgumentException("Password must be at least 8 characters long");
        }
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Password $other): bool
    {
        return $this->value === $other->value();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}