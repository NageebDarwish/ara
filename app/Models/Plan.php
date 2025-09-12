<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable=[
        'name',
        'cycle',
        'price',
        'free_videos',
        'progress_tracking',
        'community_forums',
        'exclusive_videos_added_daily',
        'no_of_exclusive_videos_added_daily',
        'premium_video_series',
        'ability_to_watch_videos_offline',
        'is_default',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

}
