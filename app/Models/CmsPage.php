<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsPage extends Model
{
    protected $fillable = [
        'slug', 'title', 'heading', 'content', 'show_in_footer', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'show_in_footer' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
