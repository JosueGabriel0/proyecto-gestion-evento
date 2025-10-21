<?php

namespace App\Application\UseCases\User;

use App\Domain\Repositories\UserRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetPaginatedUsersUseCase
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(?string $role = null, int $page = 1, int $perPage = 10): LengthAwarePaginator
    {
        return $this->repository->getUsersPaginated($role, $page, $perPage);
    }
}