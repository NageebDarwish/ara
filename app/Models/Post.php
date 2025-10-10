<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    protected $fillable=['user_id','subject','body','file'];
    // protected $with=['user','tags','comments','likes','disLikes'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(PostTag::class);
    }

    public function comments()
    {
        return $this->hasMany(PostComment::class)->whereNull('parent_id')
        ->with(['replies', 'user']);
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class);
    }

    public function disLikes()
    {
        return $this->hasMany(PostDislike::class);
    }
    public function savers()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * Get the full URL for the file
     */
    public function getFileUrlAttribute()
    {
        if (!$this->file) {
            return null;
        }

        // If it's already a full URL, return as is
        if (filter_var($this->file, FILTER_VALIDATE_URL)) {
            return $this->file;
        }

        // If it's a storage path, return the full URL
        if (str_contains($this->file, '/storage/')) {
            return url($this->file);
        }

        // For compressed images, use the optimized URL
        if (str_contains($this->file, '.webp') || str_contains($this->file, '.jpg')) {
            return \App\Helpers\ImageHelper::optimized($this->file);
        }

        // Fallback to asset URL
        return asset($this->file);
    }

    /**
     * Get the file URL for API responses
     */
    public function getFileAttribute($value)
    {
        // If this is being accessed in an API context, return the full URL
        if (request()->is('api/*')) {
            return $this->getFileUrlAttribute();
        }

        return $value;
    }
}
