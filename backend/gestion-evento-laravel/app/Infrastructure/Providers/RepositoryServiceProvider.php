<?php

namespace App\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Repositories\UserRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\UserRepositoryImpl;

// Para roles
use App\Domain\Repositories\RoleRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\RoleRepositoryImpl;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // User repository
        $this->app->bind(UserRepository::class, UserRepositoryImpl::class);

        // Role repository
        $this->app->bind(RoleRepository::class, RoleRepositoryImpl::class);
    }
}