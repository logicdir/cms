<?php

namespace App\Modules\Content\Services;

class ShortcodeRegistry
{
    protected array $shortcodes = [];

    public function register(string $tag, callable $handler): void
    {
        $this->shortcodes[$tag] = $handler;
    }

    public function has(string $tag): bool
    {
        return isset($this->shortcodes[$tag]);
    }

    public function get(string $tag): ?callable
    {
        return $this->shortcodes[$tag] ?? null;
    }

    public function all(): array
    {
        return array_keys($this->shortcodes);
    }

    public function execute(string $tag, array $attributes = [], ?string $content = null): string
    {
        if (!$this->has($tag)) {
            return '';
        }

        $handler = $this->get($tag);
        return call_user_func($handler, $attributes, $content);
    }
}
