<?php

namespace App\Modules\Content\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'parent_id', 
        'lft', 'rgt', 'depth', 'image_id',
    ];

    protected $casts = [
        'lft' => 'integer',
        'rgt' => 'integer',
        'depth' => 'integer',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    // Nested Set helpers
    public static function tree()
    {
        return static::orderBy('lft')->get();
    }

    public function getDescendants()
    {
        return static::whereBetween('lft', [$this->lft, $this->rgt])
            ->where('id', '!=', $this->id)
            ->orderBy('lft')
            ->get();
    }

    public function getAncestors()
    {
        return static::where('lft', '<', $this->lft)
            ->where('rgt', '>', $this->rgt)
            ->orderBy('lft')
            ->get();
    }

    public function isRoot(): bool
    {
        return $this->parent_id === null;
    }

    public function isLeaf(): bool
    {
        return ($this->rgt - $this->lft) === 1;
    }
}
