<?php

namespace App\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom([
            app_path('Infrastructure/Persistence/Migrations'),
        ]);
    }
}