<?php

namespace App\Application\UseCases\User;

use App\Domain\Repositories\UserRepository;
use App\Domain\Entities\User;

class GetUserUseCase
{
    public function __construct(private UserRepository $userRepository) {}

    public function execute(int $id): ?User
    {
        return $this->userRepository->find($id);
    }
}