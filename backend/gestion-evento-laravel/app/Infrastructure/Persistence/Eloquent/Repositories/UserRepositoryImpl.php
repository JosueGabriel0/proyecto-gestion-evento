<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Repositories\UserRepository;
use App\Domain\Entities\User;
use App\Domain\ValueObjects\Email;
use App\Domain\ValueObjects\Password;
use App\Infrastructure\Persistence\Eloquent\Models\UserModel;

class UserRepositoryImpl implements UserRepository
{
    public function all(): array
    {
        return UserModel::all()
            ->map(fn(UserModel $model) => $this->mapToEntity($model))
            ->toArray();
    }

    public function find(int $id): ?User
    {
        $model = UserModel::find($id);
        return $model ? $this->mapToEntity($model) : null;
    }

    public function create(User $user): User
    {
        $model = new UserModel();
        $model->name = $user->getName();
        $model->email = (string) $user->getEmail();
        $model->password = (string) $user->getPassword();
        $model->role_id = $user->getRoleId();
        $model->save();

        return $this->mapToEntity($model);
    }

    public function update(User $user): User
    {
        $model = UserModel::where('email', (string) $user->getEmail())->firstOrFail();
        $model->name = $user->getName();
        $model->password = (string) $user->getPassword();
        $model->role_id = $user->getRoleId();
        $model->save();

        return $this->mapToEntity($model);
    }

    public function delete(int $id): void
    {
        UserModel::destroy($id);
    }

    /**
     * Convierte un Eloquent Model a Domain Entity
     */
    private function mapToEntity(UserModel $model): User
    {
        return new User(
            $model->name,
            new Email($model->email),
            new Password($model->password),
            $model->role_id
        );
    }
}