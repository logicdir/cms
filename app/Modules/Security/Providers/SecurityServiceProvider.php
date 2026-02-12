<?php

namespace App\Modules\Security\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Security\Http\Middleware\SecurityHeadersMiddleware;
use App\Modules\Security\Http\Middleware\InputSanitizationMiddleware;
use App\Modules\Security\Http\Middleware\FileUploadSecurityMiddleware;

class SecurityServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Settings or bindings
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        // Note: For shared hosting, we recommend adding these to the 'web' group in Kernel.php
        // but we can also register them here if we want them forced.
    }
}
