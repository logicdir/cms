<?php

namespace App\Modules\Content\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function contents(): BelongsToMany
    {
        return $this->belongsToMany(Content::class, 'content_tag');
    }

    public static function createFromName(string $name): self
    {
        return static::create([
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name),
        ]);
    }
}
