<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WatchedVideo extends Model
{
    protected $fillable = ['user_id', 'video_count'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
