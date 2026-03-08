<?php 

namespace Vendor\Administration\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\PageRevision;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuPageController extends Controller
{

// In your MenuController or wherever you're loading menus
public function index(Request $request)
{
    $query = Menu::with(['children' => function ($query) {
        $query->with(['children'])->orderBy('order');
    }])->orderBy('order');
    
    // Apply filters
    if ($request->has('search') && $request->search) {
        $query->where('title', 'like', '%' . $request->search . '%')
              ->orWhere('slug', 'like', '%' . $request->search . '%');
    }
    
    if ($request->has('type') && $request->type) {
        $query->where('type', $request->type);
    }
    
    if ($request->has('status') && $request->status) {
        $query->where('is_active', $request->status === 'active');
    }
    
    // For parent filter
    if ($request->has('parent')) {
        if ($request->parent === 'root') {
            $query->whereNull('parent_id');
        } elseif ($request->parent === 'child') {
            $query->whereNotNull('parent_id');
        }
    }
    
    // Sort
    $sortBy = $request->get('sort_by', 'order');
    $sortDirection = $request->get('sort_direction', 'asc');
    $query->orderBy($sortBy, $sortDirection);
    
    if ($request->ajax()) {
        // For tree view, get all menus with children
        if ($request->has('tree')) {
            $menus = $query->whereNull('parent_id')->get();
            
            return response()->json([
                'success' => true,
                'data' => $menus,
                'tree' => true
            ]);
        }
        
        // For table view, paginate
        $menus = $query->get();
        
        return response()->json([
            'success' => true,
            'data' => $menus,
            'current_page' => 1,
            'last_page' => 1,
            'total' => count($menus),
            'per_page' => count($menus),
            'message' => 'Menus loaded successfully'
        ]);
    }
    
    // For non-ajax requests
    $menus = $query->paginate(10);
    
    return view('administration::menus.index', compact('menus'));
}
    // Éditer la page d'un menu
    public function edit(Menu $menu)
{
    // Vérifier si le menu a une page
    if (!$menu->has_page) {
        // Version ultra-minimaliste (2 sections basiques)
        $pageContent = '<body>
    <!-- Section 1: Titre et Introduction -->
    <section id="section-1" class="simple-section">
        <div class="section-container">
            <h1>' . e($menu->title) . '</h1>
            <p>Cette page a été créée automatiquement. Utilisez l\'éditeur pour la personnaliser.</p>
        </div>
    </section>

    <!-- Section 2: Contenu Principal -->
    <section id="section-2" class="simple-section">
        <div class="section-container">
            <div class="content-block">
                <h2>Votre Contenu Ici</h2>
                <p>Ajoutez du texte, des images ou d\'autres éléments dans cette section.</p>
                <p>Vous pouvez supprimer, modifier ou ajouter de nouvelles sections selon vos besoins.</p>
            </div>
        </div>
    </section>
</body>';

        $pageStyles = '
        /* Styles très basiques pour GrapeJS */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
        }
        
        .simple-section {
            padding: 60px 20px;
        }
        
        .section-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        #section-1 {
            background: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
            color: white;
            text-align: center;
        }
        
        #section-1 h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
        }
        
        #section-1 p {
            font-size: 1.2em;
            max-width: 600px;
            margin: 0 auto;
        }
        
        #section-2 {
            background: white;
        }
        
        .content-block {
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        #section-2 h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 2em;
        }
        
        #section-2 p {
            line-height: 1.6;
            margin-bottom: 15px;
            color: #555;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .simple-section {
                padding: 40px 15px;
            }
            
            #section-1 h1 {
                font-size: 2em;
            }
            
            .content-block {
                padding: 25px;
            }
        }
        
        /* GrapeJS compatibility */
        [data-gjs-type="section"] {
            min-height: 100px;
        }
        
        [data-gjs-type="text"] {
            min-height: 20px;
        }
    ';

        $menu->update([
            'has_page' => true,
            'page_slug' => $menu->slug . '-' . Str::random(6),
            'page_content' => $pageContent,
            'page_styles' => $pageStyles,
            'page_meta' => [
                'title' => $menu->title,
                'description' => 'Page de ' . $menu->title,
                'keywords' => $menu->title . ', tourisme, voyage'
            ]
        ]);
        
        $menu->createRevision('Création initiale de la page');
    }
    
    return redirect('menus/template/edit/' . $menu->id);
}
    
    // Mettre à jour le contenu de la page
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'content' => 'required|string',
            'styles' => 'nullable|string',
            'meta' => 'nullable|array',
            'change_description' => 'nullable|string|max:255'
        ]);
        
        $menu->update([
            'page_content' => $request->content,
            'page_styles' => $request->styles,
            'page_meta' => $request->meta,
            'page_status' => 'draft' // Mettre en brouillon après modification
        ]);
        
        // Créer une révision
        $menu->createRevision($request->change_description);
        
        return response()->json([
            'success' => true,
            'message' => 'Page sauvegardée avec succès',
            'data' => [
                'status' => 'draft',
                'saved_at' => now()->format('d/m/Y H:i:s')
            ]
        ]);
    }
    
    // Publier la page
    public function publish(Request $request, Menu $menu)
    {
        $menu->update([
            'page_status' => 'published',
            'published_at' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Page publiée avec succès',
            'data' => [
                'status' => 'published',
                'published_at' => $menu->published_at
            ]
        ]);
    }
    
    // Dépublier la page
    public function unpublish(Request $request, Menu $menu)
    {
        $menu->update([
            'page_status' => 'draft'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Page retirée de la publication',
            'data' => [
                'status' => 'draft'
            ]
        ]);
    }
    
    // Prévisualiser la page
    public function preview(Menu $menu)
    {
        if (!$menu->has_page) {
            abort(404);
        }
        
        return view('pages.preview', compact('menu'));
    }
    
    // Activer/désactiver la page
    public function togglePage(Request $request, Menu $menu)
    {
        $menu->update([
            'has_page' => !$menu->has_page
        ]);
        
        $status = $menu->has_page ? 'activée' : 'désactivée';
        
        return response()->json([
            'success' => true,
            'message' => "Page {$status} avec succès",
            'data' => [
                'has_page' => $menu->has_page
            ]
        ]);
    }
    
    // Voir les révisions
    public function revisions(Menu $menu)
    {
        $revisions = $menu->pageRevisions()->with('user')->latest()->get();
        
        return response()->json([
            'success' => true,
            'data' => $revisions
        ]);
    }
    
    // Restaurer une révision
    public function restoreRevision(Request $request, Menu $menu, PageRevision $revision)
    {
        $menu->restoreRevision($revision);
        
        // Créer une nouvelle révision pour la restauration
        $menu->createRevision("Restauration de la version {$revision->version}");
        
        return response()->json([
            'success' => true,
            'message' => 'Révision restaurée avec succès'
        ]);
    }

    // app/Http\Controllers\Admin\MenuPageController.php
public function updateSettings(Request $request, Menu $menu)
{
    $request->validate([
        'title' => 'nullable|string|max:255',
        'slug' => 'nullable|string|max:255|unique:menus,page_slug,' . $menu->id,
        'config' => 'nullable|array'
    ]);
    
    $updates = [];
    
    if ($request->title) {
        $updates['page_meta->title'] = $request->title;
    }
    
    if ($request->slug) {
        $updates['page_slug'] = $request->slug;
    }
    
    if ($request->config) {
        $currentConfig = $menu->page_config ?? [];
        $updates['page_config'] = array_merge($currentConfig, $request->config);
    }
    
    $menu->update($updates);
    
    return response()->json([
        'success' => true,
        'message' => 'Paramètres mis à jour avec succès'
    ]);
}

public function updateSeo(Request $request, Menu $menu)
{
    $request->validate([
        'meta' => 'required|array'
    ]);
    
    $currentMeta = $menu->page_meta ?? [];
    $newMeta = array_merge($currentMeta, $request->meta);
    
    $menu->update([
        'page_meta' => $newMeta
    ]);
    
    return response()->json([
        'success' => true,
        'message' => 'Paramètres SEO mis à jour avec succès'
    ]);
}

public function previewRevision(Menu $menu, PageRevision $revision)
{
    if ($revision->menu_id !== $menu->id) {
        abort(404);
    }
    
    return view('admin.menus.revision-preview', compact('menu', 'revision'));
}
}