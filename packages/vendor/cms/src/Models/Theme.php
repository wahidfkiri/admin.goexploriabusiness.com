<?php

namespace Vendor\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Etablissement;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\CDNService;

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
        'storage_type', 
    ];

    protected $casts = [
        'config' => 'array',
        'is_default' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $appends = ['preview_url', 'storage_display'];

    protected $cdnService;

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();
        
        // Set default storage type on creating
        static::creating(function ($theme) {
            if (!$theme->storage_type) {
                $theme->storage_type = env('CDN_ENABLED', false) ? 'cdn' : 'local';
            }
        });
    }

    /**
     * Get CDN service instance.
     */
    protected function getCdnService()
{
    if (!$this->cdnService) {
        $this->cdnService = app(\App\Services\ThemeCDNService::class);
    }
    return $this->cdnService;
}


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
        
        if ($this->isCdnStorage()) {
            // For CDN, return the virtual path
            return "cms/themes/{$etablissementId}/{$this->slug}";
        }
        
        // Local storage path
        return storage_path("app/public/cms/themes/{$etablissementId}/{$this->slug}");
    }

    /**
     * Get the URL for theme assets.
     */
    public function getAssetUrl($path = ''): string
    {
        $assetPath = ltrim($path, '/');
        
        if ($this->isCdnStorage()) {
            // Get base URL from environment
            $cdnUrl = rtrim(env('CDN_URL', 'https://upload.goexploriabusiness.com'), '/');
            $themePath = $this->getFullPath();
            return "{$cdnUrl}/storage/{$themePath}/assets/{$assetPath}";
        }
        
        // Local storage URL
        return url("/storage/cms/themes/{$this->slug}/assets/{$assetPath}");
    }

    /**
     * Check if theme uses CDN storage.
     */
    public function isCdnStorage(): bool
    {
        return $this->storage_type === 'cdn' && env('THEME_CDN_ENABLED', false);
    }

    /**
     * Check if theme uses local storage.
     */
    public function isLocalStorage(): bool
    {
        return $this->storage_type === 'local' || (!$this->isCdnStorage());
    }

    /**
     * Get storage display name.
     */
    public function getStorageDisplayAttribute(): string
    {
        return $this->isCdnStorage() ? 'CDN' : 'Local';
    }

    /**
     * Scope for themes on CDN.
     */
    public function scopeCdn($query)
    {
        return $query->where('storage_type', 'cdn');
    }

    /**
     * Scope for themes on local storage.
     */
    public function scopeLocal($query)
    {
        return $query->where('storage_type', 'local');
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
        return !empty($this->preview_image);
    }

    /**
     * Get preview image URL.
     */
    public function getPreviewImageUrl(): ?string
    {
        if (!$this->hasPreviewImage()) {
            return null;
        }
        
        // If preview image is already a full URL (CDN)
        if ($this->isCdnUrl($this->preview_image)) {
            return $this->preview_image;
        }
        
        // If preview image is stored locally
        if ($this->isLocalStorage() && Storage::disk('public')->exists($this->preview_image)) {
            return Storage::disk('public')->url($this->preview_image);
        }
        
        return null;
    }

    /**
     * Get preview image URL attribute.
     */
    public function getPreviewUrlAttribute(): ?string
    {
        return $this->getPreviewImageUrl();
    }

    /**
     * Check if theme directory exists.
     */
    public function exists(): bool
    {
        if ($this->isCdnStorage()) {
            // For CDN, we need to check if the layout file exists via API
            $layoutPath = $this->getFullPath() . '/layout.blade.php';
            $content = $this->getCdnService()->getFile($layoutPath);
            return !is_null($content);
        }
        
        return file_exists($this->getFullPath());
    }

    /**
     * Check if theme has layout file.
     */
    public function hasLayout(): bool
    {
        if ($this->isCdnStorage()) {
            $layoutPath = $this->getFullPath() . '/layout.blade.php';
            $content = $this->getCdnService()->getFile($layoutPath);
            return !is_null($content);
        }
        
        return file_exists($this->getFullPath() . '/layout.blade.php');
    }

    /**
     * Get layout content.
     */
    public function getLayoutContent(): ?string
    {
        if ($this->isCdnStorage()) {
            $layoutPath = $this->getFullPath() . '/layout.blade.php';
            return $this->getCdnService()->getFile($layoutPath);
        }
        
        $layoutPath = $this->getFullPath() . '/layout.blade.php';
        if (file_exists($layoutPath)) {
            return file_get_contents($layoutPath);
        }
        
        return null;
    }

    /**
     * Get theme configuration content.
     */
    public function getThemeConfigContent(): ?array
    {
        if ($this->isCdnStorage()) {
            $configPath = $this->getFullPath() . '/theme.json';
            $content = $this->getCdnService()->getFile($configPath);
            if ($content) {
                return json_decode($content, true);
            }
            return null;
        }
        
        $configPath = $this->getFullPath() . '/theme.json';
        if (file_exists($configPath)) {
            return json_decode(file_get_contents($configPath), true);
        }
        
        return null;
    }

    /**
     * Get partial content.
     */
    public function getPartialContent($partialName): ?string
    {
        $partialPath = $this->getFullPath() . "/partials/{$partialName}.blade.php";
        
        if ($this->isCdnStorage()) {
            return $this->getCdnService()->getFile($partialPath);
        }
        
        if (file_exists($partialPath)) {
            return file_get_contents($partialPath);
        }
        
        return null;
    }

    /**
     * Get page content.
     */
    public function getPageContent($pageName): ?string
    {
        $pagePath = $this->getFullPath() . "/pages/{$pageName}.blade.php";
        
        if ($this->isCdnStorage()) {
            return $this->getCdnService()->getFile($pagePath);
        }
        
        if (file_exists($pagePath)) {
            return file_get_contents($pagePath);
        }
        
        return null;
    }

    /**
     * Check if a URL is from our CDN.
     */
    protected function isCdnUrl($url): bool
    {
        if (!$url) {
            return false;
        }
        
        $cdnUrl = env('CDN_URL', 'https://upload.goexploriabusiness.com');
        return Str::startsWith($url, $cdnUrl);
    }

    /**
     * Extract path from CDN URL.
     */
    protected function extractPathFromCdnUrl($url): string
    {
        $cdnUrl = env('CDN_URL', 'https://upload.goexploriabusiness.com');
        $path = str_replace($cdnUrl . '/storage/', '', $url);
        return $path;
    }

    /**
     * Get all theme files recursively.
     */
    public function getAllFiles(): array
    {
        $files = [];
        
        if ($this->isCdnStorage()) {
            // For CDN, we would need an API endpoint to list files
            // For now, return empty or implement based on your CDN capabilities
            Log::warning('Listing all files from CDN is not fully implemented');
            return $files;
        }
        
        $basePath = $this->getFullPath();
        if (!file_exists($basePath)) {
            return $files;
        }
        
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($basePath, \RecursiveDirectoryIterator::SKIP_DOTS)
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $relativePath = str_replace($basePath . '/', '', $file->getPathname());
                $files[] = $relativePath;
            }
        }
        
        return $files;
    }

    /**
     * Get theme size (human readable).
     */
    public function getSizeAttribute(): string
    {
        if ($this->isCdnStorage()) {
            // For CDN, we would need an API endpoint to get total size
            return 'N/A';
        }
        
        $basePath = $this->getFullPath();
        if (!file_exists($basePath)) {
            return '0 B';
        }
        
        $size = 0;
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($basePath, \RecursiveDirectoryIterator::SKIP_DOTS)
        );
        
        foreach ($iterator as $file) {
            $size += $file->getSize();
        }
        
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }
        
        return round($size, 2) . ' ' . $units[$i];
    }

    /**
     * Delete theme files.
     */
    public function deleteFiles(): bool
    {
        $service = app(\Vendor\Cms\Services\ThemeService::class);
        $service->deleteThemeFiles($this);
        return true;
    }
}