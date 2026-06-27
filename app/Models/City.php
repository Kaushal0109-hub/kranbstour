<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    protected $fillable = [
        'key', 'slug', 'name', 'tagline', 'icon', 'description', 'home_highlights',
        'tour_count_label', 'card_image', 'banner_image', 'route_name',
        'sort_order', 'is_spotlight', 'is_active',
    ];

    protected $casts = [
        'home_highlights' => 'array',
        'is_spotlight' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function categories(): HasMany
    {
        return $this->hasMany(TourCategory::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
