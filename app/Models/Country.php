<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasPage;

class Country extends Model
{
    use HasFactory, SoftDeletes, HasPage;

    protected $fillable = [
        'name',
        'code',
        'image',
        'iso2',
        'phone_code',
        'capital',
        'currency',
        'currency_symbol',
        'flag',
        'latitude',
        'longitude',
        'description',
        'population',
        'area',
        'official_language',
        'timezones',
        'region',
        'is_active',
        'continent_id'
    ];

    protected $casts = [
        'timezones' => 'array',
        'population' => 'integer',
        'area' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // Relation avec le continent
    public function continent(): BelongsTo
    {
        return $this->belongsTo(Continent::class);
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_country')
                    ->withTimestamps();
    }

    // Relation avec les villes (si vous en avez)
    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    // Accessor pour le nom complet avec code
    public function getFullNameAttribute(): string
    {
        return "{$this->name} ({$this->code})";
    }

    public function scopeActive($query)
{
    return $query->where('is_active', true);
}

    // Scope pour les pays par continent
    public function scopeByContinent($query, $continentCode)
    {
        return $query->whereHas('continent', function ($q) use ($continentCode) {
            $q->where('code', $continentCode);
        });
    }

    // Scope pour les pays d'une région spécifique
    public function scopeByRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    public function provinces()
    {
        return $this->hasMany(Province::class);
    }

    
// Dans app/Models/Country.php, ajoutez:
public function villes(): HasMany
{
    return $this->hasMany(Ville::class);
}
}