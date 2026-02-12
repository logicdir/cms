<?php

namespace App\Modules\Core\Contracts\Shortcode;

interface ShortcodeInterface
{
    /**
     * Render the shortcode.
     *
     * @param array $attributes
     * @param string|null $content
     * @return string
     */
    public function render(array $attributes, ?string $content = null): string;

    /**
     * Get the shortcode tag name.
     *
     * @return string
     */
    public function getTag(): string;

    /**
     * Define default attributes for the shortcode.
     *
     * @return array
     */
    public function getDefaultAttributes(): array;
}
