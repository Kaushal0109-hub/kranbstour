<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageImportantInfo extends Model
{
    public $timestamps = false;

    protected $fillable = ['package_id', 'heading', 'item_text', 'sort_order'];

    public function package(): BelongsTo
    {
        return $this->belongsTo(TourPackage::class, 'package_id');
    }
}
