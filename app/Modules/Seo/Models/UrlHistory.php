<?php

namespace App\Modules\Seo\Models;

use Illuminate\Database\Eloquent\Model;

class UrlHistory extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'content_type',
        'content_id',
        'old_slug',
        'new_slug',
        'changed_at'
    ];

    protected $casts = [
        'changed_at' => 'datetime'
    ];
}
