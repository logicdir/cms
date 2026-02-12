<?php

namespace App\Modules\Content\Models;

use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Revision extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'content_id', 'title', 'content', 'excerpt', 'user_id', 'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
