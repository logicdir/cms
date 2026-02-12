<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleDependency extends Model
{
    protected $fillable = ['module_id', 'dependent_slug', 'version_constraint'];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
}
