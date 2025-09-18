<?php

namespace App\Application\UseCases\Role;

use App\Domain\Repositories\RoleRepository;

class ListRoleUseCase
{
    public function __construct(private RoleRepository $roleRepository) {}

    public function execute(): array
    {
        return $this->roleRepository->all();
    }
}