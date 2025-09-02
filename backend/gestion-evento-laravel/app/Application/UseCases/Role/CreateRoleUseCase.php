<?php

namespace App\Application\UseCases\Role;

use App\Domain\Entities\Role;
use App\Domain\Repositories\RoleRepository;

class CreateRoleUseCase
{
    public function __construct(private RoleRepository $roleRepository) {}

    public function execute(string $nombre, ?string $foto): Role
    {
        // Crear la entidad de dominio Role
        $role = new Role(0, $nombre, $foto); // id = 0 para nuevo rol; se asignará al guardar

        // Guardar el rol usando el repositorio
        return $this->roleRepository->create($role);
    }
}