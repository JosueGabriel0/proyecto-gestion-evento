<?php

namespace App\Application\UseCases\Role;

use App\Domain\Repositories\RoleRepository;

class DeleteRoleUseCase
{
    public function __construct(private RoleRepository $roleRepository) {}

    public function execute(int $id): void
    {
        $this->roleRepository->delete($id);
    }
}