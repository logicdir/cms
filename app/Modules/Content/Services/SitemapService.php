<?php

namespace App\Modules\Content\Services;

use App\Modules\Content\Models\Content;
use Illuminate\Support\Facades\Cache;

class SitemapService
{
    public function generate(): string
    {
        return Cache::remember('sitemap.xml', 86400, function () {
            $posts = Content::ofType('post')->published()->get();
            $pages = Content::ofType('page')->published()->get();

            $xml = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

            // Homepage
            $xml .= $this->buildUrl(url('/'), now(), '1.0', 'daily');

            // Pages
            foreach ($pages as $page) {
                $xml .= $this->buildUrl(
                    url('/pages/' . $page->slug),
                    $page->updated_at,
                    '0.8',
                    'weekly'
                );
            }

            // Posts
            foreach ($posts as $post) {
                $xml .= $this->buildUrl(
                    url('/posts/' . $post->slug),
                    $post->updated_at,
                    '0.6',
                    'weekly'
                );
            }

            $xml .= '</urlset>';

            return $xml;
        });
    }

    protected function buildUrl(string $loc, $lastmod, string $priority, string $changefreq): string
    {
        return sprintf(
            '<url><loc>%s</loc><lastmod>%s</lastmod><priority>%s</priority><changefreq>%s</changefreq></url>',
            htmlspecialchars($loc),
            $lastmod->toW3cString(),
            $priority,
            $changefreq
        );
    }
}
