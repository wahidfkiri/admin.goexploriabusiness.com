<?php

namespace Vendor\Cms\Services;

use Vendor\Cms\Models\Theme;
use Vendor\Cms\Models\Page;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use ZipArchive;
use Vendor\Cms\Services\ThemeCDNService;

class ThemeService
{
    protected $themeCdnService;
    protected $cdnEnabled;
    
    public function __construct(ThemeCDNService $themeCdnService)
    {
        $this->themeCdnService = $themeCdnService;
        $this->cdnEnabled = env('THEME_CDN_ENABLED', false);
    }
    
    /**
     * Upload and extract a theme.
     */
    public function uploadTheme($file, $name, $etablissementId): Theme
    {
        $slug = Str::slug($name);
        $themePath = "cms/themes/{$etablissementId}/{$slug}";
        
        if ($this->cdnEnabled) {
            // Upload to CDN
            return $this->uploadThemeToCDN($file, $name, $slug, $etablissementId, $themePath);
        } else {
            // Local upload (original logic)
            return $this->uploadThemeToLocal($file, $name, $slug, $etablissementId, $themePath);
        }
    }
    
    /**
     * Upload theme to CDN
     */
    protected function uploadThemeToCDN($file, $name, $slug, $etablissementId, $themePath): Theme
    {
        Log::info('Starting theme upload to CDN', [
            'theme_name' => $name,
            'slug' => $slug,
            'etablissement_id' => $etablissementId,
            'theme_path' => $themePath
        ]);
        
        // Extract zip temporarily
        $tempDir = storage_path("app/temp/theme_{$etablissementId}_{$slug}_" . time());
        
        if (!file_exists($tempDir)) {
            if (!mkdir($tempDir, 0755, true)) {
                throw new \Exception('Impossible de créer le dossier temporaire');
            }
        }
        
        // Extract zip
        $zip = new ZipArchive();
        $zipPath = $file->getPathname();
        
        if ($zip->open($zipPath) === true) {
            if (!$zip->extractTo($tempDir)) {
                throw new \Exception('Erreur lors de l\'extraction du fichier ZIP');
            }
            $zip->close();
        } else {
            throw new \Exception('Impossible d\'ouvrir le fichier ZIP');
        }
        
        try {
            // Validate theme structure
            $this->validateThemeStructure($tempDir);
            
            // Upload all files to CDN
            $this->uploadDirectoryToCDN($tempDir, $themePath);
            
            // Extract preview image from zip root
            $previewImage = $this->extractPreviewImageFromCDN($zipPath, $themePath, $slug, $etablissementId);
            
            // Extract home page content
            $homeContent = $this->extractHomePageContent($tempDir);
            
            // Check if theme already exists globally
            $existingTheme = Theme::where('slug', $slug)->first();
                
            if ($existingTheme) {
                throw new \Exception('Un thème avec ce nom existe déjà');
            }
            
            // Create theme record
            $theme = Theme::create([
                'name' => $name,
                'slug' => $slug,
                'path' => $themePath,
                'preview_image' => $previewImage,
                'version' => $this->getThemeVersion($tempDir),
                'description' => $this->getThemeDescription($tempDir),
                'is_default' => Theme::count() === 0,
                'storage_type' => 'cdn', // Add this column to your themes table
            ]);
            
            // Create default home page with extracted content
            $this->createDefaultHomePage($etablissementId, $theme, $homeContent);
            
            // Clean up temp directory
            $this->deleteDirectory($tempDir);
            
            Log::info('Theme uploaded to CDN successfully', [
                'theme_id' => $theme->id,
                'theme_name' => $theme->name,
                'cdn_path' => $themePath
            ]);
            
            return $theme;
            
        } catch (\Exception $e) {
            // Clean up temp directory on error
            $this->deleteDirectory($tempDir);
            throw $e;
        }
    }
    
    /**
     * Upload theme to local storage (original logic)
     */
    protected function uploadThemeToLocal($file, $name, $slug, $etablissementId, $themePath): Theme
    {
        $localPath = "app/public/{$themePath}";
        $fullPath = storage_path($localPath);
        
        // Create directory
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
        $previewImage = $this->extractPreviewImageLocal($zipPath, $fullPath, $slug, $etablissementId);
        
        // Extract home page content
        $homeContent = $this->extractHomePageContent($fullPath);
        
        // Check if theme already exists globally
        $existingTheme = Theme::where('slug', $slug)->first();
            
        if ($existingTheme) {
            throw new \Exception('Un thème avec ce nom existe déjà');
        }
        
        // Create theme record
        $theme = Theme::create([
            'name' => $name,
            'slug' => $slug,
            'path' => $themePath,
            'preview_image' => $previewImage,
            'version' => $this->getThemeVersion($fullPath),
            'description' => $this->getThemeDescription($fullPath),
            'is_default' => Theme::count() === 0,
            'storage_type' => 'local', // Add this column to your themes table
        ]);
        
        // Create default home page with extracted content
        $this->createDefaultHomePage($etablissementId, $theme, $homeContent);
        
        return $theme;
    }
    
    /**
     * Upload a directory recursively to CDN
     */
    protected function uploadDirectoryToCDN($sourceDir, $targetPath)
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($sourceDir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($files as $file) {
            if ($file->isDir()) {
                continue;
            }
            
            $relativePath = str_replace($sourceDir . DIRECTORY_SEPARATOR, '', $file->getPathname());
            $cdnFilePath = $targetPath . '/' . str_replace(DIRECTORY_SEPARATOR, '/', $relativePath);
            
            Log::info('Uploading theme file to CDN', [
                'local_path' => $file->getPathname(),
                'cdn_path' => $cdnFilePath,
                'file_size' => $file->getSize()
            ]);
            
            $uploadResult = $this->themeCdnService->upload($file->getPathname(), dirname($cdnFilePath), 'public');
            
            if (!isset($uploadResult['success']) || !$uploadResult['success']) {
                throw new \Exception('Failed to upload file to CDN: ' . ($uploadResult['error'] ?? 'Unknown error'));
            }
        }
    }
    
    /**
     * Extract preview image from zip for CDN
     */
    protected function extractPreviewImageFromCDN($zipPath, $themePath, $slug, $etablissementId): ?string
    {
        $zip = new ZipArchive();
        $previewImagePath = null;
        
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        
        if ($zip->open($zipPath) === true) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                $isRootFile = !str_contains($filename, '/') || substr_count($filename, '/') === 1;
                
                if ($isRootFile) {
                    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    $basename = strtolower(pathinfo($filename, PATHINFO_FILENAME));
                    
                    if (in_array($extension, $imageExtensions)) {
                        $previewNames = ['screenshot', 'preview', 'thumbnail', 'cover', 'theme-preview', 'theme'];
                        
                        if (in_array($basename, $previewNames)) {
                            $imageContent = $zip->getFromName($filename);
                            
                            // Create temp file to upload
                            $tempFile = tempnam(sys_get_temp_dir(), 'theme_preview');
                            file_put_contents($tempFile, $imageContent);
                            
                            $previewImageName = "preview.{$extension}";
                            $uploadResult = $this->themeCdnService->upload($tempFile, $themePath, 'public');
                            
                            unlink($tempFile);
                            
                            if (isset($uploadResult['success']) && $uploadResult['success']) {
                                $previewImagePath = $uploadResult['url'];
                                break;
                            }
                        }
                    }
                }
            }
            
            if (!$previewImagePath) {
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $filename = $zip->getNameIndex($i);
                    $isRootFile = !str_contains($filename, '/') || substr_count($filename, '/') === 1;
                    
                    if ($isRootFile) {
                        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                        
                        if (in_array($extension, $imageExtensions)) {
                            $imageContent = $zip->getFromName($filename);
                            
                            $tempFile = tempnam(sys_get_temp_dir(), 'theme_preview');
                            file_put_contents($tempFile, $imageContent);
                            
                            $uploadResult = $this->themeCdnService->upload($tempFile, $themePath, 'public');
                            
                            unlink($tempFile);
                            
                            if (isset($uploadResult['success']) && $uploadResult['success']) {
                                $previewImagePath = $uploadResult['url'];
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
     * Extract preview image locally (original logic)
     */
    protected function extractPreviewImageLocal($zipPath, $extractPath, $slug, $etablissementId): ?string
    {
        $zip = new ZipArchive();
        $previewImagePath = null;
        
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        
        if ($zip->open($zipPath) === true) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                $isRootFile = !str_contains($filename, '/') || substr_count($filename, '/') === 1;
                
                if ($isRootFile) {
                    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    $basename = strtolower(pathinfo($filename, PATHINFO_FILENAME));
                    
                    if (in_array($extension, $imageExtensions)) {
                        $previewNames = ['screenshot', 'preview', 'thumbnail', 'cover', 'theme-preview', 'theme'];
                        
                        if (in_array($basename, $previewNames)) {
                            $imageContent = $zip->getFromName($filename);
                            $previewImageName = "themes/{$etablissementId}/{$slug}/preview.{$extension}";
                            $storagePath = Storage::disk('public')->put($previewImageName, $imageContent);
                            
                            if ($storagePath) {
                                $previewImagePath = $previewImageName;
                                break;
                            }
                        }
                    }
                }
            }
            
            if (!$previewImagePath) {
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $filename = $zip->getNameIndex($i);
                    $isRootFile = !str_contains($filename, '/') || substr_count($filename, '/') === 1;
                    
                    if ($isRootFile) {
                        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                        
                        if (in_array($extension, $imageExtensions)) {
                            $imageContent = $zip->getFromName($filename);
                            $previewImageName = "themes/{$etablissementId}/{$slug}/preview.{$extension}";
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
     * Extract home page content from the theme.
     */
    protected function extractHomePageContent($themePath): ?string
    {
        // Chercher home.html, index.html ou home.blade.php à la racine
        $homeFiles = ['home.html', 'index.html', 'home.blade.php'];
        
        foreach ($homeFiles as $file) {
            $filePath = $themePath . '/' . $file;
            if (file_exists($filePath)) {
                $content = file_get_contents($filePath);
                Log::info('Home page content found in: ' . $file);
                
                // Si c'est un fichier Blade, extraire le contenu de la section
                if (str_ends_with($file, '.blade.php')) {
                    $content = $this->extractBladeContent($content);
                }
                
                return $this->cleanHtmlContent($content);
            }
        }
        
        Log::info('No home page content found in theme, using default');
        return null;
    }
    
    /**
     * Extract content from Blade file between @section('content') and @endsection.
     */
    protected function extractBladeContent($content): string
    {
        // Extraire le contenu entre @section('content') et @endsection
        $pattern = '/@section\([\'"]content[\'"]\s*(.*?)\s*@endsection/s';
        if (preg_match($pattern, $content, $matches)) {
            return trim($matches[1]);
        }
        
        // Si pas de section content, retourner tout le contenu sans les directives Blade
        $content = preg_replace('/@extends\([^\)]+\)/', '', $content);
        $content = preg_replace('/@section\([^\)]+\)/', '', $content);
        $content = preg_replace('/@endsection/', '', $content);
        $content = preg_replace('/@include\([^\)]+\)/', '', $content);
        $content = preg_replace('/@yield\([^\)]+\)/', '', $content);
        $content = preg_replace('/@stack\([^\)]+\)/', '', $content);
        
        return trim($content);
    }
    
    /**
     * Clean HTML content by removing DOCTYPE, html, head, body tags.
     */
    protected function cleanHtmlContent($content): string
    {
        // Enlever les balises DOCTYPE, html, head, body si présentes
        $content = preg_replace('/<!DOCTYPE[^>]*>/i', '', $content);
        $content = preg_replace('/<html[^>]*>/i', '', $content);
        $content = preg_replace('/<\/html>/i', '', $content);
        $content = preg_replace('/<head[^>]*>.*<\/head>/is', '', $content);
        $content = preg_replace('/<body[^>]*>/i', '', $content);
        $content = preg_replace('/<\/body>/i', '', $content);
        
        // Nettoyer les espaces
        $content = trim($content);
        
        return $content;
    }
    
    /**
     * Create default home page with theme content.
     */
    protected function createDefaultHomePage($etablissementId, $theme, $homeContent = null)
    {
        // Si un contenu a été extrait, l'utiliser
        if ($homeContent && !empty(trim($homeContent))) {
            $content = $homeContent;
            Log::info('Using extracted home page content for theme: ' . $theme->name);
        } else {
            // Sinon, utiliser un contenu par défaut basé sur le thème
            $content = $this->getDefaultHomeContent($theme);
            Log::info('Using default home page content for theme: ' . $theme->name);
        }
        
        // Créer la page d'accueil
        Page::updateOrCreate([
            'etablissement_id' => $etablissementId,
            'title' => 'Accueil',
            'slug' => 'home',
        ], [
            'content' => $content,
            'status' => 'published',
            'visibility' => 'public',
            'is_home' => true,
            'published_at' => now(),
        ]);
        
        Log::info('Default home page created for etablissement ' . $etablissementId . ' with theme: ' . $theme->name);
    }
    
    /**
     * Get default home content based on theme.
     */
    protected function getDefaultHomeContent($theme): string
    {
        return '<section class="hero-section" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 100px 20px; text-align: center;">
            <div class="container" style="max-width: 1200px; margin: 0 auto;">
                <h1 style="font-size: 3rem; margin-bottom: 20px;">Bienvenue sur notre site</h1>
                <p style="font-size: 1.2rem; margin-bottom: 30px;">Ceci est votre page d\'accueil avec le thème <strong>' . e($theme->name) . '</strong></p>
                <a href="#" class="btn" style="display: inline-block; background: white; color: #667eea; padding: 12px 30px; border-radius: 50px; text-decoration: none; font-weight: bold;">Commencer</a>
            </div>
        </section>
        <section class="features" style="padding: 60px 20px;">
            <div class="container" style="max-width: 1200px; margin: 0 auto;">
                <h2 style="text-align: center; margin-bottom: 40px;">Nos services</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
                    <div style="background: #f8f9fa; padding: 30px; border-radius: 10px; text-align: center;">
                        <i class="fas fa-rocket" style="font-size: 2rem; color: #667eea; margin-bottom: 15px;"></i>
                        <h3>Innovation</h3>
                        <p>Des solutions innovantes pour votre entreprise.</p>
                    </div>
                    <div style="background: #f8f9fa; padding: 30px; border-radius: 10px; text-align: center;">
                        <i class="fas fa-users" style="font-size: 2rem; color: #667eea; margin-bottom: 15px;"></i>
                        <h3>Expertise</h3>
                        <p>Une équipe d\'experts à votre service.</p>
                    </div>
                    <div style="background: #f8f9fa; padding: 30px; border-radius: 10px; text-align: center;">
                        <i class="fas fa-headset" style="font-size: 2rem; color: #667eea; margin-bottom: 15px;"></i>
                        <h3>Support 24/7</h3>
                        <p>Une assistance disponible à tout moment.</p>
                    </div>
                </div>
            </div>
        </section>';
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
        
        if (!file_exists($path . '/assets')) {
            mkdir($path . '/assets', 0755, true);
        }
        
        if (!file_exists($path . '/partials')) {
            mkdir($path . '/partials', 0755, true);
        }
        
        if (!file_exists($path . '/pages')) {
            mkdir($path . '/pages', 0755, true);
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
        
        $etablissement->themes()->updateExistingPivot(
            $etablissement->themes()->pluck('id')->toArray(),
            ['is_active' => false]
        );
        
        $etablissement->themes()->updateExistingPivot($theme->id, [
            'is_active' => true
        ]);
        
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
        
        $this->clearThemeCache($etablissementId);
    }
    
    /**
     * Delete theme files only (not database record).
     */
    public function deleteThemeFiles(Theme $theme, $etablissementId = null): void
    {
        $etablissementId = $etablissementId ?: $theme->etablissement_id;
        
        if ($theme->storage_type === 'cdn' && $this->cdnEnabled) {
            // Delete from CDN
            $cdnPath = "cms/themes/{$etablissementId}/{$theme->slug}";
            $this->deleteDirectoryFromCDN($cdnPath);
            
            if ($theme->preview_image && $this->isCdnUrl($theme->preview_image)) {
                $path = $this->extractPathFromCdnUrl($theme->preview_image);
                $this->themeCdnService->delete($path);
            }
        } else {
            // Delete from local storage
            $fullPath = storage_path("app/public/cms/themes/{$etablissementId}/{$theme->slug}");
            
            if (file_exists($fullPath)) {
                $this->deleteDirectory($fullPath);
            }
            
            if ($theme->preview_image && Storage::disk('public')->exists($theme->preview_image)) {
                Storage::disk('public')->delete($theme->preview_image);
            }
        }
    }
    
    /**
     * Delete a directory recursively from CDN
     */
    protected function deleteDirectoryFromCDN($cdnPath)
    {
        // Note: CDN might not support directory deletion
        // You may need to implement listing and deleting files individually
        // or use a CDN API endpoint that supports directory deletion
        Log::warning('Directory deletion from CDN may not be fully supported', [
            'path' => $cdnPath
        ]);
        
        // You can implement a list and delete approach if your CDN supports listing
        // For now, we'll log that we need to delete files individually
    }
    
    /**
     * Delete a theme completely (files + database).
     */
    public function deleteTheme(Theme $theme): void
    {
        $this->deleteThemeFiles($theme);
        $theme->delete();
        
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
        if (function_exists('opcache_reset')) {
            opcache_reset();
        }
        
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
        if ($theme->storage_type === 'cdn' && $this->cdnEnabled) {
            // For CDN, we need to download the config file
            $configContent = $this->themeCdnService->getFile($theme->path . '/config.json');
            if ($configContent) {
                $config = json_decode($configContent, true);
                return is_array($config) ? $config : [];
            }
            return [];
        } else {
            $configFile = storage_path($theme->path . '/config.json');
            
            if (file_exists($configFile)) {
                $config = json_decode(file_get_contents($configFile), true);
                return is_array($config) ? $config : [];
            }
            
            return [];
        }
    }
    
    /**
     * Save theme configuration.
     */
    public function saveThemeConfig(Theme $theme, array $config): void
    {
        if ($theme->storage_type === 'cdn' && $this->cdnEnabled) {
            // For CDN, we need to upload the config file
            $tempFile = tempnam(sys_get_temp_dir(), 'theme_config');
            file_put_contents($tempFile, json_encode($config, JSON_PRETTY_PRINT));
            
            $this->themeCdnService->upload($tempFile, $theme->path, 'public');
            
            unlink($tempFile);
        } else {
            $configFile = storage_path($theme->path . '/config.json');
            file_put_contents($configFile, json_encode($config, JSON_PRETTY_PRINT));
        }
        
        $theme->config = $config;
        $theme->save();
    }
    
    /**
     * Duplicate a theme.
     */
    public function duplicateTheme(Theme $theme, string $newName, $etablissementId): Theme
    {
        $newSlug = Str::slug($newName);
        $newPath = "cms/themes/{$etablissementId}/{$newSlug}";
        
        if ($theme->storage_type === 'cdn' && $this->cdnEnabled) {
            // For CDN, we need to copy files from source to destination
            // This requires listing source files and uploading them to new location
            throw new \Exception('Theme duplication from CDN is not yet implemented');
        } else {
            $sourcePath = storage_path("app/public/cms/themes/{$etablissementId}/{$theme->slug}");
            $destPath = storage_path("app/public/{$newPath}");
            
            if (!file_exists($destPath)) {
                mkdir($destPath, 0755, true);
                $this->copyDirectory($sourcePath, $destPath);
            }
        }
        
        $newTheme = Theme::create([
            'name' => $newName,
            'slug' => $newSlug,
            'path' => $newPath,
            'version' => $theme->version,
            'description' => $theme->description,
            'config' => $theme->config,
            'is_default' => false,
            'storage_type' => $theme->storage_type,
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
    
    /**
     * Get all themes available for an etablissement.
     */
    public function getAvailableThemesForEtablissement($etablissementId): \Illuminate\Support\Collection
    {
        $etablissement = \App\Models\Etablissement::findOrFail($etablissementId);
        
        $linkedThemeIds = $etablissement->themes()->pluck('theme_id')->toArray();
        $allThemes = Theme::all();
        
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
        $this->clearThemeCache($etablissementId);
    }
    
    /**
     * Check if a URL is from our CDN
     */
    protected function isCdnUrl($url)
    {
        if (!$url) {
            return false;
        }
        
        $cdnUrl = env('CDN_URL', 'https://upload.goexploriabusiness.com');
        return Str::startsWith($url, $cdnUrl);
    }
    
    /**
     * Extract the storage path from a CDN URL
     */
    protected function extractPathFromCdnUrl($url)
    {
        $cdnUrl = env('CDN_URL', 'https://upload.goexploriabusiness.com');
        $path = str_replace($cdnUrl . '/storage/', '', $url);
        return $path;
    }
}