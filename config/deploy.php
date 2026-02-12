<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Deployment Configuration
    |--------------------------------------------------------------------------
    */
    
    'environments' => [
        'production' => [
            'host' => env('DEPLOY_PROD_HOST'),
            'user' => env('DEPLOY_PROD_USER'),
            'path' => env('DEPLOY_PROD_PATH', '/home/user/public_html'),
        ],
        'staging' => [
            'host' => env('DEPLOY_STAGING_HOST'),
            'user' => env('DEPLOY_STAGING_USER'),
            'path' => env('DEPLOY_STAGING_PATH', '/home/user/staging_html'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Post-Deployment Tasks
    |--------------------------------------------------------------------------
    */
    'tasks' => [
        'migrate' => true,
        'cache_warm' => true,
        'optimization' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Backup Settings
    |--------------------------------------------------------------------------
    */
    'backups' => [
        'keep_count' => 5,
        'include_storage' => true,
    ],
];
