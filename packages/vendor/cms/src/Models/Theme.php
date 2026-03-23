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
        'etablissement_id',
        'name',
        'slug',
        'path',
        'preview_image',
        'config',
        'is_active',
        'is_default',
        'version',
        'description',
    ];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the etablissement that owns the theme.
     */
    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class, 'etablissement_id');
    }

    /**
     * Get the full path to the theme directory.
     * CORRIGÉ: Ne plus utiliser cette méthode comme attribut
     */
    public function getFullPathAttribute()
    {
        return $this->getFullPath();
    }
    
    /**
 * Get the full path to the theme directory.
 */
public function getFullPath()
{
    $path = $this->path;
    
    // Si le chemin est déjà absolu
    if (strpos($path, storage_path()) === 0) {
        return rtrim($path, '/');
    }
    
    // Si le chemin commence par 'app/'
    if (str_starts_with($path, 'app/')) {
        return rtrim(storage_path($path), '/');
    }
    
    // Si le chemin commence par '/'
    if (str_starts_with($path, '/')) {
        return rtrim($path, '/');
    }
    
    // Sinon, supposer que c'est relatif à storage/app
    return rtrim(storage_path('app/' . $path), '/');
}

    /**
     * Scope for active themes.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Activate this theme.
     */
    public function activate(): bool
    {
        // Deactivate all other themes for this etablissement
        self::where('etablissement_id', $this->etablissement_id)
            ->where('id', '!=', $this->id)
            ->update(['is_active' => false]);
        
        $this->is_active = true;
        return $this->save();
    }

    /**
     * Deactivate this theme.
     */
    public function deactivate(): bool
    {
        $this->is_active = false;
        return $this->save();
    }

    /**
     * Check if theme is active.
     */
    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }

    /**
     * Get theme configuration.
     */
    public function getConfig($key = null, $default = null)
    {
        $config = $this->config ?? [];
        
        if ($key === null) {
            return $config;
        }
        
        return $config[$key] ?? $default;
    }

    /**
     * Set theme configuration.
     */
    public function setConfig($key, $value): bool
    {
        $config = $this->config ?? [];
        $config[$key] = $value;
        $this->config = $config;
        return $this->save();
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