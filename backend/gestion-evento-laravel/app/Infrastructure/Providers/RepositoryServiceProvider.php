<?php

namespace App\Infrastructure\Providers;

use App\Domain\Repositories\EscuelaRepository;
use App\Domain\Repositories\FacultadRepository;
use App\Domain\Repositories\FilialRepository;
use App\Domain\Repositories\RoleRepository;
use App\Domain\Repositories\UserRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\EscuelaRepositoryImpl;
use App\Infrastructure\Persistence\Eloquent\Repositories\FacultadRepositoryImpl;
use App\Infrastructure\Persistence\Eloquent\Repositories\FilialRepositoryImpl;
use App\Infrastructure\Persistence\Eloquent\Repositories\RoleRepositoryImpl;
use App\Infrastructure\Persistence\Eloquent\Repositories\UserRepositoryImpl;
use Carbon\Laravel\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(FilialRepository::class, FilialRepositoryImpl::class);

        $this->app->bind(FacultadRepository::class, FacultadRepositoryImpl::class);

        $this->app->bind(EscuelaRepository::class, EscuelaRepositoryImpl::class);

        $this->app->bind(RoleRepository::class, RoleRepositoryImpl::class);

        $this->app->bind(UserRepository::class, UserRepositoryImpl::class);
    }
}