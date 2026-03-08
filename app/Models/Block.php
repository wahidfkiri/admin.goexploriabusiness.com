<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Block extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'thumbnail',
        'icon',
        'html_content',
        'css_content',
        'js_content',
        'section_id',
        'categorie_id',
        'category',
        'website_type',
        'tags',
        'is_responsive',
        'is_free',
        'width',
        'height',
        'usage_count',
        'views_count',
        'rating',
        'order',
        'is_active'
    ];

    protected $casts = [
        'tags' => 'array',
        'is_responsive' => 'boolean',
        'is_free' => 'boolean',
        'is_active' => 'boolean'
    ];

    // Relation avec la section
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    // Scopes utiles
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function categorie()
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }

    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }

    public function scopeResponsive($query)
    {
        return $query->where('is_responsive', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByWebsiteType($query, $type)
    {
        return $query->where('website_type', $type);
    }

    public function scopePopular($query)
    {
        return $query->orderByDesc('usage_count');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }

    // Méthode pour incrémenter l'utilisation
    public function incrementUsage()
    {
        $this->increment('usage_count');
        $this->save();
    }
}