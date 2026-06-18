<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gallery extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'property_id',
        'image_path',
        'caption',
        'sort_order',
    ];

    /**
     * Get the property that owns the gallery image.
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}
