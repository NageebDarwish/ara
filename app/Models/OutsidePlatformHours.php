<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutsidePlatformHours extends Model
{
    protected $fillable = ['user_id', 'date', 'activity', 'duration', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
