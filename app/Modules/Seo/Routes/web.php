<?php

use App\Modules\Seo\Services\SitemapService;
use Illuminate\Support\Facades\Route;

// Sitemap routes
Route::get('/sitemap.xml', function (SitemapService $sitemap) {
    return response($sitemap->generateIndex())
        ->header('Content-Type', 'application/xml');
})->name('sitemap.index');

Route::get('/sitemap-{type}.xml', function (string $type, SitemapService $sitemap) {
    // This would need to be expanded with actual content queries
    $xml = match($type) {
        'posts' => $sitemap->generateContentSitemap('posts', \App\Modules\Content\Models\Post::published()),
        'pages' => $sitemap->generateContentSitemap('pages', \App\Modules\Content\Models\Page::published()),
        'images' => $sitemap->generateImageSitemap(),
        default => abort(404)
    };
    
    return response($xml)->header('Content-Type', 'application/xml');
})->name('sitemap.type');

// robots.txt
Route::get('/robots.txt', function () {
    $content = "User-agent: *\n";
    $content .= "Allow: /\n";
    $content .= "Sitemap: " . url('/sitemap.xml') . "\n";
    
    return response($content)->header('Content-Type', 'text/plain');
})->name('robots');
