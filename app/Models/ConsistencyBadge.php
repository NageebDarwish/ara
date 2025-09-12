<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsistencyBadge extends Model
{
    protected $fillable = ['name', 'icon', 'streak_days'];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
