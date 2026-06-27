<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'quote', 'reviewer_name', 'place', 'city', 'rating', 'title',
        'avatar_image', 'review_date_label', 'show_on_home', 'show_on_package',
        'sort_order', 'is_active',
    ];

    protected $casts = [
        'show_on_home' => 'boolean',
        'show_on_package' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
