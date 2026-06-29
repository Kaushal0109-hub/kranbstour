<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomePromoSection extends Model
{
    protected $fillable = [
        'key', 'badge', 'title', 'subtitle', 'description', 'tags', 'price_label',
        'cta_label', 'cta_route', 'secondary_cta_label', 'secondary_cta_route',
        'category_slug', 'city_keys', 'is_active',
    ];

    protected $casts = [
        'tags' => 'array',
        'city_keys' => 'array',
        'is_active' => 'boolean',
    ];

    public static function getByKey(string $key): ?self
    {
        return static::query()->where('key', $key)->where('is_active', true)->first();
    }
}
