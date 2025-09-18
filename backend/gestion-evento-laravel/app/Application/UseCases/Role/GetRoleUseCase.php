<?php

namespace App\Application\UseCases\Role;

use App\Domain\Repositories\RoleRepository;
use App\Domain\Entities\Role;

class GetRoleUseCase
{
    public function __construct(private RoleRepository $roleRepository) {}

    public function execute(int $id): ?Role
    {
        return $this->roleRepository->find($id);
    }
}