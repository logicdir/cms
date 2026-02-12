<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

/**
 * Interface ModuleRepositoryInterface
 *
 * Handles module storage, retrieval, and lifecycle operations.
 */
interface ModuleRepositoryInterface
{
    /**
     * Get all detected modules.
     * 
     * @return Collection<int, ModuleInterface>
     */
    public function all(): Collection;

    /**
     * Find a module by its slug.
     */
    public function find(string $slug): ?ModuleInterface;

    /**
     * Get all installed modules.
     * 
     * @return Collection<int, ModuleInterface>
     */
    public function installed(): Collection;

    /**
     * Get all active modules.
     * 
     * @return Collection<int, ModuleInterface>
     */
    public function active(): Collection;

    /**
     * Install a module from a given path.
     */
    public function install(string $path): bool;

    /**
     * Uninstall a module by its slug.
     */
    public function uninstall(string $slug): bool;

    /**
     * Activate a module by its slug.
     */
    public function activate(string $slug): bool;

    /**
     * Deactivate a module by its slug.
     */
    public function deactivate(string $slug): bool;
}
