<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relation avec les blocs
    public function blocks()
    {
        return $this->hasMany(Block::class);
    }

    // Scope pour sections actives
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope trié par ordre
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }
}