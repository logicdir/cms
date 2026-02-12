<?php

namespace App\Modules\Seo\Providers;

use Illuminate\Support\ServiceProvider;

class SeoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Modules\Seo\Services\SeoMetaService::class);
        $this->app->singleton(\App\Modules\Seo\Services\StructuredDataService::class);
        $this->app->singleton(\App\Modules\Seo\Services\SitemapService::class);
        $this->app->singleton(\App\Modules\Seo\Services\RedirectService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
    }
}
