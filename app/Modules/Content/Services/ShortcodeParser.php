<?php

namespace App\Modules\Content\Services;

use Illuminate\Support\Facades\Cache;

class ShortcodeParser
{
    protected const MAX_DEPTH = 3;
    protected ShortcodeRegistry $registry;
    protected array $whitelist;

    public function __construct(ShortcodeRegistry $registry, array $whitelist = [])
    {
        $this->registry = $registry;
        $this->whitelist = $whitelist;
    }

    public function parse(string $content, int $depth = 0): string
    {
        if ($depth >= self::MAX_DEPTH) {
            return $content;
        }

        // Check cache
        $hash = md5($content . $depth);
        $cached = Cache::get("shortcode.parsed.{$hash}");
        
        if ($cached !== null) {
            return $cached;
        }

        // Pattern: [tag attr="value"]content[/tag] or [tag attr="value"]
        $pattern = '/\[(\w+)(?:\s+([^\]]*))?\](?:(.*?)\[\/\1\])?/s';

        $parsed = preg_replace_callback($pattern, function ($matches) use ($depth) {
            $tag = $matches[1];
            $attrString = $matches[2] ?? '';
            $content = $matches[3] ?? null;

            // Security: Check whitelist
            if (!empty($this->whitelist) && !in_array($tag, $this->whitelist)) {
                return $matches[0]; // Return unchanged
            }

            // Check if shortcode is registered
            if (!$this->registry->has($tag)) {
                return $matches[0]; // Return unchanged
            }

            // Parse attributes
            $attributes = $this->parseAttributes($attrString);

            // Execute shortcode
            $result = $this->registry->execute($tag, $attributes, $content);

            // Recursively parse if result contains more shortcodes
            return $this->parse($result, $depth + 1);
        }, $content);

        // Cache the result
        Cache::put("shortcode.parsed.{$hash}", $parsed, 3600);

        return $parsed;
    }

    public function extract(string $content): array
    {
        $pattern = '/\[(\w+)(?:\s+([^\]]*))?\](?:(.*?)\[\/\1\])?/s';
        $shortcodes = [];

        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $shortcodes[] = [
                'tag' => $match[1],
                'attributes' => $this->parseAttributes($match[2] ?? ''),
                'content' => $match[3] ?? null,
            ];
        }

        return $shortcodes;
    }

    protected function parseAttributes(string $attrString): array
    {
        $attributes = [];
        
        // Pattern: key="value" or key='value'
        preg_match_all('/(\w+)=(["\'])(.*?)\2/', $attrString, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $attributes[$match[1]] = $match[3];
        }

        return $attributes;
    }
}
