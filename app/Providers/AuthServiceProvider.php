<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Models\Board;
use App\Models\Tenant;
use App\Policies\BoardPolicy;
use App\Policies\TenantPolicy;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * O array de mapeamento de policies da aplicação.
     */
    protected $policies = [
        Board::class => BoardPolicy::class,
        Tenant::class => TenantPolicy::class,
        // Se tiver outras policies, adiciona aqui
    ];

    /**
     * Bootstrap any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('admin', function ($user) {
            return $user->is_admin === 1;
        });
    }
}
