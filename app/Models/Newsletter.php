<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    protected $fillable = [
        'subject',
        'body',
        'recipient_type',
        'selected_users',
        'status',
        'scheduled_at',
        'sent_at',
        'recipients_count',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'selected_users' => 'array',
    ];
}

