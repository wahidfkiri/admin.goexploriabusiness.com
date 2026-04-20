<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Plan extends Model
{
    use HasFactory;

    protected $table = 'plans';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'services',
        'price',
        'currency',
        'duration_days',
        'billing_cycle',
        'features',
        'limits',
        'sort_order',
        'is_active',
        'is_popular'
    ];

    protected $casts = [
        'features' => 'array',
        'limits' => 'array',
        'services' => 'array',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_popular' => 'boolean',
        'sort_order' => 'integer',
        'duration_days' => 'integer'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($plan) {
            if (empty($plan->slug)) {
                $plan->slug = Str::slug($plan->name);
            }
        });

        static::updating(function ($plan) {
            if ($plan->isDirty('name')) {
                $plan->slug = Str::slug($plan->name);
            }
        });
    }

    public function abonnements()
    {
        return $this->hasMany(Abonnement::class);
    }

    public function activeAbonnements()
    {
        return $this->hasMany(Abonnement::class)->where('status', 'active');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('price');
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', ' ') . ' ' . $this->currency;
    }

    public function getServicesListAttribute()
    {
        if (is_array($this->services)) {
            return $this->services;
        }
        return json_decode($this->services, true) ?? [];
    }

    public function getFeaturesListAttribute()
    {
        return $this->features ?? [];
    }
}