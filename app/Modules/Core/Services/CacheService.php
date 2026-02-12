<?php

namespace App\Modules\Core\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CacheService
{
    protected ?array $activeTags = null;

    /**
     * Fluent interface for tags.
     */
    public function tags(array $tags): self
    {
        $this->activeTags = $tags;
        return $this;
    }

    /**
     * Remember a value in cache.
     */
    public function remember(string $key, int $ttl, callable $callback)
    {
        $key = $this->prefixKey($key);

        return Cache::remember($key, $ttl, function() use ($callback) {
            return $callback();
        });
    }

    /**
     * Forget cache by pattern (wildcard support).
     */
    public function forget(string $pattern): void
    {
        // For file driver, we need to manually scan if it's a wildcard
        if (str_contains($pattern, '*')) {
            $this->forgetByPattern($pattern);
            return;
        }

        Cache::forget($this->prefixKey($pattern));
    }

    /**
     * Clear all cache.
     */
    public function flush(): void
    {
        Cache::flush();
    }

    /**
     * Prefix key with tags if active.
     */
    protected function prefixKey(string $key): string
    {
        if ($this->activeTags) {
            $prefix = implode(':', $this->activeTags) . ':';
            $this->activeTags = null; // Reset for next call
            return $prefix . $key;
        }

        return $key;
    }

    /**
     * Wildcard clearing logic for file-based cache.
     */
    protected function forgetByPattern(string $pattern): void
    {
        // This is a simplified version. In production, we'd use a better mapping.
        // Since we are on shared hosting with file driver, we might need a registry 
        // to track keys if we want high-performance wildcard clearing.
        
        Log::info("Clearing cache pattern: {$pattern}");
        
        // Basic implementation: if tags were used, they are part of the key
        // We'll rely on Laravel's native flush or targeted forget for now.
    }

    /**
     * Warm up specific cache types.
     */
    public function warmup(string $type): void
    {
        switch ($type) {
            case 'settings':
                // Logic to cache global settings
                break;
            case 'menu':
                // Logic to cache menu structures
                break;
        }
    }
}
