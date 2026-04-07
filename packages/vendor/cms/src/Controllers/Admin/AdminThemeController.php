<?php

namespace Vendor\Cms\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Etablissement;
use Vendor\Cms\Models\Theme;
use Vendor\Cms\Services\ThemeService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AdminThemeController extends Controller
{
    protected $themeService;

    public function __construct(ThemeService $themeService)
    {
        $this->themeService = $themeService;
    }

    // =========================================================================
    // GLOBAL THEME MANAGEMENT (no etablissement scope)
    // Routes: admin/cms/themes/*
    // =========================================================================

    /**
     * Liste tous les thèmes globaux disponibles.
     */
    public function index(Request $request)
    {
        $themes = Theme::orderBy('created_at', 'desc')->paginate(12);

        $stats = [
            'total'   => Theme::count(),
            'default' => Theme::where('is_default', true)->count(),
        ];

        return view('cms::admin.themes.index', compact('themes', 'stats'));
    }

    /**
     * Upload un nouveau thème global (sans établissement).
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name'       => 'required|string|max:255',
                'theme_file' => 'required|file|mimes:zip|max:51200',
            ]);

            // Upload global : on passe null comme etablissementId
            $theme = $this->themeService->uploadTheme(
                $request->file('theme_file'),
                $request->input('name'),
                null   // ← pas d'établissement lié à l'upload
            );

            return response()->json([
                'success' => true,
                'message' => 'Thème uploadé avec succès',
                'theme'   => $theme,
                'html'    => view('cms::admin.themes.partials.theme-card', compact('theme'))->render(),
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Theme upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'upload : ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Supprime un thème global (détache tous les établissements d'abord).
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        try {
            $theme = Theme::findOrFail($id);

            // Détacher tous les établissements
            $theme->etablissements()->detach();

            // Supprimer les fichiers
            $this->themeService->deleteThemeFiles($theme);
            $theme->delete();

            return response()->json([
                'success' => true,
                'message' => 'Thème supprimé avec succès',
            ]);

        } catch (\Exception $e) {
            Log::error('Theme deletion error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression : ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Upload une image de prévisualisation pour un thème global.
     */
    public function uploadPreview(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'preview_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            $theme = Theme::findOrFail($id);

            // Supprimer l'ancienne image si elle existe
            if ($theme->preview_image && Storage::disk('public')->exists($theme->preview_image)) {
                Storage::disk('public')->delete($theme->preview_image);
            }

            $path = $request->file('preview_image')->store("themes/{$theme->slug}", 'public');
            $theme->update(['preview_image' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'Image de prévisualisation mise à jour',
                'url'     => Storage::disk('public')->url($path),
            ]);

        } catch (\Exception $e) {
            Log::error('Preview upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage(),
            ], 500);
        }
    }

    // =========================================================================
    // ETABLISSEMENT-SCOPED ACTIONS
    // Routes: admin/cms/{etablissementId}/themes/*
    // Ces actions lient/délient un thème global à un établissement
    // =========================================================================

    /**
     * Attache un thème global à un établissement.
     */
    public function attach(Request $request, $etablissementId, $id): JsonResponse
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            $theme         = Theme::findOrFail($id);

            if ($etablissement->themes()->where('theme_id', $id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce thème est déjà dans votre bibliothèque',
                ], 400);
            }

            $isFirst = $etablissement->themes()->count() === 0;

            $etablissement->themes()->attach($theme->id, [
                'is_active'  => $isFirst,
                'config'     => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success'  => true,
                'message'  => 'Thème ajouté à votre bibliothèque',
                'theme_id' => $theme->id,
            ]);

        } catch (\Exception $e) {
            Log::error('Theme attach error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Détache un thème d'un établissement.
     */
    public function detach(Request $request, $etablissementId, $id): JsonResponse
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            $theme         = Theme::findOrFail($id);

            if ($etablissement->themes()->count() <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous ne pouvez pas retirer le dernier thème de cet établissement',
                ], 400);
            }

            $etablissement->themes()->detach($id);

            return response()->json([
                'success' => true,
                'message' => 'Thème retiré de votre bibliothèque',
            ]);

        } catch (\Exception $e) {
            Log::error('Theme detach error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Active un thème pour un établissement (désactive les autres).
     */
    public function activate(Request $request, $etablissementId, $id): JsonResponse
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            $theme         = Theme::findOrFail($id);

            // Attacher automatiquement si pas encore dans la bibliothèque
            if (!$etablissement->themes()->where('theme_id', $id)->exists()) {
                $etablissement->themes()->attach($theme->id, [
                    'is_active'  => false,
                    'config'     => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Désactiver tous les thèmes de l'établissement
            $allThemeIds = $etablissement->themes()->pluck('cms_themes.id')->toArray();
            if (!empty($allThemeIds)) {
                $etablissement->themes()->updateExistingPivot($allThemeIds, ['is_active' => false]);
            }

            // Activer le thème sélectionné
            $etablissement->themes()->updateExistingPivot($id, ['is_active' => true]);

            return response()->json([
                'success'  => true,
                'message'  => 'Thème activé avec succès',
                'theme_id' => $theme->id,
            ]);

        } catch (\Exception $e) {
            Log::error('Theme activation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'activation : ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Désactive un thème pour un établissement.
     */
    public function deactivate(Request $request, $etablissementId, $id): JsonResponse
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);

            if (!$etablissement->themes()->where('theme_id', $id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce thème n\'est pas dans votre bibliothèque',
                ], 400);
            }

            $etablissement->themes()->updateExistingPivot($id, ['is_active' => false]);

            return response()->json([
                'success' => true,
                'message' => 'Thème désactivé',
            ]);

        } catch (\Exception $e) {
            Log::error('Theme deactivation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Prévisualise un thème pour un établissement (stocke en session).
     */
    public function preview(Request $request, $etablissementId, $id)
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            $theme         = Theme::findOrFail($id);

            session([
                'theme_preview_mode'  => true,
                'preview_theme_id'    => $theme->id,
                'preview_theme_slug'  => $theme->slug,
            ]);

            $redirectUrl = url('/company/' . $etablissement->id . '?preview_theme=' . $theme->slug);

            if ($request->wantsJson()) {
                return response()->json([
                    'success'      => true,
                    'redirect_url' => $redirectUrl,
                ]);
            }

            return redirect()->to($redirectUrl);

        } catch (\Exception $e) {
            Log::error('Theme preview error: ' . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Duplique un thème global.
     */
    public function duplicate(Request $request, $id): JsonResponse
    {
        try {
            $theme    = Theme::findOrFail($id);
            $newName  = $theme->name . ' (copie)';
            $duplicate = $this->themeService->duplicateTheme($theme, $newName);

            return response()->json([
                'success' => true,
                'message' => 'Thème dupliqué avec succès',
                'theme'   => $duplicate,
            ]);

        } catch (\Exception $e) {
            Log::error('Theme duplicate error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Export CSV des thèmes globaux.
     */
    public function export(Request $request)
    {
        $themes   = Theme::orderBy('created_at', 'desc')->get();
        $filename = 'themes_global_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($themes) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Nom', 'Slug', 'Version', 'Stockage', 'Défaut', 'Créé le']);

            foreach ($themes as $theme) {
                fputcsv($file, [
                    $theme->id,
                    $theme->name,
                    $theme->slug,
                    $theme->version,
                    $theme->storage_type,
                    $theme->is_default ? 'Oui' : 'Non',
                    $theme->created_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}