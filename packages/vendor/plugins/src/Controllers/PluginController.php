<?php

namespace Vendor\Plugins\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Plugin;
use App\Models\PluginCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class PluginController extends Controller
{
    /**
     * Display a listing of the plugins.
     */
    public function index()
    {
        $plugins = Plugin::with('category')->get();
        $categories = PluginCategory::all();
        
        $stats = [
            'total' => Plugin::count(),
            'active' => Plugin::where('status', 'active')->count(),
            'inactive' => Plugin::where('status', 'inactive')->count(),
            'updates' => Plugin::where('status', 'active')->where('version', '<', 'latest')->count(),
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
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }
        
        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }
        
        // Filter by price
        if ($request->has('price') && $request->price) {
            $query->where('price_type', $request->price);
        }
        
        // Search
        if ($request->has('search') && $request->search) {
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
        $query->orderBy($sortBy, $sortOrder);
        
        $plugins = $query->get();
        
        return response()->json([
            'success' => true,
            'data' => $plugins,
            'total' => $plugins->count()
        ]);
    }

    /**
     * Store a newly created plugin.
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
            'type' => 'required|in:core,official,third-party,custom',
            'category_id' => 'nullable|exists:plugin_categories,id',
            'icon' => 'nullable|string',
            'documentation_url' => 'nullable|url',
            'demo_url' => 'nullable|url',
        ]);
        
        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = 'pending';
        $validated['installed_at'] = now();
        
        $plugin = Plugin::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Module installé avec succès',
            'data' => $plugin->load('category')
        ]);
    }

    /**
     * Upload and install plugin from ZIP file.
     */
    public function uploadPlugin(Request $request)
    {
        $request->validate([
            'plugin_file' => 'required|file|mimes:zip|max:51200' // Max 50MB
        ]);
        
        $file = $request->file('plugin_file');
        $filename = $file->getClientOriginalName();
        
        // Store the file temporarily
        $path = $file->store('temp/plugins');
        
        // Extract and read manifest
        $zip = new ZipArchive();
        $zipPath = storage_path('app/' . $path);
        
        if ($zip->open($zipPath) === true) {
            // Look for manifest.json or plugin.json
            $manifest = null;
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                if (basename($filename) === 'manifest.json' || basename($filename) === 'plugin.json') {
                    $manifestContent = $zip->getFromName($filename);
                    $manifest = json_decode($manifestContent, true);
                    break;
                }
            }
            $zip->close();
            
            if (!$manifest) {
                Storage::delete($path);
                return response()->json([
                    'success' => false,
                    'message' => 'Fichier manifest.json ou plugin.json introuvable dans l\'archive'
                ], 400);
            }
            
            // Validate manifest
            $requiredFields = ['name', 'version', 'description', 'author'];
            foreach ($requiredFields as $field) {
                if (!isset($manifest[$field])) {
                    Storage::delete($path);
                    return response()->json([
                        'success' => false,
                        'message' => "Champ '{$field}' manquant dans le manifest"
                    ], 400);
                }
            }
            
            // Check if plugin already exists
            $existingPlugin = Plugin::where('slug', Str::slug($manifest['name']))->first();
            if ($existingPlugin) {
                Storage::delete($path);
                return response()->json([
                    'success' => false,
                    'message' => 'Un module avec ce nom existe déjà'
                ], 400);
            }
            
            // Create plugin record
            $plugin = Plugin::create([
                'name' => $manifest['name'],
                'slug' => Str::slug($manifest['name']),
                'description' => $manifest['description'],
                'version' => $manifest['version'],
                'author' => $manifest['author'],
                'author_website' => $manifest['author_website'] ?? null,
                'price_type' => $manifest['price_type'] ?? 'free',
                'price' => $manifest['price'] ?? null,
                'type' => $manifest['type'] ?? 'third-party',
                'status' => 'inactive',
                'category_id' => $manifest['category_id'] ?? null,
                'icon' => $manifest['icon'] ?? 'fas fa-puzzle-piece',
                'documentation_url' => $manifest['documentation_url'] ?? null,
                'demo_url' => $manifest['demo_url'] ?? null,
                'installed_at' => now(),
            ]);
            
            // Move file to plugins directory
            $pluginPath = storage_path('app/plugins/' . $plugin->slug);
            if (!is_dir($pluginPath)) {
                mkdir($pluginPath, 0755, true);
            }
            rename($zipPath, $pluginPath . '/plugin.zip');
            
            return response()->json([
                'success' => true,
                'message' => 'Module uploadé et installé avec succès',
                'data' => $plugin->load('category')
            ]);
        }
        
        Storage::delete($path);
        
        return response()->json([
            'success' => false,
            'message' => 'Impossible d\'extraire le fichier ZIP'
        ], 400);
    }

    /**
     * Update the specified plugin.
     */
    public function update(Request $request, $id)
    {
        $plugin = Plugin::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:plugins,name,' . $id,
            'description' => 'required|string',
            'version' => 'required|string|max:50',
            'author' => 'required|string|max:255',
            'author_website' => 'nullable|url',
            'price_type' => 'required|in:free,paid',
            'price' => 'required_if:price_type,paid|nullable|numeric|min:0',
            'type' => 'required|in:core,official,third-party,custom',
            'category_id' => 'nullable|exists:plugin_categories,id',
            'icon' => 'nullable|string',
            'documentation_url' => 'nullable|url',
            'demo_url' => 'nullable|url',
        ]);
        
        $validated['slug'] = Str::slug($validated['name']);
        
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
            ], 400);
        }
        
        $plugin->update(['status' => 'active']);
        
        // Trigger any activation hooks here
        event(new \App\Events\PluginActivated($plugin));
        
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
            ], 400);
        }
        
        $plugin->update(['status' => 'inactive']);
        
        // Trigger any deactivation hooks here
        event(new \App\Events\PluginDeactivated($plugin));
        
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
                'message' => 'Ce module ne peut pas être désinstallé'
            ], 400);
        }
        
        // Delete plugin files if they exist
        $pluginPath = storage_path('app/plugins/' . $plugin->slug);
        if (is_dir($pluginPath)) {
            $this->deleteDirectory($pluginPath);
        }
        
        $plugin->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Module désinstallé avec succès'
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
                'core' => Plugin::where('type', 'core')->count(),
                'official' => Plugin::where('type', 'official')->count(),
                'third_party' => Plugin::where('type', 'third-party')->count(),
                'custom' => Plugin::where('type', 'custom')->count(),
                'updates_available' => Plugin::where('status', 'active')
                    ->whereRaw('version < ?', ['latest'])->count(),
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
     * Helper method to delete directory recursively.
     */
    private function deleteDirectory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }
        
        if (!is_dir($dir)) {
            return unlink($dir);
        }
        
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            
            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }
        
        return rmdir($dir);
    }
}