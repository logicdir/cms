<?php

namespace App\Modules;

use App\Contracts\ModuleInterface;
use Illuminate\Support\Str;

/**
 * Class BaseModule
 *
 * Base class for all modules to extend.
 */
abstract class BaseModule implements ModuleInterface
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $slug;

    /** @var string */
    protected $version = '1.0.0';

    /** @var array<string, string> */
    protected $dependencies = [];

    /** @var array<string> */
    protected $permissions = [];

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name ?? class_basename($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getSlug(): string
    {
        return $this->slug ?? Str::slug($this->getName());
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    /**
     * {@inheritdoc}
     */
    public function install(): bool
    {
        // Default implementation does nothing
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function uninstall(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function activate(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deactivate(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    /**
     * {@inheritdoc}
     */
    public function getMenuItems(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function registerHooks(): void
    {
        // Default implementation does nothing
    }
}
