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
        'services',         // Rich HTML from WYSIWYG editor
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
        'features'     => 'array',
        'limits'       => 'array',
        'price'        => 'decimal:2',
        'is_active'    => 'boolean',
        'is_popular'   => 'boolean',
        'sort_order'   => 'integer',
        'duration_days'=> 'integer'
    ];

    // =====================
    // BOOT — Auto-slug
    // =====================
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

    // =====================
    // RELATIONS
    // =====================

    public function abonnements()
    {
        return $this->hasMany(Abonnement::class);
    }

    public function activeAbonnements()
    {
        return $this->hasMany(Abonnement::class)->where('status', 'active');
    }

    /**
     * Plugins attached to this plan (many-to-many)
     */
    public function plugins()
    {
        return $this->belongsToMany(Plugin::class, 'plan_plugin')
                    ->withPivot('is_included')
                    ->withTimestamps();
    }

    /**
     * All media (images + videos) for this plan
     */
    public function media()
    {
        return $this->hasMany(PlanMedia::class)->orderBy('sort_order');
    }

    /**
     * Only images
     */
    public function images()
    {
        return $this->hasMany(PlanMedia::class)->where('type', 'image')->orderBy('sort_order');
    }

    /**
     * Only videos
     */
    public function videos()
    {
        return $this->hasMany(PlanMedia::class)->where('type', 'video')->orderBy('sort_order');
    }

    /**
     * Primary media (featured image or hero video)
     */
    public function primaryMedia()
    {
        return $this->hasOne(PlanMedia::class)->where('is_primary', true);
    }

    // =====================
    // SCOPES
    // =====================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('price');
    }

    // =====================
    // ACCESSORS
    // =====================

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

    /**
     * Get IDs of attached plugins (for pre-checking form checkboxes)
     */
    public function getPluginIdsAttribute(): array
    {
        return $this->plugins()->pluck('plugins.id')->toArray();
    }
}