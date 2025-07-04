<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TargetList extends Model
{
    protected $guarded = [];

    public function targets()
    {
        return $this->hasMany(Target::class);
    }
}
