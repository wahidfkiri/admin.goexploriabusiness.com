<?php

namespace Vendor\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Etablissement;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'cms';
    protected $table = 'cms_pages';

    protected $fillable = [
        'etablissement_id',
        'user_id',
        'title',
        'slug',
        'content',
        'meta',
        'status',
        'visibility',
        'password',
        'is_home', // Ajout de is_home
        'settings',
        'published_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'settings' => 'array',
        'is_home' => 'boolean',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
            
            // Vérifier si c'est la première page ou si is_home est demandé
            if ($page->is_home) {
                // Désactiver is_home sur toutes les autres pages du même établissement
                static::where('etablissement_id', $page->etablissement_id)
                    ->where('id', '!=', $page->id)
                    ->update(['is_home' => false]);
            }
        });
        
        static::updating(function ($page) {
            if ($page->is_home) {
                // Désactiver is_home sur toutes les autres pages du même établissement
                static::where('etablissement_id', $page->etablissement_id)
                    ->where('id', '!=', $page->id)
                    ->update(['is_home' => false]);
            }
        });
    }

    /**
     * Get the etablissement that owns the page.
     */
    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class, 'etablissement_id');
    }

    /**
     * Get the user who created/updated the page.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope for published pages.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope for home page.
     */
    public function scopeHome($query)
    {
        // Vérifier si la colonne is_home existe
        if ($this->hasColumn('is_home')) {
            return $query->where('is_home', true);
        }
        
        // Fallback: chercher par slug 'home' ou première page
        return $query->where('slug', 'home')
            ->orWhere('title', 'Accueil');
    }

    /**
     * Scope for drafts.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope for archived pages.
     */
    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    /**
     * Scope for public pages.
     */
    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }

    /**
     * Check if page is published.
     */
    public function isPublished(): bool
    {
        return $this->status === 'published' && 
               ($this->published_at === null || $this->published_at <= now());
    }

    /**
     * Check if page is home page.
     */
    public function isHomePage(): bool
    {
        // Vérifier si la colonne is_home existe et est true
        if ($this->hasColumn('is_home') && $this->is_home) {
            return true;
        }
        
        // Fallback: vérifier par slug
        return $this->slug === 'home';
    }

    /**
     * Publish the page.
     */
    public function publish()
    {
        $this->status = 'published';
        $this->published_at = $this->published_at ?? now();
        return $this->save();
    }

    /**
     * Set as home page.
     */
    public function setAsHome(): bool
    {
        // Vérifier si la colonne is_home existe
        if ($this->hasColumn('is_home')) {
            // Désactiver is_home sur toutes les autres pages
            static::where('etablissement_id', $this->etablissement_id)
                ->update(['is_home' => false]);
            
            $this->is_home = true;
            return $this->save();
        }
        
        // Fallback: définir le slug comme 'home'
        $this->slug = 'home';
        return $this->save();
    }

    /**
     * Check if column exists in the table.
     */
    protected function hasColumn(string $column): bool
    {
        return \Illuminate\Support\Facades\Schema::connection('cms')
            ->hasColumn($this->getTable(), $column);
    }

    /**
     * Unpublish the page.
     */
    public function unpublish()
    {
        $this->status = 'draft';
        return $this->save();
    }

    /**
     * Get meta data.
     */
    public function getMeta($key = null, $default = null)
    {
        if ($key === null) {
            return $this->meta ?? [];
        }
        
        return $this->meta[$key] ?? $default;
    }

    /**
     * Get SEO title.
     */
    public function getSeoTitleAttribute()
    {
        return $this->getMeta('seo_title', $this->title);
    }

    /**
     * Get SEO description.
     */
    public function getSeoDescriptionAttribute()
    {
        return $this->getMeta('seo_description');
    }
}