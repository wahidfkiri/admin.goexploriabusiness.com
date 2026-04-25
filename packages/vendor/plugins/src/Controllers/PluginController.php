<?php

namespace Vendor\Plugins\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Plugin;
use App\Models\PluginCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PluginController extends Controller
{
    /**
     * Display a listing of the plugins.
     */
    public function index()
    {
        $plugins = Plugin::with('category')->get();
        $categories = PluginCategory::orderBy('order')->get();
        
        $stats = [
            'total' => Plugin::count(),
            'active' => Plugin::where('status', 'active')->count(),
            'inactive' => Plugin::where('status', 'inactive')->count(),
            'updates' => 0, // À implémenter selon votre logique de mise à jour
            'free' => Plugin::where('price_type', 'free')->count(),
        ];
        
        return view('plugins::index', compact('plugins', 'categories', 'stats'));
    }

    /**
     * Get plugins data for AJAX.
     */
    public function getPlugins(Request $request)
    {
        $query = Plugin::with('category');
        
        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        // Filter by price
        if ($request->filled('price')) {
            $query->where('price_type', $request->price);
        }
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }
        
        // Sort
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        
        // Validate sort column to prevent SQL injection
        $allowedSorts = ['name', 'version', 'author', 'installed_at', 'downloads', 'rating', 'price', 'status'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'name';
        }
        
        $query->orderBy($sortBy, $sortOrder);
        
        // Pagination
        $perPage = $request->get('per_page', 9);
        $plugins = $query->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $plugins->items(),
            'total' => $plugins->total(),
            'current_page' => $plugins->currentPage(),
            'last_page' => $plugins->lastPage(),
            'per_page' => $plugins->perPage(),
        ]);
    }

    /**
     * Store a newly created plugin (manual addition).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:plugins,name',
            'description' => 'required|string',
            'version' => 'required|string|max:50',
            'author' => 'required|string|max:255',
            'author_website' => 'nullable|url',
            'price_type' => 'required|in:free,paid',
            'price' => 'required_if:price_type,paid|nullable|numeric|min:0',
            'type' => 'required|in:official,third-party,custom',
            'category_id' => 'required|exists:plugin_categories,id',
            'icon' => 'nullable|string|max:100',
            'documentation_url' => 'nullable|url',
            'demo_url' => 'nullable|url',
            'main_media_type' => 'nullable|in:image,video',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:8192|required_if:main_media_type,image',
            'main_video' => 'nullable|mimes:mp4,mov,avi,webm|max:51200|required_if:main_media_type,video',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:8192',
        ]);
        
        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = 'inactive';
        $validated['installed_at'] = now();
        $validated['rating'] = 0;
        $validated['rating_count'] = 0;
        $validated['downloads'] = 0;
        $validated['can_be_disabled'] = true;
        $validated['can_be_uninstalled'] = true;
        
        // Set default icon if not provided
        if (empty($validated['icon'])) {
            $validated['icon'] = 'fas fa-puzzle-piece';
        }

        $validated['main_media_type'] = $validated['main_media_type'] ?? null;
        $validated['main_image_path'] = null;
        $validated['main_video_path'] = null;
        $validated['gallery_images'] = [];

        if ($request->hasFile('main_image')) {
            $validated['main_image_path'] = $this->storeUploadedFile($request->file('main_image'), 'plugins/main/images');
        }

        if ($request->hasFile('main_video')) {
            $validated['main_video_path'] = $this->storeUploadedFile($request->file('main_video'), 'plugins/main/videos');
        }

        if ($request->hasFile('gallery_images')) {
            $gallery = [];
            foreach ($request->file('gallery_images') as $image) {
                $gallery[] = $this->storeUploadedFile($image, 'plugins/gallery');
            }
            $validated['gallery_images'] = $gallery;
        }

        unset($validated['main_image'], $validated['main_video']);
        
        $plugin = Plugin::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Module ajouté avec succès',
            'data' => $plugin->load('category')
        ]);
    }

    /**
     * Update the specified plugin.
     */
    public function update(Request $request, $id)
    {
        $plugin = Plugin::findOrFail($id);
        
        // Check if core module
        if ($plugin->type === 'core') {
            return response()->json([
                'success' => false,
                'message' => 'Le module système ne peut pas être modifié'
            ], 403);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:plugins,name,' . $id,
            'description' => 'required|string',
            'version' => 'required|string|max:50',
            'author' => 'required|string|max:255',
            'author_website' => 'nullable|url',
            'price_type' => 'required|in:free,paid',
            'price' => 'required_if:price_type,paid|nullable|numeric|min:0',
            'type' => 'required|in:official,third-party,custom',
            'category_id' => 'required|exists:plugin_categories,id',
            'icon' => 'nullable|string|max:100',
            'documentation_url' => 'nullable|url',
            'demo_url' => 'nullable|url',
            'main_media_type' => 'nullable|in:image,video',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:8192',
            'main_video' => 'nullable|mimes:mp4,mov,avi,webm|max:51200',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:8192',
            'clear_gallery' => 'nullable|boolean',
        ]);
        
        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('main_image')) {
            $this->deleteStoredFile($plugin->main_image_path);
            $validated['main_image_path'] = $this->storeUploadedFile($request->file('main_image'), 'plugins/main/images');
            $validated['main_media_type'] = 'image';
        }

        if ($request->hasFile('main_video')) {
            $this->deleteStoredFile($plugin->main_video_path);
            $validated['main_video_path'] = $this->storeUploadedFile($request->file('main_video'), 'plugins/main/videos');
            $validated['main_media_type'] = 'video';
        }

        if ($request->boolean('clear_gallery')) {
            foreach (($plugin->gallery_images ?? []) as $oldImage) {
                $this->deleteStoredFile($oldImage);
            }
            $validated['gallery_images'] = [];
        }

        if ($request->hasFile('gallery_images')) {
            $gallery = $plugin->gallery_images ?? [];
            foreach ($request->file('gallery_images') as $image) {
                $gallery[] = $this->storeUploadedFile($image, 'plugins/gallery');
            }
            $validated['gallery_images'] = $gallery;
        }

        unset($validated['main_image'], $validated['main_video']);
        
        $plugin->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Module mis à jour avec succès',
            'data' => $plugin->load('category')
        ]);
    }

    /**
     * Activate the specified plugin.
     */
    public function activate($id)
    {
        $plugin = Plugin::findOrFail($id);
        
        if (!$plugin->can_be_disabled && $plugin->status === 'inactive') {
            return response()->json([
                'success' => false,
                'message' => 'Ce module ne peut pas être activé'
            ], 403);
        }
        
        $plugin->update(['status' => 'active']);
        
        return response()->json([
            'success' => true,
            'message' => 'Module activé avec succès',
            'data' => $plugin
        ]);
    }

    /**
     * Deactivate the specified plugin.
     */
    public function deactivate($id)
    {
        $plugin = Plugin::findOrFail($id);
        
        if (!$plugin->can_be_disabled) {
            return response()->json([
                'success' => false,
                'message' => 'Ce module ne peut pas être désactivé'
            ], 403);
        }
        
        $plugin->update(['status' => 'inactive']);
        
        return response()->json([
            'success' => true,
            'message' => 'Module désactivé avec succès',
            'data' => $plugin
        ]);
    }

    /**
     * Remove the specified plugin.
     */
    public function destroy($id)
    {
        $plugin = Plugin::findOrFail($id);
        
        if (!$plugin->can_be_uninstalled) {
            return response()->json([
                'success' => false,
                'message' => 'Ce module ne peut pas être supprimé'
            ], 403);
        }
        
        if ($plugin->type === 'core') {
            return response()->json([
                'success' => false,
                'message' => 'Le module système ne peut pas être supprimé'
            ], 403);
        }
        
        $this->deleteStoredFile($plugin->main_image_path);
        $this->deleteStoredFile($plugin->main_video_path);
        foreach (($plugin->gallery_images ?? []) as $galleryImage) {
            $this->deleteStoredFile($galleryImage);
        }

        $plugin->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Module supprimé avec succès'
        ]);
    }

    /**
     * Get plugin settings.
     */
    public function getSettings($id)
    {
        $plugin = Plugin::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $plugin->settings ?? []
        ]);
    }

    /**
     * Update plugin settings.
     */
    public function updateSettings(Request $request, $id)
    {
        $plugin = Plugin::findOrFail($id);
        
        $validated = $request->validate([
            'settings' => 'required|array'
        ]);
        
        $plugin->update(['settings' => $validated['settings']]);
        
        return response()->json([
            'success' => true,
            'message' => 'Paramètres mis à jour avec succès',
            'data' => $plugin->settings
        ]);
    }

    /**
     * Get statistics for dashboard.
     */
    public function getStats()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total' => Plugin::count(),
                'active' => Plugin::where('status', 'active')->count(),
                'inactive' => Plugin::where('status', 'inactive')->count(),
                'pending' => Plugin::where('status', 'pending')->count(),
                'free' => Plugin::where('price_type', 'free')->count(),
                'paid' => Plugin::where('price_type', 'paid')->count(),
                'updates_available' => 0, // À implémenter
            ]
        ]);
    }

    /**
     * Get categories for filters.
     */
    public function getCategories()
    {
        $categories = PluginCategory::orderBy('order')->get();
        
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
 * Get active plugins for apps launcher.
 */
public function getActivePlugins(Request $request)
{
    $query = Plugin::with('category')
        ->where('status', 'active')
        ->where('type', '!=', 'core'); // Exclure les modules core si nécessaire
    
    // Search
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }
    
    $perPage = $request->get('per_page', 100);
    $plugins = $query->orderBy('name')->paginate($perPage);
    
    return response()->json([
        'success' => true,
        'data' => $plugins->items(),
        'total' => $plugins->total(),
    ]);
}

/**
 * Show plugin details page.
 */
public function show($id)
{
    $plugin = Plugin::with('category')->findOrFail($id);
    return view('plugins::show', compact('plugin'));
}

private function storeUploadedFile($file, $folder): string
{
    $path = $file->store($folder, 'public');
    return (string) $path;
}

private function deleteStoredFile(?string $path): void
{
    if (!$path) {
        return;
    }

    if (!Str::startsWith($path, ['http://', 'https://']) && Storage::disk('public')->exists($path)) {
        Storage::disk('public')->delete($path);
    }
}
}
