<?php

namespace App\Modules\Seo\Services;

use App\Modules\Seo\Models\Redirect;
use App\Modules\Seo\Models\UrlHistory;
use Illuminate\Support\Facades\Cache;

class RedirectService
{
    /**
     * Find a matching redirect for the given URL.
     */
    public function findRedirect(string $url): ?Redirect
    {
        // Check cache first
        $cacheKey = "redirect_" . md5($url);
        
        return Cache::remember($cacheKey, 3600, function() use ($url) {
            // Try exact match first
            $redirect = Redirect::where('from_url', $url)
                ->where('is_regex', false)
                ->first();

            if ($redirect) return $redirect;

            // Try regex matches
            $regexRedirects = Redirect::where('is_regex', true)->get();
            
            foreach ($regexRedirects as $redirect) {
                if (preg_match($redirect->from_url, $url)) {
                    return $redirect;
                }
            }

            return null;
        });
    }

    /**
     * Create automatic redirect from URL history.
     */
    public function createAutoRedirect($content, string $oldSlug): void
    {
        // Record in URL history
        UrlHistory::create([
            'content_type' => $content->getMorphClass(),
            'content_id' => $content->id,
            'old_slug' => $oldSlug,
            'new_slug' => $content->slug,
            'changed_at' => now()
        ]);

        // Create redirect
        Redirect::create([
            'from_url' => '/' . $oldSlug,
            'to_url' => '/' . $content->slug,
            'type' => '301',
            'is_regex' => false
        ]);

        $this->clearCache();
    }

    /**
     * Clear redirect cache.
     */
    public function clearCache(): void
    {
        Cache::flush(); // In production, use more targeted cache clearing
    }
}
