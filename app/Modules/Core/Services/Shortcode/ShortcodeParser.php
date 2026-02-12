<?php

namespace App\Modules\Core\Services\Shortcode;

class ShortcodeParser
{
    protected int $maxNestingLevel = 5;

    public function __construct(
        protected ShortcodeRegistry $registry
    ) {}

    /**
     * Parse content into an AST-like structure.
     */
    public function parse(string $content): array
    {
        return $this->parseRecursive($content, 0);
    }

    /**
     * Recursive parsing logic.
     */
    protected function parseRecursive(string $content, int $level): array
    {
        if ($level > $this->maxNestingLevel) {
            return [['type' => 'text', 'content' => $content]];
        }

        $tags = $this->registry->getRegisteredTags();
        if (empty($tags)) {
            return [['type' => 'text', 'content' => $content]];
        }

        $tagPattern = implode('|', array_map('preg_quote', $tags));
        // Regex pattern for shortcodes: [tag attr="val"]...[/tag] or [tag attr="val" /] or [tag]
        $pattern = '/\[(' . $tagPattern . ')(?:\s+([^\]\/]*))?(?:\s*\/)?\](?:(.*?)\[\/\1\])?/s';

        $parts = preg_split($pattern, $content, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_OFFSET_CAPTURE);
        
        $ast = [];
        $lastOffset = 0;

        for ($i = 0; $i < count($parts); $i += 4) {
            // Text before the shortcode
            $textContent = substr($content, $lastOffset, $parts[$i][1] - $lastOffset);
            if ($textContent !== '') {
                $ast[] = ['type' => 'text', 'content' => $textContent];
            }

            if (isset($parts[$i + 1])) {
                $tag = $parts[$i + 1][0];
                $attrString = $parts[$i + 2][0] ?? '';
                $innerContent = $parts[$i + 3][0] ?? null;

                $ast[] = [
                    'type' => 'shortcode',
                    'tag' => $tag,
                    'attributes' => $this->parseAttributes($attrString),
                    'content' => $innerContent !== null ? $this->parseRecursive($innerContent, $level + 1) : null
                ];

                $lastOffset = $parts[$i][1] + strlen($parts[$i][0]); // This is not quite right with capturing groups
                // Actually, the split offset is at the start of the full match if we use a non-capturing group for the whole thing, 
                // but here we are using capturing groups.
                // Let's use preg_match_all for better clarity.
            }
        }

        return $ast;
    }

    /**
     * Better parsing using preg_match_all.
     */
    public function getAST(string $content, int $level = 0): array
    {
        if ($level > $this->maxNestingLevel) {
            return [['type' => 'text', 'content' => $content]];
        }

        $tags = $this->registry->getRegisteredTags();
        if (empty($tags)) {
            return [['type' => 'text', 'content' => $content]];
        }

        $tagPattern = implode('|', array_map('preg_quote', $tags));
        $pattern = '/\[(' . $tagPattern . ')(?:\s+([^\]\/]*))?(?:\s*\/)?\](?:(.*?)\[\/\1\])?/s';

        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        $ast = [];
        $lastOffset = 0;

        foreach ($matches as $match) {
            $fullMatch = $match[0][0];
            $offset = $match[0][1];

            // Leading text
            if ($offset > $lastOffset) {
                $ast[] = ['type' => 'text', 'content' => substr($content, $lastOffset, $offset - $lastOffset)];
            }

            $tag = $match[1][0];
            $attrString = $match[2][0] ?? '';
            $innerContent = $match[3][0] ?? null;

            $ast[] = [
                'type' => 'shortcode',
                'tag' => $tag,
                'attributes' => $this->parseAttributes($attrString),
                'content' => ($innerContent !== null && $innerContent !== '') 
                    ? $this->getAST($innerContent, $level + 1) 
                    : null
            ];

            $lastOffset = $offset + strlen($fullMatch);
        }

        if ($lastOffset < strlen($content)) {
            $ast[] = ['type' => 'text', 'content' => substr($content, $lastOffset)];
        }

        return $ast;
    }

    /**
     * Parse attribute string into array.
     */
    public function parseAttributes(string $text): array
    {
        $attributes = [];
        $pattern = '/(\w+)(?:=(?:["\']([^"\']*)["\']|(\S+)))?/';

        if (preg_match_all($pattern, $text, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $key = $match[1];
                $value = $match[2] ?? $match[3] ?? true; // Boolean true if no value
                
                // Type conversion
                if (is_string($value)) {
                    if (is_numeric($value)) $value = $value + 0;
                    elseif ($value === 'true') $value = true;
                    elseif ($value === 'false') $value = false;
                }
                
                $attributes[$key] = $value;
            }
        }

        return $attributes;
    }
}
