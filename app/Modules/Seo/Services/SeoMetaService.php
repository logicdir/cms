<?php

namespace App\Modules\Seo\Services;

use App\Modules\Seo\Models\SeoMeta;
use App\Modules\Seo\Models\SeoSetting;
use Illuminate\Support\Facades\Cache;

class SeoMetaService
{
    /**
     * Get or generate SEO meta for content.
     */
    public function getMeta($content): array
    {
        $cacheKey = "seo_meta_{$content->getMorphClass()}_{$content->id}";
        
        return Cache::remember($cacheKey, 3600, function() use ($content) {
            $meta = $content->seoMeta ?? new SeoMeta();
            
            return [
                'title' => $meta->meta_title ?: $this->generateTitle($content),
                'description' => $meta->meta_description ?: $this->generateDescription($content),
                'keywords' => $meta->meta_keywords,
                'robots' => $meta->robots ?? 'index,follow',
                'canonical' => $meta->canonical_url ?: url()->current(),
                'og_title' => $meta->og_title ?: ($meta->meta_title ?: $this->generateTitle($content)),
                'og_description' => $meta->og_description ?: ($meta->meta_description ?: $this->generateDescription($content)),
                'og_image' => $meta->og_image ?: $this->getDefaultImage(),
                'twitter_card' => $meta->twitter_card ?? 'summary_large_image'
            ];
        });
    }

    /**
     * Set SEO meta for content.
     */
    public function setMeta($content, array $data): void
    {
        $content->seoMeta()->updateOrCreate(
            ['seoable_type' => $content->getMorphClass(), 'seoable_id' => $content->id],
            $data
        );

        $this->clearCache($content);
    }

    /**
     * Generate title using template.
     */
    protected function generateTitle($content): string
    {
        $template = SeoSetting::getValue('title_template', '%title% | %sitename%');
        $siteName = SeoSetting::getValue('site_name', config('app.name'));
        
        return str_replace(
            ['%title%', '%sitename%'],
            [$content->title ?? 'Untitled', $siteName],
            $template
        );
    }

    /**
     * Generate description from content.
     */
    protected function generateDescription($content): string
    {
        if (isset($content->excerpt) && $content->excerpt) {
            return str_limit(strip_tags($content->excerpt), 160);
        }
        
        if (isset($content->content)) {
            return str_limit(strip_tags($content->content), 160);
        }
        
        return SeoSetting::getValue('default_description', '');
    }

    /**
     * Get default OG image.
     */
    protected function getDefaultImage(): ?string
    {
        return SeoSetting::getValue('default_og_image');
    }

    /**
     * Clear cache for content.
     */
    protected function clearCache($content): void
    {
        $cacheKey = "seo_meta_{$content->getMorphClass()}_{$content->id}";
        Cache::forget($cacheKey);
    }
}
