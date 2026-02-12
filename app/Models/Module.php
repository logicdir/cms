<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Module
 *
 * Eloquent model for the modules table.
 */
class Module extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'version',
        'description',
        'author',
        'path',
        'is_active',
        'providers',
        'priority',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'providers' => 'array',
        'priority' => 'integer',
    ];

    /**
     * Get module dependencies.
     */
    public function dependencies(): HasMany
    {
        return $this->hasMany(ModuleDependency::class);
    }

    /**
     * Get module settings.
     */
    public function settings(): HasMany
    {
        return $this->hasMany(ModuleSetting::class);
    }
}
