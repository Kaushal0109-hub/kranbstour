<?php

namespace App\Models;

use App\Helpers\CurrencyHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TourPackage extends Model
{
    protected $fillable = [
        'category_id', 'slug', 'title', 'duration', 'price', 'price_display', 'rating', 'tag',
        'image', 'summary', 'description', 'full_description', 'review_count',
        'is_featured', 'featured_section', 'featured_order', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'rating' => 'decimal:1',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(TourCategory::class, 'category_id');
    }

    public function highlights(): HasMany
    {
        return $this->hasMany(PackageHighlight::class, 'package_id')->orderBy('sort_order');
    }

    public function itineraries(): HasMany
    {
        return $this->hasMany(PackageItinerary::class, 'package_id')->orderBy('sort_order');
    }

    public function inclusions(): HasMany
    {
        return $this->hasMany(PackageInclusion::class, 'package_id')->orderBy('sort_order');
    }

    public function exclusions(): HasMany
    {
        return $this->hasMany(PackageExclusion::class, 'package_id')->orderBy('sort_order');
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(PackageFaq::class, 'package_id')->orderBy('sort_order');
    }

    public function locationTags(): HasMany
    {
        return $this->hasMany(PackageLocationTag::class, 'package_id')->orderBy('sort_order');
    }

    public function galleryImages(): HasMany
    {
        return $this->hasMany(PackageGalleryImage::class, 'package_id')->orderBy('sort_order');
    }

    public function features(): HasMany
    {
        return $this->hasMany(PackageFeature::class, 'package_id')->orderBy('sort_order');
    }

    public function importantInfos(): HasMany
    {
        return $this->hasMany(PackageImportantInfo::class, 'package_id')->orderBy('sort_order');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'package_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function getPriceFormattedAttribute(): string
    {
        return CurrencyHelper::formatAmount($this->price, $this->price_display);
    }
}
