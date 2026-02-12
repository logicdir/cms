<?php

namespace App\Modules\Seo\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SitemapService
{
    protected string $disk = 'public';

    /**
     * Generate sitemap index.
     */
    public function generateIndex(): string
    {
        return Cache::remember('sitemap_index', 86400, function() {
            $xml = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            
            $sitemaps = [
                ['url' => url('/sitemap-posts.xml'), 'lastmod' => now()],
                ['url' => url('/sitemap-pages.xml'), 'lastmod' => now()],
                ['url' => url('/sitemap-categories.xml'), 'lastmod' => now()],
            ];

            foreach ($sitemaps as $sitemap) {
                $xml .= '<sitemap>';
                $xml .= '<loc>' . e($sitemap['url']) . '</loc>';
                $xml .= '<lastmod>' . $sitemap['lastmod']->toAtomString() . '</lastmod>';
                $xml .= '</sitemap>';
            }

            $xml .= '</sitemapindex>';
            
            return $xml;
        });
    }

    /**
     * Generate content sitemap.
     */
    public function generateContentSitemap(string $type, $query): string
    {
        $cacheKey = "sitemap_{$type}";
        
        return Cache::remember($cacheKey, 3600, function() use ($query) {
            $xml = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

            foreach ($query->get() as $item) {
                $xml .= '<url>';
                $xml .= '<loc>' . e($item->url ?? url("/{$item->slug}")) . '</loc>';
                $xml .= '<lastmod>' . $item->updated_at->toAtomString() . '</lastmod>';
                $xml .= '<changefreq>weekly</changefreq>';
                $xml .= '<priority>0.8</priority>';
                $xml .= '</url>';
            }

            $xml .= '</urlset>';
            
            return $xml;
        });
    }

    /**
     * Generate image sitemap.
     */
    public function generateImageSitemap(): string
    {
        return Cache::remember('sitemap_images', 3600, function() {
            $xml = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

            // This would integrate with the Media module
            // For now, placeholder structure
            $xml .= '<url>';
            $xml .= '<loc>' . url('/') . '</loc>';
            $xml .= '<image:image>';
            $xml .= '<image:loc>' . asset('images/logo.png') . '</image:loc>';
            $xml .= '<image:caption>Site Logo</image:caption>';
            $xml .= '</image:image>';
            $xml .= '</url>';

            $xml .= '</urlset>';
            
            return $xml;
        });
    }

    /**
     * Clear all sitemap caches.
     */
    public function clearCache(): void
    {
        Cache::forget('sitemap_index');
        Cache::forget('sitemap_posts');
        Cache::forget('sitemap_pages');
        Cache::forget('sitemap_categories');
        Cache::forget('sitemap_images');
    }
}
