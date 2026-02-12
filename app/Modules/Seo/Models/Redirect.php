<?php

namespace App\Modules\Seo\Models;

use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    protected $fillable = [
        'from_url',
        'to_url',
        'type',
        'is_regex',
        'hits',
        'last_used_at'
    ];

    protected $casts = [
        'is_regex' => 'boolean',
        'last_used_at' => 'datetime'
    ];

    /**
     * Increment hit counter.
     */
    public function recordHit(): void
    {
        $this->increment('hits');
        $this->update(['last_used_at' => now()]);
    }
}
