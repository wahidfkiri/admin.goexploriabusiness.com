<?php
// app/Models/PluginCategory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PluginCategory extends Model
{
    use HasFactory;

    protected $table = 'plugin_categories';

    protected $fillable = [
        'name', 'slug', 'icon', 'description', 'order'
    ];

    /**
     * Get the plugins for the category.
     */
    public function plugins(): HasMany
    {
        return $this->hasMany(Plugin::class, 'category_id');
    }
}