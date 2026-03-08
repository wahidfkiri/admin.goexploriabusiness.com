<?php
// app/Services/TemplateScraperService.php

namespace App\Services;

use App\Models\Template;
use App\Spiders\TemplateSpider;
use RoachPHP\Roach;
use RoachPHP\Spider\Configuration\Overrides;

class TemplateScraperService
{
    public function scrapeAndSave(string $url, string $name = null): ?Template
    {
        $name = $name ?? $this->generateTemplateName($url);
        
        $items = Roach::collectSpider(
            TemplateSpider::class,
            new Overrides(startUrls: [$url])
        );
        
        if (empty($items)) {
            return null;
        }
        \Log::info('Scraped metadata:', $items[0]['metadata'] ?? []);
        
        return Template::create([
            'name' => $name,
            'user_id' => 1,
          //  'url' => $url,
            'html_content' => $items[0]['html_body'],
            'css_content' => $items[0]['css_content'],
           // 'metadata' => $items[0]['metadata']
        ]);
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
    
    private function generateTemplateName(string $url): string
    {
        $parsed = parse_url($url);
        $host = $parsed['host'] ?? 'unknown';
        $path = $parsed['path'] ?? '';
        
        $name = str_replace(['www.', '.com', '.org', '.net'], '', $host);
        $name .= str_replace(['/', '-', '_'], ' ', substr($path, 0, 50));
        
        return trim($name) ?: 'Scraped Template';
    }
}