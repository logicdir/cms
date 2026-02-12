<?php

namespace App\Modules\Adsense\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdImpression extends Model
{
    public $timestamps = false; // We use created_at only

    protected $fillable = [
        'ad_unit_id', 'page_url', 'user_agent', 'ip_address', 'clicked'
    ];

    protected $casts = [
        'clicked' => 'boolean',
    ];

    public function adUnit(): BelongsTo
    {
        return $this->belongsTo(AdUnit::class);
    }
}
