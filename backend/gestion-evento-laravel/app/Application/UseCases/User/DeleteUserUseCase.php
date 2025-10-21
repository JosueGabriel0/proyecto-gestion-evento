<?php

namespace App\Application\UseCases\User;

use App\Domain\Repositories\UserRepository;

class DeleteUserUseCase
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(int $id): void
    {
        $this->repository->delete($id);
    }
}