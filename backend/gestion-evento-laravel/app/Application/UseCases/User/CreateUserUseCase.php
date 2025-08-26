<?php

namespace App\Application\UseCases\User;

use App\Domain\Entities\User;
use App\Domain\Repositories\UserRepository;
use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Password;

class CreateUserUseCase
{
    public function __construct(private UserRepository $userRepository) {}

    public function execute(string $name, string $email, string $password, int $roleId): User
    {
        $user = new User(
            $name,
            new Email($email),
            new Password($password),
            $roleId
        );

        return $this->userRepository->create($user);
    }
}