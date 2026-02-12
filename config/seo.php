<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Site Information
    |--------------------------------------------------------------------------
    */
    'site_name' => env('SEO_SITE_NAME', config('app.name')),
    'site_description' => env('SEO_SITE_DESCRIPTION', ''),
    
    /*
    |--------------------------------------------------------------------------
    | Default Meta Templates
    |--------------------------------------------------------------------------
    */
    'title_template' => '%title% | %sitename%',
    'title_separator' => '|',
    
    /*
    |--------------------------------------------------------------------------
    | Open Graph Defaults
    |--------------------------------------------------------------------------
    */
    'og_type' => 'website',
    'og_locale' => 'en_US',
    'default_og_image' => env('SEO_DEFAULT_IMAGE', '/images/og-default.jpg'),
    
    /*
    |--------------------------------------------------------------------------
    | Twitter Card Defaults
    |--------------------------------------------------------------------------
    */
    'twitter_card' => 'summary_large_image',
    'twitter_site' => env('SEO_TWITTER_HANDLE', ''),
    
    /*
    |--------------------------------------------------------------------------
    | Analytics
    |--------------------------------------------------------------------------
    */
    'google_analytics_id' => env('GOOGLE_ANALYTICS_ID', ''),
    'google_tag_manager_id' => env('GOOGLE_TAG_MANAGER_ID', ''),
    'google_site_verification' => env('GOOGLE_SITE_VERIFICATION', ''),
    
    /*
    |--------------------------------------------------------------------------
    | Sitemap Configuration
    |--------------------------------------------------------------------------
    */
    'sitemap' => [
        'cache_duration' => 3600, // 1 hour
        'max_urls' => 50000,
    ],
];
