<?php

namespace App\Modules\Content\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentMeta extends Model
{
    use HasFactory;

    protected $fillable = ['content_id', 'meta_key', 'meta_value'];

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }
}
