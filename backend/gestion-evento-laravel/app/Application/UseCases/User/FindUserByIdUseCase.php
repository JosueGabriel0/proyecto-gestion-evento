<?php

namespace App\Application\UseCases\User;

use App\Domain\Repositories\UserRepository;
use App\Domain\Entities\User;

class FindUserByIdUseCase
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(int $id): ?User
    {
        return $this->repository->find($id);
    }
}