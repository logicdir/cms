<?php

namespace App\Modules\Content\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    use HasFactory;

    protected $primaryKey = 'content_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'content_id', 'parent_id', 'lft', 'rgt', 'depth',
    ];

    protected $casts = [
        'lft' => 'integer',
        'rgt' => 'integer',
        'depth' => 'integer',
    ];

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'parent_id', 'content_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Page::class, 'parent_id', 'content_id');
    }

    // Nested Set helpers
    public function getDescendants()
    {
        return static::whereBetween('lft', [$this->lft, $this->rgt])
            ->where('content_id', '!=', $this->content_id)
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
}
