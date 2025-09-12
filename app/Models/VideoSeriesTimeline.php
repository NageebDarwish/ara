<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoSeriesTimeline extends Model
{
    protected $fillable = ['user_id', 'series_video_id', 'watched_time','progress_time', 'date', 'is_completed', 'series_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seriesVideo()
    {
    return $this->belongsTo(SeriesVideo::class,'series_video_id');
    }
}
