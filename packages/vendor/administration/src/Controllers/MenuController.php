<?php 

namespace Vendor\Administration\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    // Display menus
    // In your MenuController or wherever you're loading menus
public function index(Request $request)
{
    $query = Menu::with(['children' => function ($query) {
        $query->with(['children'])->orderBy('order');
    }])->where('menu_type', 'Accueil')->orderBy('order');
    
    // Apply filters
    // if ($request->has('search') && $request->search) {
    //     $query->where('title', 'like', '%' . $request->search . '%')
    //           ->orWhere('slug', 'like', '%' . $request->search . '%');
    // }
    
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
            'total' => $menus->count(),
            'per_page' => $menus->count()
        ]);
    }
    
    // For non-ajax requests
    $menus = $query->paginate(10);
    
    return view('administration::menus.index', compact('menus'));
}
    
    // Get statistics
    public function statistics()
    {
        $stats = [
            'total_menus' => Menu::where('menu_type', 'Accueil')->count(),
            'active_menus' => Menu::where('is_active', true)->where('menu_type', 'Accueil')->count(),
            'main_menus' => Menu::whereNull('parent_id')->where('menu_type', 'Accueil')->count(),
            'sub_menus' => Menu::whereNotNull('parent_id')->where('menu_type', 'Accueil')->count(),
        ];
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
    
    // Get categories for dropdown
    public function getCategories()
    {
        $categories = Category::select('id', 'name')
            ->orderBy('name')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }
    
    // Get activities for dropdown
    public function getActivities()
    {
        $activities = Activity::with('categoryRelation:id,name')
            ->select('id', 'name', 'categorie_id')
            ->orderBy('name')
            ->get()
            ->map(function($activity) {
                return [
                    'id' => $activity->id,
                    'name' => $activity->name,
                    'category' => $activity->categoryRelation->name ?? null
                ];
            });
        
        return response()->json([
            'success' => true,
            'data' => $activities
        ]);
    }
    
    // Get parent menus (for level 1)
    public function getParentMenus()
    {
        $parents = Menu::whereNull('parent_id')
            ->where('menu_type', 'Accueil')
            ->select('id', 'title')
            ->orderBy('order')
            ->orderBy('title')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $parents
        ]);
    }
    
    // Get sub-parent menus (for level 2)
    public function getSubParentMenus()
    {
        $parents = Menu::whereNotNull('parent_id')
            ->whereNull(DB::raw('(SELECT parent_id FROM menus as m2 WHERE m2.parent_id = menus.id LIMIT 1)'))
            ->where('menu_type', 'Accueil')
            ->select('id', 'title')
            ->orderBy('order')
            ->orderBy('title')
            ->get()
            ->map(function($menu) {
                $menu->title = $menu->title . ' (Sous-menu)';
                return $menu;
            });
        
        return response()->json([
            'success' => true,
            'data' => $parents
        ]);
    }
    
    // Get all parent menus for edit
    public function getAllParentMenus()
    {
        $parents = Menu::whereNull('parent_id')
            ->where('menu_type', 'Accueil')
            ->select('id', 'title')
            ->orderBy('order')
            ->orderBy('title')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $parents
        ]);
    }
    
    // Store menu
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:menus,slug',
            'type' => 'required|in:custom,category,activity',
            'parent_id' => 'nullable|exists:menus,id',
            'reference_id' => 'nullable',
            'order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);
        
        // Handle reference_id based on type
        $referenceId = null;
        if ($request->type === 'category') {
            $referenceId = $request->reference_id;
        } elseif ($request->type === 'activity') {
            $referenceId = $request->reference_id;
        }
        
        $menu = Menu::create([
            'title' => $request->title,
            'slug' => $request->slug,
            'type' => $request->type,
            'parent_id' => $request->parent_id,
            'reference_id' => $referenceId,
            'menu_type' => 'Accueil', // Default menu type, can be modified later
            'order' => $request->order ?? 0,
            'route' => $request->route,
            'url' => $request->url,
            'icon' => $request->icon,
            'is_active' => $request->is_active ?? true,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Menu créé avec succès',
            'data' => $menu
        ]);
    }
    
    // Edit menu
    public function edit(Menu $menu)
    {
        return response()->json([
            'success' => true,
            'data' => $menu->load(['parent', 'category', 'activity'])
        ]);
    }
    
    // Update menu
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:menus,slug,' . $menu->id,
            'type' => 'required|in:custom,category,activity',
            'parent_id' => 'nullable|exists:menus,id',
            'reference_id' => 'nullable',
            'order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);
        
        // Check if parent is valid (cannot be own child)
        if ($request->parent_id) {
            $childIds = $menu->children()->pluck('id')->toArray();
            if (in_array($request->parent_id, $childIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Un menu ne peut pas être parent de ses propres enfants'
                ], 422);
            }
        }
        
        // Handle reference_id based on type
        $referenceId = null;
        if ($request->type === 'category' || $request->type === 'activity') {
            $referenceId = $request->reference_id;
        }
        
        $menu->update([
            'title' => $request->title,
            'slug' => $request->slug,
            'type' => $request->type,
            'parent_id' => $request->parent_id,
            'reference_id' => $referenceId,
            'order' => $request->order ?? 0,
            'route' => $request->route,
            'url' => $request->url,
            'icon' => $request->icon,
            'is_active' => $request->is_active ?? true,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Menu mis à jour avec succès',
            'data' => $menu
        ]);
    }
    
    // Delete menu
    public function destroy(Menu $menu)
    {
        // Delete children recursively
        $this->deleteChildren($menu);
        
        $menu->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Menu supprimé avec succès'
        ]);
    }
    
    // Recursive delete children
    private function deleteChildren(Menu $menu)
    {
        foreach ($menu->children as $child) {
            $this->deleteChildren($child);
            $child->delete();
        }
    }
    
    // Move menu order
    public function move(Menu $menu, $direction)
    {
        $sibling = null;
        
        if ($direction === 'up') {
            $sibling = Menu::where('parent_id', $menu->parent_id)
                ->where('order', '<', $menu->order)
                ->orderBy('order', 'desc')
                ->first();
        } else {
            $sibling = Menu::where('parent_id', $menu->parent_id)
                ->where('order', '>', $menu->order)
                ->orderBy('order', 'asc')
                ->first();
        }
        
        if ($sibling) {
            $tempOrder = $menu->order;
            $menu->order = $sibling->order;
            $sibling->order = $tempOrder;
            
            $menu->save();
            $sibling->save();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Ordre mis à jour'
        ]);
    }
}