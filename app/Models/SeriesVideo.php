<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeriesVideo extends Model
{
    use HasFactory;
    protected $fillable = [
        'series_id',
        'video_id',
        'plan',
        'is_hide',
        'playlist_id',
    ];

    protected $with = ['timeline', 'video'];

    public function series()
    {
        return $this->belongsTo(Series::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function downloadLists()
    {
        return $this->morphMany(DownloadList::class, 'downloadable');
    }
    public function videoHistories()
    {
        return $this->morphMany(VideoHistory::class, 'history');
    }

    public function timeline()
    {
        return $this->hasMany(VideoSeriesTimeline::class, 'series_video_id');
    }

    public function hideVideos()
    {
        return $this->hasMany(HideVideo::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
