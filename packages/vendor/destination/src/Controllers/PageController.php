<?php

namespace Vendor\Destination\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Continent;
use App\Models\Country;
use App\Models\Region;
use App\Models\Province;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Si c'est une requête AJAX, retourner JSON
        if ($request->ajax()) {
            return $this->getPagesJson($request);
        }
        
        // Sinon, retourner la vue
        $types = [
            'continent' => 'Continent',
            'country' => 'Pays',
            'region' => 'Région',
            'province' => 'Province',
            'city' => 'Ville'
        ];
        
        return view('pages.index', compact('types'));
    }

    /**
     * Get pages data for AJAX requests
     */
    private function getPagesJson(Request $request)
    {
        $query = Page::with(['pageable'])
            ->orderBy('created_at', 'desc');
        
        // Recherche
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('slug', 'LIKE', "%{$search}%")
                  ->orWhere('html_content', 'LIKE', "%{$search}%");
            });
        }
        
        // Filtrage par type
        if ($request->has('type') && !empty($request->type)) {
            $modelClass = 'App\\Models\\' . ucfirst($request->type);
            $query->where('pageable_type', $modelClass);
        }
        
        // Filtrage par statut
        if ($request->has('status') && !empty($request->status)) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);
        
        // Pagination
        $perPage = $request->get('per_page', 10);
        $pages = $query->paginate($perPage);
        
        // Format data for response
        $formattedPages = $pages->map(function($page) {
            return [
                'id' => $page->id,
                'name' => $page->name,
                'slug' => $page->slug,
                'html_content' => $page->html_content,
                'css_content' => $page->css_content,
                'is_active' => $page->is_active,
                'pageable_type' => $page->pageable_type,
                'pageable_id' => $page->pageable_id,
                'pageable' => $page->pageable ? [
                    'id' => $page->pageable->id,
                    'name' => $page->pageable->name,
                    'code' => $page->pageable->code ?? null,
                ] : null,
                'created_at' => $page->created_at->format('d/m/Y H:i'),
                'updated_at' => $page->updated_at->format('d/m/Y H:i'),
                'url' => url("/pages/{$page->slug}"),
                'edit_url' => route('pages.edit', $page->id),
                'delete_url' => route('pages.destroy', $page->id),
            ];
        });
        
        return response()->json([
            'success' => true,
            'current_page' => $pages->currentPage(),
            'data' => $formattedPages,
            'first_page_url' => $pages->url(1),
            'from' => $pages->firstItem(),
            'last_page' => $pages->lastPage(),
            'last_page_url' => $pages->url($pages->lastPage()),
            'next_page_url' => $pages->nextPageUrl(),
            'path' => $pages->path(),
            'per_page' => $pages->perPage(),
            'prev_page_url' => $pages->previousPageUrl(),
            'to' => $pages->lastItem(),
            'total' => $pages->total(),
        ]);
    }

    /**
     * Get statistics for dashboard
     */
    public function statistics()
    {
        $totalPages = Page::count();
        $activePages = Page::where('is_active', true)->count();
        
        // Group by type
        $byType = Page::selectRaw('pageable_type, COUNT(*) as count')
            ->groupBy('pageable_type')
            ->get()
            ->map(function($item) {
                $type = str_replace('App\\Models\\', '', $item->pageable_type);
                return [
                    'type' => $type,
                    'count' => $item->count
                ];
            });
        
        // Recent pages (last 30 days)
        $recentPages = Page::where('created_at', '>=', now()->subDays(30))->count();
        
        return response()->json([
            'success' => true,
            'data' => [
                'total_pages' => $totalPages,
                'active_pages' => $activePages,
                'unique_types' => $byType->count(),
                'recent_pages' => $recentPages,
                'by_type' => $byType,
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $type = $request->get('type');
        $id = $request->get('id');
        
        $types = [
            'continent' => 'Continent',
            'country' => 'Pays',
            'region' => 'Région',
            'province' => 'Province',
            'city' => 'Ville'
        ];
        
        $selectedDestination = null;
        if ($type && $id) {
            $model = $this->getModelByType($type);
            $selectedDestination = $model::find($id);
        }
        
        return view('pages.create', compact('types', 'type', 'selectedDestination'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $logContext = [
        'user_id' => auth()->id(),
        'ip' => $request->ip(),
        'user_agent' => $request->userAgent(),
        'data' => $request->except(['html_content', 'css_content']) // Exclure les gros contenus
    ];

    try {
        // Log de début de création
        \Log::info('Début création de page', $logContext);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug',
            'pageable_type' => 'required|string',
            'pageable_id' => 'required|integer',
            'html_content' => 'nullable|string',
            'css_content' => 'nullable|string',
            'is_active' => 'nullable',
        ]);

        if ($validator->fails()) {
            $validationErrors = $validator->errors()->all();
            
            // Log des erreurs de validation
            \Log::warning('Validation failed for page creation', [
                ...$logContext,
                'errors' => $validationErrors
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur de validation',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('warning', 'Veuillez corriger les erreurs ci-dessous');
        }

        // Convert string type to model class
        $modelClass = ucfirst($request->pageable_type);
        
        // Vérifier si la classe existe
        if (!class_exists($modelClass)) {
            \Log::error('Model class not found', [
                ...$logContext,
                'model_class' => $modelClass
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Type de destination non valide'
                ], 400);
            }
            return redirect()->back()
                ->with('error', 'Type de destination non valide')
                ->withInput();
        }

        try {
            // Verify destination exists
            $destination = $modelClass::find($request->pageable_id);
            
            if (!$destination) {
                \Log::warning('Destination not found', [
                    ...$logContext,
                    'pageable_type' => $request->pageable_type,
                    'pageable_id' => $request->pageable_id
                ]);
                
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Destination non trouvée'
                    ], 404);
                }
                return redirect()->back()
                    ->with('error', 'Destination non trouvée')
                    ->withInput();
            }
        } catch (\Exception $e) {
            \Log::error('Error finding destination', [
                ...$logContext,
                'pageable_type' => $request->pageable_type,
                'pageable_id' => $request->pageable_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la vérification de la destination'
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Erreur lors de la vérification de la destination')
                ->withInput();
        }

        // Log avant création de la page
        \Log::info('Creating new page', [
            ...$logContext,
            'page_name' => $request->name,
            'page_slug' => $request->slug,
            'pageable_type' => $modelClass,
            'pageable_id' => $request->pageable_id,
            'html_content_length' => strlen($request->html_content ?? ''),
            'css_content_length' => strlen($request->css_content ?? ''),
            'is_active' => $request->boolean('is_active')
        ]);

        try {
            // Create page
            $page = new Page();
            $page->name = $request->name;
            $page->slug = $request->slug;
            $page->html_content = $request->html_content;
            $page->css_content = $request->css_content;
            $page->is_active = $request->boolean('is_active');
            $page->pageable_type = $modelClass;
            $page->pageable_id = $request->pageable_id;
            $page->save();
            
            // Log de succès
            \Log::info('Page created successfully', [
                ...$logContext,
                'page_id' => $page->id,
                'page_name' => $page->name,
                'page_slug' => $page->slug
            ]);
            
            // Si vous avez un système d'activités/audit, enregistrez l'action
            // $this->logActivity(
            //     'page_created',
            //     "Page '{$page->name}' créée",
            //     $page->toArray(),
            //     auth()->user()
            // );
            
        } catch (\Illuminate\Database\QueryException $dbException) {
            \Log::error('Database error creating page', [
                ...$logContext,
                'error' => $dbException->getMessage(),
                'sql' => $dbException->getSql(),
                'bindings' => $dbException->getBindings(),
                'error_code' => $dbException->getCode()
            ]);
            
            // Vérifier si c'est une erreur de duplicate
            if ($dbException->errorInfo[1] == 1062) { // MySQL duplicate entry
                $errorMessage = 'Cette URL (slug) est déjà utilisée. Veuillez en choisir une autre.';
                
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage,
                        'error_type' => 'duplicate_slug'
                    ], 409);
                }
                return redirect()->back()
                    ->with('error', $errorMessage)
                    ->withInput();
            }
            
            $errorMessage = 'Erreur de base de données lors de la création de la page';
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }
            return redirect()->back()
                ->with('error', $errorMessage)
                ->withInput();
                
        } catch (\Exception $e) {
            \Log::error('General error creating page', [
                ...$logContext,
                'error' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            $errorMessage = 'Une erreur inattendue est survenue lors de la création de la page';
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'error' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }
            return redirect()->back()
                ->with('error', $errorMessage)
                ->withInput();
        }

        // Préparer la réponse de succès
        $responseData = [
            'success' => true,
            'message' => 'Page créée avec succès',
            'data' => [
                'id' => $page->id,
                'name' => $page->name,
                'slug' => $page->slug,
                'url' => route('pages.show', $page->slug),
                'edit_url' => route('pages.edit', $page->id),
                'is_active' => $page->is_active,
                'created_at' => $page->created_at->format('d/m/Y H:i'),
                'destination' => [
                    'type' => $request->pageable_type,
                    'id' => $destination->id,
                    'name' => $destination->name
                ]
            ]
        ];

        if ($request->ajax()) {
            return response()->json($responseData);
        }

        return redirect()->url('geo-map::provinces.index')
            ->with('success', 'Page créée avec succès')
            ->with('page_data', $responseData['data']);

    } catch (\Throwable $th) {
        // Catch any unexpected errors
        \Log::critical('Unexpected error in page store method', [
            ...$logContext,
            'error' => $th->getMessage(),
            'exception' => get_class($th),
            'trace' => $th->getTraceAsString()
        ]);

        // Notify administrators (si vous avez une notification système)
        $this->notifyAdmins('CRITICAL: Page creation failed', [
            'error' => $th->getMessage(),
            'user' => auth()->user() ? auth()->user()->email : 'Guest',
            'time' => now()->toDateTimeString()
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur critique est survenue. Notre équipe technique a été notifiée.'
            ], 500);
        }

        return redirect()->back()
            ->with('error', 'Une erreur critique est survenue. Notre équipe technique a été notifiée.')
            ->withInput();
    }
}

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $page = Page::with('pageable')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
        
        // Increment views or other analytics
        $this->recordPageView($page);
        $province = \App\Models\Province::findOrFail($page->pageable_id);
        
        return view('geo-map::provinces.index', compact('page','province'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $page = Page::with('pageable')->findOrFail($id);
        
        // Extract type from pageable_type
        $type = strtolower(str_replace('App\\Models\\', '', $page->pageable_type));
        
        $types = [
            'continent' => 'Continent',
            'country' => 'Pays',
            'region' => 'Région',
            'province' => 'Province',
            'city' => 'Ville'
        ];
        
        return view('pages.edit', compact('page', 'types', 'type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $logContext = [
        'user_id' => auth()->id(),
        'ip' => $request->ip(),
        'user_agent' => $request->userAgent(),
        'page_id' => $id,
        'data' => $request->except(['html_content', 'css_content'])
    ];

    try {
        // Log de début de mise à jour
        \Log::info('Début mise à jour de page', $logContext);
        
        try {
            $page = Page::findOrFail($id);
            
            // Log avant modification pour audit
            $originalData = [
                'name' => $page->name,
                'slug' => $page->slug,
                'html_content_length' => strlen($page->html_content ?? ''),
                'css_content_length' => strlen($page->css_content ?? ''),
                'is_active' => $page->is_active,
                'pageable_type' => $page->pageable_type,
                'pageable_id' => $page->pageable_id
            ];
            
            \Log::info('Page found for update', [
                ...$logContext,
                'original_data' => $originalData
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::warning('Page not found for update', [
                ...$logContext,
                'error' => $e->getMessage()
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Page non trouvée'
                ], 404);
            }
            return redirect()->route('pages.index')
                ->with('error', 'Page non trouvée');
                
        } catch (\Exception $e) {
            \Log::error('Error finding page for update', [
                ...$logContext,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la recherche de la page'
                ], 500);
            }
            return redirect()->route('pages.index')
                ->with('error', 'Erreur lors de la recherche de la page');
        }

        // Validation des données
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('pages', 'slug')->ignore($page->id)
            ],
            'html_content' => 'nullable|string',
            'css_content' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            $validationErrors = $validator->errors()->all();
            
            \Log::warning('Validation failed for page update', [
                ...$logContext,
                'page_name' => $page->name,
                'errors' => $validationErrors
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur de validation',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('warning', 'Veuillez corriger les erreurs ci-dessous');
        }

        // Vérifier si le slug a changé
        $slugChanged = $page->slug !== $request->slug;
        
        // Préparer les données de mise à jour
        $updateData = [
            'name' => $request->name,
            'slug' => $request->slug,
            'html_content' => $request->html_content,
            'css_content' => $request->css_content,
            'is_active' => $request->boolean('is_active'),
        ];
        
        // Log avant mise à jour
        \Log::info('Updating page data', [
            ...$logContext,
            'page_name' => $page->name,
            'slug_changed' => $slugChanged,
            'new_html_length' => strlen($request->html_content ?? ''),
            'new_css_length' => strlen($request->css_content ?? ''),
            'is_active_changed' => $page->is_active != $request->boolean('is_active'),
            'update_summary' => array_keys($updateData)
        ]);

        try {
            // Mettre à jour la page
            $page->name = $request->name;
            $page->slug = $request->slug;
            $page->html_content = $request->html_content;
            $page->css_content = $request->css_content;
            $page->is_active = $request->boolean('is_active');
            $page->save();
            
            // Récupérer les données après mise à jour
            $page->refresh();
            
            // Déterminer quels champs ont changé
            $changedFields = [];
            foreach ($updateData as $field => $newValue) {
                if ($field === 'is_active') {
                    $newValue = (bool) $newValue;
                    $oldValue = (bool) $originalData[$field] ?? false;
                } elseif (in_array($field, ['html_content', 'css_content'])) {
                    $oldValue = $originalData[$field . '_length'] ?? 0;
                    $newValue = strlen($newValue ?? '');
                } else {
                    $oldValue = $originalData[$field] ?? null;
                }
                
                if ($oldValue !== $newValue) {
                    $changedFields[$field] = [
                        'old' => $oldValue,
                        'new' => $field === 'is_active' ? ($newValue ? 'Actif' : 'Inactif') : $newValue
                    ];
                }
            }
            
            // Log de succès avec détails des modifications
            \Log::info('Page updated successfully', [
                ...$logContext,
                'page_name' => $page->name,
                'changed_fields' => $changedFields,
                'slug_changed' => $slugChanged,
                'update_summary' => array_keys($changedFields)
            ]);
            
            // Enregistrer l'activité d'audit
            $this->logActivity(
                'page_updated',
                "Page '{$page->name}' mise à jour",
                [
                    'page_id' => $page->id,
                    'changed_fields' => $changedFields,
                    'original_data' => $originalData,
                    'new_data' => $updateData
                ],
                auth()->user()
            );
            
            // Si le slug a changé, loguer un avertissement spécifique
            if ($slugChanged) {
                \Log::warning('Page slug changed', [
                    ...$logContext,
                    'old_slug' => $originalData['slug'],
                    'new_slug' => $page->slug,
                    'page_name' => $page->name
                ]);
                
                // Optionnel : invalider le cache pour l'ancien slug
                // Cache::forget("page.{$originalData['slug']}");
            }
            
            // Notifier si la page est devenue active/inactive
            if (isset($changedFields['is_active'])) {
                $statusMessage = $page->is_active 
                    ? "Page '{$page->name}' est maintenant active" 
                    : "Page '{$page->name}' est maintenant inactive";
                
                \Log::info($statusMessage, [
                    ...$logContext,
                    'page_name' => $page->name,
                    'old_status' => $originalData['is_active'] ? 'Actif' : 'Inactif',
                    'new_status' => $page->is_active ? 'Actif' : 'Inactif'
                ]);
            }
            
        } catch (\Illuminate\Database\QueryException $dbException) {
            \Log::error('Database error updating page', [
                ...$logContext,
                'page_name' => $page->name,
                'error' => $dbException->getMessage(),
                'sql' => $dbException->getSql(),
                'bindings' => $dbException->getBindings(),
                'error_code' => $dbException->getCode(),
                'error_info' => $dbException->errorInfo
            ]);
            
            // Vérifier les erreurs spécifiques
            if ($dbException->errorInfo[1] == 1062) { // MySQL duplicate entry
                $errorMessage = 'Cette URL (slug) est déjà utilisée. Veuillez en choisir une autre.';
                
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage,
                        'error_type' => 'duplicate_slug'
                    ], 409);
                }
                return redirect()->back()
                    ->with('error', $errorMessage)
                    ->withInput();
            }
            
            $errorMessage = 'Erreur de base de données lors de la mise à jour de la page';
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }
            return redirect()->back()
                ->with('error', $errorMessage)
                ->withInput();
                
        } catch (\PDOException $pdoException) {
            \Log::error('PDO error updating page', [
                ...$logContext,
                'page_name' => $page->name,
                'error' => $pdoException->getMessage(),
                'error_code' => $pdoException->getCode(),
                'sql_state' => $pdoException->getCode()
            ]);
            
            $errorMessage = 'Erreur de connexion à la base de données';
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }
            return redirect()->back()
                ->with('error', $errorMessage)
                ->withInput();
                
        } catch (\Exception $e) {
            \Log::error('General error updating page', [
                ...$logContext,
                'page_name' => $page->name,
                'error' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            $errorMessage = 'Une erreur inattendue est survenue lors de la mise à jour de la page';
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'error' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }
            return redirect()->back()
                ->with('error', $errorMessage)
                ->withInput();
        }

        // Préparer la réponse de succès
        $responseData = [
            'success' => true,
            'message' => 'Page mise à jour avec succès',
            'data' => [
                'id' => $page->id,
                'name' => $page->name,
                'slug' => $page->slug,
                'url' => route('pages.show', $page->slug),
                'edit_url' => route('pages.edit', $page->id),
                'is_active' => $page->is_active,
                'created_at' => $page->created_at->format('d/m/Y H:i'),
                'updated_at' => $page->updated_at->format('d/m/Y H:i'),
                'changes' => $changedFields ?? [],
                'destination' => [
                    'type' => class_basename($page->pageable_type),
                    'id' => $page->pageable_id,
                    'name' => $page->pageable->name ?? 'N/A'
                ]
            ]
        ];

        // Optionnel : vider le cache si nécessaire
        $this->clearPageCache($page);

        if ($request->ajax()) {
            return response()->json($responseData);
        }

        return redirect()->route('pages.index')
            ->with('success', 'Page mise à jour avec succès')
            ->with('page_data', $responseData['data'])
            ->with('changes', $changedFields ?? []);

    } catch (\Throwable $th) {
        // Catch any unexpected errors
        \Log::critical('Unexpected error in page update method', [
            ...$logContext,
            'error' => $th->getMessage(),
            'exception' => get_class($th),
            'trace' => $th->getTraceAsString()
        ]);

        // Notifier les administrateurs
        $this->notifyAdmins('CRITICAL: Page update failed', [
            'page_id' => $id,
            'error' => $th->getMessage(),
            'user' => auth()->user() ? auth()->user()->email : 'Guest',
            'time' => now()->toDateTimeString()
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur critique est survenue. Notre équipe technique a été notifiée.'
            ], 500);
        }

        return redirect()->back()
            ->with('error', 'Une erreur critique est survenue. Notre équipe technique a été notifiée.')
            ->withInput();
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, Request $request)
    {
        $page = Page::findOrFail($id);
        $page->delete();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Page supprimée avec succès'
            ]);
        }
        
        return redirect()->route('pages.index')
            ->with('success', 'Page supprimée avec succès');
    }

    /**
     * Get destinations for a specific type
     */
    public function getDestinations($type)
    {
        $model = $this->getModelByType($type);
        $destinations = $model::select('id', 'name', 'code')->get();
        
        return response()->json([
            'success' => true,
            'data' => $destinations
        ]);
    }

    /**
     * Get pages by destination type and ID
     */
    public function getPagesByDestination($type, $id)
    {
        $model = $this->getModelByType($type);
        $destination = $model::findOrFail($id);
        
        $pages = $destination->pages()
            ->select('id', 'name', 'slug', 'is_active', 'created_at', 'updated_at')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'destination' => [
                'id' => $destination->id,
                'name' => $destination->name,
                'type' => $type
            ],
            'pages' => $pages,
            'count' => $pages->count()
        ]);
    }

    /**
     * Create page for specific destination
     */
    public function createForDestination(Request $request, $type, $id)
    {
        $model = $this->getModelByType($type);
        $destination = $model::findOrFail($id);
        
        $types = [
            'continent' => 'Continent',
            'country' => 'Pays',
            'region' => 'Région',
            'province' => 'Province',
            'city' => 'Ville'
        ];
        
        return view('pages.create', [
            'types' => $types,
            'type' => $type,
            'selectedDestination' => $destination,
            'preselected' => true
        ]);
    }

    /**
     * Get all pages grouped by type
     */
    public function pagesByType($type = null)
    {
        if ($type) {
            $modelClass = 'App\\Models\\' . ucfirst($type);
            $pages = Page::where('pageable_type', $modelClass)
                ->with('pageable')
                ->orderBy('name')
                ->paginate(20);
                
            $typeLabel = $this->getTypeLabel($type);
            
            return view('pages.by-type', [
                'pages' => $pages,
                'type' => $type,
                'typeLabel' => $typeLabel
            ]);
        }
        
        // Get all pages grouped by type
        $pagesByType = [];
        $types = ['continent', 'country', 'region', 'province', 'city'];
        
        foreach ($types as $type) {
            $modelClass = 'App\\Models\\' . ucfirst($type);
            $pages = Page::where('pageable_type', $modelClass)
                ->with('pageable')
                ->orderBy('name')
                ->limit(5)
                ->get();
                
            if ($pages->count() > 0) {
                $pagesByType[$type] = [
                    'label' => $this->getTypeLabel($type),
                    'pages' => $pages,
                    'count' => Page::where('pageable_type', $modelClass)->count()
                ];
            }
        }
        
        return view('pages.by-type-overview', compact('pagesByType'));
    }

    /**
     * Search pages
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $pages = Page::with('pageable')
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('slug', 'LIKE', "%{$query}%")
                  ->orWhere('html_content', 'LIKE', "%{$query}%");
            })
            ->where('is_active', true)
            ->orderBy('name')
            ->paginate(20);
        
        return view('pages.search', compact('pages', 'query'));
    }

    /**
     * API endpoint for page search
     */
    public function apiSearch(Request $request)
    {
        $query = $request->get('q');
        $type = $request->get('type');
        
        $searchQuery = Page::with('pageable')
            ->where('is_active', true);
        
        if ($query) {
            $searchQuery->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('slug', 'LIKE', "%{$query}%");
            });
        }
        
        if ($type) {
            $modelClass = 'App\\Models\\' . ucfirst($type);
            $searchQuery->where('pageable_type', $modelClass);
        }
        
        $pages = $searchQuery->orderBy('name')->limit(20)->get();
        
        return response()->json([
            'success' => true,
            'data' => $pages->map(function($page) {
                return [
                    'id' => $page->id,
                    'name' => $page->name,
                    'slug' => $page->slug,
                    'type' => strtolower(str_replace('App\\Models\\', '', $page->pageable_type)),
                    'destination' => $page->pageable ? $page->pageable->name : null,
                    'url' => url("/pages/{$page->slug}")
                ];
            })
        ]);
    }

    /**
     * Toggle page status
     */
    public function toggleStatus($id, Request $request)
    {
        $page = Page::findOrFail($id);
        $page->is_active = !$page->is_active;
        $page->save();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour',
                'is_active' => $page->is_active
            ]);
        }
        
        return redirect()->back()->with('success', 'Statut mis à jour');
    }

    /**
     * Generate slug from name
     */
    public function generateSlug(Request $request)
    {
        $name = $request->get('name');
        
        if (!$name) {
            return response()->json([
                'success' => false,
                'message' => 'Nom requis'
            ], 400);
        }
        
        $slug = Str::slug($name);
        
        // Check if slug exists
        $counter = 1;
        $originalSlug = $slug;
        
        while (Page::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return response()->json([
            'success' => true,
            'slug' => $slug
        ]);
    }

    /**
     * Get pages by specific destination type and ID for dropdown
     */
    public function getPagesForDropdown($type, $id)
    {
        $model = $this->getModelByType($type);
        $destination = $model::findOrFail($id);
        
        $pages = $destination->pages()
            ->where('is_active', true)
            ->select('id', 'name', 'slug')
            ->orderBy('name')
            ->get()
            ->map(function($page) {
                return [
                    'id' => $page->id,
                    'name' => $page->name,
                    'slug' => $page->slug,
                    'url' => url("/pages/{$page->slug}")
                ];
            });
        
        return response()->json([
            'success' => true,
            'data' => $pages
        ]);
    }

    /**
     * Helper method to get model by type
     */
    private function getModelByType($type)
    {
        return match($type) {
            'continent' => Continent::class,
            'country' => Country::class,
            'region' => Region::class,
            'province' => Province::class,
            'city' => City::class,
            default => abort(404, 'Type non valide')
        };
    }

    /**
     * Helper method to get type label
     */
    private function getTypeLabel($type)
    {
        return match($type) {
            'continent' => 'Continent',
            'country' => 'Pays',
            'region' => 'Région',
            'province' => 'Province',
            'city' => 'Ville',
            default => $type
        };
    }

    /**
     * Record page view for analytics
     */
    private function recordPageView(Page $page)
    {
        // You can implement view counting, analytics, etc.
        // Example:
        // $page->increment('views');
        // or save to analytics table
    }

    /**
     * Preview page content
     */
    public function preview(Request $request)
    {
        $html = $request->get('html_content', '');
        $css = $request->get('css_content', '');
        
        $fullHtml = "<!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1'>
            <style>
                body { font-family: Arial, sans-serif; padding: 20px; }
                .page-preview { max-width: 1000px; margin: 0 auto; }
                {$css}
            </style>
        </head>
        <body>
            <div class='page-preview'>
                {$html}
            </div>
        </body>
        </html>";
        
        return response($fullHtml);
    }

    /**
     * Export pages
     */
    public function export(Request $request)
    {
        $type = $request->get('type');
        $format = $request->get('format', 'json');
        
        $query = Page::with('pageable');
        
        if ($type) {
            $modelClass = 'App\\Models\\' . ucfirst($type);
            $query->where('pageable_type', $modelClass);
        }
        
        $pages = $query->get();
        
        if ($format === 'json') {
            return response()->json([
                'success' => true,
                'data' => $pages
            ]);
        } elseif ($format === 'csv') {
            // Implement CSV export
            $csv = "ID,Nom,Slug,Type,Destination,Statut,Créé le\n";
            
            foreach ($pages as $page) {
                $type = str_replace('App\\Models\\', '', $page->pageable_type);
                $destination = $page->pageable ? $page->pageable->name : 'N/A';
                $status = $page->is_active ? 'Actif' : 'Inactif';
                
                $csv .= "{$page->id},{$page->name},{$page->slug},{$type},{$destination},{$status},{$page->created_at}\n";
            }
            
            return response($csv, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="pages_' . date('Y-m-d') . '.csv"',
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Format non supporté'
        ], 400);
    }

    /**
     * Bulk actions
     */
    public function bulkActions(Request $request)
    {
        $action = $request->get('action');
        $ids = $request->get('ids', []);
        
        if (empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune page sélectionnée'
            ]);
        }
        
        switch ($action) {
            case 'activate':
                Page::whereIn('id', $ids)->update(['is_active' => true]);
                $message = 'Pages activées avec succès';
                break;
                
            case 'deactivate':
                Page::whereIn('id', $ids)->update(['is_active' => false]);
                $message = 'Pages désactivées avec succès';
                break;
                
            case 'delete':
                Page::whereIn('id', $ids)->delete();
                $message = 'Pages supprimées avec succès';
                break;
                
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Action non valide'
                ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

     public function showPage(Page $page)
    {
        return response()->json([
            'success' => true,
            'data' => $page
        ]);
    }
}