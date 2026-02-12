<?php

namespace App\Modules\Seo\Services;

class PerformanceService
{
    /**
     * Generate resource hints for performance.
     */
    public function generateResourceHints(): array
    {
        return [
            'preconnect' => [
                'https://fonts.googleapis.com',
                'https://fonts.gstatic.com',
            ],
            'dns-prefetch' => [
                '//www.google-analytics.com',
            ],
            'preload' => [
                // Add critical assets here
            ]
        ];
    }

    /**
     * Get critical CSS for above-the-fold content.
     * In production, this would use a tool like critical or penthouse.
     */
    public function getCriticalCss(): string
    {
        // This would be generated during build or cached
        return <<<CSS
/* Critical CSS - Above the fold */
body{margin:0;font-family:system-ui,-apple-system,sans-serif}
.header{background:#fff;border-bottom:1px solid #e5e7eb}
CSS;
    }

    /**
     * Optimize image HTML with lazy loading and srcset.
     */
    public function optimizeImageHtml(string $src, string $alt, array $sizes = []): string
    {
        $srcset = [];
        foreach ($sizes as $width => $url) {
            $srcset[] = "$url {$width}w";
        }

        $srcsetAttr = !empty($srcset) ? 'srcset="' . implode(', ', $srcset) . '"' : '';
        
        return sprintf(
            '<img src="%s" %s alt="%s" loading="lazy" decoding="async" />',
            e($src),
            $srcsetAttr,
            e($alt)
        );
    }
}
