<?php

namespace App\Application\UseCases\User;

use App\Domain\Repositories\UserRepository;

class DeleteUserUseCase
{
    public function __construct(private UserRepository $users) {}

    public function execute(int $userId): void
    {
        // Aquí podrías cargar el User y validar políticas/reglas de dominio antes de borrar.
        $this->users->delete($userId);
    }
}