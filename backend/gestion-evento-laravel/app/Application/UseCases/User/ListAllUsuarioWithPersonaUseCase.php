<?php

namespace App\Application\UseCases\User;

use App\Domain\Entities\User;
use App\Domain\Repositories\UserRepository;

class ListAllUsuarioWithPersonaUseCase
{
    public function __construct(private UserRepository $users) {}

    /**
     * @return User[]  // cada User viene con ->getPersona() si existe
     */
    public function execute(): array
    {
        return $this->users->findAll();
    }
}