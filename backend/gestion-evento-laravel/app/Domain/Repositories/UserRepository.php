<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\User as UserEntity;

interface UserRepository
{
    public function create(UserEntity $user): UserEntity;
    public function update(UserEntity $user): UserEntity;
    public function findAll(): array;                 // array<UserEntity>
    public function findById(int $id): ?UserEntity;
    public function delete(int $id): void;
}