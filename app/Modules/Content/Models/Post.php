<?php

namespace App\Modules\Content\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

    protected $primaryKey = 'content_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'content_id', 'category_id', 'allow_comments', 'comment_count',
    ];

    protected $casts = [
        'allow_comments' => 'boolean',
    ];

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
