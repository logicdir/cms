<?php

namespace App\Modules\Security\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InputSanitizationMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $input = $request->all();

        array_walk_recursive($input, function (&$value) {
            if (is_string($value)) {
                // Remove potential script tags and basic XSS vectors
                $value = strip_tags($value, '<b><i><u><strong><em><p><br><ul><li><ol><a>');
                
                // Trim and clean special characters
                $value = trim($value);
            }
        });

        $request->merge($input);

        return $next($request);
    }
}
