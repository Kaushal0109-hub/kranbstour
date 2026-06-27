<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'city',
        'travel_date',
        'travelers',
        'message',
        'is_read',
    ];

    protected $casts = [
        'travel_date' => 'date',
        'is_read' => 'boolean',
    ];
}
