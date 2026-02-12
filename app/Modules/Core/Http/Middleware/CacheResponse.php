<?php

namespace App\Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CacheResponse
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$this->shouldCache($request)) {
            return $next($request);
        }

        $key = $this->getCacheKey($request);

        return Cache::remember($key, config('logicdir.optimization.response_cache_ttl'), function () use ($request, $next) {
            $response = $next($request);
            
            // Only cache successful GET responses
            if ($response->isSuccessful() && $request->isMethod('GET')) {
                return $response;
            }

            return $response;
        });
    }

    /**
     * Determine if the request should be cached.
     */
    protected function shouldCache(Request $request): bool
    {
        if (!config('logicdir.optimization.cache_responses')) {
            return false;
        }

        if (!$request->isMethod('GET')) {
            return false;
        }

        if (auth()->check()) {
            return false;
        }

        foreach (config('logicdir.optimization.except') as $path) {
            if ($request->is($path)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Generate a unique cache key for the request.
     */
    protected function getCacheKey(Request $request): string
    {
        $url = $request->fullUrl();
        $isMobile = $request->userAgent() && preg_match('/Mobile|Android|iPhone/i', $request->userAgent()) ? 'mobile' : 'desktop';
        $locale = app()->getLocale();

        return "response:" . md5("{$url}|{$isMobile}|{$locale}");
    }
}
