<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Blog extends Model
{

    protected $fillable = [
        'blog_category_id',
        'cover_image',
        'title',
        'meta_title',
        'description',
        'meta_description',
        'content',
        'slug',
        'author',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(BlogCategory::class,'blog_category_id');
    }

    /**
     * Get the full URL for the cover image
     */
    public function getCoverImageUrlAttribute()
    {
        $value = $this->attributes['cover_image'] ?? null;

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
     * Get the cover image URL for API responses
     */
    public function getCoverImageAttribute($value)
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
