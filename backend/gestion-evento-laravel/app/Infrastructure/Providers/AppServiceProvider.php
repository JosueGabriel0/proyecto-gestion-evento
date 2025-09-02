<?php

namespace App\Infrastructure\Providers;

use App\Domain\Repositories\FilialRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\FilialRepositoryImpl;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
         $this->app->bind(FilialRepository::class, FilialRepositoryImpl::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom([
            app_path('Infrastructure/Persistence/Migrations'),
        ]);
    }
}