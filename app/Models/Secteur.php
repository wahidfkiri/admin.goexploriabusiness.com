<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasPage;

class Secteur extends Model
{
    use HasFactory, SoftDeletes, HasPage;

    protected $fillable = [
        'name',
        'code',
        'classification',
        'population',
        'area',
        'households',
        'density',
        'mayor',
        'website',
        'description',
        'history',
        'attractions',
        'transport',
        'education',
        'parks',
        'latitude',
        'longitude',
        'is_active',
        'region_id'
    ];

    protected $casts = [
        'population' => 'integer',
        'area' => 'decimal:2',
        'households' => 'integer',
        'density' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // Relation avec la région
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    

    public function scopeActive($query)
{
    return $query->where('is_active', true);
}

    // Accessor pour le nom complet
    public function getFullNameAttribute(): string
    {
        return "{$this->name} ({$this->code})";
    }

    // Accessor pour les coordonnées
    public function getCoordinatesAttribute(): string
    {
        if ($this->latitude && $this->longitude) {
            return "{$this->latitude}, {$this->longitude}";
        }
        return 'Non disponible';
    }

    // Accessor pour Google Maps URL
    public function getGoogleMapsUrlAttribute(): ?string
    {
        if ($this->latitude && $this->longitude) {
            return "https://www.google.com/maps?q={$this->latitude},{$this->longitude}";
        }
        return null;
    }

    // Calcul de la densité
    public function calculateDensity(): void
    {
        if ($this->population && $this->area && $this->area > 0) {
            $this->density = round($this->population / $this->area, 2);
        }
    }

    // Scope pour les arrondissements
    public function scopeArrondissements($query)
    {
        return $query->where('classification', 'Arrondissement');
    }

    // Scope pour les quartiers
    public function scopeQuartiers($query)
    {
        return $query->where('classification', 'Quartier');
    }

    // Scope pour les secteurs d'une région spécifique
    public function scopeByRegion($query, $regionCode)
    {
        return $query->whereHas('region', function ($q) use ($regionCode) {
            $q->where('code', $regionCode);
        });
    }

    // Événement de modèle - calcul automatique de la densité
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($secteur) {
            $secteur->calculateDensity();
        });
    }

public function villes(): HasMany
{
    return $this->hasMany(Ville::class);
}

}