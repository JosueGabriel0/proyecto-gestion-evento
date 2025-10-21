<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface RoleRepository
{
    public function all(): array;
    public function find(int $id): ?Role;
    public function create(Role $role): Role;
    public function update(Role $role): Role;
    public function delete(int $id): void;
    public function getRolesPaginated(int $page = 1, int $perPage = 10): LengthAwarePaginator;
    public function searchRoles(string $term, int $perPage = 10): LengthAwarePaginator;
}