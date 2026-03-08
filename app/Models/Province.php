<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasPage;

class Province extends Model
{
    use HasFactory, SoftDeletes, HasPage;

    protected $fillable = [
        'name',
        'code',
        'capital',
        'largest_city',
        'official_language',
        'area_rank',
        'population',
        'area',
        'timezone',
        'flag',
        'description',
        'latitude',    // Ajouté
        'longitude',   // Ajouté
        'is_active',
        'country_id'
    ];

    protected $casts = [
        'population' => 'integer',
        'area' => 'decimal:2',
        'is_active' => 'boolean',
    ];


    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class, 'activity_province')
                    ->withTimestamps();
    }

    

   public function scopeActive($query)
{
    return $query->where('is_active', true);
}

    // Relation avec le pays
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    // Relation avec les villes
    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
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

    // Scope pour les provinces d'un pays spécifique
    public function scopeByCountry($query, $countryCode)
    {
        return $query->whereHas('country', function ($q) use ($countryCode) {
            $q->where('code', $countryCode);
        });
    }

    // Scope pour les provinces avec coordonnées
    public function scopeWithCoordinates($query)
    {
        return $query->whereNotNull('latitude')->whereNotNull('longitude');
    }

    // Méthode pour formatter la population
    public function getFormattedPopulationAttribute(): string
    {
        return number_format($this->population ?? 0);
    }

    // Méthode pour formatter la superficie
    public function getFormattedAreaAttribute(): string
    {
        return number_format($this->area ?? 0) . ' km²';
    }

    // Méthode pour calculer la densité de population
    public function getPopulationDensityAttribute(): ?float
    {
        if ($this->population && $this->area && $this->area > 0) {
            return round($this->population / $this->area, 2);
        }
        return null;
    }

    // Méthode pour formatter la densité de population
    public function getFormattedPopulationDensityAttribute(): string
    {
        $density = $this->population_density;
        return $density ? number_format($density, 2) . ' hab/km²' : 'Non disponible';
    }

    // Dans app/Models/Province.php, ajoutez:
public function regions(): HasMany
{
    return $this->hasMany(Region::class);
}

// Accessor pour le nombre de régions
public function getRegionsCountAttribute(): int
{
    return $this->regions()->count();
}

// Dans app/Models/Province.php, ajoutez:
public function villes(): HasMany
{
    return $this->hasMany(Ville::class);
}

}