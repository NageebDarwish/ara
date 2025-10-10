<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
            ->whereHas('video', function($q) {
                $q->where('status', 'public');
            });
    }

    public function seriesLists()
    {
        return $this->hasMany(SeriesList::class);
    }

    /**
     * Get the full URL for the thumbnail
     */
    public function getThumbnailUrlAttribute()
    {
        if (!$this->thumbnail) {
            return null;
        }

        // If it's already a full URL, return as is
        if (filter_var($this->thumbnail, FILTER_VALIDATE_URL)) {
            return $this->thumbnail;
        }

        // If it's a storage path, return the full URL
        if (str_contains($this->thumbnail, '/storage/')) {
            return url($this->thumbnail);
        }

        // For compressed images, use the optimized URL
        if (str_contains($this->thumbnail, '.webp') || str_contains($this->thumbnail, '.jpg')) {
            return \App\Helpers\ImageHelper::optimized($this->thumbnail);
        }

        // Fallback to asset URL
        return asset($this->thumbnail);
    }

    /**
     * Get the full URL for the vertical thumbnail
     */
    public function getVerticalThumbnailUrlAttribute()
    {
        if (!$this->vertical_thumbnail) {
            return null;
        }

        // If it's already a full URL, return as is
        if (filter_var($this->vertical_thumbnail, FILTER_VALIDATE_URL)) {
            return $this->vertical_thumbnail;
        }

        // If it's a storage path, return the full URL
        if (str_contains($this->vertical_thumbnail, '/storage/')) {
            return url($this->vertical_thumbnail);
        }

        // For compressed images, use the optimized URL
        if (str_contains($this->vertical_thumbnail, '.webp') || str_contains($this->vertical_thumbnail, '.jpg')) {
            return \App\Helpers\ImageHelper::optimized($this->vertical_thumbnail);
        }

        // Fallback to asset URL
        return asset($this->vertical_thumbnail);
    }

    /**
     * Get the thumbnail URL for API responses
     */
    public function getThumbnailAttribute($value)
    {
        // If this is being accessed in an API context, return the full URL
        if (request()->is('api/*')) {
            return $this->getThumbnailUrlAttribute();
        }

        return $value;
    }

    /**
     * Get the vertical thumbnail URL for API responses
     */
    public function getVerticalThumbnailAttribute($value)
    {
        // If this is being accessed in an API context, return the full URL
        if (request()->is('api/*')) {
            return $this->getVerticalThumbnailUrlAttribute();
        }

        return $value;
    }
}
