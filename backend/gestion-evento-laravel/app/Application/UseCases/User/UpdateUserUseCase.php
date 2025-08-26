<?php

namespace App\Application\UseCases\User;

use App\Domain\Entities\User;
use App\Domain\Repositories\UserRepository;
use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Password;

class UpdateUserUseCase
{
    public function __construct(private UserRepository $userRepository) {}

    public function execute(int $id, string $name, string $email, string $password, int $roleId): User
    {
        // Buscar usuario existente
        $existingUser = $this->userRepository->find($id);
        if (!$existingUser) {
            throw new \RuntimeException("User not found");
        }

        // Crear nueva entidad con los datos actualizados
        $user = new User(
            $name,
            new Email($email),
            new Password($password),
            $roleId
        );

        return $this->userRepository->update($user);
    }
}