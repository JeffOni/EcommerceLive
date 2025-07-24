<?php

namespace App\Providers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Gate para acceso al panel administrativo
        Gate::define('admin-panel', function (User $user) {
            return $user->hasAnyRole([UserRole::ADMIN->value, UserRole::SUPER_ADMIN->value]);
        });

        // Gate para gestión de usuarios
        Gate::define('manage-users', function (User $user) {
            return $user->hasRole(UserRole::SUPER_ADMIN->value);
        });

        // Gate para verificación de pagos
        Gate::define('verify-payments', function (User $user) {
            return $user->hasAnyRole([UserRole::ADMIN->value, UserRole::SUPER_ADMIN->value]);
        });

        // Gate para gestión de productos
        Gate::define('manage-products', function (User $user) {
            return $user->hasAnyRole([UserRole::ADMIN->value, UserRole::SUPER_ADMIN->value]);
        });

        // Gate para gestión de órdenes
        Gate::define('manage-orders', function (User $user) {
            return $user->hasAnyRole([UserRole::ADMIN->value, UserRole::SUPER_ADMIN->value]);
        });

        // Gate para gestión de envíos
        Gate::define('manage-shipments', function (User $user) {
            return $user->hasAnyRole([UserRole::ADMIN->value, UserRole::SUPER_ADMIN->value]);
        });

        // Gate para configuraciones del sistema
        Gate::define('manage-settings', function (User $user) {
            return $user->hasRole(UserRole::SUPER_ADMIN->value);
        });
    }
}
