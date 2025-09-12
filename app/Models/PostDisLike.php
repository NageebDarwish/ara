<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostDisLike extends Model
{
    protected $fillable = ['post_id','user_id','is_dis_liked'];
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
