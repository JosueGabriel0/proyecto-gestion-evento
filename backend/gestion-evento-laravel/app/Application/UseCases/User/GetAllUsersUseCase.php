<?php

namespace App\Application\UseCases\User;

use App\Domain\Repositories\UserRepository;

class GetAllUsersUseCase
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(?string $role = null): array
    {
        return $this->repository->all($role);
    }
}