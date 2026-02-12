<?php

namespace App\Modules\User\Providers;

use App\Modules\User\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        
        if (file_exists(__DIR__ . '/../Routes/admin.php')) {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/admin.php');
        }

        if (file_exists(__DIR__ . '/../Routes/web.php')) {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        }

        // Define Super Admin Gate
        Gate::before(function ($user, $ability) {
            if ($user instanceof User && $user->isSuperAdmin()) {
                return true;
            }
        });

        // Register dynamic permissions
        $this->registerPermissions();
    }

    protected function registerPermissions(): void
    {
        // This would ideally be cached
        // For each permission in DB, define a Gate
        try {
            if ($this->app->runningInConsole() === false) {
                // We'll use a more efficient way in production, but for now:
                // \App\Modules\User\Models\Permission::all()->each(function ($permission) {
                //     Gate::define($permission->slug, function (User $user) use ($permission) {
                //         return $user->hasPermission($permission->slug);
                //     });
                // });
            }
        } catch (\Throwable $e) {
            // Migrations might not have run yet
        }
    }
}
