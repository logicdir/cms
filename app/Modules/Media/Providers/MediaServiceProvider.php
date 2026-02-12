<?php

namespace App\Modules\Media\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class MediaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Modules\Media\Services\MediaShortcode::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->registerRoutes();
    }

    /**
     * Register module routes.
     */
    protected function registerRoutes(): void
    {
        Route::middleware(['web', 'auth'])
            ->prefix('admin/media')
            ->name('admin.media.')
            ->group(__DIR__ . '/../Routes/admin.php');
    }
}
