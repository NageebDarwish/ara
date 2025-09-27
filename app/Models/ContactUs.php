<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    protected $fillable=['name','email','subject','message','replied_at','read_at'];
    
    protected $casts = [
        'replied_at' => 'datetime',
        'read_at' => 'datetime',
    ];
}
