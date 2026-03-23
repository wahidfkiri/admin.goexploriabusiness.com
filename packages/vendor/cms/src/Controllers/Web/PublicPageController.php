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
use Illuminate\Support\Str;

class PublicPageController extends Controller
{
    use HasSettings;

    protected $etablissement;
    protected $activeTheme;

    public function __construct(Request $request)
    {
        // Récupérer l'établissement à partir du domaine ou paramètre
        $this->etablissement = Etablissement::first();
        // $this->resolveEtablissement($request);
        
        if ($this->etablissement) {
            $this->activeTheme = Theme::first();
            // where('etablissement_id', $this->etablissement->id)
            //      ->where('is_active', true)
            //     ->first();
            
            // Debug: Log du thème trouvé
            if ($this->activeTheme) {
                \Log::info('Theme found:', [
                    'id' => $this->activeTheme->id,
                    'name' => $this->activeTheme->name,
                    'slug' => $this->activeTheme->slug,
                    'path' => $this->activeTheme->path,
                    'full_path' => $this->activeTheme->getFullPath(),
                    'exists' => $this->activeTheme->exists()
                ]);
            }
            
            // Enregistrer le namespace du thème dynamiquement
            $this->registerThemeNamespace();
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
        
        \Log::info('Registering theme namespace:', [
            'path' => $themePath,
            'exists' => $themePath && File::exists($themePath)
        ]);
        
        if ($themePath && File::exists($themePath)) {
            // Enregistrer le namespace "theme" pour ce thème
            View::addNamespace('theme', $themePath);
            
            // Alternative: enregistrer un namespace spécifique par slug
            View::addNamespace('theme_' . $this->activeTheme->slug, $themePath);
        }
    }

    /**
     * Récupérer le chemin complet du thème
     * CORRIGÉ: Utiliser la nouvelle méthode getFullPath()
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
    public function home()
    {
        $homePage = null;
        
        // Vérifier si la colonne is_home existe
        if (\Illuminate\Support\Facades\Schema::connection('cms')->hasColumn('cms_pages', 'is_home')) {
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
    public function show($slug)
    {
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
        $cacheKey = "page_{$this->etablissement->id}_{$page->id}";
        
        if (config('cms.cache_pages') && Cache::has($cacheKey)) {
            $html = Cache::get($cacheKey);
        } else {
            $theme = $this->activeTheme;
            
            if (!$theme) {
                // Utiliser le thème par défaut
                $theme = Theme::where('is_default', true)->first();
            }
            
            // Si aucun thème n'est trouvé, afficher un contenu brut
            if (!$theme) {
                return $this->renderFallback($page, 'Aucun thème installé. Veuillez installer et activer un thème.');
            }
            
            // Récupérer le chemin du thème
            $themePath = $this->getThemePath();
            
            \Log::info('Rendering page with theme:', [
                'theme_name' => $theme->name,
                'theme_path' => $themePath,
                'exists' => $themePath && File::exists($themePath),
                'has_layout' => $themePath && File::exists($themePath . '/layout.blade.php')
            ]);
            
            if (!$themePath || !File::exists($themePath)) {
                return $this->renderFallback($page, "Le thème '{$theme->name}' est introuvable. Chemin: {$themePath}");
            }
            
            // Vérifier si le fichier layout existe
            $layoutFile = $themePath . '/layout.blade.php';
            
            if (!File::exists($layoutFile)) {
                return $this->renderFallback($page, "Le fichier layout.blade.php est manquant dans le thème '{$theme->name}'");
            }
            
            try {
                $viewData = [
                    'page' => $page,
                    'content' => $page->content,
                    'etablissement' => $this->etablissement,
                    'settings' => $this->getAllSettings(),
                    'menu' => $this->getMenu(),
                ];
                
                // Méthode 1: Utiliser le namespace enregistré
                if (View::exists('theme::layout')) {
                    $html = view('theme::layout', $viewData)->render();
                } 
                // Méthode 2: Utiliser le namespace spécifique
                elseif (View::exists('theme_' . $theme->slug . '::layout')) {
                    $html = view('theme_' . $theme->slug . '::layout', $viewData)->render();
                }
                // Méthode 3: Charger directement avec file_get_contents et Blade compiler
                else {
                    $html = $this->loadViewDirectly($themePath, $page, $viewData);
                }
            } catch (\Exception $e) {
                \Log::error('Theme rendering error: ' . $e->getMessage(), [
                    'theme' => $theme->name,
                    'path' => $themePath,
                    'exception' => $e
                ]);
                
                // En cas d'erreur, afficher le fallback
                return $this->renderFallback($page, "Erreur de rendu: " . $e->getMessage());
            }
            
            if (config('cms.cache_pages')) {
                Cache::put($cacheKey, $html, now()->addMinutes(config('cms.page_cache_lifetime')));
            }
        }
        
        return response($html);
    }

    /**
     * Charger une vue directement depuis le fichier en utilisant le compilateur Blade
     */
    protected function loadViewDirectly($themePath, $page, $viewData)
    {
        $layoutPath = $themePath . '/layout.blade.php';
        
        if (!File::exists($layoutPath)) {
            throw new \Exception("Layout file not found: {$layoutPath}");
        }
        
        // Lire le contenu du fichier
        $content = File::get($layoutPath);
        
        // Créer un nom de vue temporaire unique
        $tempViewName = 'temp_theme_' . md5($themePath);
        
        // Stocker le contenu dans un fichier temporaire dans storage/framework/views
        $compiledPath = storage_path('framework/views/' . $tempViewName . '.blade.php');
        
        // Copier le fichier dans le dossier des vues temporaires
        if (!File::exists(dirname($compiledPath))) {
            File::makeDirectory(dirname($compiledPath), 0755, true);
        }
        
        File::copy($layoutPath, $compiledPath);
        
        try {
            // Rendre la vue
            $html = view()->file($compiledPath, $viewData)->render();
            
            // Nettoyer
            if (File::exists($compiledPath)) {
                File::delete($compiledPath);
            }
            
            return $html;
        } catch (\Exception $e) {
            // Nettoyer en cas d'erreur
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
        $html = '<!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>' . e($page->title) . ' - ' . e($this->etablissement->name) . '</title>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; background: #f5f5f5; color: #333; line-height: 1.6; }
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
                <div class="content">';
        
        if ($errorMessage) {
            $html .= '<div class="alert">
                        <strong>⚠️ Attention:</strong> ' . e($errorMessage) . '
                      </div>';
        }
        
        $html .= '<div class="page-content">
                    {!! $page->content !!}
                  </div>
                </div>
            </div>
            <div class="footer">
                <p>&copy; ' . date('Y') . ' ' . e($this->etablissement->name) . '. Tous droits réservés.</p>
            </div>
        </body>
        </html>';
        
        return $html;
    }

    /**
     * Créer une page d'accueil par défaut
     */
    protected function createDefaultHomePage()
    {
        $page = Page::create([
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
        
        return $page;
    }

   /**
 * Rendu des assets du thème (CSS, JS, images)
 */
public function asset($etablissementId, $themeId, $path)
{
    try {
        // Récupérer le thème
        $theme = Theme::where('id', $themeId)
            ->where('etablissement_id', $etablissementId)
            ->firstOrFail();
        
        // Construire le chemin complet du fichier
        $fullPath = $theme->getFullPath();
        
        // Nettoyer le chemin
        $fullPath = rtrim($fullPath, '/');
        $path = ltrim($path, '/');
        
        $filePath = $fullPath . '/assets/' . $path;
        
        // Normaliser le chemin pour Windows
        $filePath = str_replace('\\', '/', $filePath);
        
        \Log::info('Asset request:', [
            'theme_id' => $themeId,
            'etablissement_id' => $etablissementId,
            'path' => $path,
            'full_path' => $filePath,
            'exists' => file_exists($filePath)
        ]);
        
        // Vérifier si le fichier existe
        if (!file_exists($filePath)) {
            \Log::warning('Asset not found: ' . $filePath);
            abort(404, 'Asset not found: ' . $path);
        }
        
        // Obtenir le contenu et le type MIME
        $file = file_get_contents($filePath);
        $mimeType = mime_content_type($filePath);
        
        // Déterminer le cache-control basé sur le type de fichier
        $cacheControl = 'public, max-age=31536000'; // 1 an pour les assets statiques
        
        // Pour les fichiers CSS/JS, ajouter un cache plus court en développement
        if (app()->environment('local')) {
            $cacheControl = 'no-cache, must-revalidate';
        }
        
        return response($file, 200, [
            'Content-Type' => $mimeType,
            'Content-Length' => filesize($filePath),
            'Cache-Control' => $cacheControl,
            'Accept-Ranges' => 'bytes',
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Asset error: ' . $e->getMessage(), [
            'theme_id' => $themeId,
            'etablissement_id' => $etablissementId,
            'path' => $path
        ]);
        abort(404, 'Asset not found');
    }
}

    /**
     * Page fallback pour les routes non trouvées
     */
    public function fallback()
    {
        $page404 = Page::where('etablissement_id', $this->etablissement->id)
            ->where('slug', '404')
            ->where('status', 'published')
            ->first();
        
        if ($page404) {
            return $this->renderPage($page404);
        }
        
        abort(404);
    }

    /**
     * Résoudre l'établissement à partir du domaine ou paramètre
     */
    protected function resolveEtablissement($request)
    {
        // Option 1: Par sous-domaine
        $host = $request->getHost();
        $subdomain = explode('.', $host)[0];
        
        $etablissement = Etablissement::first();
        
        if ($etablissement) {
            return $etablissement;
        }
        
        // Option 2: Par paramètre dans l'URL
        if ($request->has('etablissement')) {
            return Etablissement::where('slug', $request->etablissement)->first();
        }
        
        // Option 3: Établissement par défaut
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
        
        return $menuItems ? $menuItems->value : [];
    }
}