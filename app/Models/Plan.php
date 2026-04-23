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
        // Informations de base
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
        'is_popular',
        
        // Section Vision
        'vision_text',
        'vision_quote',
        'vision_quote_author',
        
        // Section Investissement Marketing
        'marketing_budget',
        'marketing_features',
        
        // Section Marchés
        'markets',
        'market_languages',
        
        // Section Outils Marketing
        'marketing_tools',
        
        // Section Espaces
        'space_type',
        'space_features',
        
        // Section Résultats Concrets
        'concrete_results',
    ];

    protected $casts = [
        // Existants
        'features'     => 'array',
        'limits'       => 'array',
        'price'        => 'decimal:2',
        'is_active'    => 'boolean',
        'is_popular'   => 'boolean',
        'sort_order'   => 'integer',
        'duration_days'=> 'integer',
        
        // Nouveaux
        'markets'           => 'array',
        'market_languages'  => 'array',
        'marketing_tools'   => 'array',
        'marketing_features'=> 'array',
        'space_features'    => 'array',
        'concrete_results'  => 'array',
        'marketing_budget'  => 'decimal:2',
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

    /**
     * Destinations associated with this plan
     */
    public function destinations()
    {
        return $this->hasMany(PlanDestination::class)->orderBy('sort_order');
    }

    /**
     * Active destinations for this plan
     */
    public function activeDestinations()
    {
        return $this->hasMany(PlanDestination::class)->where('is_active', true)->orderBy('sort_order');
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

    public function scopeBySpaceType($query, $spaceType)
    {
        return $query->where('space_type', $spaceType);
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

    /**
     * Get formatted marketing budget
     */
    public function getFormattedMarketingBudgetAttribute(): string
    {
        if (!$this->marketing_budget) return '+250 000 $';
        return '+ ' . number_format($this->marketing_budget, 0, ',', ' ') . ' $';
    }

    /**
     * Get marketing features as array
     */
    public function getMarketingFeaturesListAttribute(): array
    {
        if (is_array($this->marketing_features)) {
            return $this->marketing_features;
        }
        if (is_string($this->marketing_features)) {
            return explode(',', $this->marketing_features);
        }
        return ['SEO & Ads', 'Production Média', 'Campagnes Internationales'];
    }

    /**
     * Get markets as array with defaults
     */
    public function getMarketsListAttribute(): array
    {
        if (is_array($this->markets) && !empty($this->markets)) {
            return $this->markets;
        }
        return [
            ['name' => 'Canada', 'population' => '~40M', 'icon' => 'fa-globe-americas'],
            ['name' => 'États-Unis', 'population' => '~335M', 'icon' => 'fa-flag-usa'],
            ['name' => 'Europe', 'population' => '~450M', 'icon' => 'fa-euro-sign'],
            ['name' => 'Monde', 'population' => '~8Md', 'icon' => 'fa-chart-line'],
        ];
    }

    /**
     * Get market languages
     */
    public function getMarketLanguagesListAttribute(): array
    {
        if (is_array($this->market_languages) && !empty($this->market_languages)) {
            return $this->market_languages;
        }
        return ['Jusqu\'à 25 langues', 'Marchés émergents', 'Expansion internationale', 'ROI optimisé'];
    }

    /**
     * Get marketing tools
     */
    public function getMarketingToolsListAttribute(): array
    {
        if (is_array($this->marketing_tools) && !empty($this->marketing_tools)) {
            return $this->marketing_tools;
        }
        return [
            [
                'name' => 'Marketing digital intégré',
                'icon' => 'fa-bullhorn',
                'features' => ['SEO avancé & international', 'Publicité Google & Meta Ads', 'Email marketing automatisé', 'CRM & gestion des leads']
            ],
            [
                'name' => 'Intelligence & données',
                'icon' => 'fa-database',
                'features' => ['Tableaux de bord analytiques', 'Suivi performances marketing', 'Analyse des tendances marchés']
            ],
            [
                'name' => 'Automatisation & IA',
                'icon' => 'fa-robot',
                'features' => ['Création de contenu assistée par IA', 'Call-to-action optimisés', 'Segmentation client intelligente']
            ]
        ];
    }

    /**
     * Get space type label
     */
    public function getSpaceTypeLabelAttribute(): string
    {
        $labels = [
            'entreprise' => 'Espace Entreprise',
            'destination' => 'Espace Destination',
            'partenaire' => 'Espace Partenaires',
            'perso' => 'Espace Perso',
        ];
        return $labels[$this->space_type] ?? 'Espace Entreprise';
    }

    /**
     * Get space type icon
     */
    public function getSpaceTypeIconAttribute(): string
    {
        $icons = [
            'entreprise' => 'fa-building',
            'destination' => 'fa-umbrella-beach',
            'partenaire' => 'fa-handshake',
            'perso' => 'fa-user',
        ];
        return $icons[$this->space_type] ?? 'fa-building';
    }

    /**
     * Get space type color
     */
    public function getSpaceTypeColorAttribute(): string
    {
        $colors = [
            'entreprise' => 'linear-gradient(135deg, #4f46e5, #7c3aed)',
            'destination' => 'linear-gradient(135deg, #ec4899, #f43f5e)',
            'partenaire' => 'linear-gradient(135deg, #f59e0b, #ef4444)',
            'perso' => 'linear-gradient(135deg, #10b981, #06b6d4)',
        ];
        return $colors[$this->space_type] ?? 'linear-gradient(135deg, #4f46e5, #7c3aed)';
    }

    /**
     * Get space features as array
     */
    public function getSpaceFeaturesListAttribute(): array
    {
        $defaultFeatures = [
            'entreprise' => ['Site web professionnel', 'SEO international', 'Vidéo sur carte', 'CRM & leads'],
            'destination' => ['Fiche destination premium', 'Vidéo promotionnelle', 'Réservation intégrée', 'Multilingue (25 langues)'],
            'partenaire' => ['Programme d\'affiliation', 'Commission jusqu\'à 20%', 'Outils de promotion', 'Support dédié'],
            'perso' => ['Site vitrine personnel', 'Portfolio créateur', 'Réseaux sociaux intégrés', 'Support basique'],
        ];
        
        if (is_array($this->space_features) && !empty($this->space_features)) {
            return $this->space_features;
        }
        return $defaultFeatures[$this->space_type] ?? $defaultFeatures['entreprise'];
    }

    /**
     * Get concrete results
     */
    public function getConcreteResultsListAttribute(): array
    {
        if (is_array($this->concrete_results) && !empty($this->concrete_results)) {
            return $this->concrete_results;
        }
        return [
            ['value' => '+237%', 'label' => 'de visibilité'],
            ['value' => '4.9★', 'label' => 'satisfaction'],
            ['value' => '98%', 'label' => 'rétention'],
            ['value' => '24/7', 'label' => 'support'],
        ];
    }

    /**
     * Get vision quote with default
     */
    public function getVisionQuoteTextAttribute(): string
    {
        return $this->vision_quote ?? '"Une solution conçue pour propulser les entreprises vers une croissance rapide et durable à l\'échelle mondiale."';
    }

    /**
     * Get vision quote author
     */
    public function getVisionQuoteAuthorAttribute(): string
    {
        return $this->vision_quote_author ?? 'GO EXPLORIA BUSINESS';
    }

    /**
     * Get vision text with default
     */
    public function getVisionTextAttribute(): string
    {
        return $this->attributes['vision_text'] ?? 'Une plateforme tout-en-un dédiée à la transformation digitale, au développement commercial et touristique et à la visibilité internationale des entreprises, combinant marketing, technologie et accès aux marchés globaux.';
    }

    // =====================
    // HELPER METHODS FOR VIEWS
    // =====================

    /**
     * Get price for a specific space type (if price differs by space)
     */
    public function getPriceForSpaceType(string $spaceType): string
    {
        $prices = [
            'entreprise' => $this->price,
            'destination' => $this->price * 1.3,
            'partenaire' => 0,
            'perso' => $this->price * 0.3,
        ];
        
        $amount = $prices[$spaceType] ?? $this->price;
        
        if ($amount == 0) {
            return 'Gratuit';
        }
        
        return number_format($amount, 0, ',', ' ') . ' ' . $this->currency;
    }

    /**
     * Get billing cycle label for a specific space type
     */
    public function getBillingCycleForSpaceType(string $spaceType): string
    {
        if ($spaceType === 'partenaire') {
            return 'commission sur ventes';
        }
        return '/' . ($this->billing_cycle === 'yearly' ? 'an' : 'mois');
    }
}