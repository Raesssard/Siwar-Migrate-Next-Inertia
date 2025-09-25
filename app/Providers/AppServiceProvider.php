<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

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
        Carbon::setLocale('id');
        // Di AppServiceProvider.php (atau file helper lainnya)
        // Anda bisa membuat helper custom misalnya
        Inertia::share('auth', function () {
            $user = Auth::user();

            return [
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'roles' => $user->getRoleNames(),        // array of roles
                    'permissions' => $user->getAllPermissions()->pluck('name'), // array of permissions
                ] : null,
            ];
        });
    }
}
