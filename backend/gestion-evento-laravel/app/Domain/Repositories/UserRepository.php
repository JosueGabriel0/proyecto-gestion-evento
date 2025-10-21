<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepository
{
    public function all(?string $role = null): array;

    public function find(int $id): ?User;

    public function create(User $user, string $role): User;

    public function update(User $user, ?string $role = null): User;

    public function delete(int $id): void;

    public function getUsersPaginated(?string $role = null, int $page = 1, int $perPage = 10): LengthAwarePaginator;

    public function searchUsers(string $term, ?string $role = null, int $perPage = 10): LengthAwarePaginator;
}