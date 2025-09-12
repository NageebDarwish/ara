<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
