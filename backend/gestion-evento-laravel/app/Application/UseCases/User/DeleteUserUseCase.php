<?php

namespace App\Application\UseCases\User;

use App\Domain\Repositories\UserRepository;

class DeleteUserUseCase
{
    public function __construct(private UserRepository $userRepository) {}

    public function execute(int $id): void
    {
        $this->userRepository->delete($id);
    }
}