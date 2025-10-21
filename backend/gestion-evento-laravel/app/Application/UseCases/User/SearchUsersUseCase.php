<?php

namespace App\Application\UseCases\User;

use App\Domain\Repositories\UserRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SearchUsersUseCase
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(string $term, ?string $role = null, int $perPage = 10): LengthAwarePaginator
    {
        return $this->repository->searchUsers($term, $role, $perPage);
    }
}