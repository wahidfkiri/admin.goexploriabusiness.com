<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Place extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'places';

    protected $fillable = [
        'name',
        'description',
        'latitude',
        'longitude',
        'category',
        'images',
        'video_url',
        'video_id',
        'country_id',
        'activity_id',
        'address',
        'phone',
        'website',
        'email',
        'opening_hours',
        'closing_hours',
        'price_range',
        'rating',
        'is_active',
        'is_featured',
        'sort_order',
        'tags',
        'amenities'
    ];

    protected $casts = [
        'images' => 'array',
        'tags' => 'array',
        'amenities' => 'array',
        'latitude' => 'float',
        'longitude' => 'float',
        'price_range' => 'float',
        'rating' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
        'opening_hours' => 'datetime:H:i',
        'closing_hours' => 'datetime:H:i'
    ];

    /**
     * Relation avec le pays
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Relation avec l'activité
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * Obtenir la première image
     */
    public function getFirstImageAttribute()
    {
        if ($this->images && count($this->images) > 0) {
            return $this->images[0];
        }
        return null;
    }

    /**
     * Obtenir les URLs complètes des images
     */
    public function getImageUrlsAttribute()
    {
        if (!$this->images) {
            return [];
        }

        return array_map(function ($image) {
            if (filter_var($image, FILTER_VALIDATE_URL)) {
                return $image;
            }
            return Storage::disk('public')->exists($image) 
                ? Storage::disk('public')->url($image)
                : null;
        }, $this->images);
    }

    /**
     * Obtenir l'URL de la vidéo YouTube embed
     */
    public function getEmbedVideoUrlAttribute()
    {
        if ($this->video_id) {
            return "https://www.youtube.com/embed/{$this->video_id}";
        }
        return $this->video_url;
    }

    /**
     * Obtenir les coordonnées GPS
     */
    public function getCoordinatesAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return [
                'lat' => $this->latitude,
                'lng' => $this->longitude
            ];
        }
        return null;
    }

    /**
     * Obtenir les heures d'ouverture formatées
     */
    public function getOpeningHoursFormattedAttribute()
    {
        if ($this->opening_hours && $this->closing_hours) {
            return date('H:i', strtotime($this->opening_hours)) . ' - ' . 
                   date('H:i', strtotime($this->closing_hours));
        }
        return null;
    }

    /**
     * Vérifier si le lieu est ouvert maintenant
     */
    public function getIsOpenNowAttribute()
    {
        if (!$this->opening_hours || !$this->closing_hours) {
            return null;
        }

        $now = now();
        $opening = \Carbon\Carbon::parse($this->opening_hours);
        $closing = \Carbon\Carbon::parse($this->closing_hours);

        // Si les heures de fermeture sont après minuit
        if ($closing->lessThan($opening)) {
            $closing->addDay();
        }

        return $now->between($opening, $closing);
    }

    /**
     * Obtenir la catégorie avec icône
     */
    public function getCategoryIconAttribute()
    {
        $icons = [
            'restaurant' => 'fas fa-utensils',
            'hotel' => 'fas fa-hotel',
            'museum' => 'fas fa-landmark',
            'park' => 'fas fa-tree',
            'beach' => 'fas fa-umbrella-beach',
            'shopping' => 'fas fa-shopping-bag',
            'attraction' => 'fas fa-camera',
            'historic' => 'fas fa-monument',
            'religious' => 'fas fa-church',
            'natural' => 'fas fa-mountain',
            'cultural' => 'fas fa-theater-masks',
            'sport' => 'fas fa-futbol',
            'entertainment' => 'fas fa-film',
            'transport' => 'fas fa-bus',
            'health' => 'fas fa-hospital',
            'education' => 'fas fa-graduation-cap',
        ];

        return $icons[$this->category] ?? 'fas fa-map-marker-alt';
    }

    /**
     * Scope pour les lieux actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour les lieux à la une
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope pour une catégorie spécifique
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope pour un pays spécifique
     */
    public function scopeByCountry($query, $countryId)
    {
        return $query->where('country_id', $countryId);
    }

    /**
     * Scope pour une activité spécifique
     */
    public function scopeByActivity($query, $activityId)
    {
        return $query->where('activity_id', $activityId);
    }

    /**
     * Scope de recherche
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('address', 'like', "%{$search}%")
              ->orWhere('category', 'like', "%{$search}%");
        });
    }

    /**
     * Scope de proximité (géolocalisation)
     */
    public function scopeNearby($query, $latitude, $longitude, $radius = 10)
    {
        $haversine = "(6371 * acos(cos(radians($latitude)) 
                     * cos(radians(latitude)) 
                     * cos(radians(longitude) - radians($longitude)) 
                     + sin(radians($latitude)) 
                     * sin(radians(latitude))))";

        return $query->select('*')
                     ->selectRaw("{$haversine} AS distance")
                     ->whereRaw("{$haversine} < ?", [$radius])
                     ->orderBy('distance');
    }
}