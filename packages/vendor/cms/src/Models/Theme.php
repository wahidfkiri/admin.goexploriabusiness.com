<?php

namespace Vendor\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Etablissement;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Theme extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'cms';
    protected $table = 'cms_themes';

    protected $fillable = [
        'name',
        'slug',
        'path',
        'preview_image',
        'config',
        'is_default',
        'version',
        'description',
    ];

    protected $casts = [
        'config' => 'array',
        'is_default' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relation avec les établissements (many-to-many)
     */
    public function etablissements()
    {
        return $this->belongsToMany(Etablissement::class, 'cms_etablissement_theme', 'theme_id', 'etablissement_id')
            ->withPivot('is_active', 'config')
            ->withTimestamps();
    }

    /**
     * Récupère les établissements où ce thème est actif
     */
    public function activeEtablissements()
    {
        return $this->etablissements()->wherePivot('is_active', true);
    }

    /**
     * Vérifie si le thème est actif pour un établissement donné
     */
    public function isActiveForEtablissement($etablissementId): bool
    {
        return $this->etablissements()
            ->where('etablissement_id', $etablissementId)
            ->wherePivot('is_active', true)
            ->exists();
    }

    /**
     * Active le thème pour un établissement
     */
    public function activateForEtablissement($etablissementId): bool
    {
        // Désactiver tous les autres thèmes pour cet établissement
        $this->etablissements()->newPivotStatement()
            ->where('etablissement_id', $etablissementId)
            ->update(['is_active' => false]);
        
        // Activer ce thème
        $this->etablissements()->syncWithoutDetaching([
            $etablissementId => ['is_active' => true]
        ]);
        
        return true;
    }

    /**
     * Désactive le thème pour un établissement
     */
    public function deactivateForEtablissement($etablissementId): bool
    {
        $this->etablissements()->updateExistingPivot($etablissementId, [
            'is_active' => false
        ]);
        
        return true;
    }

    /**
     * Récupère la configuration du thème pour un établissement
     */
    public function getConfigForEtablissement($etablissementId, $key = null, $default = null)
    {
        $pivot = $this->etablissements()
            ->where('etablissement_id', $etablissementId)
            ->first();
        
        if (!$pivot || !$pivot->pivot->config) {
            return $default;
        }
        
        $config = $pivot->pivot->config;
        
        if ($key === null) {
            return $config;
        }
        
        return $config[$key] ?? $default;
    }

    /**
     * Définit la configuration du thème pour un établissement
     */
    public function setConfigForEtablissement($etablissementId, $key, $value): bool
    {
        $currentConfig = $this->getConfigForEtablissement($etablissementId) ?: [];
        $currentConfig[$key] = $value;
        
        $this->etablissements()->updateExistingPivot($etablissementId, [
            'config' => $currentConfig
        ]);
        
        return true;
    }

   /**
 * Get the full path to the theme directory for a specific etablissement.
 *
 * @param int|null $etablissementId
 * @return string
 */
public function getFullPath($etablissementId = null)
{
    $etablissementId = $etablissementId ?: $this->etablissement_id;
    
    // Nouveau chemin: storage/app/public/cms/themes/{etablissementId}/{slug}/
    return storage_path("app/public/cms/themes/{$etablissementId}/{$this->slug}");
}

    /**
     * Get the URL for theme assets.
     */
    public function getAssetUrl($path = ''): string
    {
        return url("/storage/cms/themes/{$this->slug}/assets/" . ltrim($path, '/'));
    }

    /**
     * Scope for themes that are default.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Check if theme has preview image.
     */
    public function hasPreviewImage(): bool
    {
        return !empty($this->preview_image) && Storage::disk('public')->exists($this->preview_image);
    }

    /**
     * Get preview image URL.
     */
    public function getPreviewImageUrl(): ?string
    {
        if ($this->hasPreviewImage()) {
            return Storage::disk('public')->url($this->preview_image);
        }
        
        return null;
    }

    /**
     * Check if theme directory exists.
     */
    public function exists(): bool
    {
        return file_exists($this->getFullPath());
    }

    /**
     * Check if theme has layout file.
     */
    public function hasLayout(): bool
    {
        return file_exists($this->getFullPath() . '/layout.blade.php');
    }
}