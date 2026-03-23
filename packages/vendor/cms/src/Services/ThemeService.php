<?php

namespace Vendor\Cms\Services;

use Vendor\Cms\Models\Theme;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class ThemeService
{
    /**
     * Upload and extract a theme.
     */
    public function uploadTheme($file, $name, $etablissementId): Theme
    {
        $slug = Str::slug($name);
        $themePath = "app/public/cms/themes/{$etablissementId}/{$slug}";
        
        // Create directory
        $fullPath = storage_path($themePath);
        
        if (!file_exists($fullPath)) {
            if (!mkdir($fullPath, 0755, true)) {
                throw new \Exception('Impossible de créer le dossier du thème');
            }
        }
        
        // Extract zip
        $zip = new ZipArchive();
        $zipPath = $file->getPathname();
        
        if ($zip->open($zipPath) === true) {
            if (!$zip->extractTo($fullPath)) {
                throw new \Exception('Erreur lors de l\'extraction du fichier ZIP');
            }
            $zip->close();
        } else {
            throw new \Exception('Impossible d\'ouvrir le fichier ZIP');
        }
        
        // Validate theme structure
        $this->validateThemeStructure($fullPath);
        
        // Check if theme already exists
        $existingTheme = Theme::where('etablissement_id', $etablissementId)
            ->where('slug', $slug)
            ->first();
            
        if ($existingTheme) {
            throw new \Exception('Un thème avec ce nom existe déjà');
        }
        
        // Create theme record
        $theme = Theme::create([
            'etablissement_id' => $etablissementId,
            'name' => $name,
            'slug' => $slug,
            'path' => $themePath,
            'version' => $this->getThemeVersion($fullPath),
            'description' => $this->getThemeDescription($fullPath),
            'is_active' => false,
            'is_default' => Theme::where('etablissement_id', $etablissementId)->count() === 0,
        ]);
        
        // If this is the first theme, activate it
        if ($theme->is_default) {
            $this->activateTheme($theme);
        }
        
        return $theme;
    }
    
    /**
     * Validate theme structure.
     */
    protected function validateThemeStructure($path): void
    {
        $requiredFiles = ['layout.blade.php'];
        
        foreach ($requiredFiles as $file) {
            if (!file_exists($path . '/' . $file)) {
                throw new \Exception("Fichier requis manquant: {$file}");
            }
        }
        
        // Create assets directory if it doesn't exist
        if (!file_exists($path . '/assets')) {
            mkdir($path . '/assets', 0755, true);
        }
    }
    
    /**
     * Get theme version from config file.
     */
    protected function getThemeVersion($path): string
    {
        $configFile = $path . '/theme.json';
        
        if (file_exists($configFile)) {
            $config = json_decode(file_get_contents($configFile), true);
            return $config['version'] ?? '1.0.0';
        }
        
        return '1.0.0';
    }
    
    /**
     * Get theme description from config file.
     */
    protected function getThemeDescription($path): ?string
    {
        $configFile = $path . '/theme.json';
        
        if (file_exists($configFile)) {
            $config = json_decode(file_get_contents($configFile), true);
            return $config['description'] ?? null;
        }
        
        return null;
    }
    
    /**
     * Activate a theme.
     */
    public function activateTheme(Theme $theme): void
    {
        // Deactivate all other themes for this etablissement
        Theme::where('etablissement_id', $theme->etablissement_id)
            ->where('id', '!=', $theme->id)
            ->update(['is_active' => false]);
            
        $theme->is_active = true;
        $theme->save();
        
        // Clear theme cache
        $this->clearThemeCache($theme->etablissement_id);
    }
    
    /**
     * Deactivate a theme.
     */
    public function deactivateTheme(Theme $theme): void
    {
        $theme->is_active = false;
        $theme->save();
        
        // Clear theme cache
        $this->clearThemeCache($theme->etablissement_id);
    }
    
    /**
     * Delete a theme.
     */
    public function deleteTheme(Theme $theme): void
    {
        // Prevent deletion if it's the only theme
        $themeCount = Theme::where('etablissement_id', $theme->etablissement_id)->count();
        
        if ($themeCount <= 1) {
            throw new \Exception('Vous ne pouvez pas supprimer le dernier thème');
        }
        
        // Delete physical files
        $fullPath = storage_path($theme->path);
        
        if (file_exists($fullPath)) {
            $this->deleteDirectory($fullPath);
        }
        
        // Delete preview image if exists
        if ($theme->preview_image && Storage::disk('public')->exists($theme->preview_image)) {
            Storage::disk('public')->delete($theme->preview_image);
        }
        
        $theme->delete();
        
        // If the deleted theme was active, activate another one
        if ($theme->is_active) {
            $newActiveTheme = Theme::where('etablissement_id', $theme->etablissement_id)->first();
            
            if ($newActiveTheme) {
                $this->activateTheme($newActiveTheme);
            }
        }
        
        // Clear theme cache
        $this->clearThemeCache($theme->etablissement_id);
    }
    
    /**
     * Clear theme cache.
     */
    protected function clearThemeCache($etablissementId): void
    {
        // Clear view cache
        if (function_exists('opcache_reset')) {
            opcache_reset();
        }
        
        // Clear specific page cache for this etablissement
        $cacheKeys = [
            "page_{$etablissementId}_*",
            "theme_{$etablissementId}_*",
        ];
        
        foreach ($cacheKeys as $pattern) {
            \Illuminate\Support\Facades\Cache::delete($pattern);
        }
    }
    
    /**
     * Recursively delete a directory.
     */
    protected function deleteDirectory($dir): bool
    {
        if (!file_exists($dir)) {
            return true;
        }
        
        if (!is_dir($dir)) {
            return unlink($dir);
        }
        
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            
            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }
        
        return rmdir($dir);
    }
    
    /**
     * Get theme configuration.
     */
    public function getThemeConfig(Theme $theme): array
    {
        $configFile = storage_path($theme->path . '/config.json');
        
        if (file_exists($configFile)) {
            $config = json_decode(file_get_contents($configFile), true);
            return is_array($config) ? $config : [];
        }
        
        return [];
    }
    
    /**
     * Save theme configuration.
     */
    public function saveThemeConfig(Theme $theme, array $config): void
    {
        $configFile = storage_path($theme->path . '/config.json');
        
        file_put_contents($configFile, json_encode($config, JSON_PRETTY_PRINT));
        
        $theme->config = $config;
        $theme->save();
    }
    
    /**
     * Duplicate a theme.
     */
    public function duplicateTheme(Theme $theme, string $newName): Theme
    {
        $newSlug = Str::slug($newName);
        $newPath = "app/cms/themes/{$theme->etablissement_id}/{$newSlug}";
        
        // Copy files
        $sourcePath = storage_path($theme->path);
        $destPath = storage_path($newPath);
        
        if (!file_exists($destPath)) {
            mkdir($destPath, 0755, true);
            $this->copyDirectory($sourcePath, $destPath);
        }
        
        // Create new theme record
        $newTheme = Theme::create([
            'etablissement_id' => $theme->etablissement_id,
            'name' => $newName,
            'slug' => $newSlug,
            'path' => $newPath,
            'version' => $theme->version,
            'description' => $theme->description,
            'config' => $theme->config,
            'is_active' => false,
            'is_default' => false,
        ]);
        
        return $newTheme;
    }
    
    /**
     * Copy directory recursively.
     */
    protected function copyDirectory($src, $dst): void
    {
        $dir = opendir($src);
        
        @mkdir($dst);
        
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->copyDirectory($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        
        closedir($dir);
    }
}