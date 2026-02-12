<?php

namespace App\Modules\Content\Services;

use Illuminate\Support\Str;

class ExcerptService
{
    public function generate(string $content, int $words = 55): string
    {
        // Strip shortcodes first
        $content = $this->stripShortcodes($content);
        
        // Strip HTML tags
        $content = $this->stripHtml($content);
        
        // Limit words
        return Str::words($content, $words, '...');
    }

    public function stripShortcodes(string $content): string
    {
        // Remove all shortcodes: [shortcode] or [shortcode]content[/shortcode]
        return preg_replace('/\[(\w+)(?:\s+[^\]]*)?](?:(.*?)\[\/\1])?/s', '', $content);
    }

    public function stripHtml(string $content, array $allowedTags = []): string
    {
        if (empty($allowedTags)) {
            return strip_tags($content);
        }
        
        $allowed = '<' . implode('><', $allowedTags) . '>';
        return strip_tags($content, $allowed);
    }
}
