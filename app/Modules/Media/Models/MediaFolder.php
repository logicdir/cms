<?php

namespace App\Modules\Media\Models;

use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MediaFolder extends Model
{
    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'created_by'
    ];

    /**
     * Get the parent folder.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(MediaFolder::class, 'parent_id');
    }

    /**
     * Get the subfolders.
     */
    public function children(): HasMany
    {
        return $this->hasMany(MediaFolder::class, 'parent_id');
    }

    /**
     * Get the media files in this folder.
     */
    public function media(): HasMany
    {
        return $this->hasMany(Media::class, 'folder_id');
    }

    /**
     * Get the user who created the folder.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get full path of the folder.
     */
    public function getPath(): string
    {
        $path = [$this->slug];
        $parent = $this->parent;

        while ($parent) {
            array_unshift($path, $parent->slug);
            $parent = $parent->parent;
        }

        return implode('/', $path);
    }
}
