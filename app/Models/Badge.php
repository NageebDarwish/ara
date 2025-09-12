<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $fillable=[
        'type',
        'name',
        'series_count',
        'icon',
        'hours_watched',
        'streak_days',
        'criteria',
    ];
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
     public function badgeModals()
    {
        return $this->hasMany(BadgeModal::class);
    }
}