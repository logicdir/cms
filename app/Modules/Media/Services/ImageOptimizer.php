<?php

namespace App\Modules\Media\Services;

use App\Modules\Media\Models\Media;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageOptimizer
{
    protected ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Optimize image and generate variants.
     */
    public function optimize(Media $media): void
    {
        $sourcePath = Storage::disk($media->disk)->path($media->path);
        
        if (!file_exists($sourcePath)) return;

        $variants = [
            'thumbnail' => [150, 150, 'crop'],
            'medium' => [300, 200, 'fit'],
            'large' => [800, 600, 'fit'],
            'hero' => [1920, 1080, 'max']
        ];

        foreach ($variants as $name => $config) {
            $this->generateVariant($media, $name, ...$config);
        }

        // Final optimization of original if needed (e.g. convert to webp)
    }

    /**
     * Generate a specific image variant.
     */
    protected function generateVariant(Media $media, string $suffix, int $width, int $height, string $mode): void
    {
        $image = $this->manager->read(Storage::disk($media->disk)->get($media->path));
        
        match($mode) {
            'crop' => $image->cover($width, $height),
            'fit' => $image->scale(width: $width, height: $height),
            'max' => $image->scale(width: $width, height: $height, upscale: false),
            default => $image->scale(width: $width)
        };

        $extension = pathinfo($media->path, PATHINFO_EXTENSION);
        $pathWithoutExt = substr($media->path, 0, strrpos($media->path, '.'));
        $variantPath = "{$pathWithoutExt}_{$suffix}.webp";

        // Save as WebP
        Storage::disk($media->disk)->put(
            $variantPath,
            (string) $image->toWebp(80)
        );
    }
}
