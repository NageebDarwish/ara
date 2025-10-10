<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'faq_section_id',
        'question',
        'answer',
        'order'
    ];

    public function section()
    {
        return $this->belongsTo(FaqSection::class, 'faq_section_id');
    }
}
