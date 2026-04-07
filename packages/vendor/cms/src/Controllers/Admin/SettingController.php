<?php

namespace Vendor\Cms\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Etablissement;
use Vendor\Cms\Models\Setting;
use Vendor\Cms\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    /**
     * Display a listing of settings.
     */
    public function index(Request $request, $etablissementId)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        
        // Vérifier l'accès
        if (!$this->userHasAccess($request->user(), $etablissement)) {
            abort(403, 'Accès non autorisé');
        }
        
        // Récupérer tous les paramètres groupés
        $settings = Setting::where('etablissement_id', $etablissement->id)
            ->get()
            ->groupBy('group');
        
        // Récupérer les thèmes pour la sélection
        $themes = Theme::where('etablissement_id', $etablissement->id)->get();
        
        // Valeurs par défaut pour les paramètres manquants
        $defaultSettings = $this->getDefaultSettings($etablissement);
        
        return view('cms::admin.settings.index', compact('etablissement', 'settings', 'themes', 'defaultSettings'));
    }

    /**
     * Update settings.
     */
    public function update(Request $request, $etablissementId): JsonResponse
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            // if (!$this->userHasAccess($request->user(), $etablissement)) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Accès non autorisé'
            //     ], 403);
            // }
            
            $data = $request->all();
            
            // Traiter chaque paramètre
            foreach ($data as $key => $value) {
                if ($key === '_token' || $key === '_method') {
                    continue;
                }
                
                // Déterminer le groupe à partir du préfixe de la clé
                $group = $this->getGroupFromKey($key);
                $cleanKey = $this->getCleanKey($key);
                
                // Sauvegarder le paramètre
                Setting::updateOrCreate(
                    [
                        'etablissement_id' => $etablissement->id,
                        'group' => $group,
                        'key' => $cleanKey
                    ],
                    [
                        'value' => $value,
                        'type' => $this->getValueType($value)
                    ]
                );
            }
            
            // Vider le cache
            $this->clearSettingsCache($etablissement->id);
            
            return response()->json([
                'success' => true,
                'message' => 'Configuration sauvegardée avec succès'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Settings update error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la sauvegarde: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get settings by group.
     */
    public function group(Request $request, $etablissementId, $group): JsonResponse
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            if (!$this->userHasAccess($request->user(), $etablissement)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }
            
            $settings = Setting::where('etablissement_id', $etablissement->id)
                ->where('group', $group)
                ->get()
                ->pluck('value', 'key');
            
            return response()->json([
                'success' => true,
                'data' => $settings
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération'
            ], 500);
        }
    }

    /**
     * Bulk update settings.
     */
    public function bulkUpdate(Request $request, $etablissementId): JsonResponse
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            if (!$this->userHasAccess($request->user(), $etablissement)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }
            
            $validator = Validator::make($request->all(), [
                'settings' => 'required|array',
                'group' => 'nullable|string'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $group = $request->input('group', 'general');
            $settings = $request->input('settings', []);
            
            foreach ($settings as $key => $value) {
                Setting::updateOrCreate(
                    [
                        'etablissement_id' => $etablissement->id,
                        'group' => $group,
                        'key' => $key
                    ],
                    [
                        'value' => $value,
                        'type' => $this->getValueType($value)
                    ]
                );
            }
            
            $this->clearSettingsCache($etablissement->id);
            
            return response()->json([
                'success' => true,
                'message' => count($settings) . ' paramètre(s) mis à jour'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Bulk update error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour'
            ], 500);
        }
    }

    /**
     * Reset all settings to default.
     */
    public function reset(Request $request, $etablissementId): JsonResponse
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            if (!$this->userHasAccess($request->user(), $etablissement)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }
            
            // Supprimer tous les paramètres
            Setting::where('etablissement_id', $etablissement->id)->delete();
            
            // Créer les paramètres par défaut
            $defaultSettings = $this->getDefaultSettings($etablissement);
            
            foreach ($defaultSettings as $group => $settings) {
                foreach ($settings as $key => $value) {
                    Setting::create([
                        'etablissement_id' => $etablissement->id,
                        'group' => $group,
                        'key' => $key,
                        'value' => $value,
                        'type' => $this->getValueType($value)
                    ]);
                }
            }
            
            $this->clearSettingsCache($etablissement->id);
            
            return response()->json([
                'success' => true,
                'message' => 'Configuration réinitialisée avec succès'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Settings reset error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la réinitialisation'
            ], 500);
        }
    }

    /**
     * Get a specific setting.
     */
    public function get(Request $request, $etablissementId, $key): JsonResponse
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            if (!$this->userHasAccess($request->user(), $etablissement)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }
            
            $setting = Setting::where('etablissement_id', $etablissement->id)
                ->where('key', $key)
                ->first();
            
            return response()->json([
                'success' => true,
                'data' => $setting ? $setting->value : null
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération'
            ], 500);
        }
    }

    /**
     * Delete a specific setting.
     */
    public function destroy(Request $request, $etablissementId, $key): JsonResponse
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            if (!$this->userHasAccess($request->user(), $etablissement)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }
            
            Setting::where('etablissement_id', $etablissement->id)
                ->where('key', $key)
                ->delete();
            
            $this->clearSettingsCache($etablissement->id);
            
            return response()->json([
                'success' => true,
                'message' => 'Paramètre supprimé avec succès'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression'
            ], 500);
        }
    }

    /**
     * Export settings to JSON.
     */
    public function export(Request $request, $etablissementId)
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            if (!$this->userHasAccess($request->user(), $etablissement)) {
                abort(403, 'Accès non autorisé');
            }
            
            $settings = Setting::where('etablissement_id', $etablissement->id)
                ->get()
                ->groupBy('group')
                ->map(function ($group) {
                    return $group->pluck('value', 'key');
                });
            
            $filename = 'settings_' . $etablissement->slug . '_' . date('Y-m-d') . '.json';
            
            return response()->json($settings, 200, [
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Settings export error: ' . $e->getMessage());
            abort(500, 'Erreur lors de l\'export');
        }
    }

    /**
     * Import settings from JSON.
     */
    public function import(Request $request, $etablissementId): JsonResponse
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            if (!$this->userHasAccess($request->user(), $etablissement)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }
            
            $request->validate([
                'file' => 'required|file|mimes:json|max:2048'
            ]);
            
            $content = file_get_contents($request->file('file')->getPathname());
            $settings = json_decode($content, true);
            
            if (!is_array($settings)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Format JSON invalide'
                ], 422);
            }
            
            DB::beginTransaction();
            
            foreach ($settings as $group => $items) {
                foreach ($items as $key => $value) {
                    Setting::updateOrCreate(
                        [
                            'etablissement_id' => $etablissement->id,
                            'group' => $group,
                            'key' => $key
                        ],
                        [
                            'value' => $value,
                            'type' => $this->getValueType($value)
                        ]
                    );
                }
            }
            
            DB::commit();
            $this->clearSettingsCache($etablissement->id);
            
            return response()->json([
                'success' => true,
                'message' => 'Configuration importée avec succès'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Settings import error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'import: ' . $e->getMessage()
            ], 500);
        }
    }

    // ============================================
    // MÉTHODES PRIVÉES
    // ============================================

    /**
     * Get default settings.
     */
    protected function getDefaultSettings($etablissement): array
    {
        return [
            'general' => [
                'site_name' => $etablissement->name ?? 'Mon site',
                'site_slogan' => '',
                'site_description' => 'Site professionnel',
                'site_logo' => '',
                'site_favicon' => '',
                'contact_email' => $etablissement->email_contact ?? '',
                'notification_email' => '',
                'timezone' => 'Europe/Paris',
                'locale' => 'fr',
            ],
            'company' => [
                'address' => $etablissement->adresse ?? '',
                'zip_code' => $etablissement->zip_code ?? '',
                'city' => $etablissement->ville ?? '',
                'phone' => $etablissement->phone ?? '',
                'fax' => $etablissement->fax ?? '',
                'website' => $etablissement->website ?? '',
            ],
            'social' => [
                'facebook_url' => '',
                'twitter_url' => '',
                'instagram_url' => '',
                'linkedin_url' => '',
                'youtube_url' => '',
                'tiktok_url' => '',
                'pinterest_url' => '',
            ],
            'seo' => [
                'seo_title' => $etablissement->name ?? '',
                'seo_description' => '',
                'seo_keywords' => '',
                'google_analytics_id' => '',
                'google_verification' => '',
                'bing_verification' => '',
            ],
            'layout' => [
                'theme_id' => null,
                'container_width' => '1200px',
                'enable_header_sticky' => true,
                'enable_back_to_top' => true,
                'sidebar_position' => 'right',
            ],
            'newsletter' => [
                'enabled' => true,
                'api_key' => '',
                'list_id' => '',
                'double_optin' => true,
            ],
            'maintenance' => [
                'enabled' => false,
                'message' => 'Site en maintenance. Veuillez revenir plus tard.',
                'allowed_ips' => [],
            ],
        ];
    }

    /**
     * Get group from key.
     */
    protected function getGroupFromKey($key): string
    {
        $groups = [
            'site_' => 'general',
            'contact_' => 'general',
            'notification_' => 'general',
            'address' => 'company',
            'zip_' => 'company',
            'city' => 'company',
            'phone' => 'company',
            'fax' => 'company',
            'website' => 'company',
            'facebook' => 'social',
            'twitter' => 'social',
            'instagram' => 'social',
            'linkedin' => 'social',
            'youtube' => 'social',
            'tiktok' => 'social',
            'pinterest' => 'social',
            'seo_' => 'seo',
            'google_' => 'seo',
            'bing_' => 'seo',
            'theme_' => 'layout',
            'container_' => 'layout',
            'header_' => 'layout',
            'sidebar_' => 'layout',
            'newsletter_' => 'newsletter',
            'maintenance_' => 'maintenance',
        ];
        
        foreach ($groups as $prefix => $group) {
            if (str_starts_with($key, $prefix)) {
                return $group;
            }
        }
        
        return 'general';
    }

    /**
     * Get clean key without prefix.
     */
    protected function getCleanKey($key): string
    {
        // Enlever le préfixe si présent
        $prefixes = ['site_', 'contact_', 'notification_', 'seo_', 'google_', 'bing_', 'theme_', 'container_', 'header_', 'sidebar_', 'newsletter_', 'maintenance_'];
        
        foreach ($prefixes as $prefix) {
            if (str_starts_with($key, $prefix)) {
                return substr($key, strlen($prefix));
            }
        }
        
        return $key;
    }

    /**
     * Get value type.
     */
    protected function getValueType($value): string
    {
        if (is_bool($value)) {
            return 'boolean';
        }
        if (is_int($value)) {
            return 'integer';
        }
        if (is_float($value)) {
            return 'float';
        }
        if (is_array($value)) {
            return 'json';
        }
        return 'string';
    }

    /**
     * Clear settings cache.
     */
    protected function clearSettingsCache($etablissementId): void
    {
        Cache::forget("settings_{$etablissementId}");
        Cache::forget("settings_group_{$etablissementId}_*");
    }

    /**
     * Check if user has access to etablissement.
     */
    protected function userHasAccess($user, $etablissement): bool
    {
        if ($user->is_admin ?? false) {
            return true;
        }
        
        if (method_exists($user, 'etablissement') && $user->etablissement && $user->etablissement->id === $etablissement->id) {
            return true;
        }
        
        if (method_exists($user, 'etablissements') && $user->etablissements->contains($etablissement)) {
            return true;
        }
        
        return false;
    }

    /**
 * Get all SEO settings at once.
 */
public function getSeoSettings($etablissementId): JsonResponse
{
    try {
        $etablissement = Etablissement::findOrFail($etablissementId);
        
        $seoSettings = [
            'seo_title' => $etablissement->getSetting('seo_title', '', 'seo'),
            'seo_description' => $etablissement->getSetting('seo_description', '', 'seo'),
            'seo_keywords' => $etablissement->getSetting('seo_keywords', '', 'seo'),
            'google_analytics_id' => $etablissement->getSetting('google_analytics_id', '', 'seo'),
            'google_verification' => $etablissement->getSetting('google_verification', '', 'seo'),
            'bing_verification' => $etablissement->getSetting('bing_verification', '', 'seo'),
        ];
        
        return response()->json([
            'success' => true,
            'data' => $seoSettings
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la récupération'
        ], 500);
    }
}

/**
 * Preview SEO (simulateur de snippet Google).
 */
public function previewSeo(Request $request, $etablissementId): JsonResponse
{
    try {
        $title = $request->input('title');
        $description = $request->input('description');
        
        return response()->json([
            'success' => true,
            'preview' => [
                'title' => $this->truncateText($title, 60),
                'description' => $this->truncateText($description, 160),
                'url' => $request->input('url', 'https://example.com'),
                'breadcrumb' => $request->input('breadcrumb', 'example.com')
            ]
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur de prévisualisation'
        ], 500);
    }
}

/**
 * Generate robots.txt dynamically.
 */
public function robots($etablissementSlug)
{
    $etablissement = Etablissement::where('slug', $etablissementSlug)->firstOrFail();
    
    $customRobots = $etablissement->getSetting('robots_content', '', 'seo');
    
    if ($customRobots) {
        $content = $customRobots;
    } else {
        $content = $this->generateDefaultRobots($etablissement);
    }
    
    return response($content, 200, ['Content-Type' => 'text/plain']);
}

/**
 * Generate sitemap.xml dynamically.
 */
public function sitemap($etablissementSlug)
{
    $etablissement = Etablissement::where('slug', $etablissementSlug)->firstOrFail();
    
    $urls = $this->getSitemapUrls($etablissement);
    
    $xml = $this->generateSitemapXml($urls);
    
    return response($xml, 200, ['Content-Type' => 'application/xml']);
}

// ============================================
// MÉTHODES PRIVÉES POUR SEO
// ============================================

/**
 * Generate default robots.txt content.
 */
protected function generateDefaultRobots($etablissement): string
{
    $content = "User-agent: *\n";
    $content .= "Allow: /\n";
    $content .= "Disallow: /admin/\n";
    $content .= "Disallow: /api/\n";
    $content .= "Disallow: /login\n";
    $content .= "Disallow: /register\n";
    $content .= "Sitemap: " . url("/{$etablissement->slug}/sitemap.xml") . "\n";
    
    return $content;
}

/**
 * Get all URLs for sitemap.
 */
protected function getSitemapUrls($etablissement): array
{
    $urls = [];
    
    // Page d'accueil
    $urls[] = [
        'loc' => url("/{$etablissement->slug}"),
        'priority' => '1.0',
        'changefreq' => 'daily'
    ];
    
    // Récupérer les pages personnalisées
    $pages = Page::where('etablissement_id', $etablissement->id)
        ->where('is_published', true)
        ->get();
    
    foreach ($pages as $page) {
        $urls[] = [
            'loc' => url("/{$etablissement->slug}/{$page->slug}"),
            'priority' => '0.8',
            'changefreq' => 'weekly',
            'lastmod' => $page->updated_at->toIso8601String()
        ];
    }
    
    // Récupérer les articles de blog (si module existe)
    // $articles = Article::where('etablissement_id', $etablissement->id)->get();
    // ...
    
    return $urls;
}

/**
 * Generate sitemap XML.
 */
protected function generateSitemapXml($urls): string
{
    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    
    foreach ($urls as $url) {
        $xml .= '  <url>' . "\n";
        $xml .= '    <loc>' . htmlspecialchars($url['loc']) . '</loc>' . "\n";
        
        if (isset($url['lastmod'])) {
            $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . "\n";
        }
        
        if (isset($url['changefreq'])) {
            $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . "\n";
        }
        
        if (isset($url['priority'])) {
            $xml .= '    <priority>' . $url['priority'] . '</priority>' . "\n";
        }
        
        $xml .= '  </url>' . "\n";
    }
    
    $xml .= '</urlset>';
    
    return $xml;
}

/**
 * Truncate text for preview.
 */
protected function truncateText($text, $length): string
{
    if (strlen($text) <= $length) {
        return $text;
    }
    
    return substr($text, 0, $length - 3) . '...';
}
}