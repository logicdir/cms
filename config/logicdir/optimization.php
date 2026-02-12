<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Response Cache Settings
    |--------------------------------------------------------------------------
    */
    'cache_responses' => env('CACHE_RESPONSES', true),
    'response_cache_ttl' => 3600, // 1 hour
    
    // Do not cache responses for these paths
    'except' => [
        'admin/*',
        'login',
        'register',
        'api/*',
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Optimization
    |--------------------------------------------------------------------------
    */
    'image_quality' => 80,
    'convert_to_webp' => true,
    'responsive_widths' => [320, 640, 768, 1024, 1280, 1536],

    /*
    |--------------------------------------------------------------------------
    | Query Optimization
    |--------------------------------------------------------------------------
    */
    'log_slow_queries' => true,
    'slow_query_threshold' => 1000, // ms
];
