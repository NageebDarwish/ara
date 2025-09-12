<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialAchievementBadge extends Model
{
    protected $fillable = ['name', 'icon', 'criteria'];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
