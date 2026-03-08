<?php

namespace App\Services;

use App\Models\Template;
use App\Spiders\TemplateSpider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RoachPHP\Roach;
use RoachPHP\Spider\Configuration\Overrides;

class TemplateScraperService
{
    protected string $assetsBasePath = 'templates/assets';
    protected string $templateAssetsFolder;
    protected array $downloadedAssets = [];
    protected string $baseUrl;
    
    public function scrapeAndSave(string $url, string $name = null): ?Template
    {
        $name = $name ?? $this->generateTemplateName($url);
        $this->baseUrl = $this->normalizeUrl($url);
        $this->templateAssetsFolder = $this->generateAssetsFolderName($name);
        
        $items = Roach::collectSpider(
            TemplateSpider::class,
            new Overrides(startUrls: [$url])
        );
        
        if (empty($items)) {
            return null;
        }
        
        \Log::info('Scraped metadata:', $items[0]['metadata'] ?? []);
        
        // Process HTML content to download and replace assets
        $processedHtml = $this->processHtmlContent(
            $items[0]['html_body'], 
            $items[0]['metadata']['assets'] ?? []
        );
        
        // Download and process CSS files
        $processedCss = $this->processCssContent(
            $items[0]['css_content'],
            $items[0]['metadata']['css_files'] ?? []
        );
        
        // Download and process JS files
        $processedJs = $this->downloadAndProcessJsFiles(
            $items[0]['metadata']['js_files'] ?? []
        );
        
        // Download images
        $images = $this->downloadImages(
            $items[0]['metadata']['images'] ?? []
        );
        
        // Create template record
        $template = Template::create([
            'name' => $name,
            'user_id' => auth()->id() ?? 1,
            'url' => $url,
            'html_content' => $processedHtml,
            'css_content' => $processedCss,
            'js_content' => $processedJs,
            'assets_folder' => $this->templateAssetsFolder,
            'assets_data' => json_encode([
                'images' => $images,
                'css_files' => $this->downloadedAssets['css'] ?? [],
                'js_files' => $this->downloadedAssets['js'] ?? [],
                'total_assets' => count($this->downloadedAssets, COUNT_RECURSIVE) - count($this->downloadedAssets)
            ])
        ]);
        
        // Save metadata
        $this->saveAssetsManifest($template);
        
        return $template;
    }
    
    protected function processHtmlContent(string $html, array $assets): string
    {
        // Process images in HTML
        $html = preg_replace_callback(
            '/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i',
            function($matches) {
                $src = $matches[1];
                $absoluteUrl = $this->makeAbsoluteUrl($src);
                
                if ($this->isValidImageUrl($absoluteUrl)) {
                    $localPath = $this->downloadAsset($absoluteUrl, 'images');
                    if ($localPath) {
                        return str_replace($src, asset(Storage::url($localPath)), $matches[0]);
                    }
                }
                return $matches[0];
            },
            $html
        );
        
        // Process CSS links in HTML
        $html = preg_replace_callback(
            '/<link[^>]+href=["\']([^"\']+)["\'][^>]+rel=["\']stylesheet["\'][^>]*>/i',
            function($matches) {
                $href = $matches[1];
                $absoluteUrl = $this->makeAbsoluteUrl($href);
                
                if ($this->isCssFile($absoluteUrl)) {
                    $localPath = $this->downloadAsset($absoluteUrl, 'css');
                    if ($localPath) {
                        return str_replace($href, asset(Storage::url($localPath)), $matches[0]);
                    }
                }
                return $matches[0];
            },
            $html
        );
        
        // Process JS scripts in HTML
        $html = preg_replace_callback(
            '/<script[^>]+src=["\']([^"\']+)["\'][^>]*><\/script>/i',
            function($matches) {
                $src = $matches[1];
                $absoluteUrl = $this->makeAbsoluteUrl($src);
                
                if ($this->isJsFile($absoluteUrl)) {
                    $localPath = $this->downloadAsset($absoluteUrl, 'js');
                    if ($localPath) {
                        return str_replace($src, asset(Storage::url($localPath)), $matches[0]);
                    }
                }
                return $matches[0];
            },
            $html
        );
        
        // Process background images in inline styles
        $html = preg_replace_callback(
            '/background(-image)?\s*:\s*url\(["\']?([^"\'()]+)["\']?\)/i',
            function($matches) {
                $url = $matches[2];
                $absoluteUrl = $this->makeAbsoluteUrl($url);
                
                if ($this->isValidImageUrl($absoluteUrl)) {
                    $localPath = $this->downloadAsset($absoluteUrl, 'images');
                    if ($localPath) {
                        return str_replace($url, asset(Storage::url($localPath)), $matches[0]);
                    }
                }
                return $matches[0];
            },
            $html
        );
        
        return $html;
    }
    
    protected function processCssContent(string $css, array $cssFiles = []): string
    {
        // Download external CSS files mentioned in @import rules
        $css = preg_replace_callback(
            '/@import\s+(url\()?["\']?([^"\'()]+)["\']?(\))?\s*;/i',
            function($matches) {
                $url = $matches[2];
                $absoluteUrl = $this->makeAbsoluteUrl($url);
                
                if ($this->isCssFile($absoluteUrl)) {
                    $localPath = $this->downloadAsset($absoluteUrl, 'css');
                    if ($localPath) {
                        return str_replace($url, asset(Storage::url($localPath)), $matches[0]);
                    }
                }
                return $matches[0];
            },
            $css
        );
        
        // Process url() references in CSS
        $css = preg_replace_callback(
            '/url\(["\']?([^"\'()]+)["\']?\)/i',
            function($matches) {
                $url = $matches[1];
                
                // Skip data URLs
                if (strpos($url, 'data:') === 0) {
                    return $matches[0];
                }
                
                $absoluteUrl = $this->makeAbsoluteUrl($url);
                
                if ($this->isValidAssetUrl($absoluteUrl)) {
                    $localPath = $this->downloadAsset($absoluteUrl, 'css_assets');
                    if ($localPath) {
                        return 'url("' . asset(Storage::url($localPath)) . '")';
                    }
                }
                return $matches[0];
            },
            $css
        );
        
        return $css;
    }
    
    protected function downloadAndProcessJsFiles(array $jsFiles): string
    {
        $jsContent = '';
        
        foreach ($jsFiles as $jsFile) {
            $absoluteUrl = $this->makeAbsoluteUrl($jsFile);
            
            if ($this->isJsFile($absoluteUrl)) {
                $localPath = $this->downloadAsset($absoluteUrl, 'js');
                if ($localPath) {
                    $jsContent .= "// Source: " . $jsFile . "\n";
                    $jsContent .= "// Local: " . asset(Storage::url($localPath)) . "\n\n";
                }
            }
        }
        
        return $jsContent;
    }
    
    protected function downloadImages(array $images): array
    {
        $downloadedImages = [];
        
        foreach ($images as $image) {
            $absoluteUrl = $this->makeAbsoluteUrl($image);
            
            if ($this->isValidImageUrl($absoluteUrl)) {
                $localPath = $this->downloadAsset($absoluteUrl, 'images');
                if ($localPath) {
                    $downloadedImages[] = [
                        'original' => $image,
                        'local' => $localPath,
                        'url' => asset(Storage::url($localPath)),
                        'size' => Storage::size($localPath) ?? 0
                    ];
                }
            }
        }
        
        return $downloadedImages;
    }
    
    protected function downloadAsset(string $url, string $type): ?string
    {
        try {
            // Generate unique filename
            $extension = $this->getFileExtension($url, $type);
            $filename = $this->generateFilename($url, $extension);
            $folderPath = $this->assetsBasePath . '/' . $this->templateAssetsFolder . '/' . $type;
            $filePath = $folderPath . '/' . $filename;
            
            // Check if already downloaded
            if (isset($this->downloadedAssets[$type][$url])) {
                return $this->downloadedAssets[$type][$url];
            }
            
            // Download file
            $client = new \GuzzleHttp\Client(['timeout' => 30]);
            $response = $client->get($url, [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                ]
            ]);
            
            if ($response->getStatusCode() === 200) {
                // Ensure directory exists
                Storage::makeDirectory($folderPath);
                
                // Save file
                Storage::put($filePath, $response->getBody());
                
                // Track downloaded asset
                $this->downloadedAssets[$type][$url] = $filePath;
                
                \Log::info("Downloaded asset: {$url} -> {$filePath}");
                
                return $filePath;
            }
        } catch (\Exception $e) {
            \Log::error("Failed to download asset {$url}: " . $e->getMessage());
        }
        
        return null;
    }
    
    protected function saveAssetsManifest(Template $template): void
    {
        $manifest = [
            'template_id' => $template->id,
            'template_name' => $template->name,
            'created_at' => now()->toISOString(),
            'assets' => [
                'images' => $this->downloadedAssets['images'] ?? [],
                'css' => $this->downloadedAssets['css'] ?? [],
                'js' => $this->downloadedAssets['js'] ?? [],
                'css_assets' => $this->downloadedAssets['css_assets'] ?? []
            ],
            'total_files' => array_sum(array_map('count', $this->downloadedAssets)),
            'base_url' => $this->baseUrl
        ];
        
        $manifestPath = $this->assetsBasePath . '/' . $this->templateAssetsFolder . '/manifest.json';
        Storage::put($manifestPath, json_encode($manifest, JSON_PRETTY_PRINT));
    }
    
    protected function makeAbsoluteUrl(string $relativeUrl): string
    {
        // If already absolute URL
        if (filter_var($relativeUrl, FILTER_VALIDATE_URL)) {
            return $relativeUrl;
        }
        
        // Handle protocol-relative URLs
        if (strpos($relativeUrl, '//') === 0) {
            return 'https:' . $relativeUrl;
        }
        
        // Parse base URL
        $baseParts = parse_url($this->baseUrl);
        $scheme = $baseParts['scheme'] ?? 'https';
        $host = $baseParts['host'] ?? '';
        $path = $baseParts['path'] ?? '/';
        
        // Remove filename from path if present
        if (strpos(basename($path), '.') !== false) {
            $path = dirname($path) . '/';
        }
        
        // Handle absolute paths
        if (strpos($relativeUrl, '/') === 0) {
            return $scheme . '://' . $host . $relativeUrl;
        }
        
        // Handle relative paths
        return $scheme . '://' . $host . rtrim($path, '/') . '/' . ltrim($relativeUrl, './');
    }
    
    protected function normalizeUrl(string $url): string
    {
        if (!preg_match('/^https?:\/\//', $url)) {
            $url = 'https://' . $url;
        }
        
        return rtrim($url, '/');
    }
    
    protected function generateAssetsFolderName(string $templateName): string
    {
        $slug = Str::slug($templateName);
        $timestamp = now()->format('Ymd_His');
        return $slug . '_' . $timestamp . '_' . Str::random(6);
    }
    
    protected function generateFilename(string $url, string $extension): string
    {
        $path = parse_url($url, PHP_URL_PATH);
        $basename = basename($path);
        
        if (empty($basename) || $basename === '/') {
            $basename = 'file';
        }
        
        // Remove query string if present
        $basename = explode('?', $basename)[0];
        
        // If no extension in basename, add one
        if (!pathinfo($basename, PATHINFO_EXTENSION)) {
            $basename .= '.' . $extension;
        }
        
        // Make filename safe and unique
        $name = pathinfo($basename, PATHINFO_FILENAME);
        $ext = pathinfo($basename, PATHINFO_EXTENSION);
        
        $safeName = Str::slug($name);
        $uniqueId = substr(md5($url), 0, 8);
        
        return $safeName . '_' . $uniqueId . '.' . $ext;
    }
    
    protected function getFileExtension(string $url, string $type): string
    {
        $path = parse_url($url, PHP_URL_PATH);
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        
        if (!empty($extension)) {
            return strtolower($extension);
        }
        
        // Default extensions by type
        $defaults = [
            'images' => 'jpg',
            'css' => 'css',
            'js' => 'js',
            'css_assets' => $this->guessExtensionFromUrl($url)
        ];
        
        return $defaults[$type] ?? 'bin';
    }
    
    protected function guessExtensionFromUrl(string $url): string
    {
        // Check content type from URL pattern
        if (preg_match('/\.(png|jpg|jpeg|gif|svg|webp|bmp|ico)$/i', $url)) {
            return strtolower(pathinfo($url, PATHINFO_EXTENSION));
        }
        
        if (preg_match('/\.(woff|woff2|ttf|eot|otf)$/i', $url)) {
            return strtolower(pathinfo($url, PATHINFO_EXTENSION));
        }
        
        return 'bin';
    }
    
    protected function isValidImageUrl(string $url): bool
    {
        return preg_match('/\.(png|jpg|jpeg|gif|svg|webp|bmp|ico)$/i', $url) ||
               preg_match('/\/images?\//i', $url);
    }
    
    protected function isCssFile(string $url): bool
    {
        return preg_match('/\.css$/i', $url) ||
               preg_match('/\/styles?\//i', $url);
    }
    
    protected function isJsFile(string $url): bool
    {
        return preg_match('/\.js$/i', $url) ||
               preg_match('/\/scripts?\//i', $url);
    }
    
    protected function isValidAssetUrl(string $url): bool
    {
        return $this->isValidImageUrl($url) ||
               preg_match('/\.(woff|woff2|ttf|eot|otf)$/i', $url);
    }
    
    private function generateTemplateName(string $url): string
    {
        $parsed = parse_url($url);
        $host = $parsed['host'] ?? 'unknown';
        $path = $parsed['path'] ?? '';
        
        $name = str_replace(['www.', '.com', '.org', '.net'], '', $host);
        $name .= str_replace(['/', '-', '_'], ' ', substr($path, 0, 50));
        
        return trim($name) ?: 'Scraped Template';
    }
    
    public function scrapeMultiple(array $urls): array
    {
        $results = [];
        
        foreach ($urls as $url) {
            try {
                $template = $this->scrapeAndSave($url);
                if ($template) {
                    $results[] = [
                        'success' => true,
                        'template' => $template,
                        'url' => $url
                    ];
                }
            } catch (\Exception $e) {
                $results[] = [
                    'success' => false,
                    'error' => $e->getMessage(),
                    'url' => $url
                ];
            }
        }
        
        return $results;
    }
    
    /**
     * Get template assets
     */
    public function getTemplateAssets(Template $template): array
    {
        $manifestPath = $this->assetsBasePath . '/' . $template->assets_folder . '/manifest.json';
        
        if (Storage::exists($manifestPath)) {
            return json_decode(Storage::get($manifestPath), true);
        }
        
        return [];
    }
    
    /**
     * Delete template assets
     */
    public function deleteTemplateAssets(Template $template): bool
    {
        $folderPath = $this->assetsBasePath . '/' . $template->assets_folder;
        
        if (Storage::exists($folderPath)) {
            return Storage::deleteDirectory($folderPath);
        }
        
        return true;
    }
}