<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TourCategory extends Model
{
    protected $fillable = [
        'city_id', 'key', 'slug', 'city_name', 'title', 'heading', 'tagline', 'icon',
        'description', 'banner_image', 'card_image', 'tour_count_label', 'route_name',
        'map_query', 'sort_order', 'is_active', 'show_in_nav', 'nav_label',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_in_nav' => 'boolean',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function packages(): HasMany
    {
        return $this->hasMany(TourPackage::class, 'category_id');
    }

    public function monuments(): HasMany
    {
        return $this->hasMany(Monument::class, 'category_id');
    }

    public function relatedCategories(): BelongsToMany
    {
        return $this->belongsToMany(
            TourCategory::class,
            'category_related',
            'category_id',
            'related_category_id'
        )->withPivot('sort_order')->orderByPivot('sort_order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
