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
        if (!$this->cover_image) {
            return null;
        }

        // If it's already a full URL, return as is
        if (filter_var($this->cover_image, FILTER_VALIDATE_URL)) {
            return $this->cover_image;
        }

        // If it's a storage path, return the full URL
        if (str_contains($this->cover_image, '/storage/')) {
            return url($this->cover_image);
        }

        // For compressed images, use the optimized URL
        if (str_contains($this->cover_image, '.webp') || str_contains($this->cover_image, '.jpg')) {
            return \App\Helpers\ImageHelper::optimized($this->cover_image);
        }

        // Fallback to asset URL
        return asset($this->cover_image);
    }

    /**
     * Get the cover image URL for API responses
     */
    public function getCoverImageAttribute($value)
    {
        // If this is being accessed in an API context, return the full URL
        if (request()->is('api/*')) {
            return $this->getCoverImageUrlAttribute();
        }

        return $value;
    }
}
