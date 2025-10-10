<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    protected $fillable = [
        'email', 'password', 'role','otp','is_verified','is_premium', 'progress_badge_id', 'learning_badge_id', 'consistency_badge_id',
        'special_achievement_badge_id', 'name', 'original_password', 'fullname', 'progress_level_id','profile_image','verification_token','verification_token_expires_at','watching_hours','total_watching_hours',
    ];

    protected $hidden = [
        'password', 'remember_token','role','otp','verification_token','verification_token_expires_at'
    ];

     protected $with = ['progressLevel', 'goals','subscription.plan','badges','videoTimelines.video','videoSeriesTimelines.seriesVideo','videoLists','badgeModals'];


    public function downloadLists()
    {
        return $this->hasMany(DownloadList::class);
    }

    public function videoLists()
    {
        return $this->hasMany(VideoList::class);
    }
       public function seriesVideoLists()
    {
        return $this->hasMany(SeriesVideoList::class);
    }

    public function seriesLists()
    {
        return $this->hasMany(SeriesList::class);
    }

    public function videoHistories()
    {
        return $this->hasMany(VideoHistory::class);
    }

    public function goals()
    {
        return $this->hasMany(Goal::class);
    }

    public function hideVideos()
    {
        return $this->hasMany(HideVideo::class);
    }

    public function progressBadge()
    {
        return $this->belongsTo(ProgressBadge::class);
    }

    public function learningBadge()
    {
        return $this->belongsTo(LearningBadge::class);
    }

    public function consistencyBadge()
    {
        return $this->belongsTo(ConsistencyBadge::class);
    }

    public function achievementBadge()
    {
        return $this->belongsTo(SpecialAchievementBadge::class);
    }

    public function progressLevel()
    {
        return $this->belongsTo(ProgressLevel::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function cardDetails()
    {
        return $this->hasOne(CardDetail::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class);
    }
      public function videoTimelines()
    {
        return $this->hasMany(VideoTimeline::class);
    }

    public function videoSeriesTimelines()
    {
        return $this->hasMany(VideoSeriesTimeline::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function savedPosts()
    {
        return $this->belongsToMany(Post::class)->withTimestamps();
    }

    public function likes()
    {
        return $this->hasMany(CommentLike::class, 'user_id');
    }

     public function givenPostLikes()
    {
        return $this->hasMany(PostLike::class,'user_id');
    }

    public function givenPostComments()
    {
        return $this->hasMany(PostComment::class,'user_id');
    }
    public function givenVideoComments()
    {
        return $this->hasMany(Comment::class,'user_id');
    }

     public function badgeModals()
    {
        return $this->hasMany(BadgeModal::class);
    }

    /**
     * Get the full URL for the profile image
     */
    public function getProfileImageUrlAttribute()
    {
        $value = $this->attributes['profile_image'] ?? null;

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
     * Get the profile image URL for API responses
     */
    public function getProfileImageAttribute($value)
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
