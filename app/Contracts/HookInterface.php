<?php

namespace App\Contracts;

/**
 * Interface HookInterface
 *
 * Provides a WordPress-like hook system (Actions and Filters).
 */
interface HookInterface
{
    /**
     * Register a callback for a specific hook.
     * 
     * @param string $hook The name of the action or filter.
     * @param callable $callback The callback to execute.
     * @param int $priority Execution priority (lower numbers run first).
     */
    public function register(string $hook, callable $callback, int $priority = 10): void;

    /**
     * Execute all callbacks registered for an action hook.
     * 
     * @param string $hook The name of the action.
     * @param array $args Arguments to pass to the callbacks.
     */
    public function execute(string $hook, array $args = []): void;

    /**
     * Apply all filters registered for a filter hook.
     * 
     * @param string $hook The name of the filter.
     * @param mixed $value The initial value to filter.
     * @param array $args Additional arguments to pass to the filters.
     * @return mixed The filtered value.
     */
    public function filter(string $hook, mixed $value, array $args = []): mixed;
}
