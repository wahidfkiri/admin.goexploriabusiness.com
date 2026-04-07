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
}