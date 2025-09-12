<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HideVideo extends Model
{
    protected $fillable = ['user_id', 'series_video_id', 'video_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seriesVideo()
    {
        return $this->belongsTo(SeriesVideo::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
