<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Storage Disk
    |--------------------------------------------------------------------------
    |
    | This is the disk where media files and their variants will be stored.
    | You can use any disks defined in your config/filesystems.php.
    |
    | Supported: "public", "s3"
    |
    */
    'disk' => env('MEDIA_DISK', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Image Variants
    |--------------------------------------------------------------------------
    |
    | Define the sizes and modes for automatically generated thumbnails.
    | Modes: "crop" (cover), "fit" (scale down), "max" (scale without upscaling)
    |
    */
    'variants' => [
        'thumbnail' => ['width' => 150, 'height' => 150, 'mode' => 'crop'],
        'medium'    => ['width' => 300, 'height' => 200, 'mode' => 'fit'],
        'large'     => ['width' => 800, 'height' => 600, 'mode' => 'fit'],
        'hero'      => ['width' => 1920, 'height' => 1080, 'mode' => 'max'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Upload Constraints
    |--------------------------------------------------------------------------
    */
    'max_file_size' => 1024 * 10, // 10MB in KB
    
    'allowed_mimes' => [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/svg+xml',
        'application/pdf',
        'video/mp4',
        'video/quicktime',
    ],
];
