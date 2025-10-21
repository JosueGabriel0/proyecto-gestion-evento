<?php

namespace App\Application\UseCases\User;

use App\Domain\Repositories\UserRepository;
use App\Domain\Entities\User;

class UpdateUserUseCase
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(User $user, ?string $role = null): User
    {
        return $this->repository->update($user, $role);
    }
}