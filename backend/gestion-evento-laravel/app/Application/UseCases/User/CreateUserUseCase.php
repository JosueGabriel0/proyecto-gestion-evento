<?php

namespace App\Application\UseCases\User;

use App\Domain\Repositories\UserRepository;
use App\Domain\Entities\User;

class CreateUserUseCase
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(User $user, string $role): User
    {
        return $this->repository->create($user, $role);
    }
}