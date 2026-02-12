<?php

namespace App\Modules\Appearance\Providers;

use Illuminate\Support\ServiceProvider;

class AppearanceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Register theme views
        $this->loadViewsFrom(resource_path('themes'), 'themes');
        
        if (file_exists(__DIR__ . '/../Routes/admin.php')) {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/admin.php');
        }
    }
}
