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
        $this->cdnEnabled      = env('THEME_CDN_ENABLED', false);
    }

    /**
     * Upload and extract a theme globally (no etablissement in path).
     * $etablissementId is kept for optional home page creation only.
     */
    public function uploadTheme($file, $name, $etablissementId = null): Theme
    {
        $slug      = Str::slug($name);
        // PATH GLOBAL : pas d'établissement_id
        $themePath = "cms/themes/{$slug}";

        if ($this->cdnEnabled) {
            return $this->uploadThemeToCDN($file, $name, $slug, $etablissementId, $themePath);
        } else {
            return $this->uploadThemeToLocal($file, $name, $slug, $etablissementId, $themePath);
        }
    }

    /**
     * Upload theme to CDN (global path).
     */
    protected function uploadThemeToCDN($file, $name, $slug, $etablissementId, $themePath): Theme
    {
        Log::info('Starting global theme upload to CDN', [
            'theme_name' => $name,
            'slug'       => $slug,
            'theme_path' => $themePath,
        ]);

        $tempDir = storage_path("app/temp/theme_{$slug}_" . time());

        if (!file_exists($tempDir)) {
            if (!mkdir($tempDir, 0755, true)) {
                throw new \Exception('Impossible de créer le dossier temporaire');
            }
        }

        $zip     = new ZipArchive();
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
            $this->validateThemeStructure($tempDir);
            $this->uploadDirectoryToCDN($tempDir, $themePath);

            $previewImage = $this->extractPreviewImageFromCDN($zipPath, $themePath, $slug);
            $homeContent  = $this->extractHomePageContent($tempDir);

            $existingTheme = Theme::where('slug', $slug)->first();
            if ($existingTheme) {
                throw new \Exception('Un thème avec ce nom existe déjà');
            }

            $theme = Theme::create([
                'name'         => $name,
                'slug'         => $slug,
                'path'         => $themePath,
                'preview_image'=> $previewImage,
                'version'      => $this->getThemeVersion($tempDir),
                'description'  => $this->getThemeDescription($tempDir),
                'is_default'   => Theme::count() === 0,
                'storage_type' => 'cdn',
            ]);

            if ($etablissementId) {
                $this->createDefaultHomePage($etablissementId, $theme, $homeContent);
            }

            $this->deleteDirectory($tempDir);

            Log::info('Global theme uploaded to CDN successfully', [
                'theme_id'   => $theme->id,
                'theme_name' => $theme->name,
            ]);

            return $theme;

        } catch (\Exception $e) {
            $this->deleteDirectory($tempDir);
            throw $e;
        }
    }

    /**
     * Upload theme to local storage (global path, no etablissement).
     */
    protected function uploadThemeToLocal($file, $name, $slug, $etablissementId, $themePath): Theme
    {
        $localPath = "app/public/{$themePath}";
        $fullPath  = storage_path($localPath);

        if (!file_exists($fullPath)) {
            if (!mkdir($fullPath, 0755, true)) {
                throw new \Exception('Impossible de créer le dossier du thème');
            }
        }

        $zip     = new ZipArchive();
        $zipPath = $file->getPathname();

        if ($zip->open($zipPath) === true) {
            if (!$zip->extractTo($fullPath)) {
                throw new \Exception('Erreur lors de l\'extraction du fichier ZIP');
            }
            $zip->close();
        } else {
            throw new \Exception('Impossible d\'ouvrir le fichier ZIP');
        }

        $this->validateThemeStructure($fullPath);

        $previewImage = $this->extractPreviewImageLocal($zipPath, $fullPath, $slug);
        $homeContent  = $this->extractHomePageContent($fullPath);

        $existingTheme = Theme::where('slug', $slug)->first();
        if ($existingTheme) {
            throw new \Exception('Un thème avec ce nom existe déjà');
        }

        $theme = Theme::create([
            'name'         => $name,
            'slug'         => $slug,
            'path'         => $themePath,
            'preview_image'=> $previewImage,
            'version'      => $this->getThemeVersion($fullPath),
            'description'  => $this->getThemeDescription($fullPath),
            'is_default'   => Theme::count() === 0,
            'storage_type' => 'local',
        ]);

        if ($etablissementId) {
            $this->createDefaultHomePage($etablissementId, $theme, $homeContent);
        }

        return $theme;
    }

    // -------------------------------------------------------------------------
    // Helpers (unchanged except preview path — no etablissementId in path)
    // -------------------------------------------------------------------------

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
            $cdnFilePath  = $targetPath . '/' . str_replace(DIRECTORY_SEPARATOR, '/', $relativePath);

            $uploadResult = $this->themeCdnService->upload($file->getPathname(), dirname($cdnFilePath), 'public');

            if (!isset($uploadResult['success']) || !$uploadResult['success']) {
                throw new \Exception('Failed to upload file to CDN: ' . ($uploadResult['error'] ?? 'Unknown error'));
            }
        }
    }

    protected function extractPreviewImageFromCDN($zipPath, $themePath, $slug): ?string
    {
        $zip             = new ZipArchive();
        $previewImagePath = null;
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

        if ($zip->open($zipPath) === true) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename   = $zip->getNameIndex($i);
                $isRootFile = !str_contains($filename, '/') || substr_count($filename, '/') === 1;

                if ($isRootFile) {
                    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    $basename  = strtolower(pathinfo($filename, PATHINFO_FILENAME));

                    if (in_array($extension, $imageExtensions)) {
                        $previewNames = ['screenshot', 'preview', 'thumbnail', 'cover', 'theme-preview', 'theme'];

                        if (in_array($basename, $previewNames)) {
                            $imageContent = $zip->getFromName($filename);
                            $tempFile     = tempnam(sys_get_temp_dir(), 'theme_preview');
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

    protected function extractPreviewImageLocal($zipPath, $extractPath, $slug): ?string
    {
        $zip             = new ZipArchive();
        $previewImagePath = null;
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

        if ($zip->open($zipPath) === true) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename   = $zip->getNameIndex($i);
                $isRootFile = !str_contains($filename, '/') || substr_count($filename, '/') === 1;

                if ($isRootFile) {
                    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    $basename  = strtolower(pathinfo($filename, PATHINFO_FILENAME));

                    if (in_array($extension, $imageExtensions)) {
                        $previewNames = ['screenshot', 'preview', 'thumbnail', 'cover', 'theme-preview', 'theme'];

                        if (in_array($basename, $previewNames)) {
                            $imageContent    = $zip->getFromName($filename);
                            // PATH GLOBAL : pas d'établissement dans le nom du fichier
                            $previewImageName = "themes/{$slug}/preview.{$extension}";
                            $storagePath     = Storage::disk('public')->put($previewImageName, $imageContent);

                            if ($storagePath) {
                                $previewImagePath = $previewImageName;
                                break;
                            }
                        }
                    }
                }
            }

            // Fallback : première image trouvée
            if (!$previewImagePath) {
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $filename   = $zip->getNameIndex($i);
                    $isRootFile = !str_contains($filename, '/') || substr_count($filename, '/') === 1;

                    if ($isRootFile) {
                        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                        if (in_array($extension, $imageExtensions)) {
                            $imageContent    = $zip->getFromName($filename);
                            $previewImageName = "themes/{$slug}/preview.{$extension}";
                            $storagePath     = Storage::disk('public')->put($previewImageName, $imageContent);

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

    protected function validateThemeStructure($path)
    {
        if (!file_exists($path . '/layout.blade.php')) {
            throw new \Exception('Le thème doit contenir un fichier layout.blade.php');
        }
    }

    protected function getThemeVersion($path): string
    {
        $configPath = $path . '/theme.json';
        if (file_exists($configPath)) {
            $config = json_decode(file_get_contents($configPath), true);
            return $config['version'] ?? '1.0.0';
        }
        return '1.0.0';
    }

    protected function getThemeDescription($path): ?string
    {
        $configPath = $path . '/theme.json';
        if (file_exists($configPath)) {
            $config = json_decode(file_get_contents($configPath), true);
            return $config['description'] ?? null;
        }
        return null;
    }

    protected function extractHomePageContent($themePath): ?string
    {
        $homeFiles = ['home.html', 'index.html', 'home.blade.php'];

        foreach ($homeFiles as $file) {
            $filePath = $themePath . '/' . $file;
            if (file_exists($filePath)) {
                $content = file_get_contents($filePath);
                if (str_ends_with($file, '.blade.php')) {
                    $content = $this->extractBladeContent($content);
                }
                return $this->cleanHtmlContent($content);
            }
        }

        return null;
    }

    protected function extractBladeContent($content): string
    {
        $pattern = '/@section\([\'"]content[\'"]\s*(.*?)\s*@endsection/s';
        if (preg_match($pattern, $content, $matches)) {
            return trim($matches[1]);
        }
        return preg_replace('/@\w+[^@]*/', '', $content);
    }

    protected function cleanHtmlContent($content): string
    {
        return trim($content);
    }

    protected function createDefaultHomePage($etablissementId, Theme $theme, ?string $homeContent)
    {
        $existing = \Vendor\Cms\Models\Page::where('etablissement_id', $etablissementId)
            ->where('is_home', true)
            ->first();

        if ($existing) {
            return;
        }

        \Vendor\Cms\Models\Page::create([
            'etablissement_id' => $etablissementId,
            'title'            => 'Accueil',
            'slug'             => 'home',
            'content'          => $homeContent ?? '<div class="container"><h1>Bienvenue</h1></div>',
            'status'           => 'published',
            'visibility'       => 'public',
            'is_home'          => true,
            'published_at'     => now(),
        ]);
    }

    /**
     * Duplicate a theme (global).
     */
    public function duplicateTheme(Theme $theme, string $newName): Theme
    {
        $newSlug  = Str::slug($newName);
        $newPath  = "cms/themes/{$newSlug}";
        $oldPath  = storage_path("app/public/{$theme->path}");
        $newLocal = storage_path("app/public/{$newPath}");

        if (file_exists($oldPath)) {
            $this->copyDirectory($oldPath, $newLocal);
        }

        return Theme::create([
            'name'         => $newName,
            'slug'         => $newSlug,
            'path'         => $newPath,
            'preview_image'=> $theme->preview_image,
            'version'      => $theme->version,
            'description'  => $theme->description,
            'config'       => $theme->config,
            'is_default'   => false,
            'storage_type' => $theme->storage_type,
        ]);
    }

    /**
     * Delete theme files.
     */
    public function deleteThemeFiles(Theme $theme): void
    {
        if ($theme->isLocalStorage()) {
            $fullPath = storage_path("app/public/{$theme->path}");
            if (file_exists($fullPath)) {
                $this->deleteDirectory($fullPath);
            }
            // Preview image
            if ($theme->preview_image && Storage::disk('public')->exists($theme->preview_image)) {
                Storage::disk('public')->delete($theme->preview_image);
            }
        }
    }

    protected function copyDirectory($source, $dest)
    {
        if (!file_exists($dest)) {
            mkdir($dest, 0755, true);
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $target = $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathname();
            if ($item->isDir()) {
                if (!file_exists($target)) {
                    mkdir($target, 0755, true);
                }
            } else {
                copy($item, $target);
            }
        }
    }

    protected function deleteDirectory($dir)
    {
        if (!file_exists($dir)) {
            return;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }

        rmdir($dir);
    }
}