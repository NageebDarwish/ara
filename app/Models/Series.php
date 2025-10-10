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
        $value = $this->attributes['thumbnail'] ?? null;

        if (!$value) {
            return null;
        }

        // If it's already a full URL, return as is
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        // If it's a storage path, return the full URL
        if (str_contains($value, '/storage/')) {
            return url($value);
        }

        // For compressed images, use the optimized URL
        if (str_contains($value, '.webp') || str_contains($value, '.jpg')) {
            return \App\Helpers\ImageHelper::optimized($value);
        }

        // Fallback to asset URL
        return asset($value);
    }

    /**
     * Get the full URL for the vertical thumbnail
     */
    public function getVerticalThumbnailUrlAttribute()
    {
        $value = $this->attributes['vertical_thumbnail'] ?? null;

        if (!$value) {
            return null;
        }

        // If it's already a full URL, return as is
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        // If it's a storage path, return the full URL
        if (str_contains($value, '/storage/')) {
            return url($value);
        }

        // For compressed images, use the optimized URL
        if (str_contains($value, '.webp') || str_contains($value, '.jpg')) {
            return \App\Helpers\ImageHelper::optimized($value);
        }

        // Fallback to asset URL
        return asset($value);
    }

    /**
     * Get the thumbnail URL for API responses
     */
    public function getThumbnailAttribute($value)
    {
        // If this is being accessed in an API context, return the full URL
        if (request()->is('api/*')) {
            if (!$value) {
                return null;
            }

            // If it's already a full URL, return as is
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            }

            // If it's a storage path, return the full URL
            if (str_contains($value, '/storage/')) {
                return url($value);
            }

            // For compressed images, use the optimized URL
            if (str_contains($value, '.webp') || str_contains($value, '.jpg')) {
                return \App\Helpers\ImageHelper::optimized($value);
            }

            // Fallback to asset URL
            return asset($value);
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
            if (!$value) {
                return null;
            }

            // If it's already a full URL, return as is
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            }

            // If it's a storage path, return the full URL
            if (str_contains($value, '/storage/')) {
                return url($value);
            }

            // For compressed images, use the optimized URL
            if (str_contains($value, '.webp') || str_contains($value, '.jpg')) {
                return \App\Helpers\ImageHelper::optimized($value);
            }

            // Fallback to asset URL
            return asset($value);
        }

        return $value;
    }
}
