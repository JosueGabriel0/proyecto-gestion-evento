<?php

namespace App\Application\UseCases\Role;

use App\Domain\Entities\Role;
use App\Domain\Repositories\RoleRepository;

class UpdateRoleUseCase
{
    public function __construct(private RoleRepository $roleRepository) {}

    public function execute(int $id, string $nombre, ?string $foto): Role
    {
        // Buscar rol existente
        $existingRole = $this->roleRepository->find($id);
        if (!$existingRole) {
            throw new \RuntimeException("Role not found");
        }

        // Crear nueva entidad con los datos actualizados
        $role = new Role($id, $nombre, $foto);

        return $this->roleRepository->update($role);
    }
}