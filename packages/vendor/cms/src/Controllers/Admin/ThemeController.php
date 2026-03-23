<?php

namespace Vendor\Cms\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Etablissement;
use Vendor\Cms\Models\Theme;
use Vendor\Cms\Services\ThemeService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ThemeController extends Controller
{
    protected $themeService;

    public function __construct(ThemeService $themeService)
    {
        $this->themeService = $themeService;
    }

    /**
     * Display a listing of themes.
     */
    public function index(Request $request)
    {
        $etablissement = Etablissement::first();
        
        if (!$etablissement) {
            abort(404, 'Aucun établissement trouvé');
        }
        
        $themes = Theme::where('etablissement_id', $etablissement->id)
            ->orderBy('is_active', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);
            
        $activeTheme = Theme::where('etablissement_id', $etablissement->id)
            ->where('is_active', true)
            ->first();
            
        $stats = [
            'total' => Theme::where('etablissement_id', $etablissement->id)->count(),
            'active' => $activeTheme ? 1 : 0,
            'available' => Theme::where('etablissement_id', $etablissement->id)
                ->where('is_active', false)
                ->count(),
        ];
        
        return view('cms::admin.themes.index', compact('themes', 'activeTheme', 'stats', 'etablissement'));
    }

    /**
     * Store a newly created theme.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'theme_file' => 'required|file|mimes:zip|max:10240',
            ]);
            
            $etablissement = Etablissement::first();
            
            if (!$etablissement) {
                throw new \Exception('Aucun établissement trouvé');
            }
            
            $theme = $this->themeService->uploadTheme(
                $request->file('theme_file'),
                $request->input('name'),
                $etablissement->id
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Thème uploadé avec succès',
                'theme' => $theme,
                'html' => view('cms::admin.themes.partials.theme-card', compact('theme'))->render()
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Theme upload error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'upload: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Activate a theme.
     */
    public function activate(Request $request, $id): JsonResponse
    {
        try {
            $etablissement = $request->user()->etablissement;
            
            $theme = Theme::where('id', $id)
                ->where('etablissement_id', $etablissement->id)
                ->firstOrFail();
                
            $theme->activate();
            
            return response()->json([
                'success' => true,
                'message' => 'Thème activé avec succès',
                'theme_id' => $theme->id
            ]);
            
        } catch (\Exception $e) {
            Log::error('Theme activation error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'activation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Deactivate a theme.
     */
    public function deactivate(Request $request, $id): JsonResponse
    {
        try {
            $etablissement = $request->user()->etablissement;
            
            $theme = Theme::where('id', $id)
                ->where('etablissement_id', $etablissement->id)
                ->firstOrFail();
                
            $theme->deactivate();
            
            return response()->json([
                'success' => true,
                'message' => 'Thème désactivé avec succès'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Theme deactivation error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la désactivation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a theme.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        try {
            $etablissement = $request->user()->etablissement;
            
            $theme = Theme::where('id', $id)
                ->firstOrFail();
                
            $this->themeService->deleteTheme($theme);
            
            return response()->json([
                'success' => true,
                'message' => 'Thème supprimé avec succès'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Theme deletion error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

   public function preview(Request $request, $id)
    {
        try {
            $etablissement = Etablissement::first();
            
            $theme = Theme::where('id', $id)
                ->where('etablissement_id', $etablissement->id)
                ->firstOrFail();
            
            // Store preview mode in session
            session([
                'theme_preview_mode' => true,
                'preview_theme_id' => $theme->id,
                'preview_theme_slug' => $theme->slug
            ]);
            
            // Redirect to home page with preview parameter
            return redirect()->to('/?preview_theme=' . $theme->slug);
            
        } catch (\Exception $e) {
            Log::error('Theme preview error: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'aperçu: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Erreur lors de l\'aperçu: ' . $e->getMessage());
        }
    }
    
    /**
     * Public preview without authentication (optional)
     */
    public function publicPreview(Request $request, $id)
    {
        try {
            $theme = Theme::findOrFail($id);
            
            // Store preview mode in session
            session([
                'theme_preview_mode' => true,
                'preview_theme_id' => $theme->id,
                'preview_theme_slug' => $theme->slug
            ]);
            
            // Redirect to home page with preview parameter
            return redirect()->to('/?preview_theme=' . $theme->slug);
            
        } catch (\Exception $e) {
            Log::error('Public theme preview error: ' . $e->getMessage());
            abort(404, 'Theme not found');
        }
    }

    /**
     * Update theme settings.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string|max:500',
                'config' => 'nullable|array',
            ]);
            
            $etablissement = $request->user()->etablissement;
            
            $theme = Theme::where('id', $id)
                ->where('etablissement_id', $etablissement->id)
                ->firstOrFail();
                
            $updateData = [];
            
            if ($request->has('name')) {
                $updateData['name'] = $request->input('name');
            }
            
            if ($request->has('description')) {
                $updateData['description'] = $request->input('description');
            }
            
            if ($request->has('config')) {
                $updateData['config'] = array_merge($theme->config ?? [], $request->input('config'));
            }
            
            $theme->update($updateData);
            
            return response()->json([
                'success' => true,
                'message' => 'Thème mis à jour avec succès',
                'theme' => $theme
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Theme update error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload preview image for theme.
     */
    public function uploadPreview(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'preview_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            
            $etablissement = $request->user()->etablissement;
            
            $theme = Theme::where('id', $id)
                ->where('etablissement_id', $etablissement->id)
                ->firstOrFail();
                
            $path = $request->file('preview_image')->store("themes/{$etablissement->id}/{$theme->slug}", 'public');
            
            $theme->update(['preview_image' => $path]);
            
            return response()->json([
                'success' => true,
                'message' => 'Image de prévisualisation uploadée avec succès',
                'url' => Storage::disk('public')->url($path)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Preview upload error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'upload: ' . $e->getMessage()
            ], 500);
        }
    }
}