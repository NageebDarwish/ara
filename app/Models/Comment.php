<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
     protected $fillable = [
        'comment',
        'commentable_id',
        'commentable_type',
        'user_id'
    ];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
     public function likes()
    {
        return $this->hasMany(VideoCommentLike::class, 'comment_id');
    }
}
