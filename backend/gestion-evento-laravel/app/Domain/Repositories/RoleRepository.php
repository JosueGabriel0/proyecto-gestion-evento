<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Role;

interface RoleRepository
{
    public function all(): array;
    public function find(int $id): ?Role;
    public function create(Role $user): Role;
    public function update(Role $user): Role;
    public function delete(int $id): void;
    public function getRolesPaginated(int $perPage = 10);
    public function searchRoles(string $term, int $perPage = 10);
}