<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // Modelos => Políticas
    ];

    public function boot(): void
    {
        $this->registerPolicies();
        Passport::routes(); // ✅ Solo aquí
    }
}