<?php
// app/Models/MapPointImage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapPointImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'map_point_id',
        'image',
        'thumbnail',
        'caption',
        'sort_order',
        'is_main'
    ];

    protected $casts = [
        'is_main' => 'boolean',
        'sort_order' => 'integer'
    ];

    // Relations
    public function mapPoint()
    {
        return $this->belongsTo(MapPoint::class);
    }

    // Accesseurs
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->image);
    }

    public function getThumbUrlAttribute()
    {
        return $this->thumbnail 
            ? asset('storage/' . $this->thumbnail)
            : $this->url;
    }

    // Scope
    public function scopeMain($query)
    {
        return $query->where('is_main', true);
    }
}