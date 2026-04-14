<?php
// app/Models/Plugin.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Plugin extends Model
{
    use HasFactory;

    protected $table = 'plugins';

    protected $fillable = [
        'name', 'slug', 'description', 'version', 'author', 'author_website',
        'icon', 'price_type', 'price', 'type', 'status', 'category_id',
        'rating', 'rating_count', 'downloads', 'settings', 'compatibility',
        'changelog', 'documentation_url', 'demo_url', 'is_core', 'can_be_disabled',
        'can_be_uninstalled', 'installed_at', 'last_checked_at'
    ];

    protected $casts = [
        'settings' => 'array',
        'compatibility' => 'array',
        'is_core' => 'boolean',
        'can_be_disabled' => 'boolean',
        'can_be_uninstalled' => 'boolean',
        'price' => 'decimal:2',
        'rating' => 'float',
        'installed_at' => 'datetime',
        'last_checked_at' => 'datetime',
    ];

    /**
     * Get the category that owns the plugin.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(PluginCategory::class, 'category_id');
    }

    /**
     * Scope for active plugins.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for inactive plugins.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope for pending plugins.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for free plugins.
     */
    public function scopeFree($query)
    {
        return $query->where('price_type', 'free');
    }

    /**
     * Scope for paid plugins.
     */
    public function scopePaid($query)
    {
        return $query->where('price_type', 'paid');
    }

    /**
     * Scope by category.
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Check if plugin has update available.
     */
    public function hasUpdateAvailable(): bool
    {
        // This would check against a remote repository or package manager
        // For now, return false
        return false;
    }

    /**
     * Get the star rating HTML.
     */
    public function getStarRatingAttribute(): string
    {
        $fullStars = floor($this->rating);
        $halfStar = ($this->rating - $fullStars) >= 0.5;
        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);

        $stars = '';
        for ($i = 0; $i < $fullStars; $i++) {
            $stars .= '<i class="fas fa-star star-filled"></i>';
        }
        if ($halfStar) {
            $stars .= '<i class="fas fa-star-half-alt star-filled"></i>';
        }
        for ($i = 0; $i < $emptyStars; $i++) {
            $stars .= '<i class="fas fa-star star-empty"></i>';
        }

        return $stars;
    }

    /**
     * Get status badge HTML.
     */
    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'active' => '<span class="status-badge status-active"><i class="fas fa-circle"></i> Actif</span>',
            'inactive' => '<span class="status-badge status-inactive"><i class="fas fa-circle"></i> Inactif</span>',
            'pending' => '<span class="status-badge status-pending"><i class="fas fa-circle"></i> En attente</span>',
        ];

        return $badges[$this->status] ?? $badges['inactive'];
    }

    /**
     * Get type badge HTML.
     */
    public function getTypeBadgeAttribute(): string
    {
        $badges = [
            'core' => '<span class="badge-core">Cœur</span>',
            'official' => '<span class="badge-official">Officiel</span>',
            'third-party' => '',
            'custom' => '<span class="badge-core">Personnalisé</span>',
        ];

        return $badges[$this->type] ?? '';
    }

    /**
     * Get price badge HTML.
     */
    public function getPriceBadgeAttribute(): string
    {
        if ($this->price_type === 'free') {
            return '';
        }
        return '<span class="badge-paid">Payant</span>';
    }
}