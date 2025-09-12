<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BadgeModal extends Model
{
    protected $fillable=['user_id','badge_id','opened'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function badge()
    {
        return $this->belongsTo(Badge::class);
    }
}