<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressLevel extends Model
{
    protected $fillable = [
        'name',
        'watching_hours',
        'known_words'
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
