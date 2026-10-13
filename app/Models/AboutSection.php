<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AboutSection extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'slug',
        'body',
        'image',
        'order',
        'is_active',
    ];

    /**
     * Attribute casting configuration.
     *
     * - order is cast to integer for correct sorting
     * - is_active is cast to boolean for visibility control
     */
    protected $casts = [
        'order'     => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Query scope: active about sections in display order.
     *
     * Responsibilities:
     * - Retrieve only sections marked as active
     * - Sort by the custom `order` column for the public About page
     *
     * Usage example:
     * AboutSection::active()->get();
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }
}
