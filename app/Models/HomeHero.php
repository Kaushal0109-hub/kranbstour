<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeHero extends Model
{
    protected $fillable = [
        'badge_text', 'rating_text', 'heading_line1', 'heading_line2', 'subtitle',
        'search_placeholder', 'background_image', 'thumbnail_keys', 'is_active',
    ];

    protected $casts = [
        'thumbnail_keys' => 'array',
        'is_active' => 'boolean',
    ];
}
