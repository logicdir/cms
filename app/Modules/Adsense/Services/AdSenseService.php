<?php

namespace App\Modules\Adsense\Services;

use App\Modules\Core\Models\Setting;

class AdSenseService
{
    /**
     * Get the AdSense Publisher ID.
     */
    public function getPublisherId(): ?string
    {
        // Assuming global settings module stores this
        return config('logicdir.adsense.publisher_id');
    }

    /**
     * Generate the Auto-Ads script tag.
     */
    public function getAutoAdsScript(): string
    {
        $pubId = $this->getPublisherId();
        if (!$pubId) return '';

        return sprintf(
            '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=%s" crossorigin="anonymous"></script>',
            e($pubId)
        );
    }
}
