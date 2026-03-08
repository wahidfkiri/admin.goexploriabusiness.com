<?php

namespace Vendor\Project\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Etablissement;
use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class ProjectController extends Controller
{
    /**
     * Constructeur avec middleware
     */
    public function __construct()
    {
        // $this->middleware('auth');
        // $this->middleware('verified')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the projects.
     */
    public function index(Request $request)
    {
        // Si c'est une requête AJAX
        if ($request->ajax()) {
            return $this->getProjectsData($request);
        }
        
        // Récupérer les données pour les filtres
        $clients = Etablissement::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);
        
        $users = User::where('is_active', true)
            // ->where('etablissement_id', Auth::user()->etablissement_id)
            ->orderBy('name')
            ->get(['id', 'name']);
        
        $statuses = [
            'planning' => 'Planification',
            'in_progress' => 'En cours',
            'on_hold' => 'En pause',
            'completed' => 'Terminé',
            'cancelled' => 'Annulé',
        ];
        
        return view('project::index', compact('clients', 'users', 'statuses'));
    }

    /**
     * Get projects data for AJAX requests.
     */
    private function getProjectsData(Request $request)
    {
        try {
            $query = Project::with([
                'etablissement', 
                'user', 
                'client',
                'tasks' => function($q) {
                    $q->select('id', 'project_id', 'status', 'estimated_hours', 'estimated_cost');
                }
            ]);
            // ->where('etablissement_id', Auth::user()->etablissement_id);
            
            // Apply search
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('contract_number', 'like', "%{$search}%")
                      ->orWhere('contact_name', 'like', "%{$search}%")
                      ->orWhereHas('client', function($clientQuery) use ($search) {
                          $clientQuery->where('name', 'like', "%{$search}%");
                      });
                });
            }
            
            // Apply filters
            if ($request->filled('client_id')) {
                $query->where('client_id', $request->client_id);
            }
            
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->filled('is_active')) {
                $query->where('is_active', $request->is_active === '1');
            }
            
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', Carbon::parse($request->date_from));
            }
            
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', Carbon::parse($request->date_to));
            }
            
            if ($request->filled('budget_min')) {
                $query->where('estimated_budget', '>=', $request->budget_min);
            }
            
            if ($request->filled('budget_max')) {
                $query->where('estimated_budget', '<=', $request->budget_max);
            }
            
            if ($request->filled('has_tasks')) {
                if ($request->has_tasks === 'yes') {
                    $query->has('tasks', '>', 0);
                } elseif ($request->has_tasks === 'no') {
                    $query->doesntHave('tasks');
                }
            }
            
            // Apply sorting
            $sortField = $request->get('sort_by', 'created_at');
            $sortDirection = $request->get('sort_direction', 'desc');
            
            // Validate sort field
            $allowedSortFields = ['name', 'status', 'created_at', 'updated_at', 'start_date', 'end_date', 'estimated_budget'];
            if (in_array($sortField, $allowedSortFields)) {
                $query->orderBy($sortField, $sortDirection);
            } else {
                $query->orderBy('created_at', 'desc');
            }
            
            // Get total count before pagination for stats
            $totalProjects = $query->count();
            
            // Pagination
            $perPage = $request->get('per_page', 15);
            $projects = $query->paginate($perPage);
            
            // Transform projects for response
            $projects->getCollection()->transform(function($project) {
                $tasksCount = $project->tasks->count();
                $completedTasksCount = $project->tasks->where('status', 'approved')->count();
                $totalHours = $project->tasks->sum('estimated_hours');
                $totalCost = $project->tasks->sum('estimated_cost');
                
                return [
                    'id' => $project->id,
                    'name' => $project->name,
                    'description' => \Str::limit(strip_tags($project->description), 100),
                    'contract_number' => $project->contract_number,
                    'contact_name' => $project->contact_name,
                    'start_date' => $project->start_date ? $project->start_date->format('d/m/Y') : null,
                    'end_date' => $project->end_date ? $project->end_date->format('d/m/Y') : null,
                    'start_date_raw' => $project->start_date,
                    'end_date_raw' => $project->end_date,
                    'status' => $project->status,
                    'status_formatted' => $project->formatted_status,
                    'status_color' => $project->status_color,
                    'estimated_hours' => $project->estimated_hours,
                    'estimated_budget' => $project->estimated_budget,
                    'estimated_budget_formatted' => number_format($project->estimated_budget, 2, ',', ' ') . ' €',
                    'progress' => $project->progress,
                    'tasks_count' => $tasksCount,
                    'completed_tasks_count' => $completedTasksCount,
                    'tasks_completion_percentage' => $tasksCount > 0 ? round(($completedTasksCount / $tasksCount) * 100) : 0,
                    'total_tasks_hours' => $totalHours,
                    'total_tasks_cost' => $totalCost,
                    'total_tasks_cost_formatted' => number_format($totalCost, 2, ',', ' ') . ' €',
                    'is_active' => $project->is_active,
                    'is_overdue' => $project->isOverdue(),
                    'days_remaining' => $project->daysRemaining,
                    'created_at' => $project->created_at->format('d/m/Y H:i'),
                    'created_at_raw' => $project->created_at,
                    'updated_at' => $project->updated_at->format('d/m/Y H:i'),
                    
                    // Relations
                    'etablissement' => $project->etablissement ? [
                        'id' => $project->etablissement->id,
                        'name' => $project->etablissement->name,
                        'ville' => $project->etablissement->ville,
                    ] : null,
                    
                    'user' => $project->user ? [
                        'id' => $project->user->id,
                        'name' => $project->user->name,
                        'email' => $project->user->email,
                        'avatar' => $project->user->avatar ?? null,
                    ] : null,
                    
                    'client' => $project->client ? [
                        'id' => $project->client->id,
                        'name' => $project->client->name,
                        'ville' => $project->client->ville,
                        'phone' => $project->client->phone,
                        'email' => $project->client->email_contact,
                    ] : null,
                    
                    // URLs
                    'urls' => [
                        'show' => route('projects.show', $project->id),
                        'edit' => route('projects.edit', $project->id),
                        'delete' => route('projects.destroy', $project->id),
                        'tasks' => route('projects.tasks', $project->id),
                    ]
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $projects->items(),
                'current_page' => $projects->currentPage(),
                'last_page' => $projects->lastPage(),
                'per_page' => $projects->perPage(),
                'total' => $projects->total(),
                'from' => $projects->firstItem(),
                'to' => $projects->lastItem(),
                'prev_page_url' => $projects->previousPageUrl(),
                'next_page_url' => $projects->nextPageUrl(),
                'total_projects' => $totalProjects,
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error loading projects: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement des projets',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get project statistics.
     */
    public function statistics(Request $request)
    {
        try {
            $etablissementId = Auth::user()->etablissement_id;
            
            // Statistiques de base
            $total = Project::count();
            $active = Project::where('is_active', true)
                ->count();
            
            // Statistiques par statut
            $byStatus = Project::select('status', DB::raw('count(*) as total'), DB::raw('sum(estimated_budget) as total_budget'))
                ->groupBy('status')
                ->get()
                ->keyBy('status');
            
            // Statistiques temporelles
            $currentMonth = now()->startOfMonth();
            $lastMonth = now()->subMonth()->startOfMonth();
            
            $createdThisMonth = Project::where('created_at', '>=', $currentMonth)
                ->count();
            
            $createdLastMonth = Project::whereBetween('created_at', [$lastMonth, $currentMonth])
                ->count();
            
            $completedThisMonth = Project::where('status', 'completed')
                ->where('updated_at', '>=', $currentMonth)
                ->count();
            
            // Statistiques des tâches
            $tasksStats = DB::table('tasks')
                ->join('projects', 'tasks.project_id', '=', 'projects.id')
                ->select(
                    DB::raw('count(*) as total_tasks'),
                    DB::raw('sum(case when tasks.status = "approved" then 1 else 0 end) as completed_tasks'),
                    DB::raw('sum(tasks.estimated_hours) as total_hours'),
                    DB::raw('sum(tasks.estimated_cost) as total_cost')
                )
                ->first();
            
            // Projets par client (top 5)
            $byClient = Project::whereNotNull('client_id')
                ->select('client_id', DB::raw('count(*) as total'), DB::raw('sum(estimated_budget) as total_budget'))
                ->with('client:id,name')
                ->groupBy('client_id')
                ->orderByDesc('total')
                ->limit(5)
                ->get()
                ->map(function($item) {
                    return [
                        'client_id' => $item->client_id,
                        'client_name' => $item->client->name ?? 'Client inconnu',
                        'total' => $item->total,
                        'total_budget' => $item->total_budget,
                        'total_budget_formatted' => number_format($item->total_budget, 2, ',', ' ') . ' €',
                    ];
                });
            
            // Projets par responsable
            $byUser = Project::whereNotNull('user_id')
                ->select('user_id', DB::raw('count(*) as total'))
                ->with('user:id,name')
                ->groupBy('user_id')
                ->orderByDesc('total')
                ->limit(5)
                ->get()
                ->map(function($item) {
                    return [
                        'user_id' => $item->user_id,
                        'user_name' => $item->user->name ?? 'Utilisateur inconnu',
                        'total' => $item->total,
                    ];
                });
            
            // Projets en retard
            $overdueProjects = Project::whereNotNull('end_date')
                ->where('end_date', '<', now())
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->count();
            
            // Projets sans tâches
            $projectsWithoutTasks = Project::doesntHave('tasks')
                ->count();
            
            // Totaux financiers
            $totalBudget = Project::sum('estimated_budget');
            
            $averageBudget = $total > 0 ? $totalBudget / $total : 0;
            
            // Évolution mensuelle (6 derniers mois)
            $monthlyEvolution = collect(range(5, 0))->map(function($monthsAgo) use ($etablissementId) {
                $date = now()->subMonths($monthsAgo);
                $startOfMonth = $date->copy()->startOfMonth();
                $endOfMonth = $date->copy()->endOfMonth();
                
                $created = Project::where('etablissement_id', $etablissementId)
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->count();
                
                $completed = Project::where('etablissement_id', $etablissementId)
                    ->where('status', 'completed')
                    ->whereBetween('updated_at', [$startOfMonth, $endOfMonth])
                    ->count();
                
                return [
                    'month' => $date->format('Y-m'),
                    'month_label' => $date->locale('fr')->isoFormat('MMM YYYY'),
                    'created' => $created,
                    'completed' => $completed,
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => [
                    // Totaux
                    'total' => $total,
                    'active' => $active,
                    'inactive' => $total - $active,
                    
                    // Par statut
                    'planning' => $byStatus['planning']->total ?? 0,
                    'in_progress' => $byStatus['in_progress']->total ?? 0,
                    'on_hold' => $byStatus['on_hold']->total ?? 0,
                    'completed' => $byStatus['completed']->total ?? 0,
                    'cancelled' => $byStatus['cancelled']->total ?? 0,
                    
                    // Budgets par statut
                    'planning_budget' => $byStatus['planning']->total_budget ?? 0,
                    'in_progress_budget' => $byStatus['in_progress']->total_budget ?? 0,
                    'on_hold_budget' => $byStatus['on_hold']->total_budget ?? 0,
                    'completed_budget' => $byStatus['completed']->total_budget ?? 0,
                    'cancelled_budget' => $byStatus['cancelled']->total_budget ?? 0,
                    
                    // Statistiques temporelles
                    'created_this_month' => $createdThisMonth,
                    'created_last_month' => $createdLastMonth,
                    'completed_this_month' => $completedThisMonth,
                    'growth_percentage' => $createdLastMonth > 0 
                        ? round((($createdThisMonth - $createdLastMonth) / $createdLastMonth) * 100, 1)
                        : 0,
                    
                    // Statistiques des tâches
                    'total_tasks' => $tasksStats->total_tasks ?? 0,
                    'completed_tasks' => $tasksStats->completed_tasks ?? 0,
                    'tasks_completion_percentage' => ($tasksStats->total_tasks ?? 0) > 0 
                        ? round((($tasksStats->completed_tasks ?? 0) / ($tasksStats->total_tasks ?? 1)) * 100, 1)
                        : 0,
                    'total_hours' => $tasksStats->total_hours ?? 0,
                    'total_tasks_cost' => $tasksStats->total_cost ?? 0,
                    'total_tasks_cost_formatted' => number_format($tasksStats->total_cost ?? 0, 2, ',', ' ') . ' €',
                    
                    // Analyses
                    'by_client' => $byClient,
                    'by_user' => $byUser,
                    'overdue_projects' => $overdueProjects,
                    'projects_without_tasks' => $projectsWithoutTasks,
                    
                    // Finances
                    'total_budget' => $totalBudget,
                    'total_budget_formatted' => number_format($totalBudget, 2, ',', ' ') . ' €',
                    'average_budget' => $averageBudget,
                    'average_budget_formatted' => number_format($averageBudget, 2, ',', ' ') . ' €',
                    
                    // Évolution
                    'monthly_evolution' => $monthlyEvolution,
                    
                    // Pourcentages
                    'completion_rate' => $total > 0 
                        ? round((($byStatus['completed']->total ?? 0) / $total) * 100, 1)
                        : 0,
                    'active_percentage' => $total > 0 
                        ? round(($active / $total) * 100, 1)
                        : 0,
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error loading statistics: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement des statistiques',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Show the form for creating a new project.
     */
    public function create()
    {
        $clients = Etablissement::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'ville', 'phone', 'email_contact']);
        
        $users = User::where('is_active', true)
            // ->where('etablissement_id', Auth::user()->etablissement_id)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);
        
        $statuses = [
            'planning' => 'Planification',
            'in_progress' => 'En cours',
            'on_hold' => 'En pause',
            'completed' => 'Terminé',
            'cancelled' => 'Annulé',
        ];
        
        // Récupérer les contrats récents pour suggestion
        $recentContracts = Project::where('etablissement_id', Auth::user()->etablissement_id)
            ->whereNotNull('contract_number')
            ->orderByDesc('created_at')
            ->limit(10)
            ->pluck('contract_number');
        
        return view('project::projects.create', compact('clients', 'users', 'statuses', 'recentContracts'));
    }

    /**
     * Store a newly created project in storage.
     */
    /**
 * Store a newly created project in storage.
 */
public function store(Request $request)
{
    // Validation
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'client_id' => 'nullable|exists:etablissements,id',
        'user_id' => 'required|exists:users,id',
        'contract_number' => 'nullable|string|max:255',
        'contact_name' => 'nullable|string|max:255',
        'contact_phone' => 'nullable|string|max:20',
        'contact_email' => 'nullable|email|max:255',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'status' => 'required|in:planning,in_progress,on_hold,completed,cancelled',
        'estimated_hours' => 'nullable|integer|min:0|max:999999',
        'hourly_rate' => 'nullable|numeric|min:0|max:999999.99',
        'estimated_budget' => 'nullable|numeric|min:0|max:9999999.99',
        'is_active' => 'boolean',
        'tags' => 'nullable|string',
        'priority' => 'nullable|in:low,medium,high,urgent',
    ], [
        'name.required' => 'Le nom du projet est obligatoire',
        'user_id.required' => 'Le responsable est obligatoire',
        'end_date.after_or_equal' => 'La date de fin doit être postérieure à la date de début',
        'estimated_hours.max' => 'Le nombre d\'heures estimées est trop élevé',
        'estimated_budget.max' => 'Le budget estimé est trop élevé',
    ]);

    if ($validator->fails()) {
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        return redirect()->back()->withErrors($validator)->withInput();
    }
    
    try {
        DB::beginTransaction();
        
        // Calculer le budget si non fourni mais heures et taux fournis
        $estimatedBudget = $request->estimated_budget;
        if (!$estimatedBudget && $request->estimated_hours && $request->hourly_rate) {
            $estimatedBudget = $request->estimated_hours * $request->hourly_rate;
        }
        
        $etablissement = Etablissement::first();
        
        // Créer le projet
        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'etablissement_id' => $etablissement->id,
            'client_id' => $request->client_id,
            'user_id' => $request->user_id,
            'contract_number' => $request->contract_number,
            'contact_name' => $request->contact_name,
            'contact_phone' => $request->contact_phone,
            'contact_email' => $request->contact_email,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
            'estimated_hours' => $request->estimated_hours,
            'hourly_rate' => $request->hourly_rate,
            'estimated_budget' => $estimatedBudget,
            'is_active' => $request->has('is_active'),
            'metadata' => json_encode([
                'tags' => $request->tags ? explode(',', $request->tags) : [],
                'priority' => $request->priority ?? 'medium',
                'created_by' => Auth::user()->name,
                'created_at' => now()->toDateTimeString(),
            ]),
        ]);

        // ============================================
        // ENVOI D'EMAIL AU RESPONSABLE DU PROJET
        // ============================================
        try {
            // Récupérer l'utilisateur responsable
            $responsibleUser = User::find($request->user_id);
            
            // Vérifier si l'utilisateur existe et a un email
            if ($responsibleUser && $responsibleUser->email) {
                // Envoyer l'email
                Mail::to($responsibleUser->email)->send(new \Vendor\Project\Mail\ProjectCreated($project));
                
                \Log::info('Email sent to project responsible', [
                    'project_id' => $project->id,
                    'user_id' => $responsibleUser->id,
                    'user_email' => $responsibleUser->email,
                    'user_name' => $responsibleUser->name
                ]);
            } else {
                \Log::warning('Project responsible has no email', [
                    'project_id' => $project->id,
                    'user_id' => $request->user_id
                ]);
            }
            
        } catch (\Exception $e) {
            // Ne pas bloquer la création du projet si l'email échoue
            \Log::error('Failed to send email to project responsible', [
                'project_id' => $project->id,
                'user_id' => $request->user_id,
                'error' => $e->getMessage()
            ]);
        }
        // ============================================
        // FIN DE L'ENVOI D'EMAIL
        // ============================================
        
        // Log l'activité
        // activity()
        //     ->performedOn($project)
        //     ->causedBy(Auth::user())
        //     ->withProperties(['name' => $project->name])
        //     ->log('Projet créé');
        
        DB::commit();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Projet créé avec succès',
                'data' => $project,
                'redirect' => route('projects.show', $project->id)
            ]);
        }
        
        return redirect()->route('projects.show', $project->id)
            ->with('success', 'Projet créé avec succès');
            
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error creating project: ' . $e->getMessage());
        
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du projet',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
        
        return redirect()->back()
            ->with('error', 'Erreur lors de la création du projet')
            ->withInput();
    }
}

    /**
     * Display the specified project.
     */
    public function show(Project $project)
    {
       // $this->authorize('view', $project);
        
        // Charger les relations nécessaires
        $project->load([
            'etablissement', 
            'user', 
            'client',
            'tasks' => function($query) {
                $query->with(['user', 'creator', 'comments' => function($q) {
                    $q->latest()->limit(5);
                }])->orderBy('due_date');
            }
        ]);
        
        // Statistiques du projet
        $stats = [
            'total_tasks' => $project->tasks->count(),
            'completed_tasks' => $project->tasks->where('status', 'approved')->count(),
            'in_progress_tasks' => $project->tasks->where('status', 'in_progress')->count(),
            'pending_tasks' => $project->tasks->where('status', 'pending')->count(),
            'total_hours' => $project->tasks->sum('estimated_hours'),
            'total_cost' => $project->tasks->sum('estimated_cost'),
            'tasks_by_status' => $project->tasks->groupBy('status')->map->count(),
        ];
        
        // Tâches récentes
        $recentTasks = $project->tasks()->with('user')->latest()->limit(5)->get();
        
        // Timeline des activités
        $activities = $project->latest()->limit(10)->get();

        $users = \App\Models\User::all();
        
        return view('project::projects.show', compact('project', 'stats', 'recentTasks', 'activities','users'));
    }

    /**
     * Show the form for editing the specified project.
     */
    public function edit(Project $project)
    {
       // $this->authorize('update', $project);
        
        $clients = Etablissement::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'ville']);
        
        $users = User::where('is_active', true)
          //  ->where('etablissement_id', Auth::user()->etablissement_id)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);
        
        $statuses = [
            'planning' => 'Planification',
            'in_progress' => 'En cours',
            'on_hold' => 'En pause',
            'completed' => 'Terminé',
            'cancelled' => 'Annulé',
        ];
        
        // Décoder les métadonnées
        $metadata = json_decode($project->metadata, true) ?? [];
        $tags = isset($metadata['tags']) ? implode(',', $metadata['tags']) : '';
        $priority = $metadata['priority'] ?? 'medium';
        
        return view('project::projects.edit', compact('project', 'clients', 'users', 'statuses', 'tags', 'priority'));
    }

    /**
     * Update the specified project in storage.
     */
    public function update(Request $request, Project $project)
    {
      //  $this->authorize('update', $project);
        
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'nullable|exists:etablissements,id',
            'user_id' => 'required|exists:users,id',
            'contract_number' => 'nullable|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:planning,in_progress,on_hold,completed,cancelled',
            'estimated_hours' => 'nullable|integer|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
            'estimated_budget' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'tags' => 'nullable|string',
            'priority' => 'nullable|in:low,medium,high,urgent',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        try {
            DB::beginTransaction();
            
            // Calculer le budget si non fourni mais heures et taux fournis
            $estimatedBudget = $request->estimated_budget;
            if (!$estimatedBudget && $request->estimated_hours && $request->hourly_rate) {
                $estimatedBudget = $request->estimated_hours * $request->hourly_rate;
            }
            
            // Préparer les métadonnées
            $metadata = json_decode($project->metadata, true) ?? [];
            $metadata['tags'] = $request->tags ? explode(',', $request->tags) : [];
            $metadata['priority'] = $request->priority ?? 'medium';
            $metadata['updated_by'] = Auth::user()->name;
            $metadata['updated_at'] = now()->toDateTimeString();
            
            // Mettre à jour le projet
            $oldStatus = $project->status;
            $project->update([
                'name' => $request->name,
                'description' => $request->description,
                'client_id' => $request->client_id,
                'user_id' => $request->user_id,
                'contract_number' => $request->contract_number,
                'contact_name' => $request->contact_name,
                'contact_phone' => $request->contact_phone,
                'contact_email' => $request->contact_email,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status' => $request->status,
                'estimated_hours' => $request->estimated_hours,
                'hourly_rate' => $request->hourly_rate,
                'estimated_budget' => $estimatedBudget,
                'is_active' => $request->has('is_active'),
                'metadata' => json_encode($metadata),
            ]);
            
            // Log le changement de statut si modifié
            if ($oldStatus !== $request->status) {
                // activity()
                //     ->performedOn($project)
                //     ->causedBy(Auth::user())
                //     ->withProperties([
                //         'old_status' => $oldStatus,
                //         'new_status' => $request->status
                //     ])
                //     ->log('Statut du projet modifié');
            }
            
            // Log la mise à jour
            // activity()
            //     ->performedOn($project)
            //     ->causedBy(Auth::user())
            //     ->log('Projet mis à jour');
            
            DB::commit();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Projet mis à jour avec succès',
                    'data' => $project,
                    'redirect' => route('projects.show', $project->id)
                ]);
            }
            
            return redirect()->route('projects.show', $project->id)
                ->with('success', 'Projet mis à jour avec succès');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating project: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour du projet',
                    'error' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour du projet')
                ->withInput();
        }
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy(Project $project)
    {
       // $this->authorize('delete', $project);
        
        try {
            DB::beginTransaction();
            
            $projectName = $project->name;
            $tasksCount = $project->tasks()->count();
            
            // Log avant suppression
            // activity()
            //     ->performedOn($project)
            //     ->causedBy(Auth::user())
            //     ->withProperties([
            //         'name' => $projectName,
            //         'tasks_count' => $tasksCount
            //     ])
            //     ->log('Projet supprimé');
            
            // Supprimer le projet (les tâches seront supprimées en cascade si configuré)
            $project->delete();
            
            DB::commit();
            
            // Réponse selon le type de requête
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Le projet '{$projectName}' a été supprimé avec succès",
                    'tasks_deleted' => $tasksCount
                ]);
            }
            
            return redirect()->route('projects.index')
                ->with('success', "Le projet '{$projectName}' a été supprimé avec succès");
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting project: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression du projet',
                    'error' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression du projet');
        }
    }

    /**
     * Duplicate a project.
     */
    public function duplicate(Project $project)
    {
     //   $this->authorize('view', $project);
        
        try {
            DB::beginTransaction();
            
            $newProject = $project->replicate();
            $newProject->name = $project->name . ' (copie)';
            $newProject->status = 'planning';
            $newProject->created_at = now();
            $newProject->updated_at = now();
            $newProject->save();
            
            // Dupliquer les métadonnées
            $metadata = json_decode($project->metadata, true) ?? [];
            $metadata['duplicated_from'] = $project->id;
            $metadata['duplicated_at'] = now()->toDateTimeString();
            $metadata['duplicated_by'] = Auth::user()->name;
            $newProject->metadata = json_encode($metadata);
            $newProject->save();
            
            // Optionnel : dupliquer les tâches ?
            // if ($request->has('duplicate_tasks')) {
            //     foreach ($project->tasks as $task) {
            //         $newTask = $task->replicate();
            //         $newTask->project_id = $newProject->id;
            //         $newTask->save();
            //     }
            // }
            
            // activity()
            //     ->performedOn($newProject)
            //     ->causedBy(Auth::user())
            //     ->withProperties(['original' => $project->id])
            //     ->log('Projet dupliqué');
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Projet dupliqué avec succès',
                'data' => $newProject,
                'redirect' => route('projects.edit', $newProject->id)
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error duplicating project: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la duplication du projet',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Export projects to CSV.
     */
    public function export(Request $request)
    {
        try {
            $query = Project::with(['client', 'user', 'tasks'])
                ->where('etablissement_id', Auth::user()->etablissement_id);
            
            // Appliquer les filtres si fournis
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->filled('client_id')) {
                $query->where('client_id', $request->client_id);
            }
            
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }
            
            $projects = $query->get();
            
            // Créer le CSV
            $filename = 'projets_' . now()->format('Y-m-d_His') . '.csv';
            $handle = fopen('php://temp', 'w');
            
            // En-têtes CSV
            fputcsv($handle, [
                'ID',
                'Nom',
                'Client',
                'Responsable',
                'N° Contrat',
                'Contact',
                'Date début',
                'Date fin',
                'Statut',
                'Heures estimées',
                'Budget estimé',
                'Tâches',
                'Tâches complétées',
                'Avancement (%)',
                'Créé le',
                'Actif'
            ], ';');
            
            // Données
            foreach ($projects as $project) {
                fputcsv($handle, [
                    $project->id,
                    $project->name,
                    $project->client->name ?? 'N/A',
                    $project->user->name ?? 'N/A',
                    $project->contract_number ?? 'N/A',
                    $project->contact_name ?? 'N/A',
                    $project->start_date ? $project->start_date->format('d/m/Y') : 'N/A',
                    $project->end_date ? $project->end_date->format('d/m/Y') : 'N/A',
                    $project->formatted_status,
                    $project->estimated_hours ?? 0,
                    number_format($project->estimated_budget ?? 0, 2, ',', ' ') . ' €',
                    $project->tasks->count(),
                    $project->tasks->where('status', 'approved')->count(),
                    $project->progress,
                    $project->created_at->format('d/m/Y H:i'),
                    $project->is_active ? 'Oui' : 'Non'
                ], ';');
            }
            
            rewind($handle);
            $content = stream_get_contents($handle);
            fclose($handle);
            
            return response($content)
                ->header('Content-Type', 'text/csv; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
            
        } catch (\Exception $e) {
            \Log::error('Error exporting projects: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'export des projets');
        }
    }

    /**
     * Get tasks for a specific project.
     */
    public function tasks(Project $project, Request $request)
    {
      //  $this->authorize('view', $project);
        
        $tasks = $project->tasks()
            ->with(['user', 'creator'])
            ->when($request->status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->user_id, function($query, $userId) {
                return $query->where('user_id', $userId);
            })
            ->orderBy($request->get('sort_by', 'due_date'), $request->get('sort_direction', 'asc'))
            ->paginate($request->get('per_page', 20));
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $tasks
            ]);
        }
        
        return view('projects.tasks', compact('project', 'tasks'));
    }

    /**
     * Update project status.
     */
    public function updateStatus(Request $request, Project $project)
    {
      //  $this->authorize('update', $project);
        
        $request->validate([
            'status' => 'required|in:planning,in_progress,on_hold,completed,cancelled'
        ]);
        
        try {
            $oldStatus = $project->status;
            $project->status = $request->status;
            $project->save();
            
            // activity()
            //     ->performedOn($project)
            //     ->causedBy(Auth::user())
            //     ->withProperties([
            //         'old_status' => $oldStatus,
            //         'new_status' => $request->status
            //     ])
            //     ->log('Statut du projet mis à jour');
            
            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour avec succès',
                'data' => [
                    'status' => $project->status,
                    'formatted_status' => $project->formatted_status
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du statut'
            ], 500);
        }
    }

    /**
     * Bulk delete projects.
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:projects,id'
        ]);
        
        try {
            DB::beginTransaction();
            
            $projects = Project::whereIn('id', $request->ids)
                ->where('etablissement_id', Auth::user()->etablissement_id)
                ->get();
            
            $count = $projects->count();
            $names = $projects->pluck('name')->implode(', ');
            
            foreach ($projects as $project) {
              //  $this->authorize('delete', $project);
                $project->delete();
            }
            
            // activity()
            //     ->causedBy(Auth::user())
            //     ->withProperties(['count' => $count, 'ids' => $request->ids])
            //     ->log('Suppression multiple de projets');
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "{$count} projet(s) supprimé(s) avec succès",
                'deleted_ids' => $request->ids
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression multiple'
            ], 500);
        }
    }

    /**
     * Get project timeline data.
     */
    public function timeline(Project $project)
    {
      //  $this->authorize('view', $project);
        
        $timeline = collect();
        
        // Ajouter la création du projet
        $timeline->push([
            'type' => 'project_created',
            'title' => 'Projet créé',
            'description' => 'Le projet a été créé',
            'user' => $project->creator->name ?? 'Système',
            'date' => $project->created_at,
            'icon' => 'fas fa-plus-circle',
            'color' => 'success'
        ]);
        
        // Ajouter les changements de statut
        $statusChanges = activity()
            ->where('subject_type', Project::class)
            ->where('subject_id', $project->id)
            ->where('description', 'Statut du projet modifié')
            ->orWhere('description', 'Statut du projet mis à jour')
            ->get();
        
        foreach ($statusChanges as $change) {
            $properties = $change->properties;
            $timeline->push([
                'type' => 'status_change',
                'title' => 'Changement de statut',
                'description' => "Statut changé de '{$properties['old_status']}' à '{$properties['new_status']}'",
                'user' => $change->causer->name ?? 'Système',
                'date' => $change->created_at,
                'icon' => 'fas fa-exchange-alt',
                'color' => 'info'
            ]);
        }
        
        // Ajouter les tâches
        foreach ($project->tasks as $task) {
            $timeline->push([
                'type' => 'task_created',
                'title' => 'Tâche créée',
                'description' => "Tâche: {$task->name}",
                'user' => $task->creator->name ?? 'Système',
                'date' => $task->created_at,
                'icon' => 'fas fa-tasks',
                'color' => 'primary',
                'task_id' => $task->id
            ]);
            
            // Ajouter les commentaires des tâches
            foreach ($task->comments as $comment) {
                $timeline->push([
                    'type' => 'task_comment',
                    'title' => 'Commentaire ajouté',
                    'description' => "Sur la tâche '{$task->name}': " . \Str::limit($comment->content, 100),
                    'user' => $comment->user->name ?? 'Système',
                    'date' => $comment->created_at,
                    'icon' => 'fas fa-comment',
                    'color' => 'secondary',
                    'task_id' => $task->id
                ]);
            }
        }
        
        // Trier par date
        $timeline = $timeline->sortByDesc('date')->values();
        
        return response()->json([
            'success' => true,
            'data' => $timeline
        ]);
    }

    /**
     * Get project Gantt data.
     */
    public function gantt(Project $project)
    {
     //   $this->authorize('view', $project);
        
        $data = [
            'project' => [
                'id' => $project->id,
                'name' => $project->name,
                'start' => $project->start_date ? $project->start_date->format('Y-m-d') : null,
                'end' => $project->end_date ? $project->end_date->format('Y-m-d') : null,
                'progress' => $project->progress / 100,
            ],
            'tasks' => $project->tasks->map(function($task) {
                return [
                    'id' => $task->id,
                    'name' => $task->name,
                    'start' => $task->due_date ? $task->due_date->format('Y-m-d') : null,
                    'end' => $task->delivery_date ? $task->delivery_date->format('Y-m-d') : null,
                    'progress' => $task->status === 'approved' ? 1 : ($task->status === 'in_progress' ? 0.5 : 0),
                    'status' => $task->status,
                    'assigned_to' => $task->user->name ?? null,
                ];
            })
        ];
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Get project summary for dashboard.
     */
    public function summary()
    {
        $etablissementId = Auth::user()->etablissement_id;
        
        $recentProjects = Project::where('etablissement_id', $etablissementId)
            ->with(['client', 'user'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(function($project) {
                return [
                    'id' => $project->id,
                    'name' => $project->name,
                    'client_name' => $project->client->name ?? 'N/A',
                    'user_name' => $project->user->name ?? 'N/A',
                    'status' => $project->formatted_status,
                    'status_color' => $project->status_color,
                    'progress' => $project->progress,
                    'url' => route('projects.show', $project->id)
                ];
            });
        
        $upcomingDeadlines = Project::where('etablissement_id', $etablissementId)
            ->whereNotNull('end_date')
            ->where('end_date', '>=', now())
            ->where('end_date', '<=', now()->addDays(14))
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->orderBy('end_date')
            ->limit(5)
            ->get()
            ->map(function($project) {
                return [
                    'id' => $project->id,
                    'name' => $project->name,
                    'end_date' => $project->end_date->format('d/m/Y'),
                    'days_remaining' => now()->diffInDays($project->end_date, false) + 1,
                    'progress' => $project->progress,
                    'url' => route('projects.show', $project->id)
                ];
            });
        
        $stats = [
            'total' => Project::where('etablissement_id', $etablissementId)->count(),
            'in_progress' => Project::where('etablissement_id', $etablissementId)->where('status', 'in_progress')->count(),
            'completed' => Project::where('etablissement_id', $etablissementId)->where('status', 'completed')->count(),
            'overdue' => Project::where('etablissement_id', $etablissementId)
                ->whereNotNull('end_date')
                ->where('end_date', '<', now())
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->count(),
        ];
        
        return response()->json([
            'success' => true,
            'data' => [
                'recent_projects' => $recentProjects,
                'upcoming_deadlines' => $upcomingDeadlines,
                'stats' => $stats
            ]
        ]);
    }

    /**
 * Display calendar view for projects.
 */
public function calendar(Request $request)
{
    // Si c'est une requête AJAX pour les événements
    if ($request->ajax() && $request->has('events')) {
        return $this->getCalendarEvents($request);
    }
    
    // Récupérer les données pour les filtres
    $clients = Etablissement::where('is_active', true)
        ->orderBy('name')
        ->get(['id', 'name']);
    
    $users = User::where('is_active', true)
        ->orderBy('name')
        ->get(['id', 'name']);
    
    $statuses = [
        'planning' => 'Planification',
        'in_progress' => 'En cours',
        'on_hold' => 'En pause',
        'completed' => 'Terminé',
        'cancelled' => 'Annulé',
    ];
    
    return view('project::projects.calendar', compact('clients', 'users', 'statuses'));
}

/**
 * Get calendar events for AJAX requests.
 */
private function getCalendarEvents(Request $request)
{
    try {
        $query = Project::with(['client', 'user', 'tasks']);
        
        // Filtrer par dates
        $startDate = $request->get('start', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end', now()->endOfMonth()->format('Y-m-d'));
        
        // Appliquer les filtres
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active === '1');
        }
        
        // Récupérer les projets avec des dates dans la période
        $projects = $query->where(function($q) use ($startDate, $endDate) {
            $q->whereBetween('start_date', [$startDate, $endDate])
              ->orWhereBetween('end_date', [$startDate, $endDate])
              ->orWhere(function($sub) use ($startDate, $endDate) {
                  $sub->where('start_date', '<=', $startDate)
                      ->where('end_date', '>=', $endDate);
              });
        })->get();
        
        $events = [];
        
        foreach ($projects as $project) {
            // Événement principal du projet
            if ($project->start_date && $project->end_date) {
                $events[] = [
                    'id' => 'project_' . $project->id,
                    'title' => $project->name,
                    'start' => $project->start_date->format('Y-m-d'),
                    'end' => $project->end_date->addDay()->format('Y-m-d'), // FullCalendar exclusive end date
                    'backgroundColor' => $this->getCalendarColor($project->status),
                    'borderColor' => $this->getCalendarColor($project->status, true),
                    'textColor' => '#ffffff',
                    'extendedProps' => [
                        'type' => 'project',
                        'project_id' => $project->id,
                        'description' => \Str::limit(strip_tags($project->description), 150),
                        'client' => $project->client->name ?? 'Aucun client',
                        'user' => $project->user->name ?? 'Non assigné',
                        'status' => $project->formatted_status,
                        'status_color' => $project->status_color,
                        'progress' => $project->progress,
                        'url' => route('projects.show', $project->id),
                        'is_overdue' => $project->isOverdue(),
                        'contract_number' => $project->contract_number,
                    ]
                ];
            }
            
            // Ajouter les jalons importants des tâches
            foreach ($project->tasks->whereNotNull('due_date') as $task) {
                if ($task->due_date->between($startDate, $endDate)) {
                    $events[] = [
                        'id' => 'task_' . $task->id,
                        'title' => '📋 ' . $task->name,
                        'start' => $task->due_date->format('Y-m-d'),
                        'backgroundColor' => $this->getTaskCalendarColor($task->status),
                        'borderColor' => $this->getTaskCalendarColor($task->status, true),
                        'textColor' => '#ffffff',
                        'extendedProps' => [
                            'type' => 'task',
                            'project_id' => $project->id,
                            'project_name' => $project->name,
                            'task_id' => $task->id,
                            'description' => \Str::limit(strip_tags($task->description), 150),
                            'assigned_to' => $task->user->name ?? 'Non assigné',
                            'status' => $task->formatted_status,
                            'status_color' => $task->status_color,
                            'url' => route('tasks.show', $task->id),
                        ]
                    ];
                }
            }
        }
        
        return response()->json($events);
        
    } catch (\Exception $e) {
        \Log::error('Error loading calendar events: ' . $e->getMessage());
        
        return response()->json([
            'error' => 'Erreur lors du chargement des événements',
            'debug' => config('app.debug') ? $e->getMessage() : null
        ], 500);
    }
}

/**
 * Get color for calendar based on project status.
 */
private function getCalendarColor($status, $darker = false)
{
    $colors = [
        'planning' => $darker ? '#3a56e4' : '#4a6cf7',
        'in_progress' => $darker ? '#049a72' : '#06b48a',
        'on_hold' => $darker ? '#e69c2f' : '#ffb347',
        'completed' => $darker ? '#1e7b4c' : '#28a745',
        'cancelled' => $darker ? '#b02a37' : '#dc3545',
    ];
    
    return $colors[$status] ?? ($darker ? '#6c757d' : '#adb5bd');
}

/**
 * Get color for calendar based on task status.
 */
private function getTaskCalendarColor($status, $darker = false)
{
    $colors = [
        'pending' => $darker ? '#9b59b6' : '#b87dc9',
        'in_progress' => $darker ? '#3a9bb8' : '#45b7d1',
        'completed' => $darker ? '#1e7b4c' : '#28a745',
        'approved' => $darker ? '#1e7b4c' : '#28a745',
        'cancelled' => $darker ? '#b02a37' : '#dc3545',
    ];
    
    return $colors[$status] ?? ($darker ? '#6c757d' : '#adb5bd');
}
}