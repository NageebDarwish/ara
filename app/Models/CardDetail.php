<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardDetail extends Model
{
    protected $fillable=[
        'user_id',
        'card_number',
        'card_type',
        'expiry_date',
        'cardholder_name',
        'cvv',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
