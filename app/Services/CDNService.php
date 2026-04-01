<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CDNService
{
    protected $baseUrl;
    protected $apiKey;
    protected $apiSecret;
    protected $timeout;
    protected $retryTimes;
    
    public function __construct()
    {
        $this->baseUrl = rtrim(env('CDN_URL', 'https://upload.goexploriabusiness.com'), '/');
        $this->apiKey = env('CDN_API_KEY');
        $this->apiSecret = env('CDN_API_SECRET');
        $this->timeout = env('CDN_TIMEOUT', 30);
        $this->retryTimes = env('CDN_RETRY_TIMES', 3);
        
        Log::channel('cdn')->info('CDN Service initialized', [
            'base_url' => $this->baseUrl,
            'has_api_key' => !empty($this->apiKey),
            'has_api_secret' => !empty($this->apiSecret),
            'timeout' => $this->timeout,
            'retry_times' => $this->retryTimes
        ]);
    }
    
    /**
     * Upload a file to CDN
     */
    public function upload($file, $path = '', $visibility = 'public')
    {
        $startTime = microtime(true);
        $fileInfo = $this->getFileInfo($file);
        $requestId = (string) Str::uuid();
        
        Log::channel('cdn')->info('CDN Upload Started', [
            'request_id' => $requestId,
            'file_name' => $fileInfo['name'],
            'file_size' => $fileInfo['size'],
            'file_mime' => $fileInfo['mime'],
            'target_path' => $path,
            'visibility' => $visibility,
            'cdn_url' => $this->baseUrl
        ]);
        
        try {
            // Vérifier la configuration
            if (!$this->isConfigured()) {
                Log::channel('cdn')->error('CDN Upload Failed - Configuration Error', [
                    'request_id' => $requestId,
                    'error' => 'CDN not configured. Missing API keys or URL'
                ]);
                
                return [
                    'success' => false,
                    'error' => 'CDN not configured. Please check .env file',
                    'missing' => [
                        'api_key' => empty($this->apiKey),
                        'api_secret' => empty($this->apiSecret),
                        'base_url' => empty($this->baseUrl)
                    ]
                ];
            }
            
            // Préparer le contenu du fichier
            $fileContent = $this->getFileContent($file);
            $fileName = $fileInfo['name'];
            
            if ($fileContent === false) {
                Log::channel('cdn')->error('CDN Upload Failed - Cannot Read File', [
                    'request_id' => $requestId,
                    'file_name' => $fileName,
                    'file_path' => $file instanceof UploadedFile ? $file->getPathname() : $file
                ]);
                
                return [
                    'success' => false,
                    'error' => 'Cannot read file content'
                ];
            }
            
            Log::channel('cdn')->debug('CDN Upload Request Details', [
                'request_id' => $requestId,
                'file_content_length' => strlen($fileContent),
                'api_endpoint' => $this->baseUrl . '/api/upload',
                'headers' => [
                    'X-API-Key' => substr($this->apiKey, 0, 10) . '...',
                    'X-API-Secret' => substr($this->apiSecret, 0, 10) . '...'
                ]
            ]);
            
            // Envoyer la requête
            $response = Http::timeout($this->timeout)
                ->retry($this->retryTimes, 100)
                ->withHeaders([
                    'X-API-Key' => $this->apiKey,
                    'X-API-Secret' => $this->apiSecret,
                ])
                ->attach('file', $fileContent, $fileName)
                ->post($this->baseUrl . '/api/upload', [
                    'path' => $path,
                    'visibility' => $visibility
                ]);
            
            $duration = round((microtime(true) - $startTime) * 1000, 2);
            
            if ($response->successful()) {
                $result = $response->json();
                
                Log::channel('cdn')->info('CDN Upload Successful', [
                    'request_id' => $requestId,
                    'file_name' => $fileName,
                    'file_size' => $fileInfo['size'],
                    'duration_ms' => $duration,
                    'cdn_url' => $result['url'] ?? null,
                    'cdn_path' => $result['path'] ?? null,
                    'response_status' => $response->status(),
                    'response_data' => $result
                ]);
                
                return array_merge($result, [
                    'success' => true,
                    'request_id' => $requestId,
                    'duration_ms' => $duration
                ]);
            }
            
            // Log d'erreur HTTP
            Log::channel('cdn')->error('CDN Upload Failed - HTTP Error', [
                'request_id' => $requestId,
                'file_name' => $fileName,
                'file_size' => $fileInfo['size'],
                'duration_ms' => $duration,
                'http_status' => $response->status(),
                'response_body' => $response->body(),
                'response_headers' => $response->headers()
            ]);
            
            return [
                'success' => false,
                'error' => 'Upload failed with HTTP status: ' . $response->status(),
                'http_status' => $response->status(),
                'response' => $response->json(),
                'request_id' => $requestId,
                'duration_ms' => $duration
            ];
            
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::channel('cdn')->error('CDN Upload Failed - Connection Error', [
                'request_id' => $requestId,
                'file_name' => $fileInfo['name'],
                'error' => $e->getMessage(),
                'cdn_url' => $this->baseUrl,
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'error' => 'Connection failed: ' . $e->getMessage(),
                'request_id' => $requestId
            ];
            
        } catch (\Exception $e) {
            Log::channel('cdn')->error('CDN Upload Failed - Exception', [
                'request_id' => $requestId,
                'file_name' => $fileInfo['name'],
                'error' => $e->getMessage(),
                'error_class' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'request_id' => $requestId
            ];
        }
    }
    
    /**
     * Delete a file from CDN
     */
    public function delete($path)
    {
        $startTime = microtime(true);
        $requestId = (string) Str::uuid();
        
        Log::channel('cdn')->info('CDN Delete Started', [
            'request_id' => $requestId,
            'path' => $path,
            'cdn_url' => $this->baseUrl
        ]);
        
        try {
            if (!$this->isConfigured()) {
                Log::channel('cdn')->error('CDN Delete Failed - Configuration Error', [
                    'request_id' => $requestId,
                    'path' => $path
                ]);
                
                return [
                    'success' => false,
                    'error' => 'CDN not configured'
                ];
            }
            
            Log::channel('cdn')->debug('CDN Delete Request Details', [
                'request_id' => $requestId,
                'delete_url' => $this->baseUrl . '/api/file/' . urlencode($path)
            ]);
            
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'X-API-Key' => $this->apiKey,
                    'X-API-Secret' => $this->apiSecret,
                ])
                ->delete($this->baseUrl . '/api/file/' . urlencode($path));
            
            $duration = round((microtime(true) - $startTime) * 1000, 2);
            
            if ($response->successful()) {
                $result = $response->json();
                
                Log::channel('cdn')->info('CDN Delete Successful', [
                    'request_id' => $requestId,
                    'path' => $path,
                    'duration_ms' => $duration,
                    'response_status' => $response->status(),
                    'result' => $result
                ]);
                
                return array_merge($result, [
                    'success' => true,
                    'request_id' => $requestId,
                    'duration_ms' => $duration
                ]);
            }
            
            Log::channel('cdn')->error('CDN Delete Failed - HTTP Error', [
                'request_id' => $requestId,
                'path' => $path,
                'duration_ms' => $duration,
                'http_status' => $response->status(),
                'response_body' => $response->body()
            ]);
            
            return [
                'success' => false,
                'error' => 'Delete failed with HTTP status: ' . $response->status(),
                'http_status' => $response->status(),
                'request_id' => $requestId,
                'duration_ms' => $duration
            ];
            
        } catch (\Exception $e) {
            Log::channel('cdn')->error('CDN Delete Failed - Exception', [
                'request_id' => $requestId,
                'path' => $path,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'request_id' => $requestId
            ];
        }
    }
    
    /**
     * Get file from CDN
     */
    public function getFile($path)
    {
        $startTime = microtime(true);
        $requestId = (string) Str::uuid();
        
        Log::channel('cdn')->info('CDN Get File Started', [
            'request_id' => $requestId,
            'path' => $path,
            'cdn_url' => $this->baseUrl
        ]);
        
        try {
            $url = $this->baseUrl . '/storage/' . $path;
            
            Log::channel('cdn')->debug('CDN Get File Request', [
                'request_id' => $requestId,
                'url' => $url
            ]);
            
            $response = Http::timeout($this->timeout)
                ->get($url);
            
            $duration = round((microtime(true) - $startTime) * 1000, 2);
            
            if ($response->successful()) {
                Log::channel('cdn')->info('CDN Get File Successful', [
                    'request_id' => $requestId,
                    'path' => $path,
                    'duration_ms' => $duration,
                    'content_length' => strlen($response->body()),
                    'content_type' => $response->header('Content-Type')
                ]);
                
                return $response->body();
            }
            
            Log::channel('cdn')->warning('CDN Get File Failed - HTTP Error', [
                'request_id' => $requestId,
                'path' => $path,
                'duration_ms' => $duration,
                'http_status' => $response->status(),
                'response_body' => substr($response->body(), 0, 500)
            ]);
            
            return null;
            
        } catch (\Exception $e) {
            Log::channel('cdn')->error('CDN Get File Failed - Exception', [
                'request_id' => $requestId,
                'path' => $path,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return null;
        }
    }
    
    /**
     * Test CDN connection
     */
    public function testConnection(): array
    {
        $requestId = (string) Str::uuid();
        
        Log::channel('cdn')->info('CDN Connection Test Started', [
            'request_id' => $requestId,
            'cdn_url' => $this->baseUrl
        ]);
        
        try {
            $startTime = microtime(true);
            $response = Http::timeout(10)->get($this->baseUrl);
            $duration = round((microtime(true) - $startTime) * 1000, 2);
            
            $result = [
                'success' => $response->successful(),
                'status' => $response->status(),
                'duration_ms' => $duration,
                'cdn_url' => $this->baseUrl,
                'configured' => $this->isConfigured(),
                'request_id' => $requestId
            ];
            
            Log::channel('cdn')->info('CDN Connection Test Completed', $result);
            
            return $result;
            
        } catch (\Exception $e) {
            Log::channel('cdn')->error('CDN Connection Test Failed', [
                'request_id' => $requestId,
                'error' => $e->getMessage(),
                'cdn_url' => $this->baseUrl
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'configured' => $this->isConfigured(),
                'request_id' => $requestId
            ];
        }
    }
    
    /**
     * Check if CDN is properly configured
     */
    public function isConfigured(): bool
    {
        $configured = !empty($this->apiKey) && !empty($this->apiSecret) && !empty($this->baseUrl);
        
        if (!$configured) {
            Log::channel('cdn')->warning('CDN Configuration Incomplete', [
                'has_base_url' => !empty($this->baseUrl),
                'has_api_key' => !empty($this->apiKey),
                'has_api_secret' => !empty($this->apiSecret)
            ]);
        }
        
        return $configured;
    }
    
    /**
     * Get file information
     */
    protected function getFileInfo($file): array
    {
        if ($file instanceof UploadedFile) {
            return [
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType(),
                'type' => 'uploaded_file'
            ];
        }
        
        if (is_string($file) && file_exists($file)) {
            return [
                'name' => basename($file),
                'size' => filesize($file),
                'mime' => mime_content_type($file),
                'type' => 'file_path'
            ];
        }
        
        return [
            'name' => 'unknown',
            'size' => 0,
            'mime' => 'unknown',
            'type' => 'unknown'
        ];
    }
    
    /**
     * Get file content
     */
    protected function getFileContent($file)
    {
        if ($file instanceof UploadedFile) {
            return file_get_contents($file->getRealPath());
        }
        
        if (is_string($file) && file_exists($file)) {
            return file_get_contents($file);
        }
        
        return false;
    }
}