<?php

namespace Vendor\Cms\Controllers\Admin;

use App\Http\Controllers\Controller;
use Vendor\Cms\Models\Page;
use Vendor\Cms\Models\Theme;
use App\Models\Etablissement;
use Vendor\Cms\Services\PageService;
use Vendor\Cms\Requests\PageRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PageController extends Controller
{
    protected $pageService;

    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }

    /**
     * Display a listing of pages.
     */
    public function index(Request $request, $etablissementId)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        
        $query = Page::where('etablissement_id', $etablissement->id);
        
        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        // Filter by visibility
        if ($request->has('visibility') && $request->visibility != 'all') {
            $query->where('visibility', $request->visibility);
        }
        
        // Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }
        
        $pages = $query->orderBy('updated_at', 'desc')->paginate(15);
        
        $stats = [
            'total' => Page::where('etablissement_id', $etablissement->id)->count(),
            'published' => Page::where('etablissement_id', $etablissement->id)
                ->where('status', 'published')
                ->count(),
            'drafts' => Page::where('etablissement_id', $etablissement->id)
                ->where('status', 'draft')
                ->count(),
            'archived' => Page::where('etablissement_id', $etablissement->id)
                ->where('status', 'archived')
                ->count(),
        ];
        
        return view('cms::admin.pages.index', compact('pages', 'stats', 'etablissement'));
    }

    /**
     * Show the form for creating a new page.
     */
    public function create(Request $request, $etablissementId)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        
        // Récupérer les thèmes liés à l'établissement via la relation many-to-many
        $themes = $etablissement->themes()->get();
        
        return view('cms::admin.pages.create', compact('themes', 'etablissement'));
    }

    /**
     * Store a newly created page.
     */
    public function store(PageRequest $request, $etablissementId): JsonResponse
    {
        try {
            DB::connection('cms')->beginTransaction();
            
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            // if (!$this->userHasAccess($request->user(), $etablissement)) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Accès non autorisé'
            //     ], 403);
            // }
            
            $data = $request->validated();
            $data['etablissement_id'] = $etablissement->id;
            $data['user_id'] = $request->user()->id;
            
            // Generate slug if not provided
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['title']);
            }
            
            // Ensure unique slug
            $data['slug'] = $this->generateUniqueSlug($data['slug'], $etablissement->id);
            
            // Handle content (from GrapeJS)
            if ($request->has('content')) {
                $data['content'] = $request->input('content');
            }
            
            // Handle meta data
            if ($request->has('meta')) {
                $data['meta'] = $request->input('meta');
            }
            
            // Handle settings
            if ($request->has('settings')) {
                $data['settings'] = $request->input('settings');
            }
            
            // Handle is_home
            if ($request->has('is_home') && $request->is_home) {
                // Remove is_home from all other pages
                Page::where('etablissement_id', $etablissement->id)
                    ->update(['is_home' => false]);
                $data['is_home'] = true;
            }
            
            // Set published_at if status is published
            if ($data['status'] === 'published' && empty($data['published_at'])) {
                $data['published_at'] = now();
            }
            
            $page = Page::create($data);
            
            DB::connection('cms')->commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Page créée avec succès',
                'page' => $page,
                'redirect' => route('cms.admin.pages.edit', [
                    'etablissementId' => $etablissement->id,
                    'id' => $page->id
                ])
            ]);
            
        } catch (\Exception $e) {
            DB::connection('cms')->rollBack();
            Log::error('Page creation error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified page.
     */
    public function show(Request $request, $etablissementId, $id)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        
        if (!$this->userHasAccess($request->user(), $etablissement)) {
            abort(403, 'Accès non autorisé');
        }
        
        $page = Page::where('id', $id)
            ->where('etablissement_id', $etablissement->id)
            ->firstOrFail();
        
        return view('cms::admin.pages.show', compact('page', 'etablissement'));
    }

    /**
     * Show the form for editing the specified page.
     */
    public function edit(Request $request, $etablissementId, $id)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        
        $page = Page::where('id', $id)
            ->where('etablissement_id', $etablissement->id)
            ->firstOrFail();
        
        // Récupérer les thèmes liés à l'établissement via la relation many-to-many
        $themes = $etablissement->themes()->get();
        
        return view('cms::admin.pages.edit', compact('page', 'themes', 'etablissement'));
    }

    /**
     * Update the specified page.
     */
    public function update(PageRequest $request, $etablissementId, $id): JsonResponse
    {
        try {
            DB::connection('cms')->beginTransaction();
            
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            $page = Page::where('id', $id)
                ->where('etablissement_id', $etablissement->id)
                ->firstOrFail();
            
            $data = $request->validated();
            
            // Handle slug
            if (!empty($data['slug']) && $data['slug'] !== $page->slug) {
                $data['slug'] = $this->generateUniqueSlug($data['slug'], $etablissement->id, $page->id);
            }
            
            // Handle content (from GrapeJS)
            if ($request->has('content')) {
                $data['content'] = $request->input('content');
            }
            
            // Handle meta data
            if ($request->has('meta')) {
                $data['meta'] = $request->input('meta');
            }
            
            // Handle settings
            if ($request->has('settings')) {
                $data['settings'] = $request->input('settings');
            }
            
            // Handle is_home
            if ($request->has('is_home') && $request->is_home && !$page->is_home) {
                // Remove is_home from all other pages
                Page::where('etablissement_id', $etablissement->id)
                    ->where('id', '!=', $page->id)
                    ->update(['is_home' => false]);
                $data['is_home'] = true;
            } elseif ($request->has('is_home') && !$request->is_home && $page->is_home) {
                $data['is_home'] = false;
            }
            
            // Handle status change
            if ($data['status'] === 'published' && $page->status !== 'published') {
                $data['published_at'] = $data['published_at'] ?? now();
            }
            
            $page->update($data);
            
            DB::connection('cms')->commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Page mise à jour avec succès',
                'page' => $page
            ]);
            
        } catch (\Exception $e) {
            DB::connection('cms')->rollBack();
            Log::error('Page update error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified page.
     */
    public function destroy(Request $request, $etablissementId, $id): JsonResponse
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            if (!$this->userHasAccess($request->user(), $etablissement)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }
            
            $page = Page::where('id', $id)
                ->where('etablissement_id', $etablissement->id)
                ->firstOrFail();
            
            // Prevent deletion of home page if it's the only page
            if ($page->is_home) {
                $otherPages = Page::where('etablissement_id', $etablissement->id)
                    ->where('id', '!=', $page->id)
                    ->count();
                    
                if ($otherPages === 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Impossible de supprimer la page d\'accueil s\'il n\'y a pas d\'autre page'
                    ], 400);
                }
            }
            
            $page->delete();
            
            // If deleted page was home, set another page as home
            if ($page->is_home) {
                $newHome = Page::where('etablissement_id', $etablissement->id)
                    ->where('id', '!=', $page->id)
                    ->first();
                    
                if ($newHome) {
                    $newHome->is_home = true;
                    $newHome->save();
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Page supprimée avec succès'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Page deletion error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Publish a page.
     */
    public function publish(Request $request, $etablissementId, $id): JsonResponse
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            if (!$this->userHasAccess($request->user(), $etablissement)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }
            
            $page = Page::where('id', $id)
                ->where('etablissement_id', $etablissement->id)
                ->firstOrFail();
            
            $page->status = 'published';
            $page->published_at = $page->published_at ?? now();
            $page->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Page publiée avec succès',
                'page' => $page
            ]);
            
        } catch (\Exception $e) {
            Log::error('Page publish error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la publication: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unpublish a page.
     */
    public function unpublish(Request $request, $etablissementId, $id): JsonResponse
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            if (!$this->userHasAccess($request->user(), $etablissement)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }
            
            $page = Page::where('id', $id)
                ->where('etablissement_id', $etablissement->id)
                ->firstOrFail();
            
            $page->status = 'draft';
            $page->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Page dépubliée avec succès',
                'page' => $page
            ]);
            
        } catch (\Exception $e) {
            Log::error('Page unpublish error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la dépublication: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Duplicate a page.
     */
    public function duplicate(Request $request, $etablissementId, $id): JsonResponse
    {
        try {
            DB::connection('cms')->beginTransaction();
            
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            if (!$this->userHasAccess($request->user(), $etablissement)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }
            
            $original = Page::where('id', $id)
                ->where('etablissement_id', $etablissement->id)
                ->firstOrFail();
            
            $newTitle = $original->title . ' (copie)';
            $newSlug = $this->generateUniqueSlug(Str::slug($newTitle), $etablissement->id);
            
            $duplicate = $original->replicate();
            $duplicate->title = $newTitle;
            $duplicate->slug = $newSlug;
            $duplicate->status = 'draft';
            $duplicate->is_home = false;
            $duplicate->published_at = null;
            $duplicate->save();
            
            DB::connection('cms')->commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Page dupliquée avec succès',
                'page' => $duplicate,
                'redirect' => route('cms.admin.pages.edit', [
                    'etablissementId' => $etablissement->id,
                    'id' => $duplicate->id
                ])
            ]);
            
        } catch (\Exception $e) {
            DB::connection('cms')->rollBack();
            Log::error('Page duplicate error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la duplication: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save page content via AJAX (for GrapeJS).
     */
    public function saveContent(Request $request, $etablissementId, $id): JsonResponse
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            if (!$this->userHasAccess($request->user(), $etablissement)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }
            
            $page = Page::where('id', $id)
                ->where('etablissement_id', $etablissement->id)
                ->firstOrFail();
            
            $content = $request->input('content');
            $page->content = $content;
            $page->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Contenu sauvegardé avec succès'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Page content save error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la sauvegarde: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Preview a page.
     */
    public function preview(Request $request, $etablissementId, $id)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        
        if (!$this->userHasAccess($request->user(), $etablissement)) {
            abort(403, 'Accès non autorisé');
        }
        
        $page = Page::where('id', $id)
            ->where('etablissement_id', $etablissement->id)
            ->firstOrFail();
        
        // Store preview mode in session
        session(['page_preview' => $page->id]);
        
        return redirect()->to('/company/' . $etablissement->id . '/page/' . $page->slug . '?preview=true');
    }

    /**
     * Set a page as home page.
     */
    public function setAsHome(Request $request, $etablissementId, $id): JsonResponse
    {
        try {
            DB::connection('cms')->beginTransaction();
            
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            if (!$this->userHasAccess($request->user(), $etablissement)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }
            
            // Remove is_home from all pages
            Page::where('etablissement_id', $etablissement->id)
                ->update(['is_home' => false]);
            
            // Set the selected page as home
            $page = Page::where('id', $id)
                ->where('etablissement_id', $etablissement->id)
                ->firstOrFail();
            
            $page->is_home = true;
            $page->save();
            
            DB::connection('cms')->commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Page définie comme page d\'accueil avec succès'
            ]);
            
        } catch (\Exception $e) {
            DB::connection('cms')->rollBack();
            Log::error('Set as home error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'opération: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete pages.
     */
    public function bulkDelete(Request $request, $etablissementId): JsonResponse
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            if (!$this->userHasAccess($request->user(), $etablissement)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }
            
            $ids = $request->input('ids', []);
            
            if (empty($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune page sélectionnée'
                ], 400);
            }
            
            $pages = Page::where('etablissement_id', $etablissement->id)
                ->whereIn('id', $ids)
                ->get();
            
            // Prevent deletion of home page
            $homePage = $pages->where('is_home', true)->first();
            if ($homePage && count($pages) > 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer la page d\'accueil'
                ], 400);
            }
            
            foreach ($pages as $page) {
                $page->delete();
            }
            
            return response()->json([
                'success' => true,
                'message' => count($ids) . ' page(s) supprimée(s) avec succès'
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
     * Bulk update pages status.
     */
    public function bulkUpdateStatus(Request $request, $etablissementId): JsonResponse
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            if (!$this->userHasAccess($request->user(), $etablissement)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }
            
            $ids = $request->input('ids', []);
            $status = $request->input('status');
            
            if (empty($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune page sélectionnée'
                ], 400);
            }
            
            if (!in_array($status, ['draft', 'published', 'archived'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Statut invalide'
                ], 400);
            }
            
            $updateData = ['status' => $status];
            
            if ($status === 'published') {
                $updateData['published_at'] = now();
            }
            
            Page::where('etablissement_id', $etablissement->id)
                ->whereIn('id', $ids)
                ->update($updateData);
            
            return response()->json([
                'success' => true,
                'message' => count($ids) . ' page(s) mise(s) à jour avec succès'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Bulk update status error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export pages to CSV.
     */
    public function export(Request $request, $etablissementId)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        
        if (!$this->userHasAccess($request->user(), $etablissement)) {
            abort(403, 'Accès non autorisé');
        }
        
        $pages = Page::where('etablissement_id', $etablissement->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $filename = 'pages_' . $etablissement->slug . '_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];
        
        $callback = function() use ($pages) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['ID', 'Titre', 'Slug', 'Statut', 'Visibilité', 'Créé le', 'Publié le']);
            
            foreach ($pages as $page) {
                fputcsv($file, [
                    $page->id,
                    $page->title,
                    $page->slug,
                    $page->status,
                    $page->visibility,
                    $page->created_at->format('d/m/Y H:i'),
                    $page->published_at ? $page->published_at->format('d/m/Y H:i') : 'Non publié'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate unique slug.
     */
    protected function generateUniqueSlug($slug, $etablissementId, $excludeId = null)
    {
        $originalSlug = $slug;
        $counter = 1;
        
        $query = Page::where('etablissement_id', $etablissementId)
            ->where('slug', $slug);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        while ($query->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $query = Page::where('etablissement_id', $etablissementId)
                ->where('slug', $slug);
            
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            
            $counter++;
        }
        
        return $slug;
    }

    /**
     * Get page data for AJAX.
     */
    public function getData(Request $request, $etablissementId, $id): JsonResponse
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        
        if (!$this->userHasAccess($request->user(), $etablissement)) {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé'
            ], 403);
        }
        
        $page = Page::where('id', $id)
            ->where('etablissement_id', $etablissement->id)
            ->firstOrFail();
        
        return response()->json([
            'success' => true,
            'page' => $page
        ]);
    }

    /**
     * Get all pages for AJAX (datatables).
     */
    public function getAllData(Request $request, $etablissementId): JsonResponse
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        
        if (!$this->userHasAccess($request->user(), $etablissement)) {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé'
            ], 403);
        }
        
        $pages = Page::where('etablissement_id', $etablissement->id)
            ->select(['id', 'title', 'slug', 'status', 'visibility', 'updated_at'])
            ->orderBy('updated_at', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $pages
        ]);
    }

    /**
     * Restore a soft-deleted page.
     */
    public function restore(Request $request, $etablissementId, $id): JsonResponse
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            if (!$this->userHasAccess($request->user(), $etablissement)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }
            
            $page = Page::where('etablissement_id', $etablissement->id)
                ->withTrashed()
                ->where('id', $id)
                ->firstOrFail();
            
            if (!$page->trashed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette page n\'est pas supprimée'
                ], 400);
            }
            
            $page->restore();
            
            return response()->json([
                'success' => true,
                'message' => 'Page restaurée avec succès'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Page restore error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la restauration: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Force delete a page permanently.
     */
    public function forceDelete(Request $request, $etablissementId, $id): JsonResponse
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            if (!$this->userHasAccess($request->user(), $etablissement)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }
            
            $page = Page::where('etablissement_id', $etablissement->id)
                ->withTrashed()
                ->where('id', $id)
                ->firstOrFail();
            
            // Prevent deletion of home page
            if ($page->is_home) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer définitivement la page d\'accueil'
                ], 400);
            }
            
            $page->forceDelete();
            
            return response()->json([
                'success' => true,
                'message' => 'Page supprimée définitivement'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Page force delete error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
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

    /**
 * Show the form for editing the page content only (with builder).
 */
public function editContent(Request $request, $etablissementId, $id)
{
    $etablissement = Etablissement::findOrFail($etablissementId);
    
    $page = Page::where('id', $id)
        ->where('etablissement_id', $etablissement->id)
        ->firstOrFail();
    
    return view('cms::admin.pages.edit-content', compact('page', 'etablissement'));
}

/**
 * Update page content only.
 */
public function updateContent(Request $request, $etablissementId, $id): JsonResponse
{
    try {
        $etablissement = Etablissement::findOrFail($etablissementId);
        
        $page = Page::where('id', $id)
            ->where('etablissement_id', $etablissement->id)
            ->firstOrFail();
        
        $content = $request->input('content');
        $page->content = $content;
        $page->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Contenu sauvegardé avec succès'
        ]);
        
    } catch (\Exception $e) {
        Log::error('Page content update error: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la sauvegarde: ' . $e->getMessage()
        ], 500);
    }
}
}