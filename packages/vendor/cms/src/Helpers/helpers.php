<?php

if (!function_exists('getCurrentEtablissementId')) {
    /**
     * Récupère l'ID de l'établissement depuis l'URL /company/{etablissementId}
     *
     * @return int|null
     */
    function getCurrentEtablissementId()
    {
        // Priorité 1: Depuis le paramètre de route (URL /company/{etablissementId})
        if (request()->route('etablissementId')) {
            return (int) request()->route('etablissementId');
        }
        
        // Priorité 2: Depuis l'utilisateur authentifié
        if (auth()->check() && auth()->user()->etablissement) {
            return auth()->user()->etablissement->id;
        }
        
        // Priorité 3: Depuis la session
        if (session()->has('current_etablissement_id')) {
            return (int) session('current_etablissement_id');
        }
        
        // Priorité 4: Premier établissement
        $firstEtablissement = \App\Models\Etablissement::first();
        if ($firstEtablissement) {
            return $firstEtablissement->id;
        }
        
        return null;
    }
}

if (!function_exists('getCurrentEtablissement')) {
    /**
     * Récupère l'établissement courant depuis l'URL /company/{etablissementId}
     *
     * @return \App\Models\Etablissement|null
     */
    function getCurrentEtablissement()
    {
        $id = getCurrentEtablissementId();
        
        if ($id) {
            return \App\Models\Etablissement::find($id);
        }
        
        return null;
    }
}

if (!function_exists('getCurrentTheme')) {
    /**
     * Récupère le thème actif pour l'établissement courant
     *
     * @return \Vendor\Cms\Models\Theme|null
     */
    function getCurrentTheme()
    {
        $etablissement = getCurrentEtablissement();
        
        if (!$etablissement) {
            return null;
        }
        
        // Vérifier le paramètre GET preview_theme (priorité maximale)
        if (request()->has('preview_theme')) {
            $previewSlug = request()->get('preview_theme');
            $previewTheme = \Vendor\Cms\Models\Theme::where('slug', $previewSlug)->first();
            if ($previewTheme) {
                return $previewTheme;
            }
        }
        
        // Vérifier le mode prévisualisation en session
        if (session()->has('theme_preview_mode') && session('theme_preview_mode') === true) {
            $previewThemeId = session('preview_theme_id');
            if ($previewThemeId) {
                $previewTheme = \Vendor\Cms\Models\Theme::find($previewThemeId);
                if ($previewTheme) {
                    return $previewTheme;
                }
            }
        }
        
        // Thème actif pour l'établissement via la relation many-to-many
        $activeTheme = $etablissement->themes()
            ->wherePivot('is_active', true)
            ->first();
        
        // Fallback: premier thème lié à l'établissement
        if (!$activeTheme) {
            $activeTheme = $etablissement->themes()->first();
        }
        
        // Fallback ultime: thème par défaut global
        if (!$activeTheme) {
            $activeTheme = \Vendor\Cms\Models\Theme::where('is_default', true)->first();
        }
        
        return $activeTheme;
    }
}

if (!function_exists('getThemeStoragePath')) {
    /**
     * Get the full storage path for a theme.
     *
     * @param \Vendor\Cms\Models\Theme $theme
     * @return string
     */
    function getThemeStoragePath($theme)
    {
        // Nouveau chemin: storage/app/public/cms/themes/{slug}/
        return storage_path("app/public/cms/themes/{$theme->slug}");
    }
}

if (!function_exists('theme_asset')) {
    /**
     * Get the URL for a theme asset.
     *
     * @param string $path
     * @return string
     */
    function theme_asset($path)
    {
        $theme = getCurrentTheme();
        
        if (!$theme) {
            return asset($path);
        }
        
        // Nouvelle URL: /storage/cms/themes/{slug}/assets/{path}
        return url("/storage/cms/themes/{$theme->slug}/assets/" . ltrim($path, '/'));
    }
}

if (!function_exists('theme_path')) {
    /**
     * Get the physical path to a theme file.
     *
     * @param string $path
     * @return string
     */
    function theme_path($path = '')
    {
        $theme = getCurrentTheme();
        
        if (!$theme) {
            return storage_path('app/public/cms/themes/default/' . ltrim($path, '/'));
        }
        
        return storage_path("app/public/cms/themes/{$theme->slug}/" . ltrim($path, '/'));
    }
}

if (!function_exists('render_theme_view')) {
    /**
     * Render a theme view.
     *
     * @param string $view
     * @param array $data
     * @return \Illuminate\View\View
     */
    function render_theme_view($view, $data = [])
    {
        // Récupérer l'établissement depuis l'URL
        $etablissement = getCurrentEtablissement();
        
        if (!$etablissement) {
            return view($view, $data);
        }
        
        // Récupérer le thème actif
        $theme = getCurrentTheme();
        
        if (!$theme) {
            return view($view, $data);
        }
        
        $themePath = storage_path("app/public/cms/themes/{$theme->slug}");
        $viewPath = str_replace('.', '/', $view);
        
        if (!file_exists($themePath . '/' . $viewPath . '.blade.php')) {
            return view($view, $data);
        }
        
        // Ajouter un namespace temporaire
        $namespace = 'theme_' . $theme->slug;
        \Illuminate\Support\Facades\View::addNamespace($namespace, $themePath);
        
        $data['activeTheme'] = $theme;
        $data['etablissement'] = $etablissement;
        
        return view($namespace . '::' . $view, $data);
    }
}

if (!function_exists('theme_setting')) {
    /**
     * Get a theme setting.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function theme_setting($key, $default = null)
    {
        // Récupérer l'établissement depuis l'URL
        $etablissement = getCurrentEtablissement();
        
        if (!$etablissement) {
            return $default;
        }
        
        $setting = \Vendor\Cms\Models\Setting::where('etablissement_id', $etablissement->id)
            ->where('key', $key)
            ->first();
        
        return $setting ? $setting->value : $default;
    }
}

if (!function_exists('theme_menu')) {
    /**
     * Get the menu for the current theme from pages.
     *
     * @param string $menuName
     * @return array
     */
    function theme_menu($menuName = 'main_menu')
    {
        // Récupérer l'établissement depuis l'URL
        $etablissement = getCurrentEtablissement();
        
        if (!$etablissement) {
            return [];
        }
        
        // Vérifier si un menu personnalisé existe
        $customMenu = \Vendor\Cms\Models\Setting::where('etablissement_id', $etablissement->id)
            ->where('group', 'menu')
            ->where('key', $menuName)
            ->first();
        
        if ($customMenu && $customMenu->value) {
            return $customMenu->value;
        }
        
        // Générer le menu à partir des pages publiées
        $pages = \Vendor\Cms\Models\Page::where('etablissement_id', $etablissement->id)
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->orderBy('order', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();
        
        $menu = [];
        
        foreach ($pages as $page) {
            $menu[] = [
                'id' => $page->id,
                'label' => $page->title,
                'url' => '/company/' . $etablissement->id . '/page/' . $page->slug,
                'slug' => $page->slug,
                'active' => request()->route('slug') == $page->slug,
                'is_home' => $page->is_home,
                'target' => '_self',
                'icon' => $page->getMeta('menu_icon'),
                'children' => [],
            ];
        }
        
        // Si aucune page n'existe, menu par défaut
        if (empty($menu)) {
            return [
                [
                    'label' => 'Accueil',
                    'url' => '/company/' . $etablissement->id,
                    'slug' => 'home',
                    'active' => request()->route()->getName() == 'cms.company.home',
                    'is_home' => true,
                    'target' => '_self',
                    'icon' => null,
                    'children' => [],
                ],
                [
                    'label' => 'À propos',
                    'url' => '/company/' . $etablissement->id . '/page/about',
                    'slug' => 'about',
                    'active' => request()->route('slug') == 'about',
                    'is_home' => false,
                    'target' => '_self',
                    'icon' => null,
                    'children' => [],
                ],
                [
                    'label' => 'Services',
                    'url' => '/company/' . $etablissement->id . '/page/services',
                    'slug' => 'services',
                    'active' => request()->route('slug') == 'services',
                    'is_home' => false,
                    'target' => '_self',
                    'icon' => null,
                    'children' => [],
                ],
                [
                    'label' => 'Contact',
                    'url' => '/company/' . $etablissement->id . '/page/contact',
                    'slug' => 'contact',
                    'active' => request()->route('slug') == 'contact',
                    'is_home' => false,
                    'target' => '_self',
                    'icon' => null,
                    'children' => [],
                ],
            ];
        }
        
        return $menu;
    }
}

if (!function_exists('theme_has_menu')) {
    /**
     * Check if a menu exists.
     *
     * @param string $menuName
     * @return bool
     */
    function theme_has_menu($menuName = 'main_menu')
    {
        $menu = theme_menu($menuName);
        return !empty($menu);
    }
}

if (!function_exists('is_preview_mode')) {
    /**
     * Check if preview mode is active.
     *
     * @return bool
     */
    function is_preview_mode()
    {
        if (request()->has('preview_theme')) {
            return true;
        }
        
        if (session()->has('theme_preview_mode') && session('theme_preview_mode') === true) {
            return true;
        }
        
        return false;
    }
}

if (!function_exists('debug_current_theme')) {
    /**
     * Debug function to check current theme.
     *
     * @return array
     */
    function debug_current_theme()
    {
        $etablissement = getCurrentEtablissement();
        $theme = getCurrentTheme();
        
        return [
            'etablissement_id' => $etablissement ? $etablissement->id : null,
            'etablissement_name' => $etablissement ? $etablissement->name : null,
            'theme_id' => $theme ? $theme->id : null,
            'theme_name' => $theme ? $theme->name : null,
            'theme_slug' => $theme ? $theme->slug : null,
            'theme_path' => $theme ? $theme->path : null,
            'is_default' => $theme ? $theme->is_default : false,
            'preview_mode' => is_preview_mode(),
            'session_preview' => session('theme_preview_mode', false),
            'session_preview_id' => session('preview_theme_id'),
            'get_preview' => request()->get('preview_theme'),
            'route_etablissement' => request()->route('etablissementId'),
            'current_url' => request()->url(),
        ];
    }
}

if (!function_exists('get_etablissement_themes')) {
    /**
     * Get all themes for an etablissement.
     *
     * @param int|null $etablissementId
     * @return \Illuminate\Support\Collection
     */
    function get_etablissement_themes($etablissementId = null)
    {
        $etablissement = $etablissementId 
            ? \App\Models\Etablissement::find($etablissementId)
            : getCurrentEtablissement();
        
        if (!$etablissement) {
            return collect([]);
        }
        
        return $etablissement->themes()->get();
    }
}

if (!function_exists('get_etablissement_active_theme')) {
    /**
     * Get active theme for an etablissement.
     *
     * @param int|null $etablissementId
     * @return \Vendor\Cms\Models\Theme|null
     */
    function get_etablissement_active_theme($etablissementId = null)
    {
        $etablissement = $etablissementId 
            ? \App\Models\Etablissement::find($etablissementId)
            : getCurrentEtablissement();
        
        if (!$etablissement) {
            return null;
        }
        
        return $etablissement->themes()
            ->wherePivot('is_active', true)
            ->first();
    }
}

if (!function_exists('get_theme_by_slug')) {
    /**
     * Get a theme by its slug.
     *
     * @param string $slug
     * @return \Vendor\Cms\Models\Theme|null
     */
    function get_theme_by_slug($slug)
    {
        return \Vendor\Cms\Models\Theme::where('slug', $slug)->first();
    }
}