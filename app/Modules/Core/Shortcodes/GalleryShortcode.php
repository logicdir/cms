<?php

namespace App\Modules\Core\Shortcodes;

use App\Modules\Core\Contracts\Shortcode\ShortcodeInterface;
use App\Modules\Media\Models\Media;

class GalleryShortcode implements ShortcodeInterface
{
    public function getTag(): string
    {
        return 'gallery';
    }

    public function getDefaultAttributes(): array
    {
        return [
            'ids' => '',
            'columns' => 3,
            'size' => 'medium',
            'link' => 'file'
        ];
    }

    public function render(array $attributes, ?string $content = null): string
    {
        $attributes = array_merge($this->getDefaultAttributes(), $attributes);
        $ids = array_filter(explode(',', $attributes['ids']));
        
        if (empty($ids)) return '';

        $media = Media::whereIn('id', $ids)->get();
        if ($media->isEmpty()) return '';

        $columns = (int) $attributes['columns'];
        $size = $attributes['size'];

        $html = '<div class="cms-gallery grid gap-4 mb-8" style="grid-template-columns: repeat(' . $columns . ', minmax(0, 1fr))">';
        
        foreach ($media as $item) {
            $url = match($size) {
                'thumbnail' => $item->thumbnail_url,
                'medium' => $item->medium_url,
                'large' => $item->large_url,
                default => $item->full_url
            };

            $html .= '<div class="cms-gallery-item aspect-square overflow-hidden rounded-xl shadow-sm hover:shadow-md transition-shadow">';
            if ($attributes['link'] === 'file') {
                $html .= '<a href="' . e($item->full_url) . '" class="cms-lightbox">';
            }
            $html .= '<img src="' . e($url) . '" alt="' . e($item->alt_text) . '" class="w-full h-full object-cover" loading="lazy" />';
            if ($attributes['link'] === 'file') {
                $html .= '</a>';
            }
            $html .= '</div>';
        }

        $html .= '</div>';

        return $html;
    }
}
