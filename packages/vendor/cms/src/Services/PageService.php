<?php

namespace Vendor\Cms\Services;

use Vendor\Cms\Models\Page;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PageService
{
    /**
     * Clear page cache.
     */
    public function clearPageCache($pageId = null)
    {
        if ($pageId) {
            Cache::forget("page_{$pageId}");
        } else {
            Cache::flush();
        }
    }

    /**
     * Get page by slug.
     */
    public function getPageBySlug($slug, $etablissementId)
    {
        $cacheKey = "page_slug_{$etablissementId}_{$slug}";
        
        return Cache::remember($cacheKey, 3600, function() use ($slug, $etablissementId) {
            return Page::where('etablissement_id', $etablissementId)
                ->where('slug', $slug)
                ->where('status', 'published')
                ->first();
        });
    }

    /**
     * Get home page.
     */
    public function getHomePage($etablissementId)
    {
        $cacheKey = "page_home_{$etablissementId}";
        
        return Cache::remember($cacheKey, 3600, function() use ($etablissementId) {
            return Page::where('etablissement_id', $etablissementId)
                ->where('is_home', true)
                ->where('status', 'published')
                ->first();
        });
    }

    /**
     * Get all published pages.
     */
    public function getPublishedPages($etablissementId)
    {
        $cacheKey = "pages_published_{$etablissementId}";
        
        return Cache::remember($cacheKey, 3600, function() use ($etablissementId) {
            return Page::where('etablissement_id', $etablissementId)
                ->where('status', 'published')
                ->orderBy('title')
                ->get();
        });
    }

    /**
     * Generate page sitemap.
     */
    public function generateSitemap($etablissementId)
    {
        $pages = Page::where('etablissement_id', $etablissementId)
            ->where('status', 'published')
            ->get();
        
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        foreach ($pages as $page) {
            $sitemap .= '  <url>' . "\n";
            $sitemap .= '    <loc>' . url('/page/' . $page->slug) . '</loc>' . "\n";
            $sitemap .= '    <lastmod>' . $page->updated_at->format('Y-m-d') . '</lastmod>' . "\n";
            $sitemap .= '    <priority>' . ($page->is_home ? '1.0' : '0.8') . '</priority>' . "\n";
            $sitemap .= '  </url>' . "\n";
        }
        
        $sitemap .= '</urlset>';
        
        return $sitemap;
    }
}