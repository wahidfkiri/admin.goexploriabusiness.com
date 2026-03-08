<?php 

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class PublicPageController extends Controller
{
    // Afficher une page par son slug
    public function show($slug)
    {
        $menu = Menu::where('page_slug', $slug)
            ->where('has_page', true)
            ->where('page_status', 'published')
            ->firstOrFail();
        
        return $this->renderPage($menu);
    }
    
    // Afficher une page par l'ID du menu
    public function showByMenu(Menu $menu)
    {
        if (!$menu->has_page || $menu->page_status !== 'published') {
            abort(404);
        }
        
        return $this->renderPage($menu);
    }
    
    // Rendu de la page
    private function renderPage(Menu $menu)
    {
        // Si c'est un admin ou en mode préview, on peut voir les brouillons
        if (auth()->check() && auth()->user()->can('admin')) {
            // Les admins voient toujours le contenu
        } elseif ($menu->page_status !== 'published') {
            abort(404);
        }
        
        // Récupérer le contenu
        $content = $menu->page_content;
        $styles = $menu->page_styles;
        $meta = $menu->page_meta;
        
        // Injecter les variables dans le contenu
        $content = $this->injectVariables($content, $menu);
        
        return view('pages.show', compact('menu', 'content', 'styles', 'meta'));
    }
    
    // Injecter des variables dynamiques dans le contenu
    private function injectVariables($content, Menu $menu)
    {
        $variables = [
            '{{menu_title}}' => $menu->final_title,
            '{{menu_slug}}' => $menu->slug,
            '{{page_url}}' => $menu->page_url,
            '{{current_year}}' => date('Y'),
            '{{site_name}}' => config('app.name'),
        ];
        
        return str_replace(array_keys($variables), array_values($variables), $content);
    }
}