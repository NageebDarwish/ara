<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'title',
        'description',
        'order'
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function items()
    {
        return $this->hasMany(FaqItem::class)->orderBy('order');
    }
}
