<?php

namespace App\Modules\Content\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentTranslation extends Model
{
    use HasFactory;

    protected $primaryKey = ['content_id', 'locale'];
    public $incrementing = false;

    protected $fillable = [
        'content_id', 'locale', 'title', 'slug', 'excerpt', 
        'content', 'meta_title', 'meta_description',
    ];

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }

    protected function setKeysForSaveQuery($query)
    {
        $query->where('content_id', $this->getAttribute('content_id'))
              ->where('locale', $this->getAttribute('locale'));
        return $query;
    }
}
