<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable=['name'];

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function series()
    {
        return $this->hasMany(Series::class);
    }
}
