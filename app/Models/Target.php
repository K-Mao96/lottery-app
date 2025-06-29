<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    protected $guarded = [];

    /**
     * Get the target list that owns the target.
     */
    public function targetList()
    {
        return $this->belongsTo(TargetList::class);
    }

    /**
     * Scope a query to only include non-excluded targets.
     */
    public function scopeNonExcluded($query)
    {
        return $query->where('is_excluded', false);
    }

    /**
     * Scope a query to only include selected targets.
     */
    public function scopeSelected($query)
    {
        return $query->where('is_selected', true);
    }
}
