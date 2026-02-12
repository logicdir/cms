<?php

namespace App\Modules\Core\Services\Shortcode;

use App\Modules\Core\Contracts\Shortcode\ShortcodeInterface;

class ShortcodeRegistry
{
    /** @var array<string, ShortcodeInterface> */
    protected array $shortcodes = [];

    /**
     * Register a shortcode handler.
     */
    public function register(ShortcodeInterface $shortcode): void
    {
        $this->shortcodes[$shortcode->getTag()] = $shortcode;
    }

    /**
     * Check if a shortcode tag is registered.
     */
    public function has(string $tag): bool
    {
        return isset($this->shortcodes[$tag]);
    }

    /**
     * Get a registered shortcode handler.
     */
    public function get(string $tag): ?ShortcodeInterface
    {
        return $this->shortcodes[$tag] ?? null;
    }

    /**
     * Get all registered tags.
     */
    public function getRegisteredTags(): array
    {
        return array_keys($this->shortcodes);
    }
}
