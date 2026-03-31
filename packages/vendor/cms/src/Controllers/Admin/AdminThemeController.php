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
use Illuminate\Support\Facades\Storage;

class AdminThemeController extends Controller
{
    protected $themeService;

    public function __construct(ThemeService $themeService)
    {
        $this->themeService = $themeService;
    }

    /**
     * Display a listing of themes for an etablissement.
     */
    public function index(Request $request, $etablissementId)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        
        // Récupérer tous les thèmes liés à cet établissement via la relation many-to-many
        $themes = $etablissement->themes()
            ->orderByPivot('is_active', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);
            
        $activeTheme = $etablissement->themes()
            ->wherePivot('is_active', true)
            ->first();
            
        $stats = [
            'total' => $etablissement->themes()->count(),
            'active' => $activeTheme ? 1 : 0,
            'available' => $etablissement->themes()
                ->wherePivot('is_active', false)
                ->count(),
        ];
        
        return view('cms::admin.themes.index', compact('themes', 'activeTheme', 'stats', 'etablissement'));
    }

    /**
     * Show the form for creating a new theme.
     */
    public function create(Request $request, $etablissementId)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        
        return view('cms::admin.themes.create', compact('etablissement'));
    }

    /**
     * Store a newly created theme and attach to etablissement.
     */
    public function store(Request $request, $etablissementId): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'theme_file' => 'required|file|mimes:zip|max:10240',
            ]);
            
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            // Upload du thème (ne contient plus d'établissement_id)
            $theme = $this->themeService->uploadTheme(
                $request->file('theme_file'),
                $request->input('name'),
                $etablissement->id
            );
            
            // Attacher le thème à l'établissement
            $isFirstTheme = $etablissement->themes()->count() === 0;
            
            $etablissement->themes()->attach($theme->id, [
                'is_active' => $isFirstTheme, // Premier thème = actif
                'config' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
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
     * Display the specified theme.
     */
    public function show(Request $request, $etablissementId, $id)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        
        $theme = Theme::findOrFail($id);
        
        // Vérifier que le thème est bien lié à l'établissement
        if (!$etablissement->themes()->where('theme_id', $id)->exists()) {
            abort(404, 'Thème non trouvé pour cet établissement');
        }
        
        return view('cms::admin.themes.show', compact('theme', 'etablissement'));
    }

    /**
     * Show the form for editing the specified theme.
     */
    public function edit(Request $request, $etablissementId, $id)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        
        $theme = Theme::findOrFail($id);
        
        if (!$etablissement->themes()->where('theme_id', $id)->exists()) {
            abort(404, 'Thème non trouvé pour cet établissement');
        }
        
        return view('cms::admin.themes.edit', compact('theme', 'etablissement'));
    }

   /**
 * Activate a theme for the etablissement.
 */
public function activate(Request $request, $etablissementId, $id): JsonResponse
{
    try {
        $etablissement = Etablissement::findOrFail($etablissementId);
        $theme = Theme::findOrFail($id);
        
        // Vérifier que le thème est lié à l'établissement
        if (!$etablissement->themes()->where('theme_id', $id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Ce thème n\'est pas disponible pour cet établissement'
            ], 400);
        }
        
        // Récupérer tous les IDs des thèmes liés à l'établissement
        $themeIds = $etablissement->themes()->pluck('cms_themes.id')->toArray();
        
        // Désactiver tous les thèmes de l'établissement
        if (!empty($themeIds)) {
            $etablissement->themes()->updateExistingPivot($themeIds, [
                'is_active' => false
            ]);
        }
        
        // Activer le thème sélectionné
        $etablissement->themes()->updateExistingPivot($id, [
            'is_active' => true
        ]);
        
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
 * Deactivate a theme for the etablissement.
 */
public function deactivate(Request $request, $etablissementId, $id): JsonResponse
{
    try {
        $etablissement = Etablissement::findOrFail($etablissementId);
        $theme = Theme::findOrFail($id);
        
        if (!$etablissement->themes()->where('theme_id', $id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Ce thème n\'est pas disponible pour cet établissement'
            ], 400);
        }
        
        // Désactiver le thème
        $etablissement->themes()->updateExistingPivot($id, [
            'is_active' => false
        ]);
        
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
     * Update theme settings.
     */
    public function update(Request $request, $etablissementId, $id): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string|max:500',
                'config' => 'nullable|array',
            ]);
            
            $etablissement = Etablissement::findOrFail($etablissementId);
            $theme = Theme::findOrFail($id);
            
            if (!$etablissement->themes()->where('theme_id', $id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce thème n\'est pas disponible pour cet établissement'
                ], 400);
            }
                
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
     * Delete a theme (remove from etablissement and delete files if no other etablissements use it).
     */
    public function destroy(Request $request, $etablissementId, $id): JsonResponse
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            $theme = Theme::findOrFail($id);
            
            // Vérifier que le thème est lié à l'établissement
            if (!$etablissement->themes()->where('theme_id', $id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce thème n\'est pas disponible pour cet établissement'
                ], 400);
            }
            
            // Vérifier si c'est le seul thème de l'établissement
            $themeCount = $etablissement->themes()->count();
            if ($themeCount <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous ne pouvez pas supprimer le dernier thème de cet établissement'
                ], 400);
            }
            
            // Détacher le thème de l'établissement
            $etablissement->themes()->detach($id);
            
            // Vérifier si d'autres établissements utilisent ce thème
            $otherEtablissements = $theme->etablissements()->count();
            
            // Si plus aucun établissement n'utilise ce thème, supprimer les fichiers
            if ($otherEtablissements === 0) {
                $this->themeService->deleteThemeFiles($theme);
                $theme->delete();
            }
            
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

    /**
     * Preview a theme (authenticated admin).
     */
    public function preview(Request $request, $etablissementId, $id)
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            $theme = Theme::findOrFail($id);
            
            // Vérifier que le thème est lié à l'établissement
            if (!$etablissement->themes()->where('theme_id', $id)->exists()) {
                abort(404, 'Thème non trouvé pour cet établissement');
            }
            
            // Stocker le mode prévisualisation en session
            session([
                'theme_preview_mode' => true,
                'preview_theme_id' => $theme->id,
                'preview_theme_slug' => $theme->slug
            ]);
            
            // Redirection vers la page d'accueil de l'établissement
            $redirectUrl = url('/company/' . $etablissement->id . '?preview_theme=' . $theme->slug);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'redirect_url' => $redirectUrl,
                    'message' => 'Prévisualisation du thème'
                ]);
            }
            
            return redirect()->to($redirectUrl);
            
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
     * Public preview without authentication.
     */
    public function publicPreview(Request $request, $etablissementId, $id)
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            $theme = Theme::findOrFail($id);
            
            // Stocker le mode prévisualisation en session
            session([
                'theme_preview_mode' => true,
                'preview_theme_id' => $theme->id,
                'preview_theme_slug' => $theme->slug
            ]);
            
            $redirectUrl = url('/company/' . $etablissement->id . '?preview_theme=' . $theme->slug);
            
            return redirect()->to($redirectUrl);
            
        } catch (\Exception $e) {
            Log::error('Public theme preview error: ' . $e->getMessage());
            abort(404, 'Theme not found');
        }
    }

    /**
     * Upload preview image for theme.
     */
    public function uploadPreview(Request $request, $etablissementId, $id): JsonResponse
    {
        try {
            $request->validate([
                'preview_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            
            $etablissement = Etablissement::findOrFail($etablissementId);
            $theme = Theme::findOrFail($id);
            
            if (!$etablissement->themes()->where('theme_id', $id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce thème n\'est pas disponible pour cet établissement'
                ], 400);
            }
            
            $path = $request->file('preview_image')->store("themes/{$theme->slug}", 'public');
            
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

    /**
     * Delete preview image for theme.
     */
    public function deletePreview(Request $request, $etablissementId, $id): JsonResponse
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            $theme = Theme::findOrFail($id);
            
            if (!$etablissement->themes()->where('theme_id', $id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce thème n\'est pas disponible pour cet établissement'
                ], 400);
            }
            
            if ($theme->preview_image && Storage::disk('public')->exists($theme->preview_image)) {
                Storage::disk('public')->delete($theme->preview_image);
            }
            
            $theme->update(['preview_image' => null]);
            
            return response()->json([
                'success' => true,
                'message' => 'Image de prévisualisation supprimée avec succès'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Preview delete error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Duplicate a theme for the etablissement.
     */
    public function duplicate(Request $request, $etablissementId, $id): JsonResponse
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            $theme = Theme::findOrFail($id);
            
            if (!$etablissement->themes()->where('theme_id', $id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce thème n\'est pas disponible pour cet établissement'
                ], 400);
            }
            
            $newName = $theme->name . ' (copie)';
            $duplicate = $this->themeService->duplicateTheme($theme, $newName);
            
            // Attacher le nouveau thème à l'établissement
            $etablissement->themes()->attach($duplicate->id, [
                'is_active' => false,
                'config' => null,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Thème dupliqué avec succès',
                'theme' => $duplicate
            ]);
            
        } catch (\Exception $e) {
            Log::error('Theme duplicate error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la duplication: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete themes.
     */
    public function bulkDelete(Request $request, $etablissementId): JsonResponse
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            $ids = $request->input('ids', []);
            
            if (empty($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun thème sélectionné'
                ], 400);
            }
            
            $themes = Theme::whereIn('id', $ids)->get();
            
            // Vérifier le thème actif
            $activeTheme = $etablissement->themes()
                ->wherePivot('is_active', true)
                ->first();
            
            if ($activeTheme && in_array($activeTheme->id, $ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer le thème actif'
                ], 400);
            }
            
            foreach ($themes as $theme) {
                // Détacher de l'établissement
                $etablissement->themes()->detach($theme->id);
                
                // Vérifier si d'autres établissements utilisent ce thème
                if ($theme->etablissements()->count() === 0) {
                    $this->themeService->deleteThemeFiles($theme);
                    $theme->delete();
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => count($ids) . ' thème(s) supprimé(s) avec succès'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Bulk delete error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
 * Bulk activate themes.
 */
public function bulkActivate(Request $request, $etablissementId): JsonResponse
{
    try {
        $etablissement = Etablissement::findOrFail($etablissementId);
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun thème sélectionné'
            ], 400);
        }
        
        // Récupérer tous les IDs des thèmes liés à l'établissement
        $allThemeIds = $etablissement->themes()->pluck('cms_themes.id')->toArray();
        
        // Désactiver tous les thèmes
        if (!empty($allThemeIds)) {
            $etablissement->themes()->updateExistingPivot($allThemeIds, [
                'is_active' => false
            ]);
        }
        
        // Activer les thèmes sélectionnés
        foreach ($ids as $themeId) {
            if ($etablissement->themes()->where('theme_id', $themeId)->exists()) {
                $etablissement->themes()->updateExistingPivot($themeId, ['is_active' => true]);
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => count($ids) . ' thème(s) activé(s) avec succès'
        ]);
        
    } catch (\Exception $e) {
        Log::error('Bulk activate error: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de l\'activation: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Export themes to CSV.
     */
    public function export(Request $request, $etablissementId)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        
        $themes = $etablissement->themes()
            ->orderBy('created_at', 'desc')
            ->get();
        
        $filename = 'themes_' . $etablissement->slug . '_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];
        
        $callback = function() use ($themes, $etablissement) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['ID', 'Nom', 'Slug', 'Version', 'Statut', 'Créé le']);
            
            foreach ($themes as $theme) {
                $pivot = $theme->etablissements()->where('etablissement_id', $etablissement->id)->first();
                $isActive = $pivot ? $pivot->pivot->is_active : false;
                
                fputcsv($file, [
                    $theme->id,
                    $theme->name,
                    $theme->slug,
                    $theme->version,
                    $isActive ? 'Actif' : 'Inactif',
                    $theme->created_at->format('d/m/Y H:i')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Vérifier si l'utilisateur a accès à l'établissement
     */
    protected function userHasAccess($user, $etablissement): bool
    {
        if ($user->is_admin ?? false) {
            return true;
        }
        
        if (method_exists($user, 'etablissement') && $user->etablissement && $user->etablissement->id === $etablissement->id) {
            return true;
        }
        
        if (method_exists($user, 'etablissements') && $user->etablissements->contains($etablissement)) {
            return true;
        }
        
        return false;
    }
}