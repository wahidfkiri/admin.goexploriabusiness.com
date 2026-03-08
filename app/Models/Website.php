<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Website extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'categorie_id',
        'customer_id',
        'name',
        'url',
        'description',
        'status',
        'template_type',
        'color_scheme',
        'features',
        'price',
        'screenshot_path'
    ];

    protected $casts = [
        'features' => 'array',
        'price' => 'decimal:2'
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function templates(): HasMany
    {
        return $this->hasMany(Template::class);
    }

    /**
     * Relation avec la catégorie
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }

    /**
     * Relation avec le client
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Scope pour les sites actifs
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope pour les sites par catégorie
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('categorie_id', $categoryId);
    }

    /**
     * Get URL complète
     */
    public function getFullUrlAttribute()
    {
        if (strpos($this->url, 'http') !== 0) {
            return 'https://' . $this->url;
        }
        return $this->url;
    }
}