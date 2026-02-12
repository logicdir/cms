<?php

namespace App\Services;

use App\Contracts\HookInterface;

/**
 * Class HookManager
 *
 * Implements a WordPress-inspired hook system with Actions and Filters.
 */
class HookManager implements HookInterface
{
    /**
     * @var array<string, array<int, array<int, callable>>> actions[hook][priority][] = callback
     */
    protected array $actions = [];

    /**
     * @var array<string, array<int, array<int, callable>>> filters[hook][priority][] = callback
     */
    protected array $filters = [];

    /**
     * {@inheritdoc}
     */
    public function register(string $hook, callable $callback, int $priority = 10): void
    {
        // For simplicity, we store filters and actions separately but they behave similarly
        // Most hook systems allow the same hook name for both, but we prefix internally if needed.
        $this->actions[$hook][$priority][] = $callback;
        ksort($this->actions[$hook]);
    }

    /**
     * Register a filter.
     */
    public function registerFilter(string $hook, callable $callback, int $priority = 10): void
    {
        $this->filters[$hook][$priority][] = $callback;
        ksort($this->filters[$hook]);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(string $hook, array $args = []): void
    {
        if (!isset($this->actions[$hook])) {
            return;
        }

        foreach ($this->actions[$hook] as $priority => $callbacks) {
            foreach ($callbacks as $callback) {
                call_user_func_array($callback, $args);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function filter(string $hook, mixed $value, array $args = []): mixed
    {
        if (!isset($this->filters[$hook])) {
            return $value;
        }

        foreach ($this->filters[$hook] as $priority => $callbacks) {
            foreach ($callbacks as $callback) {
                // Prepend the value to the arguments
                $allArgs = array_merge([$value], $args);
                $value = call_user_func_array($callback, $allArgs);
            }
        }

        return $value;
    }
}
