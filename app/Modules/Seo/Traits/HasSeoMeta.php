<?php

namespace App\Modules\Seo\Traits;

use App\Modules\Seo\Models\SeoMeta;

trait HasSeoMeta
{
    /**
     * Get the SEO meta for this model.
     */
    public function seoMeta()
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

    /**
     * Boot the trait.
     */
    protected static function bootHasSeoMeta(): void
    {
        static::updating(function ($model) {
            // Track slug changes for automatic redirects
            if ($model->isDirty('slug') && $model->getOriginal('slug')) {
                app(\App\Modules\Seo\Services\RedirectService::class)
                    ->createAutoRedirect($model, $model->getOriginal('slug'));
            }
        });
    }
}
