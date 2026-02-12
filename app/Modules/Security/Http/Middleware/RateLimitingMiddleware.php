<?php

namespace App\Modules\Security\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class RateLimitingMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $key = $request->ip();

        if (RateLimiter::tooManyAttempts($key, 60)) {
            return response()->json(['message' => 'Too many requests. Please slow down.'], 429);
        }

        RateLimiter::hit($key, 60);

        return $next($request);
    }
}
