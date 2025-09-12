<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasFactory;

    protected $fillable=[
        'date',
        'target_minutes',
        'completed_minutes',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}