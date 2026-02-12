<?php

namespace App\Contracts;

/**
 * Interface ModuleInterface
 *
 * Defines the core contract for LogicDir CMS modules.
 */
interface ModuleInterface
{
    /**
     * Get the module name.
     */
    public function getName(): string;

    /**
     * Get the module slug (unique identifier).
     */
    public function getSlug(): string;

    /**
     * Get the module version (semver).
     */
    public function getVersion(): string;

    /**
     * Get the list of dependencies.
     * 
     * @return array<string, string> slug => version constraint
     */
    public function getDependencies(): array;

    /**
     * Perform installation logic (e.g., migrations, initial config).
     */
    public function install(): bool;

    /**
     * Perform uninstallation logic (e.g., cleanup).
     */
    public function uninstall(): bool;

    /**
     * Activate the module.
     */
    public function activate(): bool;

    /**
     * Deactivate the module.
     */
    public function deactivate(): bool;

    /**
     * Get the list of permissions registered by this module.
     * 
     * @return array<string>
     */
    public function getPermissions(): array;

    /**
     * Get the menu items for the admin sidebar.
     * 
     * @return array<string, mixed>
     */
    public function getMenuItems(): array;

    /**
     * Register action and filter hooks.
     */
    public function registerHooks(): void;
}
