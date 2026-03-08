<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasPage;

class Region extends Model
{
    use HasFactory, SoftDeletes, HasPage;

    protected $fillable = [
        'name',
        'code',
        'capital',
        'largest_city',
        'classification',
        'population',
        'area',
        'municipalities_count',
        'timezone',
        'flag',
        'description',
        'geography',
        'economy',
        'tourism',
        'latitude',
        'longitude',
        'is_active',
        'province_id'
    ];

    protected $casts = [
        'population' => 'integer',
        'area' => 'decimal:2',
        'municipalities_count' => 'integer',
        'is_active' => 'boolean'
    ];
    

    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class, 'activity_region')
                    ->withTimestamps();
    }

    

    public function scopeActive($query)
{
    return $query->where('is_active', true);
}

    // Relation avec la province
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    // Relation avec les villes (si vous en créez plus tard)
    public function cities(): HasMany
    {
        return $this->hasMany(Ville::class);
    }

    // Accessor pour le nom complet
    public function getFullNameAttribute(): string
    {
        return "{$this->name} ({$this->code})";
    }

    // Accessor pour les coordonnées formatées
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

    // Accessor pour la densité de population
    public function getPopulationDensityAttribute(): ?float
    {
        if ($this->population && $this->area && $this->area > 0) {
            return round($this->population / $this->area, 2);
        }
        return null;
    }

    // Accessor pour la densité formatée
    public function getFormattedPopulationDensityAttribute(): string
    {
        $density = $this->population_density;
        return $density ? number_format($density, 2) . ' hab/km²' : 'Non disponible';
    }

    // Scope pour les régions d'une province spécifique
    public function scopeByProvince($query, $provinceCode)
    {
        return $query->whereHas('province', function ($q) use ($provinceCode) {
            $q->where('code', $provinceCode);
        });
    }

    // Scope pour les régions administratives
    public function scopeAdministrative($query)
    {
        return $query->where('classification', 'like', '%administrative%');
    }

    // Scope pour les régions touristiques
    public function scopeTouristic($query)
    {
        return $query->where('classification', 'like', '%touristique%');
    }

    // Dans app/Models/Region.php, ajoutez:
public function secteurs(): HasMany
{
    return $this->hasMany(Secteur::class);
}

// Accessor pour le nombre de secteurs
public function getSecteursCountAttribute(): int
{
    return $this->secteurs()->count();
}

// Dans app/Models/Region.php, ajoutez:
public function villes(): HasMany
{
    return $this->hasMany(Ville::class);
}

}