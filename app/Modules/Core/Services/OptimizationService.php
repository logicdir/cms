<?php

namespace App\Modules\Core\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OptimizationService
{
    /**
     * Basic query optimization tracker.
     */
    public function trackQueries(): void
    {
        if (!config('logicdir.optimization.log_slow_queries')) {
            return;
        }

        DB::listen(function ($query) {
            if ($query->time > config('logicdir.optimization.slow_query_threshold')) {
                Log::warning("Slow query detected ({$query->time}ms): {$query->sql}", [
                    'bindings' => $query->bindings,
                ]);
            }
        });
    }

    /**
     * Detect potential N+1 issues by monitoring repeat queries.
     */
    public function detectNPlusOne(): void
    {
        // Implementation for development/debug mode
    }

    /**
     * Minify HTML output.
     */
    public function minifyHtml(string $html): string
    {
        $search = [
            '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
            '/[^\S ]+\</s',     // strip whitespaces before tags, except space
            '/(\s)+/s',         // shorten multiple whitespace sequences
            '/<!--(.|\s)*?-->/' // Remove HTML comments
        ];

        $replace = [
            '>',
            '<',
            '\\1',
            ''
        ];

        return preg_replace($search, $replace, $html);
    }
}
