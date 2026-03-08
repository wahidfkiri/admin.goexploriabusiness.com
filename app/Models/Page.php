<?php
// app/Models/Page.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Page extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'html_content',
        'css_content',
        'is_active',
        'pageable_id',
        'pageable_type'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Relation polymorphique
     */
    public function pageable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope pour les pages actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour rechercher par slug
     */
    public function scopeBySlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    /**
     * Récupérer le contenu HTML avec CSS intégré
     */
    public function getFullHtmlAttribute()
    {
        if ($this->css_content) {
            return '<style>' . $this->css_content . '</style>' . $this->html_content;
        }
        
        return $this->html_content;
    }
}