<?php

namespace App\Application\UseCases\User;

use App\Domain\Repositories\UserRepository;

class ListUsersUseCase
{
    public function __construct(private UserRepository $userRepository) {}

    public function execute(): array
    {
        return $this->userRepository->all();
    }
}