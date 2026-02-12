<?php

namespace App\Modules\Core\Shortcodes;

use App\Modules\Core\Contracts\Shortcode\ShortcodeInterface;

class VideoShortcode implements ShortcodeInterface
{
    public function getTag(): string
    {
        return 'video';
    }

    public function getDefaultAttributes(): array
    {
        return [
            'src' => '',
            'poster' => '',
            'width' => '100%',
            'height' => 'auto',
            'autoplay' => false,
            'controls' => true
        ];
    }

    public function render(array $attributes, ?string $content = null): string
    {
        $attributes = array_merge($this->getDefaultAttributes(), $attributes);
        $src = $attributes['src'];

        if (str_contains($src, 'youtube.com') || str_contains($src, 'youtu.be')) {
            return $this->renderYoutube($src, $attributes);
        }

        if (str_contains($src, 'vimeo.com')) {
            return $this->renderVimeo($src, $attributes);
        }

        return $this->renderHtml5($src, $attributes);
    }

    protected function renderYoutube(string $url, array $attr): string
    {
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
        $id = $match[1] ?? '';
        if (!$id) return '';

        return sprintf(
            '<div class="cms-video-container aspect-video mb-8 rounded-2xl overflow-hidden shadow-lg"><iframe src="https://www.youtube.com/embed/%s" width="%s" height="%s" frameborder="0" allowfullscreen></iframe></div>',
            $id, $attr['width'], $attr['height']
        );
    }

    protected function renderVimeo(string $url, array $attr): string
    {
        preg_match('/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/(?:[^\/]*)\/videos\/|album\/(?:\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)/', $url, $matches);
        $id = $matches[1] ?? '';
        if (!$id) return '';

        return sprintf(
            '<div class="cms-video-container aspect-video mb-8 rounded-2xl overflow-hidden shadow-lg"><iframe src="https://player.vimeo.com/video/%s" width="%s" height="%s" frameborder="0" allowfullscreen></iframe></div>',
            $id, $attr['width'], $attr['height']
        );
    }

    protected function renderHtml5(string $src, array $attr): string
    {
        return sprintf(
            '<video src="%s" poster="%s" width="%s" height="%s" %s %s class="cms-video w-full rounded-2xl shadow-lg mb-8"></video>',
            e($src),
            e($attr['poster']),
            $attr['width'],
            $attr['height'],
            $attr['controls'] ? 'controls' : '',
            $attr['autoplay'] ? 'autoplay' : ''
        );
    }
}
