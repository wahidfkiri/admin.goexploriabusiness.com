<?php

namespace Vendor\Cms\Controllers\Admin;

use App\Http\Controllers\Controller;
use Vendor\Cms\Models\Page;
use Vendor\Cms\Models\Theme;
use Vendor\Cms\Models\Setting;
use Vendor\Cms\Models\Media;
use Illuminate\Http\Request;
use App\Models\Etablissement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Affiche le dashboard CMS
     */
    public function index(Request $request, $etablissementId)
    {
        try {
            // Récupérer l'établissement par son ID
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            // Vérifier que l'utilisateur a accès à cet établissement
            $user = Auth::user();
            // if (!$user || !$this->userHasAccessToEtablissement($user, $etablissement)) {
            //     abort(403, 'Accès non autorisé à cet établissement');
            // }
            
            // ============================================
            // STATISTIQUES PAGES
            // ============================================
            $pages = Page::where('etablissement_id', $etablissement->id);
            $allPages = Page::where('etablissement_id', $etablissement->id)->get();

            // Tous les thèmes globaux disponibles (pour l'onglet Thèmes du dashboard)
$allGlobalThemes = \Vendor\Cms\Models\Theme::orderBy('created_at', 'desc')->get();
 
// IDs des thèmes déjà liés à cet établissement
$myThemeIds = $etablissement->themes()->pluck('cms_themes.id')->toArray();
 
// Thème actif pour cet établissement
$activeTheme = $etablissement->themes()
    ->wherePivot('is_active', true)
    ->first();
            
            $stats = [
                // Statistiques pages
                'total_pages' => $pages->count(),
                'all_pages' => $allPages,
                'published_pages' => (clone $pages)->where('status', 'published')->count(),
                'draft_pages' => (clone $pages)->where('status', 'draft')->count(),
                'archived_pages' => (clone $pages)->where('status', 'archived')->count(),
                'recent_pages' => (clone $pages)->orderBy('updated_at', 'desc')->limit(5)->get(),
                
                // ============================================
                // STATISTIQUES THÈMES (NOUVELLE ARCHITECTURE)
                // ============================================
                
                'recent_themes' => $etablissement->themes()
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get(),


                     // ============================================
    // STATISTIQUES SEO (NOUVEAU)
    // ============================================
    'seo' => $this->getSeoStats($etablissement->id),

                
                // ============================================
                // STATISTIQUES MÉDIATHÈQUE
                // ============================================
                'media_count' => Media::where('etablissement_id', $etablissement->id)->count(),
                'images_count' => Media::where('etablissement_id', $etablissement->id)
                    ->where('type', 'image')
                    ->count(),
                'documents_count' => Media::where('etablissement_id', $etablissement->id)
                    ->where('type', 'document')
                    ->count(),
                'videos_count' => Media::where('etablissement_id', $etablissement->id)
                    ->where('type', 'video')
                    ->count(),
                'media_size' => $this->getTotalMediaSize($etablissement->id),
                'media' => Media::where('etablissement_id', $etablissement->id)
                    ->orderBy('created_at', 'desc')
                    ->limit(50)
                    ->get(),
                'folders' => $this->getMediaFolders($etablissement->id),
                'recent_medias' => Media::where('etablissement_id', $etablissement->id)
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get(),
                
                // ============================================
                // STATISTIQUES NEWSLETTER
                // ============================================
                'subscribers_count' => $this->getSubscribersCount($etablissement->id),
                'open_rate' => $this->getNewsletterOpenRate($etablissement->id),
                'click_rate' => $this->getNewsletterClickRate($etablissement->id),
                'unsubscribe_rate' => $this->getNewsletterUnsubscribeRate($etablissement->id),
                'subscribers' => $this->getRecentSubscribers($etablissement->id),
                
                // ============================================
                // STATISTIQUES COMMENTAIRES
                // ============================================
                'comments_count' => $this->getCommentsCount($etablissement->id),
                'pending_comments' => $this->getPendingCommentsCount($etablissement->id),
                'approved_comments' => $this->getApprovedCommentsCount($etablissement->id),
                'spam_comments' => $this->getSpamCommentsCount($etablissement->id),
                'comments' => $this->getRecentComments($etablissement->id),
                
                // ============================================
                // STATISTIQUES GÉNÉRALES
                // ============================================
                'page_views' => $this->getPageViews($etablissement->id),
                'disk_usage' => $this->getDiskUsage($etablissement->id),
                'last_backup' => $this->getLastBackupDate($etablissement->id),
                
                // Établissement et utilisateur
                'etablissement' => $etablissement,
                'user' => $user,
            ];

            
                    // Dans $stats, ajouter/remplacer :
                    $stats['total_themes']      = $allGlobalThemes->count();         // total global
                    $stats['all_global_themes'] = $allGlobalThemes;                  // tous les thèmes globaux
                    $stats['my_theme_ids']      = $myThemeIds;                       // thèmes liés à l'établissement
                    $stats['active_theme']      = $activeTheme;                      // thème actif
                    $stats['themes']            = $etablissement->themes()->get();   // thèmes liés (pour compat)
                    $stats['homepage']          = optional(
                         \Vendor\Cms\Models\Page::where('etablissement_id', $etablissement->id)
                           ->where('is_home', true)
                          ->first()
                        )->title ?? 'Non définie';
            
            return view('cms::admin.dashboard', compact('stats'));
            
        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage(), [
                'etablissement_id' => $etablissementId,
                'user_id' => Auth::id()
            ]);
            
            return redirect()->back()->with('error', 'Erreur lors du chargement du tableau de bord');
        }
    }

    /**
     * Récupère les statistiques en AJAX
     */
    public function stats(Request $request, $etablissementId)
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            // Vérifier l'accès
            if (!$this->userHasAccessToEtablissement(Auth::user(), $etablissement)) {
                return response()->json(['error' => 'Accès non autorisé'], 403);
            }
            
            $stats = [
                'pages' => [
                    'total' => Page::where('etablissement_id', $etablissement->id)->count(),
                    'published' => Page::where('etablissement_id', $etablissement->id)
                        ->where('status', 'published')
                        ->count(),
                    'drafts' => Page::where('etablissement_id', $etablissement->id)
                        ->where('status', 'draft')
                        ->count(),
                    'archived' => Page::where('etablissement_id', $etablissement->id)
                        ->where('status', 'archived')
                        ->count(),
                ],
                'themes' => [
                    'total' => $etablissement->themes()->count(),
                    'active' => $etablissement->themes()
                        ->wherePivot('is_active', true)
                        ->count(),
                ],
                'media' => [
                    'total' => Media::where('etablissement_id', $etablissement->id)->count(),
                    'images' => Media::where('etablissement_id', $etablissement->id)
                        ->where('type', 'image')
                        ->count(),
                    'documents' => Media::where('etablissement_id', $etablissement->id)
                        ->where('type', 'document')
                        ->count(),
                    'videos' => Media::where('etablissement_id', $etablissement->id)
                        ->where('type', 'video')
                        ->count(),
                    'size' => $this->getTotalMediaSize($etablissement->id),
                ],
                'subscribers' => $this->getSubscribersCount($etablissement->id),
                'comments' => $this->getCommentsCount($etablissement->id),
                'views' => $this->getPageViews($etablissement->id),
                'last_update' => now()->format('d/m/Y H:i:s'),
            ];
            
            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);
            
        } catch (\Exception $e) {
            Log::error('Stats error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors du chargement des statistiques'
            ], 500);
        }
    }

    // ============================================
    // MÉTHODES POUR LA MÉDIATHÈQUE
    // ============================================

    /**
     * Calculer la taille totale des médias
     */
    protected function getTotalMediaSize($etablissementId): string
    {
        $total = Media::where('etablissement_id', $etablissementId)->sum('size');
        return $this->formatBytes($total);
    }

    /**
     * Récupérer la liste des dossiers de médias
     */
    protected function getMediaFolders($etablissementId): array
    {
        $folders = Media::where('etablissement_id', $etablissementId)
            ->select('folder')
            ->distinct()
            ->pluck('folder')
            ->toArray();
        
        $result = [];
        foreach ($folders as $folder) {
            $parts = explode('/', trim($folder, '/'));
            $current = '';
            foreach ($parts as $part) {
                $current = $current ? $current . '/' . $part : $part;
                if (!in_array($current, $result)) {
                    $result[] = $current;
                }
            }
        }
        
        return $result;
    }

    // ============================================
    // MÉTHODES POUR LA NEWSLETTER
    // ============================================

    /**
     * Récupérer le nombre d'abonnés
     */
    protected function getSubscribersCount($etablissementId): int
    {
        // À implémenter selon votre système de newsletter
        return Cache::get("subscribers_count_{$etablissementId}", 0);
    }

    /**
     * Récupérer le taux d'ouverture de la newsletter
     */
    protected function getNewsletterOpenRate($etablissementId): float
    {
        return Cache::get("newsletter_open_rate_{$etablissementId}", 0);
    }

    /**
     * Récupérer le taux de clic de la newsletter
     */
    protected function getNewsletterClickRate($etablissementId): float
    {
        return Cache::get("newsletter_click_rate_{$etablissementId}", 0);
    }

    /**
     * Récupérer le taux de désabonnement de la newsletter
     */
    protected function getNewsletterUnsubscribeRate($etablissementId): float
    {
        return Cache::get("newsletter_unsubscribe_rate_{$etablissementId}", 0);
    }

    /**
     * Récupérer les abonnés récents
     */
    protected function getRecentSubscribers($etablissementId)
    {
        return collect([]);
    }

    // ============================================
    // MÉTHODES POUR LES COMMENTAIRES
    // ============================================

    /**
     * Récupérer le nombre total de commentaires
     */
    protected function getCommentsCount($etablissementId): int
    {
        return Cache::get("comments_count_{$etablissementId}", 0);
    }

    /**
     * Récupérer le nombre de commentaires en attente
     */
    protected function getPendingCommentsCount($etablissementId): int
    {
        return Cache::get("pending_comments_count_{$etablissementId}", 0);
    }

    /**
     * Récupérer le nombre de commentaires approuvés
     */
    protected function getApprovedCommentsCount($etablissementId): int
    {
        return Cache::get("approved_comments_count_{$etablissementId}", 0);
    }

    /**
     * Récupérer le nombre de commentaires spam
     */
    protected function getSpamCommentsCount($etablissementId): int
    {
        return Cache::get("spam_comments_count_{$etablissementId}", 0);
    }

    /**
     * Récupérer les commentaires récents
     */
    protected function getRecentComments($etablissementId)
    {
        return collect([]);
    }

    // ============================================
    // MÉTHODES DE CACHE
    // ============================================

    /**
     * Vider le cache
     */
    public function clearCache(Request $request, $etablissementId)
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            if (!$this->userHasAccessToEtablissement(Auth::user(), $etablissement)) {
                return response()->json(['error' => 'Accès non autorisé'], 403);
            }
            
            // Vider le cache des pages
            Cache::forget("page_{$etablissement->id}_*");
            Cache::forget("theme_{$etablissement->id}_*");
            Cache::forget("media_{$etablissement->id}_*");
            Cache::forget("subscribers_count_{$etablissement->id}");
            Cache::forget("comments_count_{$etablissement->id}");
            
            // Vider le cache général
            Cache::flush();
            
            return response()->json([
                'success' => true,
                'message' => 'Cache vidé avec succès'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Clear cache error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors du vidage du cache'
            ], 500);
        }
    }

    /**
     * Vider le cache des pages uniquement
     */
    public function clearPageCache(Request $request, $etablissementId)
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            if (!$this->userHasAccessToEtablissement(Auth::user(), $etablissement)) {
                return response()->json(['error' => 'Accès non autorisé'], 403);
            }
            
            Cache::forget("page_{$etablissement->id}_*");
            
            return response()->json([
                'success' => true,
                'message' => 'Cache des pages vidé avec succès'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors du vidage du cache'
            ], 500);
        }
    }

    /**
     * Vider le cache des thèmes
     */
    public function clearThemeCache(Request $request, $etablissementId)
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            if (!$this->userHasAccessToEtablissement(Auth::user(), $etablissement)) {
                return response()->json(['error' => 'Accès non autorisé'], 403);
            }
            
            Cache::forget("theme_{$etablissement->id}_*");
            
            return response()->json([
                'success' => true,
                'message' => 'Cache des thèmes vidé avec succès'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors du vidage du cache'
            ], 500);
        }
    }

    /**
     * Vider le cache des médias
     */
    public function clearMediaCache(Request $request, $etablissementId)
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            if (!$this->userHasAccessToEtablissement(Auth::user(), $etablissement)) {
                return response()->json(['error' => 'Accès non autorisé'], 403);
            }
            
            Cache::forget("media_{$etablissement->id}_*");
            
            return response()->json([
                'success' => true,
                'message' => 'Cache des médias vidé avec succès'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors du vidage du cache'
            ], 500);
        }
    }

    // ============================================
    // MÉTHODES DE MAINTENANCE
    // ============================================

    /**
     * Activer le mode maintenance
     */
    public function enableMaintenance(Request $request, $etablissementId)
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            if (!$this->userHasAccessToEtablissement(Auth::user(), $etablissement)) {
                return response()->json(['error' => 'Accès non autorisé'], 403);
            }
            
            Setting::updateOrCreate(
                [
                    'etablissement_id' => $etablissement->id,
                    'group' => 'maintenance',
                    'key' => 'enabled'
                ],
                ['value' => true]
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Mode maintenance activé'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de l\'activation'
            ], 500);
        }
    }

    /**
     * Désactiver le mode maintenance
     */
    public function disableMaintenance(Request $request, $etablissementId)
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            if (!$this->userHasAccessToEtablissement(Auth::user(), $etablissement)) {
                return response()->json(['error' => 'Accès non autorisé'], 403);
            }
            
            Setting::where('etablissement_id', $etablissement->id)
                ->where('group', 'maintenance')
                ->where('key', 'enabled')
                ->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Mode maintenance désactivé'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la désactivation'
            ], 500);
        }
    }

    // ============================================
    // MÉTHODES PROFIL
    // ============================================

    /**
     * Profil de l'utilisateur
     */
    public function profile(Request $request, $etablissementId)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        $user = Auth::user();
        
        return view('cms::admin.profile', compact('user', 'etablissement'));
    }

    /**
     * Mettre à jour le profil
     */
    public function updateProfile(Request $request, $etablissementId)
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            $user = Auth::user();
            
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
            ]);
            
            $user->update($request->only(['name', 'email']));
            
            return response()->json([
                'success' => true,
                'message' => 'Profil mis à jour avec succès'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la mise à jour'
            ], 500);
        }
    }

    /**
     * Mettre à jour le mot de passe
     */
    public function updatePassword(Request $request, $etablissementId)
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            $user = Auth::user();
            
            $request->validate([
                'current_password' => 'required|current_password',
                'password' => 'required|min:8|confirmed',
            ]);
            
            $user->update([
                'password' => bcrypt($request->password)
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Mot de passe mis à jour avec succès'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la mise à jour du mot de passe'
            ], 500);
        }
    }

    // ============================================
    // MÉTHODES UTILITAIRES
    // ============================================

    /**
     * Vérifier si l'utilisateur a accès à l'établissement
     */
    protected function userHasAccessToEtablissement($user, $etablissement): bool
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
     * Récupérer le nombre de vues des pages
     */
    protected function getPageViews($etablissementId): int
    {
        return Cache::get("page_views_{$etablissementId}", 0);
    }

    /**
     * Récupérer l'utilisation du disque
     */
    protected function getDiskUsage($etablissementId): array
    {
        // Nouveau chemin pour les thèmes (sans ID d'établissement)
        $themePath = storage_path("app/public/cms/themes");
        $mediaPath = storage_path("app/public/cms/media/{$etablissementId}");
        $size = 0;
        
        if (file_exists($themePath)) {
            $size += $this->getDirectorySize($themePath);
        }
        
        if (file_exists($mediaPath)) {
            $size += $this->getDirectorySize($mediaPath);
        }
        
        $totalSpace = disk_total_space('/');
        $freeSpace = disk_free_space('/');
        
        return [
            'used' => $this->formatBytes($size),
            'total' => $this->formatBytes($totalSpace),
            'free' => $this->formatBytes($freeSpace),
            'percentage' => $totalSpace > 0 ? round(($size / $totalSpace) * 100, 2) : 0
        ];
    }

    /**
     * Calculer la taille d'un dossier
     */
    protected function getDirectorySize($path): int
    {
        $size = 0;
        
        if (!file_exists($path)) {
            return 0;
        }
        
        $files = scandir($path);
        
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            
            $fullPath = $path . '/' . $file;
            
            if (is_dir($fullPath)) {
                $size += $this->getDirectorySize($fullPath);
            } else {
                $size += filesize($fullPath);
            }
        }
        
        return $size;
    }

    /**
     * Formater les bytes
     */
    protected function formatBytes($bytes, $precision = 2): string
    {
        if ($bytes == 0) return '0 B';
        
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Récupérer la date du dernier backup
     */
    protected function getLastBackupDate($etablissementId): ?string
    {
        $backupPath = storage_path("app/backups/{$etablissementId}");
        
        if (file_exists($backupPath)) {
            $files = scandir($backupPath);
            $backupFiles = array_filter($files, function($file) {
                return str_ends_with($file, '.zip');
            });
            
            if (!empty($backupFiles)) {
                $latest = max($backupFiles);
                return date('d/m/Y H:i', filemtime($backupPath . '/' . $latest));
            }
        }
        
        return null;
    }

    // Ajouter ces méthodes dans DashboardController

/**
 * Récupérer les statistiques SEO
 */
protected function getSeoStats($etablissementId): array
{
    return [
        // Métriques globales
        'total_pages_indexed' => $this->getIndexedPagesCount($etablissementId),
        'total_backlinks' => $this->getBacklinksCount($etablissementId),
        'domain_authority' => $this->getDomainAuthority($etablissementId),
        'page_authority' => $this->getPageAuthority($etablissementId),
        
        // Métriques Google
        'google_analytics_id' => $this->getSettingValue($etablissementId, 'google_analytics_id', 'seo'),
        'google_verification' => $this->getSettingValue($etablissementId, 'google_verification', 'seo'),
        'search_console_verified' => $this->isSearchConsoleVerified($etablissementId),
        
        // Trafic organique (à récupérer depuis GA ou autre source)
        'organic_traffic' => Cache::get("seo_organic_traffic_{$etablissementId}", [
            'today' => 0,
            'week' => 0,
            'month' => 0,
            'change' => 0
        ]),
        
        // Performance SEO
        'seo_score' => $this->calculateSeoScore($etablissementId),
        'missing_meta' => $this->getPagesMissingMeta($etablissementId),
        'broken_links' => $this->getBrokenLinksCount($etablissementId),
        
        // Sitemap & Robots
        'sitemap_exists' => $this->sitemapExists($etablissementId),
        'robots_exists' => $this->robotsExists($etablissementId),
        'sitemap_url' => $this->getSitemapUrl($etablissementId),
        'robots_url' => $this->getRobotsUrl($etablissementId),
        
        // Mots-clés
        'top_keywords' => $this->getTopKeywords($etablissementId),
        'keywords_count' => $this->getKeywordsCount($etablissementId),
        
        // Pages
        'pages_without_description' => $this->getPagesWithoutDescription($etablissementId),
        'pages_without_title' => $this->getPagesWithoutTitle($etablissementId),
        'duplicate_meta' => $this->getDuplicateMetaCount($etablissementId),
        
        // Performance technique
        'average_load_time' => $this->getAverageLoadTime($etablissementId),
        'mobile_friendly_score' => $this->getMobileFriendlyScore($etablissementId),
        
        // Statistiques récentes
        'weekly_stats' => $this->getWeeklySeoStats($etablissementId),
        'monthly_stats' => $this->getMonthlySeoStats($etablissementId),
    ];
}

/**
 * Calculer le score SEO global
 */
protected function calculateSeoScore($etablissementId): array
{
    $score = 0;
    $maxScore = 100;
    $checks = [];
    
    // Vérification 1: Meta title présent
    $hasTitle = $this->getSettingValue($etablissementId, 'seo_title', 'seo') != '';
    $score += $hasTitle ? 15 : 0;
    $checks['meta_title'] = $hasTitle;
    
    // Vérification 2: Meta description présent
    $hasDescription = $this->getSettingValue($etablissementId, 'seo_description', 'seo') != '';
    $score += $hasDescription ? 15 : 0;
    $checks['meta_description'] = $hasDescription;
    
    // Vérification 3: Google Analytics configuré
    $hasAnalytics = $this->getSettingValue($etablissementId, 'google_analytics_id', 'seo') != '';
    $score += $hasAnalytics ? 10 : 0;
    $checks['google_analytics'] = $hasAnalytics;
    
    // Vérification 4: Sitemap présent
    $hasSitemap = $this->sitemapExists($etablissementId);
    $score += $hasSitemap ? 10 : 0;
    $checks['sitemap'] = $hasSitemap;
    
    // Vérification 5: Robots.txt présent
    $hasRobots = $this->robotsExists($etablissementId);
    $score += $hasRobots ? 10 : 0;
    $checks['robots'] = $hasRobots;
    
    // Vérification 6: Pages avec meta (proportion)
    $totalPages = Page::where('etablissement_id', $etablissementId)->count();
    $pagesWithMeta = Page::where('etablissement_id', $etablissementId)
        ->whereNotNull('meta_title')
        ->where('meta_title', '!=', '')
        ->count();
    
    $metaRatio = $totalPages > 0 ? ($pagesWithMeta / $totalPages) * 20 : 0;
    $score += $metaRatio;
    $checks['pages_meta_ratio'] = round($metaRatio, 2);
    
    // Vérification 7: Images optimisées (exemple)
    $totalImages = Media::where('etablissement_id', $etablissementId)
        ->where('type', 'image')
        ->count();
    $optimizedImages = Media::where('etablissement_id', $etablissementId)
        ->where('type', 'image')
        ->where('optimized', true)
        ->count();
    
    $imageRatio = $totalImages > 0 ? ($optimizedImages / $totalImages) * 10 : 10;
    $score += $imageRatio;
    $checks['images_optimized'] = round($imageRatio, 2);
    
    // Vérification 8: Mobile friendly (à vérifier avec API externe ou config)
    $isMobileFriendly = $this->getMobileFriendlyScore($etablissementId) > 80;
    $score += $isMobileFriendly ? 10 : 0;
    $checks['mobile_friendly'] = $isMobileFriendly;
    
    return [
        'score' => round(min($score, $maxScore), 2),
        'max_score' => $maxScore,
        'percentage' => round(($score / $maxScore) * 100, 2),
        'grade' => $this->getSeoGrade($score),
        'checks' => $checks
    ];
}

/**
 * Obtenir le grade SEO
 */
protected function getSeoGrade($score): string
{
    if ($score >= 90) return 'A+';
    if ($score >= 80) return 'A';
    if ($score >= 70) return 'B';
    if ($score >= 60) return 'C';
    if ($score >= 50) return 'D';
    if ($score >= 40) return 'E';
    return 'F';
}

/**
 * Compter les pages indexées (simulation ou via API Search Console)
 */
protected function getIndexedPagesCount($etablissementId): int
{
    return Cache::remember("seo_indexed_pages_{$etablissementId}", 3600, function() use ($etablissementId) {
        // À implémenter avec Google Search Console API
        // Pour l'instant, retourner un nombre basé sur les pages publiées
        return Page::where('etablissement_id', $etablissementId)
            ->where('status', 'published')
            ->count();
    });
}

/**
 * Compter les pages sans meta description
 */
protected function getPagesWithoutDescription($etablissementId): int
{
    return Page::where('etablissement_id', $etablissementId)
        ->where(function($q) {
            $q->whereNull('meta_description')
              ->orWhere('meta_description', '');
        })
        ->count();
}

/**
 * Compter les pages sans meta title
 */
protected function getPagesWithoutTitle($etablissementId): int
{
    return Page::where('etablissement_id', $etablissementId)
        ->where(function($q) {
            $q->whereNull('meta_title')
              ->orWhere('meta_title', '');
        })
        ->count();
}

/**
 * Compter les meta descriptions dupliquées
 */
protected function getDuplicateMetaCount($etablissementId): int
{
    $duplicates = Page::where('etablissement_id', $etablissementId)
        ->select('meta_description', DB::raw('COUNT(*) as count'))
        ->whereNotNull('meta_description')
        ->where('meta_description', '!=', '')
        ->groupBy('meta_description')
        ->having('count', '>', 1)
        ->get();
    
    return $duplicates->sum('count');
}

/**
 * Récupérer la valeur d'un setting
 */
protected function getSettingValue($etablissementId, $key, $group = 'general')
{
    $setting = Setting::where('etablissement_id', $etablissementId)
        ->where('group', $group)
        ->where('key', $key)
        ->first();
    
    return $setting ? $setting->value : null;
}

/**
 * Vérifier si Search Console est vérifié
 */
protected function isSearchConsoleVerified($etablissementId): bool
{
    $verificationCode = $this->getSettingValue($etablissementId, 'google_verification', 'seo');
    return !empty($verificationCode);
}

/**
 * Vérifier si le sitemap existe
 */
protected function sitemapExists($etablissementId): bool
{
    $etablissement = Etablissement::find($etablissementId);
    if (!$etablissement) return false;
    
    $sitemapPath = storage_path("app/public/cms/sitemaps/{$etablissement->slug}_sitemap.xml");
    return file_exists($sitemapPath);
}

/**
 * Vérifier si robots.txt existe
 */
protected function robotsExists($etablissementId): bool
{
    $etablissement = Etablissement::find($etablissementId);
    if (!$etablissement) return false;
    
    $robotsPath = storage_path("app/public/cms/robots/{$etablissement->slug}_robots.txt");
    return file_exists($robotsPath);
}

/**
 * Obtenir l'URL du sitemap
 */
protected function getSitemapUrl($etablissementId): string
{
    $etablissement = Etablissement::find($etablissementId);
    if (!$etablissement) return '';
    
    return url("/{$etablissement->slug}/sitemap.xml");
}

/**
 * Obtenir l'URL de robots.txt
 */
protected function getRobotsUrl($etablissementId): string
{
    $etablissement = Etablissement::find($etablissementId);
    if (!$etablissement) return '';
    
    return url("/{$etablissement->slug}/robots.txt");
}

/**
 * Récupérer les top keywords (simulation)
 */
protected function getTopKeywords($etablissementId): array
{
    return Cache::remember("seo_top_keywords_{$etablissementId}", 3600, function() {
        // À implémenter avec Google Search Console API
        return [
            ['keyword' => 'mot clé principal', 'position' => 5, 'clicks' => 150, 'impressions' => 1200],
            ['keyword' => 'service premium', 'position' => 8, 'clicks' => 89, 'impressions' => 890],
            ['keyword' => 'solution innovante', 'position' => 12, 'clicks' => 45, 'impressions' => 560],
        ];
    });
}

/**
 * Compter les mots-clés (simulation)
 */
protected function getKeywordsCount($etablissementId): int
{
    return Cache::remember("seo_keywords_count_{$etablissementId}", 3600, function() {
        return rand(50, 500); // À remplacer par données réelles
    });
}

/**
 * Compter les backlinks (simulation)
 */
protected function getBacklinksCount($etablissementId): int
{
    return Cache::remember("seo_backlinks_{$etablissementId}", 7200, function() {
        return rand(0, 1000); // À remplacer par API (Ahrefs, SEMrush, etc.)
    });
}

/**
 * Obtenir le Domain Authority (simulation)
 */
protected function getDomainAuthority($etablissementId): int
{
    return Cache::remember("seo_da_{$etablissementId}", 7200, function() {
        return rand(0, 100); // À remplacer par Moz API
    });
}

/**
 * Obtenir le Page Authority (simulation)
 */
protected function getPageAuthority($etablissementId): int
{
    return Cache::remember("seo_pa_{$etablissementId}", 7200, function() {
        return rand(0, 100); // À remplacer par Moz API
    });
}

/**
 * Compter les liens brisés (simulation)
 */
protected function getBrokenLinksCount($etablissementId): int
{
    return Cache::remember("seo_broken_links_{$etablissementId}", 86400, function() {
        return rand(0, 50); // À implémenter avec crawler
    });
}

/**
 * Obtenir le temps de chargement moyen (simulation)
 */
protected function getAverageLoadTime($etablissementId): float
{
    return Cache::remember("seo_load_time_{$etablissementId}", 3600, function() {
        return round(rand(500, 3000) / 1000, 2); // En secondes
    });
}

/**
 * Obtenir le score mobile friendly (simulation)
 */
protected function getMobileFriendlyScore($etablissementId): int
{
    return Cache::remember("seo_mobile_score_{$etablissementId}", 86400, function() {
        return rand(50, 100); // À implémenter avec Google PageSpeed API
    });
}

/**
 * Récupérer les statistiques SEO hebdomadaires
 */
protected function getWeeklySeoStats($etablissementId): array
{
    $stats = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = now()->subDays($i);
        $stats[$date->format('Y-m-d')] = [
            'clicks' => rand(50, 500),
            'impressions' => rand(500, 5000),
            'ctr' => round(rand(1, 10) / 100, 2),
            'position' => rand(1, 50)
        ];
    }
    return $stats;
}

/**
 * Récupérer les statistiques SEO mensuelles
 */
protected function getMonthlySeoStats($etablissementId): array
{
    $stats = [];
    for ($i = 11; $i >= 0; $i--) {
        $date = now()->subMonths($i);
        $stats[$date->format('Y-m')] = [
            'clicks' => rand(1000, 10000),
            'impressions' => rand(10000, 100000),
            'ctr' => round(rand(1, 15) / 100, 2),
            'position' => rand(1, 30)
        ];
    }
    return $stats;
}

/**
 * Générer un rapport SEO complet
 */
public function generateSeoReport(Request $request, $etablissementId)
{
    try {
        $etablissement = Etablissement::findOrFail($etablissementId);
        
        $seoStats = $this->getSeoStats($etablissementId);
        
        // Générer le rapport en PDF ou JSON
        $report = [
            'generated_at' => now()->toIso8601String(),
            'etablissement' => $etablissement->name,
            'seo_stats' => $seoStats,
            'recommendations' => $this->generateSeoRecommendations($seoStats)
        ];
        
        return response()->json([
            'success' => true,
            'report' => $report
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la génération du rapport'
        ], 500);
    }
}

/**
 * Générer des recommandations SEO
 */
protected function generateSeoRecommendations($seoStats): array
{
    $recommendations = [];
    
    if (!$seoStats['checks']['meta_title']) {
        $recommendations[] = 'Ajouter un meta title par défaut pour votre site';
    }
    
    if (!$seoStats['checks']['meta_description']) {
        $recommendations[] = 'Ajouter une meta description par défaut';
    }
    
    if (!$seoStats['checks']['google_analytics']) {
        $recommendations[] = 'Configurer Google Analytics pour suivre votre trafic';
    }
    
    if (!$seoStats['checks']['sitemap']) {
        $recommendations[] = 'Générer un sitemap.xml pour faciliter l\'indexation';
    }
    
    if ($seoStats['pages_without_description'] > 0) {
        $recommendations[] = "{$seoStats['pages_without_description']} pages n'ont pas de meta description";
    }
    
    if ($seoStats['duplicate_meta'] > 0) {
        $recommendations[] = "{$seoStats['duplicate_meta']} meta descriptions dupliquées détectées";
    }
    
    return $recommendations;
}
}