<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeriesVideo extends Model
{
    use HasFactory;
    protected $fillable=[
        'title',
        'description',
        'video',
        'series_id',
        'plan',
        'is_hide',
        'playlist_id',
        'playlist_id',
        'publishedAt',
        'scheduleDateTime',
        'status',
        'duration',
        'duration_seconds',
    ];

    protected $with=['timeline'];
    public function series()
    {
        return $this->belongsTo(Series::class);
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
        return $this->hasMany(VideoSeriesTimeline::class,'series_video_id');
    }

    public function hideVideos()
    {
        return $this->hasMany(HideVideo::class);
    }
    
     public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }

}
