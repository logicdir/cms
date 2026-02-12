<?php

namespace App\Modules\Content\Models;

use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Content extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type', 'title', 'slug', 'excerpt', 'content',
        'status', 'visibility', 'password', 'author_id',
        'featured_image_id', 'published_at', 'template', 'order',
        'meta_title', 'meta_description', 'meta_keywords',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // Relationships
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'content_tag');
    }

    public function meta(): HasMany
    {
        return $this->hasMany(ContentMeta::class);
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(Revision::class)->latest();
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ContentTranslation::class);
    }

    public function post(): HasOne
    {
        return $this->hasOne(Post::class);
    }

    public function page(): HasOne
    {
        return $this->hasOne(Page::class);
    }

    // Scopes
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    public function scopeScheduled(Builder $query): Builder
    {
        return $query->where('status', 'scheduled')
            ->where('published_at', '>', now());
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->whereFullText(['title', 'content'], $search);
    }

    // Accessors & Methods
    public function isPublished(): bool
    {
        return $this->status === 'published' && $this->published_at?->isPast();
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isScheduled(): bool
    {
        return $this->status === 'scheduled' && $this->published_at?->isFuture();
    }

    public function getMeta(string $key, $default = null)
    {
        return $this->meta()->where('meta_key', $key)->first()?->meta_value ?? $default;
    }

    public function setMeta(string $key, $value): void
    {
        $this->meta()->updateOrCreate(
            ['meta_key' => $key],
            ['meta_value' => $value]
        );
    }

    public function translate(string $locale): ?ContentTranslation
    {
        return $this->translations()->where('locale', $locale)->first();
    }

    public function hasTranslation(string $locale): bool
    {
        return $this->translations()->where('locale', $locale)->exists();
    }
}
