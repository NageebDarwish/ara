<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable=[
        'user_id',
        'plan_id',
        'amount',
        'start_date',
        'end_date',
        'status',
        'is_renewable',
        'stripe_customer_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}