<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningBadge extends Model
{
    protected $fillable = ['name', 'icon', 'hours_watched'];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
