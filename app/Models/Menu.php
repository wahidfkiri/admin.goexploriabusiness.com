<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'type',
        'parent_id',
        'reference_id',
        'menu_type',
        'order',
        'route',
        'url',
        'icon',
        'is_active',
         'has_page',
        'page_id',
        'page_content',
        'page_styles',
        'page_meta',
        'page_status',
        'page_slug'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
         'has_page' => 'boolean',
        'page_meta' => 'array',
        'page_status' => 'string'
    ];

    // Relation pour le parent
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    // Relation pour les enfants
    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order');
    }

    // Relation pour les enfants actifs seulement
    public function activeChildren(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')
                    ->where('is_active', true)
                    ->orderBy('order');
    }

    // Relation avec la catégorie (si type = 'category')
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'reference_id');
    }

    // Relation avec l'activité (si type = 'activity')
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class, 'reference_id');
    }

    // Scope pour les menus racines
    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    // Scope pour les menus actifs
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Méthode pour obtenir l'URL finale
    public function getFinalUrlAttribute(): ?string
    {
        if ($this->url) {
            return $this->url;
        }

        if ($this->route) {
            return route($this->route);
        }

        if ($this->type === 'category' && $this->category) {
            return route('categories.show', $this->category->slug);
        }

        if ($this->type === 'activity' && $this->activity) {
            return route('activities.show', $this->activity->slug);
        }

        return '#';
    }

    // Méthode pour obtenir le titre final
    public function getFinalTitleAttribute(): string
    {
        if ($this->type === 'category' && $this->category) {
            return $this->category->name;
        }

        if ($this->type === 'activity' && $this->activity) {
            return $this->activity->name;
        }

        return $this->title;
    }

    // Méthode pour vérifier si le menu a des enfants
    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    // Méthode pour vérifier si c'est un sous-menu
    public function isChild(): bool
    {
        return !is_null($this->parent_id);
    }

    // Méthode pour obtenir le niveau du menu
    public function getLevelAttribute(): int
    {
        $level = 0;
        $parent = $this->parent;
        
        while ($parent) {
            $level++;
            $parent = $parent->parent;
        }
        
        return $level;
    }

    // Méthode pour vérifier si le niveau est valide (max 2 sous-menus)
    public function canHaveChildren(): bool
    {
        return $this->level < 2;
    }
// Relation avec les révisions
    public function pageRevisions(): HasMany
    {
        return $this->hasMany(PageRevision::class);
    }
    
    // Relation avec l'utilisateur qui a créé la page
    public function pageCreator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'page_created_by');
    }
    
    // Méthode pour obtenir l'URL de la page
    public function getPageUrlAttribute(): string
    {
        if ($this->has_page && $this->page_slug) {
            return url('/pages/' . $this->page_slug);
        }
        
        return $this->final_url;
    }
    
    // Méthode pour obtenir le titre de la page
    public function getPageTitleAttribute(): string
    {
        if ($this->page_meta && isset($this->page_meta['title'])) {
            return $this->page_meta['title'];
        }
        
        return $this->final_title;
    }
    
    // Méthode pour obtenir la description de la page
    public function getPageDescriptionAttribute(): ?string
    {
        return $this->page_meta['description'] ?? null;
    }
    
    // Méthode pour vérifier si la page est publiée
    public function isPagePublished(): bool
    {
        return $this->has_page && $this->page_status === 'published';
    }
    
    // Méthode pour créer une révision
    public function createRevision(string $description = null): PageRevision
    {
        return $this->pageRevisions()->create([
            'content' => $this->page_content,
            'styles' => $this->page_styles,
            'meta' => $this->page_meta,
            'version' => 'v' . ($this->pageRevisions()->count() + 1),
            'user_id' => auth()->id(),
            'change_description' => $description
        ]);
    }
    
    // Méthode pour restaurer une révision
    public function restoreRevision(PageRevision $revision): bool
    {
        $this->update([
            'page_content' => $revision->content,
            'page_styles' => $revision->styles,
            'page_meta' => $revision->meta
        ]);
        
        return true;
    }
}