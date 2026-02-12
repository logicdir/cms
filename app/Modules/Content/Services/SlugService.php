<?php

namespace App\Modules\Content\Services;

use App\Modules\Content\Models\Content;
use Illuminate\Support\Str;

class SlugService
{
    public function generate(string $title, string $type, ?int $excludeId = null): string
    {
        $slug = $this->transliterate($title);
        return $this->ensureUnique($slug, $type, $excludeId);
    }

    public function transliterate(string $text): string
    {
        // Convert to ASCII
        $slug = Str::ascii($text);
        
        // Convert to lowercase and replace spaces/special chars with hyphens
        $slug = Str::slug($slug);
        
        return $slug;
    }

    public function ensureUnique(string $slug, string $type, ?int $excludeId = null): string
    {
        $originalSlug = $slug;
        $counter = 1;

        while ($this->slugExists($slug, $type, $excludeId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    protected function slugExists(string $slug, string $type, ?int $excludeId): bool
    {
        $query = Content::where('slug', $slug)->where('type', $type);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
