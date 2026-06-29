<?php

namespace App\Models;

use App\Services\TourCatalog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Monument extends Model
{
    protected $fillable = ['category_id', 'name', 'slug', 'description', 'image', 'sort_order'];

    protected static function booted(): void
    {
        static::saving(function (Monument $monument) {
            if (blank($monument->slug) && filled($monument->name)) {
                $monument->slug = TourCatalog::slugify($monument->name);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TourCategory::class, 'category_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
