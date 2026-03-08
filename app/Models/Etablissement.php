<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // Changé
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Etablissement extends Model
{
    use HasFactory, SoftDeletes;

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
                    ->withPivot('created_at', 'updated_at'); // Inclure les timestamps du pivot
    }
    
    /**
     * Relation Many-to-Many avec les activités actives seulement
     */
    public function activeActivities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class)
                    ->wherePivot('is_active', true) // Si vous avez un champ is_active dans le pivot
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
}