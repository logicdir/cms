<?php

namespace App\Modules\Content\Services;

use App\Modules\Content\Models\Content;
use Illuminate\Support\Str;

class SeoService
{
    public function generateTitle(Content $content): string
    {
        return $content->meta_title ?: ($content->title . ' - ' . config('app.name'));
    }

    public function generateDescription(Content $content): string
    {
        if ($content->meta_description) {
            return $content->meta_description;
        }

        if ($content->excerpt) {
            return Str::limit($content->excerpt, 160);
        }

        return Str::limit(strip_tags($content->content), 160);
    }

    public function generateCanonicalUrl(Content $content): string
    {
        $locale = app()->getLocale();
        $type = $content->type === 'post' ? 'posts' : 'pages';
        
        return url("/{$locale}/{$type}/{$content->slug}");
    }

    public function generateOpenGraph(Content $content): array
    {
        return [
            'og:title' => $this->generateTitle($content),
            'og:description' => $this->generateDescription($content),
            'og:url' => $this->generateCanonicalUrl($content),
            'og:type' => $content->type === 'post' ? 'article' : 'website',
            'og:image' => $content->featured_image_id ? asset('storage/media/' . $content->featured_image_id) : null,
        ];
    }

    public function generateStructuredData(Content $content): array
    {
        if ($content->type === 'post') {
            return [
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => $content->title,
                'description' => $this->generateDescription($content),
                'author' => [
                    '@type' => 'Person',
                    'name' => $content->author->name,
                ],
                'datePublished' => $content->published_at?->toIso8601String(),
                'dateModified' => $content->updated_at->toIso8601String(),
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $content->title,
            'description' => $this->generateDescription($content),
        ];
    }
}
