<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    ];
    
    public function category()
    {
        return $this->belongsTo(BlogCategory::class,'blog_category_id');
    }
}