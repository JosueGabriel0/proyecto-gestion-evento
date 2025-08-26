<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\User;

interface UserRepository
{
    public function all(): array;
    public function find(int $id): ?User;
    public function create(User $user): User;
    public function update(User $user): User;
    public function delete(int $id): void;
}