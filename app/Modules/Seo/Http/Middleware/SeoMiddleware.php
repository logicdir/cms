<?php

namespace App\Modules\Seo\Http\Middleware;

use App\Modules\Seo\Services\SeoMetaService;
use App\Modules\Seo\Services\StructuredDataService;
use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SeoMiddleware
{
    public function __construct(
        protected SeoMetaService $metaService,
        protected StructuredDataService $structuredDataService
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only process Inertia responses
        if ($response instanceof \Inertia\Response) {
            // Add global SEO data to all Inertia responses
            Inertia::share([
                'seo' => [
                    'website_schema' => $this->structuredDataService->generateWebSite()
                ]
            ]);
        }

        return $response;
    }
}
