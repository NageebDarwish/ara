<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'history_id',
        'history_type',
    ];


    public function history()
    {
        return $this->morphTo();
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}