<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadList extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'downloadable_id',
        'downloadable_type',
    ];


    public function downloadable()
    {
        return $this->morphTo();
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}