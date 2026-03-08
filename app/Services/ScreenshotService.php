<?php
// app/Services/ScreenshotService.php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;

class ScreenshotService
{
    /**
     * Capture une screenshot d'une URL avec Browsershot
     */
    public function captureUrl(string $url, string $type = 'thumbnail'): ?string
    {
        try {
            // Vérifier si Browsershot est disponible
            if (!class_exists(\Spatie\Browsershot\Browsershot::class)) {
                throw new Exception('Browsershot n\'est pas installé');
            }
            
            // Générer un nom de fichier unique
            $domain = parse_url($url, PHP_URL_HOST) ?? 'screenshot';
            $filename = Str::slug($domain) . '-' . Str::random(10) . '.jpg';
            $path = 'templates/screenshots/' . date('Y/m/d') . '/' . $filename;
            $fullPath = storage_path('app/public/' . $path);
            
            // Créer le répertoire si nécessaire
            Storage::disk('public')->makeDirectory(dirname($path));
            
            if ($type === 'thumbnail') {
                // Thumbnail (petite image) - version simplifiée
                \Spatie\Browsershot\Browsershot::url($url)
                    ->setOption('args', ['--no-sandbox', '--disable-web-security'])
                    ->windowSize(800, 600)
                    ->setScreenshotType('jpeg', 80)
                    ->save($fullPath);
                
                // Redimensionner l'image pour la thumbnail
                $this->resizeImage($fullPath, 400, 300);
                
            } else {
                // Screenshot complète
                \Spatie\Browsershot\Browsershot::url($url)
                    ->setOption('args', ['--no-sandbox', '--disable-web-security'])
                    ->windowSize(1920, 1080)
                    ->fullPage()
                    ->setScreenshotType('jpeg', 80)
                    ->save($fullPath);
            }
            
            return $path;
            
        } catch (Exception $e) {
            \Log::error('Screenshot capture failed: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Redimensionner une image
     */
    private function resizeImage(string $imagePath, int $width, int $height): bool
    {
        try {
            // Utiliser GD pour redimensionner
            if (extension_loaded('gd')) {
                $imageInfo = getimagesize($imagePath);
                $mime = $imageInfo['mime'];
                
                switch ($mime) {
                    case 'image/jpeg':
                        $sourceImage = imagecreatefromjpeg($imagePath);
                        break;
                    case 'image/png':
                        $sourceImage = imagecreatefrompng($imagePath);
                        break;
                    case 'image/gif':
                        $sourceImage = imagecreatefromgif($imagePath);
                        break;
                    default:
                        return false;
                }
                
                $sourceWidth = imagesx($sourceImage);
                $sourceHeight = imagesy($sourceImage);
                
                // Calculer les nouvelles dimensions en gardant le ratio
                $ratio = $sourceWidth / $sourceHeight;
                if ($width / $height > $ratio) {
                    $width = $height * $ratio;
                } else {
                    $height = $width / $ratio;
                }
                
                $newImage = imagecreatetruecolor($width, $height);
                
                // Préserver la transparence pour PNG/GIF
                if ($mime == 'image/png' || $mime == 'image/gif') {
                    imagecolortransparent($newImage, imagecolorallocatealpha($newImage, 0, 0, 0, 127));
                    imagealphablending($newImage, false);
                    imagesavealpha($newImage, true);
                }
                
                imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $width, $height, $sourceWidth, $sourceHeight);
                
                // Sauvegarder l'image
                imagejpeg($newImage, $imagePath, 80);
                
                imagedestroy($sourceImage);
                imagedestroy($newImage);
                
                return true;
            }
            
            return false;
            
        } catch (Exception $e) {
            \Log::error('Image resize failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Capture à la fois thumbnail et screenshot complète
     */
    public function captureAll(string $url): array
    {
        $screenshots = [
            'thumbnail' => null,
            'screenshot' => null
        ];
        
        try {
            // Capturer d'abord la screenshot complète
            $screenshots['screenshot'] = $this->captureUrl($url, 'screenshot');
            
            // Si la screenshot complète réussit, créer une thumbnail
            if ($screenshots['screenshot']) {
                $screenshots['thumbnail'] = $this->createThumbnailFromScreenshot($screenshots['screenshot']);
            } else {
                // Fallback: capturer directement une thumbnail
                $screenshots['thumbnail'] = $this->captureUrl($url, 'thumbnail');
            }
            
        } catch (Exception $e) {
            \Log::error('Capture all failed: ' . $e->getMessage());
        }
        
        return $screenshots;
    }
    
    /**
     * Créer une thumbnail à partir d'une screenshot existante
     */
    private function createThumbnailFromScreenshot(string $screenshotPath): ?string
    {
        try {
            $fullPath = storage_path('app/public/' . $screenshotPath);
            $thumbnailPath = str_replace('.jpg', '-thumb.jpg', $screenshotPath);
            $thumbnailFullPath = storage_path('app/public/' . $thumbnailPath);
            
            // Copier le fichier
            copy($fullPath, $thumbnailFullPath);
            
            // Redimensionner en thumbnail
            $this->resizeImage($thumbnailFullPath, 400, 300);
            
            return $thumbnailPath;
            
        } catch (Exception $e) {
            \Log::error('Thumbnail creation failed: ' . $e->getMessage());
            return null;
        }
    }
}