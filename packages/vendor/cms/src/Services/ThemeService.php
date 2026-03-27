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
     * Upload and extract a theme (sans etablissement_id).
     */
    public function uploadTheme($file, $name): Theme
    {
        $slug = Str::slug($name);
        // Nouveau chemin: storage/app/public/cms/themes/{slug}/
        $themePath = "app/public/cms/themes/{$slug}";
        
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
        
        // Extract preview image from zip root
        $previewImage = $this->extractPreviewImage($zipPath, $fullPath, $slug);
        
        // Check if theme already exists globally
        $existingTheme = Theme::where('slug', $slug)->first();
            
        if ($existingTheme) {
            throw new \Exception('Un thème avec ce nom existe déjà');
        }
        
        // Create theme record (sans etablissement_id)
        $theme = Theme::create([
            'name' => $name,
            'slug' => $slug,
            'path' => $themePath,
            'preview_image' => $previewImage,
            'version' => $this->getThemeVersion($fullPath),
            'description' => $this->getThemeDescription($fullPath),
            'is_default' => Theme::count() === 0, // Premier thème créé = thème par défaut
        ]);
        
        return $theme;
    }
    
    /**
     * Extract preview image from zip root.
     */
    protected function extractPreviewImage($zipPath, $extractPath, $slug): ?string
    {
        $zip = new ZipArchive();
        $previewImagePath = null;
        
        // Extensions d'images acceptées
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        
        if ($zip->open($zipPath) === true) {
            // Chercher les images à la racine du ZIP
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                
                // Vérifier si le fichier est à la racine (pas de slash ou seulement un dossier)
                $isRootFile = !str_contains($filename, '/') || substr_count($filename, '/') === 1;
                
                if ($isRootFile) {
                    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    $basename = strtolower(pathinfo($filename, PATHINFO_FILENAME));
                    
                    // Vérifier si c'est une image avec un nom de prévisualisation
                    if (in_array($extension, $imageExtensions)) {
                        // Noms de fichier acceptés pour la prévisualisation
                        $previewNames = ['screenshot', 'preview', 'thumbnail', 'cover', 'theme-preview', 'theme'];
                        
                        if (in_array($basename, $previewNames)) {
                            // Extraire l'image
                            $imageContent = $zip->getFromName($filename);
                            $previewImageName = "themes/{$slug}/preview.{$extension}";
                            $storagePath = Storage::disk('public')->put($previewImageName, $imageContent);
                            
                            if ($storagePath) {
                                $previewImagePath = $previewImageName;
                                break;
                            }
                        }
                    }
                }
            }
            
            // Si aucune image de preview trouvée, chercher la première image à la racine
            if (!$previewImagePath) {
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $filename = $zip->getNameIndex($i);
                    $isRootFile = !str_contains($filename, '/') || substr_count($filename, '/') === 1;
                    
                    if ($isRootFile) {
                        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                        
                        if (in_array($extension, $imageExtensions)) {
                            $imageContent = $zip->getFromName($filename);
                            $previewImageName = "themes/{$slug}/preview.{$extension}";
                            $storagePath = Storage::disk('public')->put($previewImageName, $imageContent);
                            
                            if ($storagePath) {
                                $previewImagePath = $previewImageName;
                                break;
                            }
                        }
                    }
                }
            }
            
            $zip->close();
        }
        
        return $previewImagePath;
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
     * Activate a theme for a specific etablissement (via relation).
     */
    public function activateThemeForEtablissement(Theme $theme, $etablissementId): void
    {
        $etablissement = \App\Models\Etablissement::findOrFail($etablissementId);
        
        // Désactiver tous les thèmes de l'établissement
        $etablissement->themes()->updateExistingPivot(
            $etablissement->themes()->pluck('id')->toArray(),
            ['is_active' => false]
        );
        
        // Activer le thème sélectionné
        $etablissement->themes()->updateExistingPivot($theme->id, [
            'is_active' => true
        ]);
        
        // Clear theme cache
        $this->clearThemeCache($etablissementId);
    }
    
    /**
     * Deactivate a theme for a specific etablissement.
     */
    public function deactivateThemeForEtablissement(Theme $theme, $etablissementId): void
    {
        $etablissement = \App\Models\Etablissement::findOrFail($etablissementId);
        
        $etablissement->themes()->updateExistingPivot($theme->id, [
            'is_active' => false
        ]);
        
        // Clear theme cache
        $this->clearThemeCache($etablissementId);
    }
    
    /**
     * Delete theme files only (not database record).
     */
    public function deleteThemeFiles(Theme $theme): void
    {
        $fullPath = storage_path($theme->path);
        
        if (file_exists($fullPath)) {
            $this->deleteDirectory($fullPath);
        }
        
        // Delete preview image if exists
        if ($theme->preview_image && Storage::disk('public')->exists($theme->preview_image)) {
            Storage::disk('public')->delete($theme->preview_image);
        }
    }
    
    /**
     * Delete a theme completely (files + database).
     */
    public function deleteTheme(Theme $theme): void
    {
        // Delete physical files
        $this->deleteThemeFiles($theme);
        
        // Delete the theme record
        $theme->delete();
        
        // Clear cache for all etablissements that used this theme
        $etablissementIds = $theme->etablissements()->pluck('etablissement_id')->toArray();
        foreach ($etablissementIds as $etablissementId) {
            $this->clearThemeCache($etablissementId);
        }
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
        $newPath = "app/public/cms/themes/{$newSlug}";
        
        // Copy files
        $sourcePath = storage_path($theme->path);
        $destPath = storage_path($newPath);
        
        if (!file_exists($destPath)) {
            mkdir($destPath, 0755, true);
            $this->copyDirectory($sourcePath, $destPath);
        }
        
        // Create new theme record
        $newTheme = Theme::create([
            'name' => $newName,
            'slug' => $newSlug,
            'path' => $newPath,
            'version' => $theme->version,
            'description' => $theme->description,
            'config' => $theme->config,
            'is_default' => false,
        ]);
        
        // Copy preview image if exists
        if ($theme->preview_image) {
            $oldPreviewPath = $theme->preview_image;
            $newPreviewPath = "themes/{$newSlug}/preview." . pathinfo($oldPreviewPath, PATHINFO_EXTENSION);
            
            if (Storage::disk('public')->exists($oldPreviewPath)) {
                Storage::disk('public')->copy($oldPreviewPath, $newPreviewPath);
                $newTheme->update(['preview_image' => $newPreviewPath]);
            }
        }
        
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
    
    /**
     * Get all themes available for an etablissement.
     */
    public function getAvailableThemesForEtablissement($etablissementId): \Illuminate\Support\Collection
    {
        $etablissement = \App\Models\Etablissement::findOrFail($etablissementId);
        
        // Thèmes déjà liés
        $linkedThemeIds = $etablissement->themes()->pluck('theme_id')->toArray();
        
        // Tous les thèmes
        $allThemes = Theme::all();
        
        // Séparer les thèmes liés et non liés
        $linkedThemes = $allThemes->filter(function($theme) use ($linkedThemeIds) {
            return in_array($theme->id, $linkedThemeIds);
        });
        
        $availableThemes = $allThemes->filter(function($theme) use ($linkedThemeIds) {
            return !in_array($theme->id, $linkedThemeIds);
        });
        
        return collect([
            'linked' => $linkedThemes,
            'available' => $availableThemes
        ]);
    }
    
    /**
     * Attach an existing theme to an etablissement.
     */
    public function attachThemeToEtablissement($themeId, $etablissementId, $isActive = false): void
    {
        $etablissement = \App\Models\Etablissement::findOrFail($etablissementId);
        $theme = Theme::findOrFail($themeId);
        
        // Vérifier si déjà attaché
        if ($etablissement->themes()->where('theme_id', $themeId)->exists()) {
            throw new \Exception('Ce thème est déjà associé à cet établissement');
        }
        
        $etablissement->themes()->attach($themeId, [
            'is_active' => $isActive,
            'config' => null,
        ]);
    }
    
    /**
     * Detach a theme from an etablissement.
     */
    public function detachThemeFromEtablissement($themeId, $etablissementId): void
    {
        $etablissement = \App\Models\Etablissement::findOrFail($etablissementId);
        
        $etablissement->themes()->detach($themeId);
        
        // Clear cache
        $this->clearThemeCache($etablissementId);
    }
}