<?php

namespace App\Modules\Security\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    public $timestamps = false; // Only created_at

    protected $fillable = [
        'user_id', 'event', 'auditable_type', 'auditable_id', 
        'old_values', 'new_values', 'url', 'ip_address', 'user_agent'
    ];

    protected $casts = [
        'old_values' => 'json',
        'new_values' => 'json',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Core\Models\User::class);
    }

    public function auditable()
    {
        return $this->morphTo();
    }
}
