<?php

namespace App\Modules\Core\Services\Shortcode;

class ShortcodeCompiler
{
    public function __construct(
        protected ShortcodeRegistry $registry
    ) {}

    /**
     * Compile AST into HTML string.
     */
    public function compile(array $ast): string
    {
        $html = '';

        foreach ($ast as $node) {
            if ($node['type'] === 'text') {
                $html .= $node['content'];
                continue;
            }

            if ($node['type'] === 'shortcode') {
                $handler = $this->registry->get($node['tag']);
                if ($handler) {
                    $innerContent = null;
                    if (isset($node['content']) && is_array($node['content'])) {
                        $innerContent = $this->compile($node['content']);
                    }

                    $html .= $handler->render($node['attributes'], $innerContent);
                } else {
                    // If not registered (shouldn't happen with parser whitelist but as fallback)
                    $html .= $this->rebuildShortcode($node);
                }
            }
        }

        return $html;
    }

    /**
     * Compile AST into a JSON-friendly structure for Vue rendering.
     * This keeps the structure but can process attributes if needed.
     */
    public function compileToJson(array $ast): array
    {
        return array_map(function($node) {
            if ($node['type'] === 'shortcode' && isset($node['content']) && is_array($node['content'])) {
                $node['content'] = $this->compileToJson($node['content']);
            }
            return $node;
        }, $ast);
    }

    /**
     * Rebuild original shortcode string if no handler found.
     */
    protected function rebuildShortcode(array $node): string
    {
        $attrs = '';
        foreach ($node['attributes'] as $key => $val) {
            if ($val === true) $attrs .= " $key";
            else $attrs .= " $key=\"" . e($val) . "\"";
        }

        if ($node['content'] === null) {
            return "[{$node['tag']}{$attrs} /]";
        }

        $inner = is_array($node['content']) ? $this->compile($node['content']) : $node['content'];
        return "[{$node['tag']}{$attrs}]{$inner}[/{$node['tag']}]";
    }
}
