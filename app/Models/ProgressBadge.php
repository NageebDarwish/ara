<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressBadge extends Model
{
    protected $fillable = ['name', 'icon', 'series_count'];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
