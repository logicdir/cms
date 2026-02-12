<?php

namespace App\Modules\Media\Services;

use App\Modules\Media\Models\Media;

class MediaShortcode
{
    /**
     * Handle the [media] shortcode.
     * Usage: [media id="1" size="large" align="center" class="my-class"]
     */
    public function handle(array $attributes): string
    {
        $id = $attributes['id'] ?? null;
        if (!$id) return '';

        $media = Media::find($id);
        if (!$media) return '';

        $size = $attributes['size'] ?? 'full';
        $align = $attributes['align'] ?? 'none';
        $class = $attributes['class'] ?? '';

        $url = match($size) {
            'thumbnail' => $media->thumbnail_url,
            'medium' => $media->medium_url,
            'large' => $media->large_url,
            default => $media->full_url
        };

        $alignmentClass = match($align) {
            'left' => 'float-left mr-4 mb-4',
            'right' => 'float-right ml-4 mb-4',
            'center' => 'block mx-auto mb-4',
            default => ''
        };

        return sprintf(
            '<img src="%s" alt="%s" title="%s" class="cms-media cms-media-%s %s %s" />',
            e($url),
            e($media->alt_text ?? $media->name),
            e($media->title ?? ''),
            e($size),
            e($alignmentClass),
            e($class)
        );
    }
}
