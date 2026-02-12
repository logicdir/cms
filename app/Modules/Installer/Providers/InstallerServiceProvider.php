<?php

namespace App\Modules\Installer\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Installer\Http\Middleware\EnsureNotInstalled;

class InstallerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'installer');
        
        // Load routes
        if (file_exists(__DIR__ . '/../Routes/installer.php')) {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/installer.php');
        }

        // Register middleware
        $this->app['router']->aliasMiddleware('installer', EnsureNotInstalled::class);
    }
}
