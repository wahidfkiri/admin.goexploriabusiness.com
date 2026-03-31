<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\UploadedFile;

class CDNService
{
    protected $baseUrl;
    protected $apiKey;
    protected $apiSecret;
    
    public function __construct()
    {
        $this->baseUrl = env('CDN_URL', 'https://upload.goexploriabusiness.com');
        $this->apiKey = env('CDN_API_KEY');
        $this->apiSecret = env('CDN_API_SECRET');
    }
    
    public function upload($file, $path = '', $visibility = 'public')
    {
        $response = Http::withHeaders([
            'X-API-Key' => $this->apiKey,
            'X-API-Secret' => $this->apiSecret,
        ])->attach(
            'file', file_get_contents($file), $file->getClientOriginalName()
        )->post($this->baseUrl . '/api/upload', [
            'path' => $path,
            'visibility' => $visibility
        ]);
        
        return $response->json();
    }
    
    public function delete($path)
    {
        $response = Http::withHeaders([
            'X-API-Key' => $this->apiKey,
            'X-API-Secret' => $this->apiSecret,
        ])->delete($this->baseUrl . '/api/file/' . urlencode($path));
        
        return $response->json();
    }
    
    public function getFile($path)
    {
        return Http::get($this->baseUrl . '/storage/' . $path)->body();
    }
}