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
        // Réseaux sociaux principaux
        'facebook',
        'instagram',
        'twitter',
        'linkedin',
        'youtube',
        'tiktok',
        'pinterest',
        'snapchat',
        'whatsapp',
        'telegram',
        'discord',
        'twitch',
        'reddit',
        'github',
        'medium',
        'tumblr',
        'vimeo',
        'dribbble',
        'behance',
        'soundcloud',
        'spotify',
        'tripadvisor',
        'foursquare',
        'yelp',
        'google_maps',
        // Autres champs existants
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
        if ($this->mapPoint && $this->mapPoint->adresse) $parts[] = $this->mapPoint->adresse;
        if ($this->mapPoint && $this->mapPoint->ville) $parts[] = $this->mapPoint->ville;
        if ($this->mapPoint && $this->mapPoint->code_postal) $parts[] = $this->mapPoint->code_postal;
        
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

    /**
     * Récupère tous les réseaux sociaux non vides
     */
    public function getSocialNetworksAttribute()
    {
        $socials = [];
        $fields = [
            'facebook' => 'Facebook',
            'instagram' => 'Instagram',
            'twitter' => 'X (Twitter)',
            'linkedin' => 'LinkedIn',
            'youtube' => 'YouTube',
            'tiktok' => 'TikTok',
            'pinterest' => 'Pinterest',
            'snapchat' => 'Snapchat',
            'whatsapp' => 'WhatsApp',
            'telegram' => 'Telegram',
            'discord' => 'Discord',
            'twitch' => 'Twitch',
            'reddit' => 'Reddit',
            'github' => 'GitHub',
            'medium' => 'Medium',
            'tumblr' => 'Tumblr',
            'vimeo' => 'Vimeo',
            'dribbble' => 'Dribbble',
            'behance' => 'Behance',
            'soundcloud' => 'SoundCloud',
            'spotify' => 'Spotify',
            'tripadvisor' => 'TripAdvisor',
            'foursquare' => 'Foursquare',
            'yelp' => 'Yelp',
            'google_maps' => 'Google Maps'
        ];

        foreach ($fields as $field => $label) {
            if (!empty($this->$field)) {
                $socials[$field] = [
                    'label' => $label,
                    'url' => $this->$field,
                    'icon' => $this->getSocialIcon($field)
                ];
            }
        }

        return $socials;
    }

    /**
     * Récupère l'icône Font Awesome pour chaque réseau social
     */
    private function getSocialIcon($social)
    {
        $icons = [
            'facebook' => 'fab fa-facebook',
            'instagram' => 'fab fa-instagram',
            'twitter' => 'fab fa-x-twitter',
            'linkedin' => 'fab fa-linkedin',
            'youtube' => 'fab fa-youtube',
            'tiktok' => 'fab fa-tiktok',
            'pinterest' => 'fab fa-pinterest',
            'snapchat' => 'fab fa-snapchat',
            'whatsapp' => 'fab fa-whatsapp',
            'telegram' => 'fab fa-telegram',
            'discord' => 'fab fa-discord',
            'twitch' => 'fab fa-twitch',
            'reddit' => 'fab fa-reddit',
            'github' => 'fab fa-github',
            'medium' => 'fab fa-medium',
            'tumblr' => 'fab fa-tumblr',
            'vimeo' => 'fab fa-vimeo',
            'dribbble' => 'fab fa-dribbble',
            'behance' => 'fab fa-behance',
            'soundcloud' => 'fab fa-soundcloud',
            'spotify' => 'fab fa-spotify',
            'tripadvisor' => 'fab fa-tripadvisor',
            'foursquare' => 'fab fa-foursquare',
            'yelp' => 'fab fa-yelp',
            'google_maps' => 'fab fa-google'
        ];

        return $icons[$social] ?? 'fas fa-link';
    }
}