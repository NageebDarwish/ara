<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostTag extends Model
{
    protected $fillable=['name'];
    protected $with = ['posts'];
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}
