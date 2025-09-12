<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class SeriesVideoList extends Model
{
    use HasFactory;
    protected $fillable=['user_id','series_video_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function video()
    {
        return $this->belongsTo(SeriesVideo::class,'series_video_id');
    }
}