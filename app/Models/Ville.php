<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasPage;

class Ville extends Model
{
    use HasFactory, SoftDeletes, HasPage;

    protected $fillable = [
        'name',
        'code',
        'classification',
        'status',
        'population',
        'area',
        'households',
        'density',
        'altitude',
        'founding_year',
        'mayor',
        'website',
        'description',
        'history',
        'economy',
        'attractions',
        'transport',
        'education',
        'culture',
        'postal_code_prefix',
        'latitude',
        'longitude',
        'is_active',
        'secteur_id',
        'region_id',
        'province_id',
        'country_id'
    ];

    protected $casts = [
        'population' => 'integer',
        'area' => 'decimal:2',
        'households' => 'integer',
        'density' => 'decimal:2',
        'altitude' => 'integer',
        'is_active' => 'boolean'
    ];


    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class, 'activity_city')
                    ->withTimestamps();
    }

    // Relations
    public function secteur(): BelongsTo
    {
        return $this->belongsTo(Secteur::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        $province = $this->province ? $this->province->code : '';
        return "{$this->name}, {$province}";
    }

    public function getCoordinatesAttribute(): string
    {
        if ($this->latitude && $this->longitude) {
            return "{$this->latitude}, {$this->longitude}";
        }
        return 'Non disponible';
    }

    public function getGoogleMapsUrlAttribute(): ?string
    {
        if ($this->latitude && $this->longitude) {
            return "https://www.google.com/maps?q={$this->latitude},{$this->longitude}";
        }
        return null;
    }

    public function getFormattedPopulationAttribute(): string
    {
        return number_format($this->population ?? 0);
    }

    public function getFormattedAreaAttribute(): string
    {
        return number_format($this->area ?? 0, 2) . ' km²';
    }

    // Méthode pour calculer la densité
    public function calculateDensity(): void
    {
        if ($this->population && $this->area && $this->area > 0) {
            $this->density = round($this->population / $this->area, 2);
        }
    }

    // Scopes
    public function scopeByRegion($query, $regionCode)
    {
        return $query->whereHas('region', function ($q) use ($regionCode) {
            $q->where('code', $regionCode);
        });
    }

    public function scopeByProvince($query, $provinceCode)
    {
        return $query->whereHas('province', function ($q) use ($provinceCode) {
            $q->where('code', $provinceCode);
        });
    }

    public function scopeByCountry($query, $countryCode)
    {
        return $query->whereHas('country', function ($q) use ($countryCode) {
            $q->where('code', $countryCode);
        });
    }

    public function scopeWithSecteur($query)
    {
        return $query->whereNotNull('secteur_id');
    }

    public function scopeWithoutSecteur($query)
    {
        return $query->whereNull('secteur_id');
    }

    public function scopeCapital($query)
    {
        return $query->where('status', 'like', '%capitale%');
    }

    public function scopeMetropolis($query)
    {
        return $query->where('status', 'like', '%métropole%');
    }

    // Événement de modèle
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($ville) {
            $ville->calculateDensity();
        });
    }
    
public function scopeActive($query)
{
    return $query->where('is_active', true);
}
}