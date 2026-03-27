<?php

namespace Vendor\Cms\Controllers\Web;

use App\Http\Controllers\Controller;
use Vendor\Cms\Models\Page;
use Vendor\Cms\Models\Theme;
use Vendor\Cms\Models\Setting;
use Vendor\Cms\Models\Traits\HasSettings;
use App\Models\Etablissement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PublicPageController extends Controller
{
    use HasSettings;

    protected $etablissement;
    protected $activeTheme;
    protected $previewMode = false;

    public function __construct(Request $request, $etablissementId = null)
    {
        // Récupérer l'établissement depuis l'URL
        if ($etablissementId) {
            $this->etablissement = Etablissement::findOrFail($etablissementId);
        } else {
            // Fallback pour la compatibilité
            $this->etablissement = $this->resolveEtablissement($request);
        }
        
        if (!$this->etablissement) {
            abort(404, 'Établissement non trouvé');
        }
        
        // Récupérer le thème actif
        $this->loadActiveTheme();
        
        // Vérifier le mode prévisualisation
        $this->checkPreviewMode($request);
        
        // Enregistrer le namespace du thème
        $this->registerThemeNamespace();
    }

    /**
     * Charge le thème actif
     */
    protected function loadActiveTheme()
    {
        // Vérifier le mode prévisualisation
        if ($this->previewMode && session()->has('preview_theme_id')) {
            $this->activeTheme = Theme::where('id', session('preview_theme_id'))
                ->where('etablissement_id', $this->etablissement->id)
                ->first();
        }
        
        // Sinon, prendre le thème actif
        if (!$this->activeTheme) {
            $this->activeTheme = Theme::where('etablissement_id', $this->etablissement->id)
                ->where('is_active', true)
                ->first();
        }
        
        // Log pour débogage
        if ($this->activeTheme) {
            \Log::info('Theme loaded:', [
                'id' => $this->activeTheme->id,
                'name' => $this->activeTheme->name,
                'slug' => $this->activeTheme->slug,
                'etablissement_id' => $this->etablissement->id,
                'preview_mode' => $this->previewMode
            ]);
        }
    }

    /**
     * Enregistrer le namespace du thème pour les vues
     */
    protected function registerThemeNamespace()
    {
        if (!$this->activeTheme) {
            return;
        }
        
        // Construire le chemin complet du thème
        $themePath = $this->getThemePath();
        
        if ($themePath && File::exists($themePath)) {
            // Enregistrer le namespace "theme" pour ce thème
            View::addNamespace('theme', $themePath);
            
            // Enregistrer un namespace spécifique par slug
            View::addNamespace('theme_' . $this->activeTheme->slug, $themePath);
        }
    }

    /**
     * Récupérer le chemin complet du thème
     */
    protected function getThemePath()
    {
        if (!$this->activeTheme) {
            return null;
        }
        
        // Utiliser la méthode getFullPath() du modèle
        $fullPath = $this->activeTheme->getFullPath();
        
        // Nettoyer les doubles slashes et les backslashes pour Windows
        $fullPath = str_replace('\\', '/', $fullPath);
        $fullPath = preg_replace('#/+#', '/', $fullPath);
        
        return $fullPath;
    }

    /**
     * Affiche la page d'accueil
     */
    public function home(Request $request, $etablissementId)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        $this->etablissement = $etablissement;
        
        // Recharger le thème pour cet établissement
        $this->loadActiveTheme();
        $this->registerThemeNamespace();
        
        $homePage = null;
        
        // Vérifier si la colonne is_home existe
        if (Schema::connection('cms')->hasColumn('cms_pages', 'is_home')) {
            $homePage = Page::where('etablissement_id', $this->etablissement->id)
                ->where('is_home', true)
                ->where('status', 'published')
                ->first();
        }

        if (!$homePage) {
            $homePage = Page::where('etablissement_id', $this->etablissement->id)
                ->where('slug', 'home')
                ->where('status', 'published')
                ->first();
        }
        
        // Si toujours pas de page, créer une page par défaut
        if (!$homePage) {
            $homePage = $this->createDefaultHomePage();
        }

        return $this->renderPage($homePage);
    }

    /**
     * Affiche une page par son slug
     */
    public function show(Request $request, $etablissementId, $slug)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        $this->etablissement = $etablissement;
        
        // Recharger le thème pour cet établissement
        $this->loadActiveTheme();
        $this->registerThemeNamespace();
        
        $page = Page::where('etablissement_id', $this->etablissement->id)
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return $this->renderPage($page);
    }

    /**
     * Rendu d'une page avec le thème
     */
    protected function renderPage($page)
    {
        $cacheKey = $this->getCacheKey($page);
        
        if (!$this->previewMode && config('cms.cache_pages', false) && Cache::has($cacheKey)) {
            $html = Cache::get($cacheKey);
            return $this->buildResponse($html);
        }
        
        $theme = $this->activeTheme;
        
        if (!$theme) {
            return $this->renderFallback($page, 'Aucun thème installé. Veuillez installer et activer un thème.');
        }
        
        // Récupérer le chemin du thème
        $themePath = $this->getThemePath();
        
        if (!$themePath || !File::exists($themePath)) {
            return $this->renderFallback($page, "Le thème '{$theme->name}' est introuvable.");
        }
        
        // Vérifier si le fichier layout existe
        $layoutFile = $themePath . '/layout.blade.php';
        
        if (!File::exists($layoutFile)) {
            return $this->renderFallback($page, "Le fichier layout.blade.php est manquant dans le thème '{$theme->name}'");
        }
        
        try {
            $viewData = $this->prepareViewData($page, $theme);
            
            // Méthode 1: Utiliser le namespace enregistré
            if (View::exists('theme::layout')) {
                $html = view('theme::layout', $viewData)->render();
            } 
            // Méthode 2: Utiliser le namespace spécifique
            elseif (View::exists('theme_' . $theme->slug . '::layout')) {
                $html = view('theme_' . $theme->slug . '::layout', $viewData)->render();
            }
            // Méthode 3: Charger directement le fichier
            else {
                $html = $this->loadViewDirectly($themePath, $viewData);
            }
            
            if (!$this->previewMode && config('cms.cache_pages', false)) {
                Cache::put($cacheKey, $html, now()->addMinutes(config('cms.page_cache_lifetime', 60)));
            }
            
            return $this->buildResponse($html);
            
        } catch (\Exception $e) {
            \Log::error('Theme rendering error: ' . $e->getMessage(), [
                'theme' => $theme->name,
                'path' => $themePath,
                'page_id' => $page->id,
                'exception' => $e
            ]);
            
            return $this->renderFallback($page, "Erreur de rendu: " . $e->getMessage());
        }
    }

    /**
     * Prépare les données pour la vue
     */
    protected function prepareViewData($page, $theme)
    {
        return [
            'page' => $page,
            'content' => $page->content,
            'etablissement' => $this->etablissement,
            'activeTheme' => $theme,
            'settings' => $this->getAllSettings(),
            'menu' => $this->getMenu(),
            'previewMode' => $this->previewMode,
            'assetBase' => url("/themes/{$this->etablissement->id}/{$theme->id}/assets"),
        ];
    }

    /**
     * Charger une vue directement depuis le fichier
     */
    protected function loadViewDirectly($themePath, $viewData)
    {
        $layoutPath = $themePath . '/layout.blade.php';
        
        if (!File::exists($layoutPath)) {
            throw new \Exception("Layout file not found: {$layoutPath}");
        }
        
        // Créer un nom de vue temporaire unique
        $tempViewName = 'temp_theme_' . md5($themePath);
        $compiledPath = storage_path('framework/views/' . $tempViewName . '.blade.php');
        
        if (!File::exists(dirname($compiledPath))) {
            File::makeDirectory(dirname($compiledPath), 0755, true);
        }
        
        File::copy($layoutPath, $compiledPath);
        
        try {
            $html = view()->file($compiledPath, $viewData)->render();
            if (File::exists($compiledPath)) {
                File::delete($compiledPath);
            }
            return $html;
        } catch (\Exception $e) {
            if (File::exists($compiledPath)) {
                File::delete($compiledPath);
            }
            throw $e;
        }
    }

    /**
     * Rendu fallback quand le thème n'est pas disponible
     */
    protected function renderFallback($page, $errorMessage = null)
    {
        $html = $this->getFallbackHtml($page, $errorMessage);
        return $this->buildResponse($html);
    }

    /**
     * HTML fallback
     */
    protected function getFallbackHtml($page, $errorMessage = null)
    {
        return '<!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>' . e($page->title) . ' - ' . e($this->etablissement->name) . '</title>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f5f5f5; color: #333; line-height: 1.6; }
                .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 60px 0; text-align: center; margin-bottom: 40px; }
                .header h1 { font-size: 2.5rem; margin-bottom: 10px; }
                .content { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 40px; }
                .alert { background: #fff3cd; border: 1px solid #ffc107; padding: 15px; border-radius: 8px; margin-bottom: 20px; color: #856404; }
                .footer { text-align: center; padding: 20px; color: #666; border-top: 1px solid #eee; }
                @media (max-width: 768px) {
                    .header { padding: 40px 0; }
                    .header h1 { font-size: 1.8rem; }
                    .content { padding: 20px; }
                }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="container">
                    <h1>' . e($this->etablissement->name) . '</h1>
                    <p>' . e($page->title) . '</p>
                </div>
            </div>
            <div class="container">
                <div class="content">' .
                ($errorMessage ? '<div class="alert"><strong>⚠️ Attention:</strong> ' . e($errorMessage) . '</div>' : '') .
                '<div class="page-content">' . $page->content . '</div>
                </div>
            </div>
            <div class="footer">
                <p>&copy; ' . date('Y') . ' ' . e($this->etablissement->name) . '. Tous droits réservés.</p>
            </div>
        </body>
        </html>';
    }

    /**
     * Construit la réponse HTTP
     */
    protected function buildResponse($html)
    {
        return response($html, 200, [
            'Content-Type' => 'text/html; charset=utf-8',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /**
     * Créer une page d'accueil par défaut
     */
    protected function createDefaultHomePage()
    {
        return Page::create([
            'etablissement_id' => $this->etablissement->id,
            'title' => 'Accueil',
            'slug' => 'home',
            'content' => '<h1>Bienvenue sur notre site</h1>
                          <p>Ceci est votre page d\'accueil par défaut. Vous pouvez modifier ce contenu depuis l\'interface d\'administration.</p>
                          <h2>Commencez à personnaliser votre site</h2>
                          <ul>
                              <li>Créez de nouvelles pages</li>
                              <li>Installez et activez un thème</li>
                              <li>Configurez les paramètres du site</li>
                          </ul>',
            'status' => 'published',
            'visibility' => 'public',
            'is_home' => true,
            'published_at' => now(),
        ]);
    }

    /**
     * Rendu des assets du thème (CSS, JS, images)
     */
    public function asset($etablissementId, $themeId, $path)
    {
        try {
            $theme = Theme::where('id', $themeId)
                ->where('etablissement_id', $etablissementId)
                ->firstOrFail();
            
            $fullPath = $theme->getFullPath();
            $fullPath = rtrim($fullPath, '/');
            $path = ltrim($path, '/');
            
            $filePath = $fullPath . '/assets/' . $path;
            $filePath = str_replace('\\', '/', $filePath);
            
            if (!file_exists($filePath)) {
                \Log::warning('Asset not found: ' . $filePath);
                abort(404);
            }
            
            $file = file_get_contents($filePath);
            $mimeType = mime_content_type($filePath);
            $cacheControl = app()->environment('local') ? 'no-cache' : 'public, max-age=31536000, immutable';
            
            return response($file, 200, [
                'Content-Type' => $mimeType,
                'Content-Length' => filesize($filePath),
                'Cache-Control' => $cacheControl,
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Asset error: ' . $e->getMessage());
            abort(404);
        }
    }

    /**
     * Page fallback pour les routes non trouvées
     */
    public function fallback(Request $request, $etablissementId = null)
    {
        if ($etablissementId) {
            $etablissement = Etablissement::find($etablissementId);
            if ($etablissement) {
                $this->etablissement = $etablissement;
                
                $page404 = Page::where('etablissement_id', $this->etablissement->id)
                    ->where('slug', '404')
                    ->where('status', 'published')
                    ->first();
                
                if ($page404) {
                    return $this->renderPage($page404);
                }
            }
        }
        
        abort(404);
    }

    /**
     * Vérifie le mode prévisualisation
     */
    protected function checkPreviewMode(Request $request)
    {
        if ($request->has('preview_theme')) {
            $this->previewMode = true;
            session(['preview_theme_id' => $request->preview_theme]);
        }
        
        if ($request->has('preview') && $request->preview == 'true') {
            $this->previewMode = true;
        }
        
        if (session()->has('preview_theme_id')) {
            $this->previewMode = true;
        }
        
        if (session()->has('page_preview')) {
            $this->previewMode = true;
        }
    }

    /**
     * Récupère la clé de cache
     */
    protected function getCacheKey($page)
    {
        $key = "page_{$this->etablissement->id}_{$page->id}";
        
        if ($this->previewMode) {
            $key .= '_preview';
        }
        
        return $key;
    }

    /**
     * Résoudre l'établissement
     */
    protected function resolveEtablissement($request)
    {
        // Par sous-domaine
        $host = $request->getHost();
        $subdomain = explode('.', $host)[0];
        
        // $etablissement = Etablissement::where('subdomain', $subdomain)->first();
        
        // if ($etablissement) {
        //     return $etablissement;
        // }
        
        // Par paramètre
        // if ($request->has('etablissement')) {
        //     return Etablissement::where('slug', $request->etablissement)->first();
        // }
        
        // Établissement par défaut
        return Etablissement::first();
    }

    /**
     * Récupérer tous les paramètres de l'établissement
     */
    protected function getAllSettings()
    {
        return Setting::where('etablissement_id', $this->etablissement->id)
            ->get()
            ->groupBy('group')
            ->map(function ($settings) {
                return $settings->pluck('value', 'key');
            });
    }

    /**
     * Récupérer le menu principal
     */
    protected function getMenu()
    {
        $menuItems = Setting::where('etablissement_id', $this->etablissement->id)
            ->where('group', 'menu')
            ->where('key', 'main_menu')
            ->first();
        
        if ($menuItems) {
            return $menuItems->value;
        }
        
        // Menu par défaut
        return [
            ['label' => 'Accueil', 'url' => '/company/' . $this->etablissement->id, 'active' => false],
            ['label' => 'À propos', 'url' => '/company/' . $this->etablissement->id . '/page/about', 'active' => false],
            ['label' => 'Services', 'url' => '/company/' . $this->etablissement->id . '/page/services', 'active' => false],
            ['label' => 'Contact', 'url' => '/company/' . $this->etablissement->id . '/page/contact', 'active' => false],
        ];
    }
}