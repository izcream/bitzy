<?php

namespace App\Providers;

use App\Models\Link;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('delete-link', function (User $user, Link $link): bool {
            return $user->id === $link->user_id || in_array($user->role, ['admin', 'superadmin']);
        });
        Gate::define('manage-link', function (User $user): bool {
            return in_array($user->role, ['admin', 'superadmin']);
        });
        Gate::define('manage-user', function (User $user): bool {
            return in_array($user->role, ['admin', 'superadmin']);
        });
    }
}
