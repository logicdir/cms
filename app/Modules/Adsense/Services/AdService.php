<?php

namespace App\Modules\Adsense\Services;

use App\Modules\Adsense\Models\AdUnit;
use Illuminate\Support\Facades\Cache;

class AdService
{
    /**
     * Get active ad units for a specific position.
     */
    public function getAdsForPosition(string $position, array $context = [])
    {
        return Cache::remember("ads_pos_{$position}", 3600, function () use ($position) {
            return AdUnit::where('position', $position)
                ->where('status', true)
                ->get();
        })->filter(function ($ad) use ($context) {
            return $this->validateRules($ad, $context);
        });
    }

    /**
     * Logic to auto-insert ads into post/page content.
     */
    public function injectAdsIntoContent(string $content): string
    {
        $ads = AdUnit::where('auto_insert', true)->where('status', true)->get();
        
        foreach ($ads as $ad) {
            $rules = $ad->display_rules ?? [];
            $paragraph = $rules['after_paragraph'] ?? 3;
            
            // Regex to find paragraphs
            $pattern = '/(<p>.*?<\/p>)/i';
            $paragraphs = preg_split($pattern, $content, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
            
            if (count($paragraphs) >= ($paragraph * 2)) {
                $injectionPoint = ($paragraph * 2) - 1;
                $adHtml = $this->renderAd($ad);
                array_splice($paragraphs, $injectionPoint + 1, 0, [$adHtml]);
                $content = implode('', $paragraphs);
            }
        }
        
        return $content;
    }

    /**
     * Validate display rules (device, category, etc.)
     */
    protected function validateRules(AdUnit $ad, array $context): bool
    {
        $rules = $ad->display_rules ?? [];
        
        // Example check: category exclusion
        if (!empty($rules['exclude_categories']) && !empty($context['category_id'])) {
            if (in_array($context['category_id'], $rules['exclude_categories'])) {
                return false;
            }
        }

        // Example check: Device limit
        if (!empty($rules['device'])) {
            // Very basic check - usually handled via client-side CSS or agent analysis
            // For now, assume it's valid
        }

        return true;
    }

    /**
     * Render the ad code.
     */
    public function renderAd(AdUnit $ad): string
    {
        if ($ad->type === 'adsense') {
            return '<div class="cms-ad cms-adsense">' . $ad->code . '</div>';
        }
        
        return '<div class="cms-ad cms-custom">' . $ad->code . '</div>';
    }
}
