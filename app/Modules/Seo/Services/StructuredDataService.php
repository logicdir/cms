<?php

namespace App\Modules\Seo\Services;

use App\Modules\Seo\Models\SeoSetting;

class StructuredDataService
{
    /**
     * Generate WebSite schema.
     */
    public function generateWebSite(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => SeoSetting::getValue('site_name', config('app.name')),
            'url' => url('/'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => url('/search?q={search_term_string}'),
                'query-input' => 'required name=search_term_string'
            ]
        ];
    }

    /**
     * Generate Article schema.
     */
    public function generateArticle($content): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $content->title,
            'description' => $content->excerpt ?? strip_tags(str_limit($content->content, 200)),
            'image' => $content->featured_image_url ?? SeoSetting::getValue('default_og_image'),
            'datePublished' => $content->published_at?->toIso8601String(),
            'dateModified' => $content->updated_at->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $content->author->name ?? 'Anonymous'
            ],
            'publisher' => $this->generateOrganization()
        ];
    }

    /**
     * Generate BreadcrumbList schema.
     */
    public function generateBreadcrumbs(array $items): array
    {
        $listItems = [];
        
        foreach ($items as $index => $item) {
            $listItems[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => $item['url']
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $listItems
        ];
    }

    /**
     * Generate Organization schema.
     */
    public function generateOrganization(): array
    {
        return [
            '@type' => 'Organization',
            'name' => SeoSetting::getValue('site_name', config('app.name')),
            'url' => url('/'),
            'logo' => SeoSetting::getValue('organization_logo'),
            'sameAs' => $this->getSocialLinks()
        ];
    }

    /**
     * Get social media links.
     */
    protected function getSocialLinks(): array
    {
        $links = [];
        $platforms = ['facebook', 'twitter', 'instagram', 'linkedin', 'youtube'];
        
        foreach ($platforms as $platform) {
            $url = SeoSetting::getValue("social_{$platform}");
            if ($url) $links[] = $url;
        }
        
        return $links;
    }

    /**
     * Convert schema array to JSON-LD script tag.
     */
    public function toJsonLd(array $schema): string
    {
        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
    }
}
