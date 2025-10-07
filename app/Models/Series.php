<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    use HasFactory;
    protected $fillable=[
        'level_id',
        'guide_id',
        'topic_id',
        'country_id',
        'title',
        'description',
        'plan',
        'playlist_id',
        'publishedAt',
        'scheduleDateTime',
        'status',
        'thumbnail',
        'vertical_thumbnail',
    ];
protected $with=['level','country','topic','guide','videos'];
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function guide()
    {
        return $this->belongsTo(Guide::class);
    }

    public function videos()
    {
        return $this->hasMany(SeriesVideo::class);
    }

    // Public videos only (for API/users) - excludes plan='new'
    public function publicVideos()
    {
        return $this->hasMany(SeriesVideo::class)
            ->whereIn('plan', ['free', 'premium'])
            ->where('status', 'public');
    }

    public function seriesLists()
    {
        return $this->hasMany(SeriesList::class);
    }
}
