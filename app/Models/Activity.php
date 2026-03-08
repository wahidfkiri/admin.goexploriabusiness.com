<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Activity extends Model
{
    protected $fillable = [
        'name',
        'type',
        'categorie_id',
        'description',
        'image',
        'tags',
        'slug',
        'is_active',
    ];

    
    protected $casts = [
        'is_active' => 'boolean',
        'type' => 'integer',
        'category' => 'integer',
    ];

    // Relation avec le modèle Category (utilise category au lieu de categorie_id)
    public function categoryRelation()
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }

    // Relation avec le type de catégorie
    public function typeRelation()
    {
        return $this->belongsTo(CategorieType::class, 'type');
    }

    // Accesseurs pratiques
    public function getCategoryNameAttribute()
    {
        return $this->categoryRelation ? $this->categoryRelation->name : null;
    }

    public function getTypeNameAttribute()
    {
        return $this->typeRelation ? $this->typeRelation->name : null;
    }

    // Génération automatique du slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($activity) {
            $activity->slug = Str::slug($activity->name);
        });

        static::updating(function ($activity) {
            $activity->slug = Str::slug($activity->name);
        });
    }

    /**
     * Relation Many-to-Many avec les établissements
     */
    public function etablissements(): BelongsToMany
    {
        return $this->belongsToMany(Etablissement::class)
                    ->withTimestamps()
                    ->withPivot('created_at', 'updated_at');
    }
    
    /**
     * Relation avec les établissements actifs seulement
     */
    public function activeEtablissements(): BelongsToMany
    {
        return $this->belongsToMany(Etablissement::class)
                    ->where('etablissements.is_active', true)
                    ->withTimestamps();
    }
    
    /**
     * Scope pour les activités actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    /**
     * Scope pour les activités inactives
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }
    
    /**
     * Compter le nombre d'établissements pour cette activité
     */
    public function etablissementsCount()
    {
        return $this->etablissements()->count();
    }

    public function continents()
    {
        return $this->belongsToMany(Continent::class, 'activity_continent')
                    ->withTimestamps();
    }

    /**
     * Relation avec les pays
     */
    public function countries()
    {
        return $this->belongsToMany(Country::class, 'activity_country')
                    ->withTimestamps();
    }

    /**
     * Relation avec les régions
     */
    public function regions()
    {
        return $this->belongsToMany(Region::class, 'activity_region')
                    ->withTimestamps();
    }

    /**
     * Relation avec les provinces
     */
    public function provinces()
    {
        return $this->belongsToMany(Province::class, 'activity_province')
                    ->withTimestamps();
    }

    /**
     * Relation avec les villes
     */
    public function cities()
    {
        return $this->belongsToMany(Ville::class, 'activity_city')
                    ->withTimestamps();
    }

    /**
     * Méthode pratique pour obtenir toutes les localisations
     */
    public function allLocations()
    {
        return [
            'continents' => $this->continents,
            'countries' => $this->countries,
            'regions' => $this->regions,
            'provinces' => $this->provinces,
            'cities' => $this->cities,
        ];
    }

    /**
     * Méthode pour obtenir l'URL de l'image
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    /**
     * Méthode pour obtenir les tags sous forme de tableau
     */
    public function getTagsArrayAttribute()
    {
        if (!$this->tags) {
            return [];
        }
        
        // Si les tags sont stockés en JSON
        if (Str::startsWith($this->tags, '[')) {
            return json_decode($this->tags, true);
        }
        
        // Si les tags sont une chaîne séparée par des virgules
        return array_map('trim', explode(',', $this->tags));
    }
}