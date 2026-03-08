<?php

namespace Vendor\Website\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Models\User;
use App\Models\Category;
use Vendor\Website\Requests\StoreWebsiteRequest;
use Vendor\Website\Requests\UpdateWebsiteRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Vendor\Website\Services\WebsiteCreationService;
use Illuminate\Support\Facades\Log;
use Exception;

class WebsiteController extends Controller
{

     protected $websiteCreationService;
    
    /**
     * Constructor with dependency injection
     */
    public function __construct(WebsiteCreationService $websiteCreationService)
    {
        $this->websiteCreationService = $websiteCreationService;
    }
    // Statuts disponibles
    private $statuses = [
        'active' => 'Actif',
        'inactive' => 'Inactif',
        'maintenance' => 'Maintenance',
        'development' => 'En développement'
    ];

    // Types de templates
    private $templateTypes = [
        'basic' => 'Basic',
        'premium' => 'Premium',
        'enterprise' => 'Enterprise',
        'custom' => 'Personnalisé'
    ];

    // Schémas de couleurs
    private $colorSchemes = [
        'light' => 'Clair',
        'dark' => 'Sombre',
        'blue' => 'Bleu',
        'green' => 'Vert',
        'red' => 'Rouge',
        'purple' => 'Violet',
        'orange' => 'Orange'
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Website::with(['user', 'category', 'customer'])
    ->addSelect(['websites.*'])
    ->selectSub(function ($query) {
        $query->select('name')
            ->from('customers')
            ->whereColumn('customers.user_id', 'websites.user_id')
            ->limit(1);
    }, 'customer_name');
        
        // Recherche
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('url', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        
        // Filtres
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('categorie_id') && $request->categorie_id != '') {
            $query->where('categorie_id', $request->categorie_id);
        }
        
        if ($request->has('user_id') && $request->user_id != '') {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        // Pagination
        $perPage = $request->get('per_page', 15);
        $websites = $query->paginate($perPage);
        
        // Si c'est une requête AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $websites->items(),
                'current_page' => $websites->currentPage(),
                'last_page' => $websites->lastPage(),
                'per_page' => $websites->perPage(),
                'total' => $websites->total(),
                'prev_page_url' => $websites->previousPageUrl(),
                'next_page_url' => $websites->nextPageUrl(),
            ]);
        }
        
        // Pour la vue normale
        $users = User::has('customer')->with('customer')->get();
        $categories = Category::all();
        
        return view('website::index', [
            'websites' => $websites,
            'users' => $users,
            'categories' => $categories,
            'statuses' => $this->statuses,
            'templateTypes' => $this->templateTypes,
            'colorSchemes' => $this->colorSchemes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::has('customer')->with('customer')->get();
        $categories = Category::all();
        
        return response()->json([
            'success' => true,
            'users' => $users,
            'categories' => $categories,
            'statuses' => $this->statuses,
            'templateTypes' => $this->templateTypes,
            'colorSchemes' => $this->colorSchemes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWebsiteRequest $request)
{
    $startTime = microtime(true);
    
    try {
        Log::info('Website creation request received', [
            'request_data' => $request->all(),
            'user_id' => auth()->id()
        ]);
        
        $data = $request->validated();
        Log::debug('Validated data', $data);
        
        // Use service to create website with all defaults
        $website = $this->websiteCreationService->createWebsiteWithDefaults($data);
        
        $executionTime = round((microtime(true) - $startTime) * 1000, 2);
        
        Log::info('Website created successfully', [
            'website_id' => $website->id,
            'execution_time_ms' => $executionTime,
            'has_template' => $website->templates()->exists()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Site web créé avec succès.',
            'website' => $website,
            'template_created' => $website->templates()->exists(),
            'execution_time_ms' => $executionTime
        ]);
        
    } catch (\Exception $e) {
        $executionTime = round((microtime(true) - $startTime) * 1000, 2);
        
        Log::error('Website creation failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'request_data' => $request->all(),
            'user_id' => auth()->id(),
            'execution_time_ms' => $executionTime
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la création du site web.',
            'error' => config('app.debug') ? $e->getMessage() : null,
            'execution_time_ms' => $executionTime
        ], 500);
    }
}
    
    /**
     * Create just a template for existing website
     */
    public function createTemplateForWebsite(Website $website): JsonResponse
    {
        try {
            $template = $this->websiteCreationService->createDefaultTemplate($website);
            
            return response()->json([
                'success' => true,
                'message' => 'Template créé avec succès.',
                'template' => $template
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Template creation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du template.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Website $website)
    {
        $website->load(['user', 'category', 'customer']);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'website' => $website
            ]);
        }
        
        return view('websites.show', compact('website'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Website $website)
    {
        $website->load(['user', 'category']);
        $users = User::has('customer')->with('customer')->get();
        $categories = Category::all();
        
        return response()->json([
            'success' => true,
            'website' => $website,
            'users' => $users,
            'categories' => $categories,
            'statuses' => $this->statuses,
            'templateTypes' => $this->templateTypes,
            'colorSchemes' => $this->colorSchemes
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWebsiteRequest $request, Website $website): JsonResponse
    {
        try {
            $data = $request->validated();
            
            // Gérer les features comme un tableau JSON
            if ($request->has('features')) {
                $features = is_array($request->features) ? $request->features : explode(',', $request->features);
                $data['features'] = json_encode($features);
            }
            
            $website->update($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Site web mis à jour avec succès.',
                'website' => $website->load(['user', 'category'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du site web : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Website $website): JsonResponse
    {
        try {
            $websiteName = $website->name;
            $website->delete();
            
            return response()->json([
                'success' => true,
                'message' => "Le site web '{$websiteName}' a été supprimé avec succès."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du site web : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete websites.
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $request->validate([
            'website_ids' => 'required|array',
            'website_ids.*' => 'exists:websites,id'
        ]);
        
        try {
            $websiteIds = $request->input('website_ids');
            $deletedCount = Website::whereIn('id', $websiteIds)->delete();
            
            return response()->json([
                'success' => true,
                'message' => "{$deletedCount} site(s) web supprimé(s) avec succès.",
                'deleted_count' => $deletedCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression en masse : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get website statistics.
     */
    public function statistics(): JsonResponse
    {
        try {
            $totalWebsites = Website::count();
            $activeWebsites = Website::where('status', 'active')->count();
            $websitesByCategory = Category::withCount('websites')->get();
            $thisMonth = Website::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
            
            $websitesByStatus = Website::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get();
            
            $recentWebsites = Website::with(['user', 'category'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'total' => $totalWebsites,
                    'active' => $activeWebsites,
                    'this_month' => $thisMonth,
                    'by_category' => $websitesByCategory,
                    'by_status' => $websitesByStatus,
                    'recent' => $recentWebsites
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement des statistiques.'
            ], 500);
        }
    }

    /**
     * Change website status.
     */
    public function changeStatus(Request $request, Website $website): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:active,inactive,maintenance,development'
        ]);
        
        try {
            $oldStatus = $website->status;
            $website->update(['status' => $request->status]);
            
            return response()->json([
                'success' => true,
                'message' => "Statut changé de '{$oldStatus}' à '{$request->status}'",
                'website' => $website
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du changement de statut : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get websites by user.
     */
    public function byUser(User $user): JsonResponse
    {
        try {
            $websites = Website::where('user_id', $user->id)
                ->with('category')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $websites
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des sites web.'
            ], 500);
        }
    }
}