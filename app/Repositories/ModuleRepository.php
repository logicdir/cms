<?php

namespace App\Repositories;

use App\Contracts\ModuleInterface;
use App\Contracts\ModuleRepositoryInterface;
use App\Models\Module;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ModuleRepository implements ModuleRepositoryInterface
{
    /** @var string[] */
    protected $modulePaths = [
        'app/Modules',
        'modules',
    ];

    /**
     * {@inheritdoc}
     */
    public function all(): Collection
    {
        return $this->discoverModules();
    }

    /**
     * {@inheritdoc}
     */
    public function find(string $slug): ?ModuleInterface
    {
        return $this->all()->first(fn(ModuleInterface $module) => $module->getSlug() === $slug);
    }

    /**
     * {@inheritdoc}
     */
    public function installed(): Collection
    {
        $installedSlugs = Module::pluck('slug')->toArray();
        return $this->all()->filter(fn(ModuleInterface $module) => in_array($module->getSlug(), $installedSlugs));
    }

    /**
     * {@inheritdoc}
     */
    public function active(): Collection
    {
        $activeSlugs = Module::where('is_active', true)->pluck('slug')->toArray();
        return $this->all()->filter(fn(ModuleInterface $module) => in_array($module->getSlug(), $activeSlugs));
    }

    /**
     * {@inheritdoc}
     */
    public function install(string $path): bool
    {
        $manifestPath = base_path($path . '/module.json');
        if (!File::exists($manifestPath)) {
            return false;
        }

        $manifest = json_decode(File::get($manifestPath), true);
        if (!$manifest) {
            return false;
        }

        Module::updateOrCreate(
            ['slug' => $manifest['slug']],
            [
                'name' => $manifest['name'],
                'version' => $manifest['version'],
                'description' => $manifest['description'] ?? null,
                'author' => $manifest['author'] ?? null,
                'path' => $path,
                'providers' => $manifest['providers'] ?? [],
                'priority' => $manifest['priority'] ?? 10,
            ]
        );

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function uninstall(string $slug): bool
    {
        $module = Module::where('slug', $slug)->first();
        if ($module) {
            $module->delete();
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function activate(string $slug): bool
    {
        $module = Module::where('slug', $slug)->first();
        if ($module) {
            $module->update(['is_active' => true]);
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function deactivate(string $slug): bool
    {
        $module = Module::where('slug', $slug)->first();
        if ($module) {
            $module->update(['is_active' => false]);
            return true;
        }
        return false;
    }

    /**
     * Discover modules from the filesystem.
     * 
     * @return Collection<int, ModuleInterface>
     */
    protected function discoverModules(): Collection
    {
        $modules = collect();

        foreach ($this->modulePaths as $path) {
            $fullPath = base_path($path);
            if (!File::isDirectory($fullPath)) {
                continue;
            }

            foreach (File::directories($fullPath) as $moduleDir) {
                $manifestFile = $moduleDir . '/module.json';
                if (File::exists($manifestFile)) {
                    $module = $this->createModuleFromManifest($manifestFile, $path . '/' . basename($moduleDir));
                    if ($module) {
                        $modules->push($module);
                    }
                }
            }
        }

        return $modules->sortBy(fn(ModuleInterface $module) => $module instanceof Module ? $module->priority : 10);
    }

    /**
     * Create a module instance from a manifest file.
     */
    protected function createModuleFromManifest(string $manifestPath, string $relativePath): ?ModuleInterface
    {
        $manifest = json_decode(File::get($manifestPath), true);
        if (!$manifest || !isset($manifest['entrypoint'])) {
            return null;
        }

        $className = $manifest['entrypoint'];
        
        // In a real scenario, we'd handle class loading here.
        // For shared hosting without composer dump-autoload, we might need to manually require the file.
        $classFile = base_path($relativePath . '/src/' . str_replace('\\', '/', $className) . '.php');
        if (File::exists($classFile)) {
            require_once $classFile;
        }

        if (class_exists($className)) {
            return new $className();
        }

        return null;
    }
}
