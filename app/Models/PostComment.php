<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    protected $fillable=['post_id','comment','user_id', 'parent_id'];

    protected $with = ['likes'];
    
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
     public function replies()
    {
        return $this->hasMany(PostComment::class, 'parent_id')->with('user', 'replies.user');
    }
    public function parent()
    {
        return $this->belongsTo(PostComment::class, 'parent_id');
    }

    public function likes()
    {
        return $this->hasMany(CommentLike::class, 'post_comment_id');
    }

}
