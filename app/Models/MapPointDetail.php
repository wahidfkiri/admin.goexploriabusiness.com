<?php
// app/Models/MapPointDetail.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapPointDetail extends Model
{
    use HasFactory;

    protected $table = 'map_point_details';

    protected $fillable = [
        'map_point_id',
        'long_description',
        'phone',
        'email',
        'website',
        'horaires',
        'services',
        'tarifs',
        'contact_person',
        'facebook',
        'instagram',
        'twitter',
        'linkedin',
        'rating',
        'reviews_count',
        'meta_title',
        'meta_description',
        'slug'
    ];

    protected $casts = [
        'horaires' => 'array',
        'services' => 'array',
        'tarifs' => 'array',
        'rating' => 'decimal:2',
        'reviews_count' => 'integer'
    ];

    // Relations
    public function mapPoint()
    {
        return $this->belongsTo(MapPoint::class);
    }

    // Accesseurs
    public function getFullAddressAttribute()
    {
        $parts = [];
        if ($this->mapPoint->adresse) $parts[] = $this->mapPoint->adresse;
        if ($this->mapPoint->ville) $parts[] = $this->mapPoint->ville;
        if ($this->mapPoint->code_postal) $parts[] = $this->mapPoint->code_postal;
        
        return implode(', ', $parts);
    }

    public function getStarRatingAttribute()
    {
        $full = floor($this->rating);
        $half = $this->rating - $full >= 0.5;
        
        return [
            'full' => $full,
            'half' => $half,
            'empty' => 5 - $full - ($half ? 1 : 0)
        ];
    }
}