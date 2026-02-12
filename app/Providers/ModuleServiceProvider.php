<?php

namespace App\Providers;

use App\Contracts\HookInterface;
use App\Contracts\ModuleRepositoryInterface;
use App\Models\Module;
use App\Repositories\ModuleRepository;
use App\Services\HookManager;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind the Hook Manager
        $this->app->singleton(HookInterface::class, HookManager::class);
        $this->app->alias(HookInterface::class, 'hooks');

        // Bind the Module Repository
        $this->app->singleton(ModuleRepositoryInterface::class, ModuleRepository::class);
        $this->app->alias(ModuleRepositoryInterface::class, 'modules');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (!$this->app->runningInConsole()) {
            $this->bootActiveModules();
        }
    }

    /**
     * Boot all active modules and register their resources.
     */
    protected function bootActiveModules(): void
    {
        /** @var ModuleRepositoryInterface $repository */
        $repository = $this->app->make(ModuleRepositoryInterface::class);
        $activeModules = $repository->active();

        foreach ($activeModules as $module) {
            $moduleSlug = $module->getSlug();
            $modulePath = base_path($this->getModulePath($moduleSlug));

            // 1. Register Module Service Providers
            $manifest = $this->getManifest($modulePath);
            if (isset($manifest['providers'])) {
                foreach ($manifest['providers'] as $provider) {
                    $this->app->register($provider);
                }
            }

            // 2. Load Routes
            if (File::exists($modulePath . '/routes/web.php')) {
                $this->loadRoutesFrom($modulePath . '/routes/web.php');
            }
            if (File::exists($modulePath . '/routes/api.php')) {
                $this->loadRoutesFrom($modulePath . '/routes/api.php');
            }

            // 3. Load Views
            if (File::isDirectory($modulePath . '/resources/views')) {
                $this->loadViewsFrom($modulePath . '/resources/views', $moduleSlug);
            }

            // 4. Load Migrations
            if (File::isDirectory($modulePath . '/database/migrations')) {
                $this->loadMigrationsFrom($modulePath . '/database/migrations');
            }

            // 5. Load Translations
            if (File::isDirectory($modulePath . '/resources/lang')) {
                $this->loadTranslationsFrom($modulePath . '/resources/lang', $moduleSlug);
            }

            // 6. Register Hooks
            $module->registerHooks();
        }
    }

    /**
     * Get the relative path to a module.
     */
    protected function getModulePath(string $slug): string
    {
        return Module::where('slug', $slug)->value('path') ?? "modules/{$slug}";
    }

    /**
     * Read the module manifest.
     */
    protected function getManifest(string $path): array
    {
        $manifestPath = $path . '/module.json';
        if (File::exists($manifestPath)) {
            return json_decode(File::get($manifestPath), true) ?? [];
        }
        return [];
    }
}
