<?php

namespace App\Modules\Adsense\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdPlacement extends Model
{
    protected $fillable = [
        'ad_unit_id', 'content_type', 'content_id', 
        'specific_position', 'start_date', 'end_date', 
        'priority', 'impressions_limit'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function adUnit(): BelongsTo
    {
        return $this->belongsTo(AdUnit::class);
    }
}
