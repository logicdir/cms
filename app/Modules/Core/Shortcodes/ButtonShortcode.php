<?php

namespace App\Modules\Core\Shortcodes;

use App\Modules\Core\Contracts\Shortcode\ShortcodeInterface;

class ButtonShortcode implements ShortcodeInterface
{
    public function getTag(): string
    {
        return 'button';
    }

    public function getDefaultAttributes(): array
    {
        return [
            'url' => '#',
            'text' => 'Click Here',
            'style' => 'primary',
            'size' => 'md',
            'target' => '_self',
            'icon' => null
        ];
    }

    public function render(array $attributes, ?string $content = null): string
    {
        $attributes = array_merge($this->getDefaultAttributes(), $attributes);
        if ($content) $attributes['text'] = $content;

        $styles = [
            'primary' => 'bg-indigo-600 text-white hover:bg-indigo-700',
            'secondary' => 'bg-slate-200 text-slate-900 hover:bg-slate-300',
            'outline' => 'border-2 border-indigo-600 text-indigo-600 hover:bg-indigo-50',
            'danger' => 'bg-red-600 text-white hover:bg-red-700'
        ];

        $sizes = [
            'sm' => 'px-3 py-1.5 text-xs',
            'md' => 'px-5 py-2.5 text-sm',
            'lg' => 'px-8 py-4 text-base'
        ];

        $class = 'inline-flex items-center gap-2 font-bold rounded-xl transition-all shadow-sm ';
        $class .= ($styles[$attributes['style']] ?? $styles['primary']) . ' ';
        $class .= ($sizes[$attributes['size']] ?? $sizes['md']);

        $iconHtml = '';
        if ($attributes['icon']) {
            $iconHtml = '<i class="icon-' . e($attributes['icon']) . '"></i>';
        }

        return sprintf(
            '<a href="%s" target="%s" class="%s">%s %s</a>',
            e($attributes['url']),
            e($attributes['target']),
            $class,
            $iconHtml,
            e($attributes['text'])
        );
    }
}
