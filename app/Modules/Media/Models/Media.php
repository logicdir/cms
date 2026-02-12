<?php

namespace App\Modules\Media\Models;

use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $fillable = [
        'folder_id',
        'name',
        'file_name',
        'mime_type',
        'size',
        'width',
        'height',
        'disk',
        'path',
        'url',
        'alt_text',
        'title',
        'caption',
        'created_by'
    ];

    /**
     * Get the folder this media belongs to.
     */
    public function folder(): BelongsTo
    {
        return $this->belongsTo(MediaFolder::class, 'folder_id');
    }

    /**
     * Get the user who uploaded the media.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the contents that use this media.
     */
    public function contents(): MorphToMany
    {
        // This assumes a Content model exists in another module
        return $this->morphedByMany('App\Modules\Content\Models\Content', 'mediable', 'mediables');
    }

    /**
     * Accessor for full URL.
     */
    public function getFullUrlAttribute(): string
    {
        if ($this->url) {
            return $this->url;
        }

        return Storage::disk($this->disk)->url($this->path);
    }

    /**
     * Accessor for thumbnail URL.
     */
    public function getThumbnailUrlAttribute(): string
    {
        return $this->getVariantUrl('thumbnail');
    }

    /**
     * Accessor for medium URL.
     */
    public function getMediumUrlAttribute(): string
    {
        return $this->getVariantUrl('medium');
    }

    /**
     * Accessor for large URL.
     */
    public function getLargeUrlAttribute(): string
    {
        return $this->getVariantUrl('large');
    }

    /**
     * Helper to get variant URL.
     */
    protected function getVariantUrl(string $variant): string
    {
        if (!str_contains($this->mime_type, 'image')) {
            return asset('assets/img/filetypes/file.png'); // Placeholder for non-images
        }

        $extension = pathinfo($this->path, PATHINFO_EXTENSION);
        $pathWithoutExt = substr($this->path, 0, strrpos($this->path, '.'));
        $variantPath = "{$pathWithoutExt}_{$variant}.webp";

        if (Storage::disk($this->disk)->exists($variantPath)) {
            return Storage::disk($this->disk)->url($variantPath);
        }

        return $this->full_url;
    }

    /**
     * Accessor for human readable size.
     */
    public function getHumanReadableSizeAttribute(): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $size = $this->size;
        for ($i = 0; $size > 1024; $i++) {
            $size /= 1024;
        }

        return round($size, 2) . ' ' . $units[$i];
    }
}
