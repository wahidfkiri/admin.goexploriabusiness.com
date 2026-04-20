<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Vendor\Cms\Models\Traits\HasSettings; 

class Etablissement extends Model
{
    use HasFactory, SoftDeletes, HasSettings; // Ajout du trait HasSettings

    protected $fillable = [
        'name',
        'lname',
        'ville',
        'user_id',
        'adresse',
        'zip_code',
        'phone',
        'fax',
        'email_contact',
        'website',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Relation Many-to-Many avec les activités
     */
    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class)
                    ->withTimestamps()
                    ->withPivot('created_at', 'updated_at');
    }
    
    /**
     * Relation Many-to-Many avec les activités actives seulement
     */
    public function activeActivities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class)
                    ->wherePivot('is_active', true)
                    ->withTimestamps();
    }
    
    /**
     * Scope pour les établissements actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    /**
     * Scope pour les établissements inactifs
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }
    
    /**
     * Attacher une activité à l'établissement
     */
    public function attachActivity($activityId, array $pivotData = [])
    {
        $this->activities()->attach($activityId, $pivotData);
    }
    
    /**
     * Détacher une activité de l'établissement
     */
    public function detachActivity($activityId)
    {
        $this->activities()->detach($activityId);
    }
    
    /**
     * Synchroniser les activités (remplace toutes les activités existantes)
     */
    public function syncActivities(array $activityIds)
    {
        $this->activities()->sync($activityIds);
    }
    
    // ==============================================
    // Méthodes supplémentaires pour la gestion CMS
    // ==============================================
    
    /**
     * Récupère tous les paramètres de configuration de l'établissement
     * 
     * @return array
     */
    public function getAllCmsSettings(): array
    {
        return $this->getAllSettings()->toArray();
    }
    
    /**
     * Récupère les paramètres d'un groupe spécifique
     * 
     * @param string $group
     * @return array
     */
    public function getSettingsGroup(string $group): array
    {
        return $this->getSettingsGroup($group);
    }
    
    /**
     * Récupère le thème actif de l'établissement
     * 
     * @return \Vendor\Cms\Models\Theme|null
     */
    public function getActiveTheme()
    {
        return \Vendor\Cms\Models\Theme::where('etablissement_id', $this->id)
            ->where('is_active', true)
            ->first();
    }
    
    /**
     * Récupère toutes les pages de l'établissement
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPages()
    {
        return \Vendor\Cms\Models\Page::where('etablissement_id', $this->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    /**
     * Récupère les pages publiées
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPublishedPages()
    {
        return \Vendor\Cms\Models\Page::where('etablissement_id', $this->id)
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->get();
    }
    
    /**
     * Récupère les thèmes disponibles
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getThemes()
    {
        return \Vendor\Cms\Models\Theme::where('etablissement_id', $this->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    /**
     * Vérifie si l'établissement a des pages
     * 
     * @return bool
     */
    public function hasPages(): bool
    {
        return \Vendor\Cms\Models\Page::where('etablissement_id', $this->id)->exists();
    }
    
    /**
     * Vérifie si l'établissement a un thème actif
     * 
     * @return bool
     */
    public function hasActiveTheme(): bool
    {
        return \Vendor\Cms\Models\Theme::where('etablissement_id', $this->id)
            ->where('is_active', true)
            ->exists();
    }
    
    /**
     * Compte le nombre de pages
     * 
     * @return int
     */
    public function countPages(): int
    {
        return \Vendor\Cms\Models\Page::where('etablissement_id', $this->id)->count();
    }
    
    /**
     * Compte le nombre de pages publiées
     * 
     * @return int
     */
    public function countPublishedPages(): int
    {
        return \Vendor\Cms\Models\Page::where('etablissement_id', $this->id)
            ->where('status', 'published')
            ->count();
    }
    
    /**
     * Compte le nombre de thèmes
     * 
     * @return int
     */
    public function countThemes(): int
    {
        return \Vendor\Cms\Models\Theme::where('etablissement_id', $this->id)->count();
    }
    
    /**
     * Récupère l'URL du site avec les paramètres
     * 
     * @param string $path
     * @return string
     */
    public function getSiteUrl(string $path = ''): string
    {
        $domain = $this->getSetting('domain', $this->website ?? '');
        $baseUrl = $domain ?: url('/');
        
        return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
    }
    
    /**
     * Récupère les informations de contact formatées
     * 
     * @return array
     */
    public function getContactInfo(): array
    {
        return [
            'phone' => $this->phone,
            'fax' => $this->fax,
            'email' => $this->email_contact,
            'website' => $this->website,
            'address' => $this->adresse,
            'city' => $this->ville,
            'zip_code' => $this->zip_code,
        ];
    }
    
    /**
     * Récupère les réseaux sociaux configurés
     * 
     * @return array
     */
    public function getSocialNetworks(): array
    {
        return [
            'facebook' => $this->getSetting('facebook_url'),
            'twitter' => $this->getSetting('twitter_url'),
            'instagram' => $this->getSetting('instagram_url'),
            'linkedin' => $this->getSetting('linkedin_url'),
            'youtube' => $this->getSetting('youtube_url'),
        ];
    }
    
    /**
     * Récupère les informations SEO par défaut
     * 
     * @return array
     */
    public function getSeoDefaults(): array
    {
        return [
            'title' => $this->getSetting('seo_title', $this->name),
            'description' => $this->getSetting('seo_description', $this->lname ?? ''),
            'keywords' => $this->getSetting('seo_keywords', ''),
        ];
    }

     /**
     * Relation avec les thèmes (many-to-many)
     */
    public function themes()
    {
        return $this->belongsToMany(\Vendor\Cms\Models\Theme::class, 'cms_etablissement_theme', 'etablissement_id', 'theme_id')
            ->withPivot('is_active', 'config')
            ->withTimestamps();
    }

    /**
     * Récupère le thème actif de l'établissement
     */
    public function activeTheme()
    {
        return $this->belongsToMany(\Vendor\Cms\Models\Theme::class, 'cms_etablissement_theme', 'etablissement_id', 'theme_id')
            ->wherePivot('is_active', true)
            ->first();
    }

    /**
     * Active un thème pour cet établissement
     */
    public function activateTheme($themeId): bool
    {
        // Désactiver tous les thèmes
        $this->themes()->newPivotStatement()
            ->where('etablissement_id', $this->id)
            ->update(['is_active' => false]);
        
        // Activer le thème sélectionné
        $this->themes()->syncWithoutDetaching([
            $themeId => ['is_active' => true]
        ]);
        
        return true;
    }

    /**
     * Récupère la configuration du thème actif
     */
    public function getActiveThemeConfig($key = null, $default = null)
    {
        $activeTheme = $this->activeTheme();
        
        if (!$activeTheme) {
            return $default;
        }
        
        $config = $activeTheme->pivot->config ?? [];
        
        if ($key === null) {
            return $config;
        }
        
        return $config[$key] ?? $default;
    }


public function abonnements()
{
    return $this->hasMany(Abonnement::class);
}

public function currentAbonnement()
{
    return $this->belongsTo(Abonnement::class, 'current_abonnement_id');
}

public function paiements()
{
    return $this->hasMany(Paiement::class);
}

public function hasActiveSubscription()
{
    return $this->currentAbonnement && $this->currentAbonnement->isActive();
}

public function getSubscriptionStatusLabelAttribute()
{
    $labels = [
        'active' => 'Actif',
        'expired' => 'Expiré',
        'none' => 'Aucun'
    ];
    return $labels[$this->subscription_status] ?? 'Inconnu';
}
}