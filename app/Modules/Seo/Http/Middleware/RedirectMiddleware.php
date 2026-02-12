<?php

namespace App\Modules\Seo\Http\Middleware;

use App\Modules\Seo\Services\RedirectService;
use Closure;
use Illuminate\Http\Request;

class RedirectMiddleware
{
    public function __construct(
        protected RedirectService $redirectService
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $url = $request->path();
        
        $redirect = $this->redirectService->findRedirect('/' . $url);

        if ($redirect) {
            $redirect->recordHit();
            
            $statusCode = $redirect->type === '301' ? 301 : 302;
            return redirect($redirect->to_url, $statusCode);
        }

        return $next($request);
    }
}
