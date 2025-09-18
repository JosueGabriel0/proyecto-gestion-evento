<?php

namespace App\Application\UseCases\User;

use App\Domain\Entities\User;
use App\Domain\Repositories\UserRepository;
use DomainException;

class FindUserByIdUseCase
{
    public function __construct(private UserRepository $users) {}

    /**
     * Busca un usuario (con persona) por ID.
     *
     * @param int $id
     * @return User
     *
     * @throws DomainException si no existe
     */
    public function execute(int $id): User
    {
        $user = $this->users->findById($id);

        if (!$user) {
            throw new DomainException("Usuario con ID {$id} no encontrado");
        }

        return $user;
    }
}