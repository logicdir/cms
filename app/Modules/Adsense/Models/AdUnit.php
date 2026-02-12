<?php

namespace App\Modules\Adsense\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdUnit extends Model
{
    protected $fillable = [
        'name', 'code', 'type', 'position', 'size', 
        'responsive', 'status', 'auto_insert', 'display_rules'
    ];

    protected $casts = [
        'responsive' => 'boolean',
        'status' => 'boolean',
        'auto_insert' => 'boolean',
        'display_rules' => 'array',
    ];

    public function placements(): HasMany
    {
        return $this->hasMany(AdPlacement::class);
    }

    public function impressions(): HasMany
    {
        return $this->hasMany(AdImpression::class);
    }
}
