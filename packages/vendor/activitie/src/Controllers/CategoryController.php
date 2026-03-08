<?php

namespace Vendor\Activitie\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\CategorieType;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    // Charger les relations nécessaires
    $query = Category::with(['type']) // Charger la relation type
                    ->withCount(['websites', 'templates']); // Compter les relations
    
    // Recherche
    if ($request->has('search') && !empty($request->search)) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('slug', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              // Recherche aussi dans le nom du type
              ->orWhereHas('type', function($q2) use ($search) {
                  $q2->where('name', 'like', "%{$search}%");
              });
        });
    }
    
    // Filtre par statut
    if ($request->has('status') && $request->status !== '') {
        $query->where('is_active', $request->status === 'active');
    }
    
    // Filtre par type de catégorie
    if ($request->has('categorie_type_id') && $request->categorie_type_id !== '') {
        $query->where('categorie_type_id', $request->categorie_type_id);
    }
    
    // Tri
    if ($request->has('sort_by') && !empty($request->sort_by)) {
        $sortable = ['name', 'websites_count', 'templates_count', 'created_at'];
        
        // Tri spécial pour le type de catégorie
        if ($request->sort_by === 'type_name') {
            $query->leftJoin('categorie_types', 'categories.categorie_type_id', '=', 'categorie_types.id')
                  ->orderBy('categorie_types.name', $request->sort_direction ?? 'asc')
                  ->select('categories.*'); // Important pour éviter les colonnes dupliquées
        } elseif (in_array($request->sort_by, $sortable)) {
            $query->orderBy($request->sort_by, $request->sort_direction ?? 'asc');
        }
    } else {
        $query->orderBy('name');
    }
    
    // Si requête AJAX
    if ($request->ajax()) {
        $perPage = $request->per_page ?? 10;
        $categories = $query->paginate($perPage);
        
        // Formater les données pour inclure le type
        $formattedData = $categories->map(function($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'is_active' => $category->is_active,
                'created_at' => $category->created_at,
                'updated_at' => $category->updated_at,
                'websites_count' => $category->websites_count,
                'templates_count' => $category->templates_count,
                'categorie_type_id' => $category->categorie_type_id,
                'type' => $category->type ? [
                    'id' => $category->type->id,
                    'name' => $category->type->name,
                    'slug' => $category->type->slug
                ] : null
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $formattedData,
            'current_page' => $categories->currentPage(),
            'last_page' => $categories->lastPage(),
            'per_page' => $categories->perPage(),
            'total' => $categories->total(),
            'prev_page_url' => $categories->previousPageUrl(),
            'next_page_url' => $categories->nextPageUrl(),
        ]);
    }
    
    // Pour la vue normale
    $categories = $query->paginate(10);
    $categorie_types = CategorieType::all();
    
    return view('activitie::categories.index', compact('categories', 'categorie_types'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('activitie::categories.create');
    }

/**
 * Store a newly created resource in storage.
 */
public function store(Request $request)
{
    try {
        Log::info('Début de création d\'une nouvelle catégorie', [
            'user_id' => auth()->id() ?? 'guest',
            'ip_address' => $request->ip(),
            'input_data' => $request->except(['_token', '_method'])
        ]);

        // Valider les données
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'is_active' => 'nullable',
            'categorie_type_id' => 'required|exists:categorie_types,id'
        ]);

        // Convertir is_active en boolean si présent
        if (isset($validated['is_active'])) {
            $validated['is_active'] = filter_var($validated['is_active'], FILTER_VALIDATE_BOOLEAN);
        } else {
            $validated['is_active'] = true; // Valeur par défaut
        }

        // Générer le slug automatiquement
        $validated['slug'] = Str::slug($validated['name']);
        
        Log::debug('Données validées pour la création de catégorie', [
            'validated_data' => $validated
        ]);

        // Créer la catégorie
        $category = Category::create($validated);

        Log::info('Catégorie créée avec succès', [
            'category_id' => $category->id,
            'category_name' => $category->name,
            'category_type_id' => $category->categorie_type_id,
            'slug' => $category->slug
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Catégorie créée avec succès!',
                'data' => $category
            ], 201); // 201 Created
        }

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie créée avec succès!')
            ->with('created_category', $category->id);

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::warning('Erreur de validation lors de la création de catégorie', [
            'errors' => $e->errors(),
            'input_data' => $request->all(),
            'user_id' => auth()->id() ?? 'guest'
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        }

        return back()
            ->withErrors($e->errors())
            ->withInput()
            ->with('error', 'Veuillez corriger les erreurs de validation.');

    } catch (\Illuminate\Database\QueryException $e) {
        Log::error('Erreur de base de données lors de la création de catégorie', [
            'error_message' => $e->getMessage(),
            'error_code' => $e->getCode(),
            'sql_query' => $e->getSql(),
            'bindings' => $e->getBindings(),
            'input_name' => $request->input('name'),
            'user_id' => auth()->id() ?? 'guest'
        ]);

        // Vérifier si c'est une erreur de contrainte d'unicité (duplicate entry)
        $errorCode = $e->errorInfo[1] ?? null;
        if ($errorCode == 1062) { // Code d'erreur MySQL pour duplicate entry
            $errorMessage = 'Une catégorie avec ce nom existe déjà.';
        } else {
            $errorMessage = 'Une erreur de base de données est survenue lors de la création.';
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $errorMessage
            ], 500);
        }

        return back()
            ->withInput()
            ->with('error', $errorMessage);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        Log::error('Type de catégorie non trouvé lors de la création', [
            'categorie_type_id' => $request->input('categorie_type_id'),
            'error_message' => $e->getMessage(),
            'user_id' => auth()->id() ?? 'guest'
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Le type de catégorie spécifié n\'existe pas.'
            ], 404);
        }

        return back()
            ->withInput()
            ->with('error', 'Le type de catégorie spécifié n\'existe pas.');

    } catch (\Exception $e) {
        Log::error('Erreur inattendue lors de la création de catégorie', [
            'error_message' => $e->getMessage(),
            'error_code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
            'input_name' => $request->input('name'),
            'user_id' => auth()->id() ?? 'guest'
        ]);

        $errorMessage = config('app.debug') 
            ? 'Erreur: ' . $e->getMessage()
            : 'Une erreur inattendue est survenue lors de la création.';

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $errorMessage
            ], 500);
        }

        return back()
            ->withInput()
            ->with('error', $errorMessage);
    }
}

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category->load(['websites' => function($query) {
            $query->orderBy('name')->limit(10);
        }, 'templates' => function($query) {
            $query->orderBy('name')->limit(10);
        }]);
        
        $statistics = [
            'websites_count' => $category->websites_count,
            'templates_count' => $category->templates_count,
            'total_items' => $category->websites_count + $category->templates_count,
        ];
        
        return view('categories.show', compact('category', 'statistics'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */

public function update(Request $request, Category $category)
{
    try {
        // Valider les données
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'is_active' => 'nullable',
            'categorie_type_id' => 'required|exists:categorie_types,id'
        ]);

        // Convertir is_active en boolean si présent
        if (isset($validated['is_active'])) {
            $validated['is_active'] = filter_var($validated['is_active'], FILTER_VALIDATE_BOOLEAN);
        }

        // Mettre à jour le slug si le nom change
        if ($category->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        Log::info('Mise à jour de la catégorie', [
            'category_id' => $category->id,
            'old_data' => $category->toArray(),
            'new_data' => $validated,
            'user_id' => auth()->id() ?? 'guest',
            'ip_address' => $request->ip()
        ]);

        // Mettre à jour la catégorie
        $category->update($validated);

        Log::info('Catégorie mise à jour avec succès', [
            'category_id' => $category->id,
            'category_name' => $category->name
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Catégorie mise à jour avec succès!',
                'data' => $category->fresh()
            ]);
        }

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie mise à jour avec succès!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::warning('Erreur de validation lors de la mise à jour de la catégorie', [
            'category_id' => $category->id,
            'errors' => $e->errors(),
            'input_data' => $request->all(),
            'user_id' => auth()->id() ?? 'guest'
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        }

        return back()->withErrors($e->errors())->withInput();

    } catch (\Illuminate\Database\QueryException $e) {
        Log::error('Erreur de base de données lors de la mise à jour de la catégorie', [
            'category_id' => $category->id,
            'error_message' => $e->getMessage(),
            'error_code' => $e->getCode(),
            'sql_query' => $e->getSql(),
            'bindings' => $e->getBindings(),
            'user_id' => auth()->id() ?? 'guest'
        ]);

        $errorMessage = 'Une erreur de base de données est survenue.';

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $errorMessage
            ], 500);
        }

        return back()->with('error', $errorMessage);

    } catch (\Exception $e) {
        Log::error('Erreur inattendue lors de la mise à jour de la catégorie', [
            'category_id' => $category->id,
            'error_message' => $e->getMessage(),
            'error_code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
            'user_id' => auth()->id() ?? 'guest'
        ]);

        $errorMessage = config('app.debug') 
            ? 'Erreur: ' . $e->getMessage()
            : 'Une erreur inattendue est survenue.';

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $errorMessage
            ], 500);
        }

        return back()->with('error', $errorMessage);
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Category $category)
    {
        try {
            DB::beginTransaction();
            
            // Vérifier si la catégorie est utilisée
            if ($category->websites()->count() > 0 || $category->templates()->count() > 0) {
                throw new \Exception('Cette catégorie ne peut pas être supprimée car elle est utilisée par des sites web ou des templates.');
            }
            
            // Supprimer la catégorie
            $category->delete();
            
            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Catégorie supprimée avec succès!'
                ]);
            }

            return redirect()->route('categories.index')
                ->with('success', 'Catégorie supprimée avec succès!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('categories.index')
                ->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Get statistics for dashboard
     */
    /**
 * Get statistics for dashboard
 */
public function getStatistics(Request $request)
{
    try {
        // D'abord, récupérons les catégories avec les comptages
        $categoriesWithCounts = Category::withCount(['websites', 'templates'])->get();
        
        // Calculer les statistiques
        $mostUsed = null;
        $leastUsed = null;
        $maxCount = -1;
        $minCount = PHP_INT_MAX;
        
        foreach ($categoriesWithCounts as $category) {
            $totalCount = $category->websites_count + $category->templates_count;
            
            if ($totalCount > $maxCount) {
                $maxCount = $totalCount;
                $mostUsed = $category;
            }
            
            if ($totalCount < $minCount) {
                $minCount = $totalCount;
                $leastUsed = $category;
            }
        }
        
        // Pour categories_by_usage, nous trions manuellement
        $sortedCategories = $categoriesWithCounts->sortByDesc(function($category) {
            return $category->websites_count + $category->templates_count;
        })->take(10);
        
        $stats = [
            'total_categories' => Category::count(),
            'active_categories' => Category::where('is_active', true)->count(),
            'inactive_categories' => Category::where('is_active', false)->count(),
            'categories_with_websites' => Category::has('websites')->count(),
            'categories_with_templates' => Category::has('templates')->count(),
            'most_used' => $mostUsed ? [
                'name' => $mostUsed->name,
                'slug' => $mostUsed->slug,
                'websites_count' => $mostUsed->websites_count,
                'templates_count' => $mostUsed->templates_count,
                'total_items' => $mostUsed->websites_count + $mostUsed->templates_count,
            ] : null,
            'least_used' => $leastUsed ? [
                'name' => $leastUsed->name,
                'slug' => $leastUsed->slug,
                'websites_count' => $leastUsed->websites_count,
                'templates_count' => $leastUsed->templates_count,
                'total_items' => $leastUsed->websites_count + $leastUsed->templates_count,
            ] : null,
            'total_websites_in_categories' => $categoriesWithCounts->sum('websites_count'),
            'total_templates_in_categories' => $categoriesWithCounts->sum('templates_count'),
            'categories_by_usage' => $sortedCategories->map(function($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'websites_count' => $category->websites_count,
                    'templates_count' => $category->templates_count,
                    'total_items' => $category->websites_count + $category->templates_count,
                ];
            })->values(),
            'latest_categories' => Category::orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'created_at' => $category->created_at->format('d/m/Y H:i'),
                    ];
                }),
            'categories_without_items' => Category::doesntHave('websites')->doesntHave('templates')->count(),
        ];

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Statistiques des catégories récupérées avec succès'
            ]);
        }

        return $stats;

    } catch (\Exception $e) {
        \Log::error('Erreur dans getStatistics: ' . $e->getMessage());
        
        $errorResponse = [
            'success' => false,
            'message' => 'Erreur lors du calcul des statistiques',
            'error' => $e->getMessage()
        ];

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($errorResponse, 500);
        }

        return $errorResponse;
    }
}

    /**
     * Search categories (autocomplete)
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        $categories = Category::where('name', 'like', "%{$query}%")
            ->orWhere('slug', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'name', 'slug', 'is_active']);
            
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Get categories for dropdown
     */
    public function getForDropdown()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Toggle category status
     */
    public function toggleStatus(Request $request, Category $category)
    {
        try {
            $category->update([
                'is_active' => !$category->is_active
            ]);

            $status = $category->is_active ? 'activée' : 'désactivée';

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Catégorie ' . $status . ' avec succès!',
                    'data' => $category
                ]);
            }

            return redirect()->route('categories.index')
                ->with('success', 'Catégorie ' . $status . ' avec succès!');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du changement de statut: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('categories.index')
                ->with('error', 'Erreur lors du changement de statut: ' . $e->getMessage());
        }
    }

    /**
     * Export categories data
     */
    public function export(Request $request)
    {
        $categories = Category::withCount(['websites', 'templates'])
            ->orderBy('name')
            ->get();

        // Logique d'export CSV, Excel, etc.
        if ($request->format === 'csv') {
            return response()->json([
                'success' => true,
                'message' => 'Export CSV non implémenté',
                'data' => $categories
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $categories,
            'total' => $categories->count()
        ]);
    }

    /**
     * Bulk update categories
     */
    public function bulkUpdate(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:categories,id',
                'action' => 'required|in:activate,deactivate,delete',
            ]);

            $count = 0;
            $message = '';

            switch ($validated['action']) {
                case 'activate':
                    Category::whereIn('id', $validated['ids'])->update(['is_active' => true]);
                    $count = count($validated['ids']);
                    $message = $count . ' catégorie(s) activée(s) avec succès!';
                    break;
                    
                case 'deactivate':
                    Category::whereIn('id', $validated['ids'])->update(['is_active' => false]);
                    $count = count($validated['ids']);
                    $message = $count . ' catégorie(s) désactivée(s) avec succès!';
                    break;
                    
                case 'delete':
                    // Vérifier qu'aucune catégorie n'est utilisée
                    $usedCategories = Category::whereIn('id', $validated['ids'])
                        ->where(function($query) {
                            $query->has('websites')->orHas('templates');
                        })
                        ->count();
                        
                    if ($usedCategories > 0) {
                        throw new \Exception('Certaines catégories ne peuvent pas être supprimées car elles sont utilisées.');
                    }
                    
                    Category::whereIn('id', $validated['ids'])->delete();
                    $count = count($validated['ids']);
                    $message = $count . ' catégorie(s) supprimée(s) avec succès!';
                    break;
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'count' => $count
                ]);
            }

            return redirect()->route('categories.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'opération en masse: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('categories.index')
                ->with('error', 'Erreur lors de l\'opération en masse: ' . $e->getMessage());
        }
    }
}