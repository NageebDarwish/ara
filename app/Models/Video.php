<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    protected $fillable=[
        'level_id',
        'guide_id',
        'topic_id',
        'country_id',
        'title',
        'description',
        'video',
        'plan',
        'is_hide',
        'publishedAt',
        'status',
        'scheduleDateTime',
        'duration',
        'duration_seconds',
    ];
    protected $with = ['timelines','level','topic'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function guide()
    {
        return $this->belongsTo(Guide::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function downloadLists()
    {
        return $this->morphMany(DownloadList::class, 'downloadable');
    }

    public function videoHistories()
    {
        return $this->morphMany(VideoHistory::class, 'history');
    }

    public function videoLists()
    {
        return $this->hasMany(VideoList::class);
    }

    public function timelines()
    {
        return $this->hasMany(VideoTimeline::class);
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
