<?php

namespace App\Modules\Seo\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoMeta extends Model
{
    protected $fillable = [
        'meta_title',
        'meta_description',
        'meta_keywords',
        'robots',
        'canonical_url',
        'og_title',
        'og_description',
        'og_image',
        'twitter_card'
    ];

    /**
     * Get the owning seoable model.
     */
    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }
}
