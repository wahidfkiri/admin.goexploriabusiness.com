<?php
// app/Models/MapPointVideo.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapPointVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'map_point_id',
        'title',
        'youtube_url',
        'youtube_id',
        'sort_order'
    ];

    protected $casts = [
        'sort_order' => 'integer'
    ];

    // Relations
    public function mapPoint()
    {
        return $this->belongsTo(MapPoint::class);
    }

    // Accesseurs
    public function getThumbnailAttribute()
    {
        return "https://img.youtube.com/vi/{$this->youtube_id}/hqdefault.jpg";
    }

    public function getEmbedUrlAttribute()
    {
        return "https://www.youtube.com/embed/{$this->youtube_id}";
    }
}