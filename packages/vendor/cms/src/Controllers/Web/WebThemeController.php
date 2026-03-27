<?php

namespace Vendor\Cms\Controllers\Web;

use App\Http\Controllers\Controller;
use Vendor\Cms\Models\Theme;
use Vendor\Cms\Models\Page;
use Vendor\Cms\Models\Setting;
use App\Models\Etablissement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class WebThemeController extends Controller
{
    protected $etablissement;
    protected $previewMode = false;

    /**
     * Constructeur - Récupère l'établissement depuis l'URL
     */
    public function __construct(Request $request, $etablissementId = null)
    {
        // Récupérer l'établissement uniquement depuis l'URL
        if ($etablissementId) {
            $this->etablissement = Etablissement::findOrFail($etablissementId);
        }
        
        // Vérifier le mode prévisualisation
        $this->checkPreviewMode($request);
    }

    /**
     * Affiche la page d'accueil avec le thème actif
     * Gère le paramètre GET preview_theme
     */
    public function home(Request $request, $etablissementId)
    {
        // Récupérer l'établissement
        $etablissement = Etablissement::findOrFail($etablissementId);
        $this->etablissement = $etablissement;
        
        // Vérifier le paramètre preview_theme dans l'URL (GET)
        $previewThemeSlug = $request->query('preview_theme');
        
        if ($previewThemeSlug) {
            // Chercher le thème par slug (sans condition d'établissement)
            $previewTheme = Theme::where('slug', $previewThemeSlug)->first();
            
            if ($previewTheme) {
                $this->previewMode = true;
                // Stocker en session pour les pages suivantes
                session([
                    'theme_preview_mode' => true,
                    'preview_theme_id' => $previewTheme->id,
                    'preview_theme_slug' => $previewTheme->slug
                ]);
                $theme = $previewTheme;
            } else {
                $theme = $this->getThemeToUse();
            }
        } 
        // Vérifier la session
        elseif (session()->has('theme_preview_mode') && session('theme_preview_mode') === true) {
            $this->previewMode = true;
            $previewThemeId = session('preview_theme_id');
            if ($previewThemeId) {
                $previewTheme = Theme::find($previewThemeId);
                if ($previewTheme) {
                    $theme = $previewTheme;
                } else {
                    $this->previewMode = false;
                    session()->forget(['theme_preview_mode', 'preview_theme_id', 'preview_theme_slug']);
                    $theme = $this->getThemeToUse();
                }
            } else {
                $theme = $this->getThemeToUse();
            }
        } 
        else {
            $theme = $this->getThemeToUse();
        }
        
        if (!$theme) {
            return $this->renderFallback('Aucun thème actif. Veuillez activer un thème.');
        }
        
        // Récupérer la page d'accueil
        $page = $this->getHomePage();
        
        if (!$page) {
            $page = $this->createDefaultHomePage();
        }
        
        return $this->renderTheme($theme, $page, $this->previewMode);
    }

    /**
     * Nettoyer le mode prévisualisation
     */
    public function clearPreview(Request $request, $etablissementId)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        
        // Nettoyer la session
        session()->forget(['theme_preview_mode', 'preview_theme_id', 'preview_theme_slug', 'preview_page_id', 'quick_preview']);
        
        // Rediriger vers la page d'accueil sans paramètre
        return redirect()->route('cms.company.home', ['etablissementId' => $etablissement->id]);
    }

    /**
     * Affiche une page avec le thème
     */
    public function showPage(Request $request, $etablissementId, $slug)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        $this->etablissement = $etablissement;
        
        $theme = $this->getThemeToUse();
        
        if (!$theme) {
            return $this->renderFallback('Aucun thème actif. Veuillez activer un thème.');
        }
        
        $page = Page::where('etablissement_id', $this->etablissement->id)
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();
        
        if (!$page) {
            abort(404, 'Page non trouvée');
        }
        
        return $this->renderTheme($theme, $page);
    }

    /**
     * Prévisualisation publique d'un thème (sans authentification)
     */
    public function publicPreview(Request $request, $etablissementId, $id)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        $this->etablissement = $etablissement;
        
        $theme = Theme::findOrFail($id);
        
        // Stocker en session pour la prévisualisation
        session([
            'theme_preview_mode' => true,
            'preview_theme_id' => $theme->id,
            'preview_theme_slug' => $theme->slug
        ]);
        
        // Récupérer la page d'accueil
        $page = $this->getHomePage();
        
        if (!$page) {
            $page = $this->createDefaultHomePage();
        }
        
        return $this->renderTheme($theme, $page, true);
    }

    /**
     * Prévisualisation d'une page avec un thème spécifique
     */
    public function previewPage(Request $request, $etablissementId, $themeId, $pageId)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        $this->etablissement = $etablissement;
        
        $theme = Theme::findOrFail($themeId);
        $page = Page::findOrFail($pageId);
        
        return $this->renderTheme($theme, $page, true);
    }

    /**
     * Liste des thèmes disponibles pour l'établissement
     */
    public function index(Request $request, $etablissementId)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        $this->etablissement = $etablissement;
        
        // Récupérer les thèmes liés à l'établissement
        $themes = $etablissement->themes()
            ->orderByPivot('is_active', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $activeTheme = $etablissement->themes()
            ->wherePivot('is_active', true)
            ->first();
        
        return view('cms::web.themes.index', compact('themes', 'activeTheme', 'etablissement'));
    }

    /**
     * Détails d'un thème
     */
    public function show(Request $request, $etablissementId, $id)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        $this->etablissement = $etablissement;
        
        $theme = Theme::findOrFail($id);
        
        $screenshots = $this->getThemeScreenshots($theme);
        $config = $this->getThemeConfig($theme);
        
        return view('cms::web.themes.show', compact('theme', 'screenshots', 'config', 'etablissement'));
    }

    /**
     * Aperçu rapide d'un thème
     */
    public function quickPreview(Request $request, $etablissementId, $id)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        $this->etablissement = $etablissement;
        
        $theme = Theme::findOrFail($id);
        
        // Récupérer le contenu de démonstration
        $demoContent = $this->getDemoContent($theme);
        
        return $this->renderTheme($theme, null, true, $demoContent);
    }

    /**
     * Assets du thème (CSS, JS, images)
     */
    public function asset($etablissementId, $themeId, $path)
    {
        try {
            $theme = Theme::findOrFail($themeId);
            
            $fullPath = $this->getThemePath($theme) . '/assets/' . $path;
            $fullPath = str_replace('\\', '/', $fullPath);
            
            if (!File::exists($fullPath)) {
                abort(404);
            }
            
            $file = File::get($fullPath);
            $mimeType = File::mimeType($fullPath);
            $cacheControl = 'public, max-age=31536000, immutable';
            
            return response($file, 200, [
                'Content-Type' => $mimeType,
                'Content-Length' => File::size($fullPath),
                'Cache-Control' => $cacheControl,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Theme asset error: ' . $e->getMessage());
            abort(404);
        }
    }

    /**
     * Téléchargement d'un thème
     */
    public function download(Request $request, $etablissementId, $id)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        $this->etablissement = $etablissement;
        
        $theme = Theme::findOrFail($id);
        
        $themePath = $this->getThemePath($theme);
        
        if (!File::exists($themePath)) {
            abort(404, 'Dossier du thème introuvable');
        }
        
        $zipFile = tempnam(sys_get_temp_dir(), 'theme_') . '.zip';
        $zip = new \ZipArchive();
        
        if ($zip->open($zipFile, \ZipArchive::CREATE) !== true) {
            abort(500, 'Impossible de créer l\'archive');
        }
        
        $this->addDirectoryToZip($zip, $themePath, '');
        $zip->close();
        
        return response()->download($zipFile, $theme->slug . '.zip')->deleteFileAfterSend(true);
    }

    /**
     * Sitemap XML
     */
    public function sitemap(Request $request, $etablissementId)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        $this->etablissement = $etablissement;
        
        $pages = Page::where('etablissement_id', $this->etablissement->id)
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->orderBy('updated_at', 'desc')
            ->get();
        
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        // Page d'accueil
        $sitemap .= '  <url>' . "\n";
        $sitemap .= '    <loc>' . e(url('/company/' . $etablissement->id)) . '</loc>' . "\n";
        $sitemap .= '    <priority>1.0</priority>' . "\n";
        $sitemap .= '  </url>' . "\n";
        
        foreach ($pages as $page) {
            $sitemap .= '  <url>' . "\n";
            $sitemap .= '    <loc>' . e(url('/company/' . $etablissement->id . '/page/' . $page->slug)) . '</loc>' . "\n";
            $sitemap .= '    <lastmod>' . $page->updated_at->format('Y-m-d') . '</lastmod>' . "\n";
            $sitemap .= '    <priority>0.8</priority>' . "\n";
            $sitemap .= '  </url>' . "\n";
        }
        
        $sitemap .= '</urlset>';
        
        return response($sitemap, 200, [
            'Content-Type' => 'application/xml',
            'Cache-Control' => 'public, max-age=3600'
        ]);
    }

    /**
     * Robots.txt
     */
    public function robots(Request $request, $etablissementId)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        $this->etablissement = $etablissement;
        
        $content = "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "Sitemap: " . url('/company/' . $etablissement->id . '/sitemap.xml') . "\n";
        
        // Disallow admin paths
        $content .= "Disallow: /admin/\n";
        $content .= "Disallow: /login\n";
        
        return response($content, 200, [
            'Content-Type' => 'text/plain',
            'Cache-Control' => 'public, max-age=86400'
        ]);
    }

    // ============================================
    // MÉTHODES PRIVÉES
    // ============================================

    /**
     * Rendu du thème
     */
    protected function renderTheme($theme, $page = null, $preview = false, $demoContent = null)
    {
        $cacheKey = $this->getCacheKey($theme, $page);
        
        if (!$preview && config('cms.cache_pages', false) && Cache::has($cacheKey)) {
            $html = Cache::get($cacheKey);
            return $this->buildResponse($html);
        }
        
        $themePath = $this->getThemePath($theme);
        
        if (!$themePath || !File::exists($themePath)) {
            return $this->renderFallback("Le thème '{$theme->name}' est introuvable.");
        }
        
        $layoutFile = $themePath . '/layout.blade.php';
        
        if (!File::exists($layoutFile)) {
            return $this->renderFallback("Le fichier layout.blade.php est manquant.");
        }
        
        try {
            $this->registerThemeNamespace($theme, $themePath);
            
            $viewData = $this->prepareViewData($theme, $page, $preview, $demoContent);
            
            $html = $this->renderView($viewData);
            
            if (!$preview && config('cms.cache_pages', false)) {
                Cache::put($cacheKey, $html, now()->addMinutes(config('cms.page_cache_lifetime', 60)));
            }
            
            return $this->buildResponse($html);
            
        } catch (\Exception $e) {
            Log::error('Theme rendering error: ' . $e->getMessage(), [
                'theme' => $theme->name,
                'exception' => $e
            ]);
            
            return $this->renderFallback("Erreur de rendu: " . $e->getMessage());
        }
    }

    /**
     * Enregistre le namespace du thème
     */
    protected function registerThemeNamespace($theme, $themePath)
    {
        View::addNamespace('theme', $themePath);
        View::addNamespace('theme_' . $theme->slug, $themePath);
    }

    /**
     * Prépare les données pour la vue
     */
    protected function prepareViewData($theme, $page, $preview = false, $demoContent = null)
    {
        return [
            'theme' => $theme,
            'page' => $page,
            'content' => $demoContent ?? ($page ? $page->content : ''),
            'etablissement' => $this->etablissement,
            'settings' => $this->getAllSettings(),
            'menu' => $this->getMenu(),
            'previewMode' => $preview,
            'assetBase' => url("/themes/{$theme->id}/assets"),
            'isPreview' => $preview,
        ];
    }

    /**
     * Rendu de la vue
     */
    protected function renderView($viewData)
    {
        if (View::exists('theme::layout')) {
            return view('theme::layout', $viewData)->render();
        }
        
        if (isset($viewData['theme']) && View::exists('theme_' . $viewData['theme']->slug . '::layout')) {
            return view('theme_' . $viewData['theme']->slug . '::layout', $viewData)->render();
        }
        
        throw new \Exception('Layout not found');
    }

    /**
     * Rendu fallback
     */
    protected function renderFallback($errorMessage = null)
    {
        $html = $this->getFallbackHtml($errorMessage);
        return $this->buildResponse($html);
    }

    /**
     * HTML fallback
     */
    protected function getFallbackHtml($errorMessage = null)
    {
        return '<!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>' . e($this->etablissement->name) . '</title>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f5f5f5; color: #333; line-height: 1.6; }
                .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 60px 0; text-align: center; margin-bottom: 40px; }
                .header h1 { font-size: 2.5rem; margin-bottom: 10px; }
                .content { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }
                .alert { background: #fff3cd; border: 1px solid #ffc107; padding: 15px; border-radius: 8px; margin-bottom: 20px; color: #856404; }
                .footer { text-align: center; padding: 20px; color: #666; border-top: 1px solid #eee; margin-top: 40px; }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="container">
                    <h1>' . e($this->etablissement->name) . '</h1>
                </div>
            </div>
            <div class="container">
                <div class="content">
                    ' . ($errorMessage ? '<div class="alert">⚠️ ' . e($errorMessage) . '</div>' : '') . '
                    <h2>Site en construction</h2>
                    <p>Notre site est actuellement en cours de configuration.</p>
                </div>
            </div>
            <div class="footer">
                <p>&copy; ' . date('Y') . ' ' . e($this->etablissement->name) . '</p>
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
        ]);
    }

    /**
     * Récupère le thème à utiliser
     */
    protected function getThemeToUse()
    {
        if ($this->previewMode && session()->has('theme_preview')) {
            $previewTheme = Theme::find(session('theme_preview'));
            if ($previewTheme) {
                return $previewTheme;
            }
        }
        
        // Récupérer le thème actif via la relation de l'établissement
        $activeTheme = $this->etablissement->themes()
            ->wherePivot('is_active', true)
            ->first();
        
        // Fallback: premier thème lié à l'établissement
        if (!$activeTheme) {
            $activeTheme = $this->etablissement->themes()->first();
        }
        
        return $activeTheme;
    }

    /**
     * Récupère la page d'accueil
     */
    protected function getHomePage()
    {
        $homePage = Page::where('etablissement_id', $this->etablissement->id)
            ->where('is_home', true)
            ->where('status', 'published')
            ->first();
        
        if (!$homePage) {
            $homePage = Page::where('etablissement_id', $this->etablissement->id)
                ->where('slug', 'home')
                ->where('status', 'published')
                ->first();
        }
        
        return $homePage;
    }

    /**
     * Crée une page d'accueil par défaut
     */
    protected function createDefaultHomePage()
    {
        return Page::create([
            'etablissement_id' => $this->etablissement->id,
            'title' => 'Accueil',
            'slug' => 'home',
            'content' => '<h1>Bienvenue</h1><p>Bienvenue sur notre site.</p>',
            'status' => 'published',
            'visibility' => 'public',
            'is_home' => true,
            'published_at' => now(),
        ]);
    }

    /**
     * Récupère le chemin du thème
     */
    protected function getThemePath($theme)
    {
        $path = $theme->path;
        
        if (strpos($path, storage_path()) === 0) {
            return rtrim($path, '/');
        }
        
        if (str_starts_with($path, 'app/')) {
            return rtrim(storage_path($path), '/');
        }
        
        return rtrim(storage_path('app/' . $path), '/');
    }

    /**
     * Récupère les captures d'écran du thème
     */
    protected function getThemeScreenshots($theme)
    {
        $screenshots = [];
        $screenshotDir = $this->getThemePath($theme) . '/assets/screenshots';
        
        if (File::exists($screenshotDir)) {
            $files = File::files($screenshotDir);
            foreach ($files as $file) {
                $screenshots[] = url("/themes/{$theme->id}/assets/screenshots/" . $file->getFilename());
            }
        }
        
        if (empty($screenshots) && $theme->preview_image) {
            $screenshots[] = $theme->getPreviewImageUrl();
        }
        
        return $screenshots;
    }

    /**
     * Récupère la configuration du thème
     */
    protected function getThemeConfig($theme)
    {
        $configFile = $this->getThemePath($theme) . '/theme.json';
        
        if (File::exists($configFile)) {
            return json_decode(File::get($configFile), true);
        }
        
        return [];
    }

    /**
     * Récupère le contenu de démonstration
     */
    protected function getDemoContent($theme)
    {
        $demoFile = $this->getThemePath($theme) . '/demo-content.html';
        
        if (File::exists($demoFile)) {
            return File::get($demoFile);
        }
        
        return '<h1>Contenu de démonstration</h1>
                <p>Ceci est un aperçu du thème avec du contenu de démonstration.</p>
                <p>Le thème n\'est pas encore activé sur votre site.</p>';
    }

    /**
     * Ajoute un dossier à une archive ZIP
     */
    protected function addDirectoryToZip($zip, $dir, $relativePath)
    {
        $files = File::files($dir);
        
        foreach ($files as $file) {
            $zip->addFile($file->getPathname(), $relativePath . $file->getFilename());
        }
        
        $directories = File::directories($dir);
        
        foreach ($directories as $subdir) {
            $subdirName = basename($subdir) . '/';
            $this->addDirectoryToZip($zip, $subdir, $relativePath . $subdirName);
        }
    }

    /**
     * Vérifie le mode prévisualisation
     */
    protected function checkPreviewMode(Request $request)
    {
        if ($request->has('preview_theme')) {
            $this->previewMode = true;
            session(['theme_preview' => $request->preview_theme]);
        }
        
        if (session()->has('theme_preview')) {
            $this->previewMode = true;
        }
    }

    /**
     * Récupère la clé de cache
     */
    protected function getCacheKey($theme, $page)
    {
        $key = "theme_{$this->etablissement->id}_{$theme->id}";
        
        if ($page) {
            $key .= "_page_{$page->id}";
        }
        
        if ($this->previewMode) {
            $key .= '_preview';
        }
        
        return $key;
    }

    /**
     * Récupère tous les paramètres
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
     * Récupère le menu à partir des pages publiées
     */
    protected function getMenu()
    {
        // Vérifier si un menu personnalisé existe dans les settings
        $customMenu = Setting::where('etablissement_id', $this->etablissement->id)
            ->where('group', 'menu')
            ->where('key', 'main_menu')
            ->first();
        
        if ($customMenu && $customMenu->value) {
            return $customMenu->value;
        }
        
        // Récupérer TOUTES les pages publiées
        $pages = Page::where('etablissement_id', $this->etablissement->id)
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->orderBy('created_at', 'asc')
            ->get();
        
        // S'IL Y A DES PAGES, les utiliser comme menu
        if ($pages->isNotEmpty()) {
            $menu = [];
            foreach ($pages as $page) {
                $menu[] = [
                    'id' => $page->id,
                    'label' => $page->title,
                    'url' => '/company/' . $this->etablissement->id . '/page/' . $page->slug,
                    'slug' => $page->slug,
                    'active' => request()->route('slug') == $page->slug,
                    'is_home' => $page->is_home,
                    'target' => '_self',
                    'icon' => $page->getMeta('menu_icon'),
                    'children' => [],
                ];
            }
            return $menu;
        }
        
        // SI AUCUNE PAGE, retourner le menu par défaut
        return [
            [
                'label' => 'Accueil',
                'url' => '/company/' . $this->etablissement->id,
                'slug' => 'home',
                'active' => request()->route()->getName() == 'cms.company.home',
                'is_home' => true,
                'target' => '_self',
                'icon' => null,
                'children' => [],
            ],
            [
                'label' => 'À propos',
                'url' => '/company/' . $this->etablissement->id . '/page/about',
                'slug' => 'about',
                'active' => request()->route('slug') == 'about',
                'is_home' => false,
                'target' => '_self',
                'icon' => null,
                'children' => [],
            ],
            [
                'label' => 'Services',
                'url' => '/company/' . $this->etablissement->id . '/page/services',
                'slug' => 'services',
                'active' => request()->route('slug') == 'services',
                'is_home' => false,
                'target' => '_self',
                'icon' => null,
                'children' => [],
            ],
            [
                'label' => 'Contact',
                'url' => '/company/' . $this->etablissement->id . '/page/contact',
                'slug' => 'contact',
                'active' => request()->route('slug') == 'contact',
                'is_home' => false,
                'target' => '_self',
                'icon' => null,
                'children' => [],
            ],
        ];
    }
}