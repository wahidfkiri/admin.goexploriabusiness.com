<?php

namespace Vendor\Project\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Models\Etablissement;
use App\Models\TaskFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Vendor\Project\Mail\TaskCreated;
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{
    /**
     * Constructeur avec middleware
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the tasks.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getTasksData($request);
        }
        
        $projects = Project::where('etablissement_id', Auth::user()->etablissement_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);
        
        $users = User::where('is_active', true)
            ->where('etablissement_id', Auth::user()->etablissement_id)
            ->orderBy('name')
            ->get(['id', 'name']);
        
        $statuses = [
            'pending' => 'En attente',
            'in_progress' => 'En cours',
            'test' => 'En test',
            'integrated' => 'Intégré',
            'delivered' => 'Livré',
            'approved' => 'Approuvé',
            'cancelled' => 'Annulé',
        ];
        
        return view('tasks.index', compact('projects', 'users', 'statuses'));
    }

    /**
     * Get tasks data for AJAX requests.
     */
    private function getTasksData(Request $request)
    {
        try {
            $query = Task::with([
                'project', 
                'user', 
                'creator',
                'etablissement'
            ])
            ->where('etablissement_id', Auth::user()->etablissement_id);
            
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('details', 'like', "%{$search}%")
                      ->orWhere('contract_number', 'like', "%{$search}%")
                      ->orWhere('contact_name', 'like', "%{$search}%")
                      ->orWhereHas('project', function($projectQuery) use ($search) {
                          $projectQuery->where('name', 'like', "%{$search}%");
                      });
                });
            }
            
            if ($request->filled('project_id')) {
                $query->where('project_id', $request->project_id);
            }
            
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->filled('priority')) {
                $query->whereJsonContains('metadata->priority', $request->priority);
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
            
            if ($request->filled('due_date_from')) {
                $query->whereDate('due_date', '>=', Carbon::parse($request->due_date_from));
            }
            
            if ($request->filled('due_date_to')) {
                $query->whereDate('due_date', '<=', Carbon::parse($request->due_date_to));
            }
            
            if ($request->filled('country')) {
                $query->where('country', 'like', "%{$request->country}%");
            }
            
            if ($request->filled('location')) {
                $query->where('location', 'like', "%{$request->location}%");
            }
            
            $sortField = $request->get('sort_by', 'created_at');
            $sortDirection = $request->get('sort_direction', 'desc');
            
            $allowedSortFields = ['name', 'status', 'created_at', 'updated_at', 'due_date', 'delivery_date', 'estimated_hours'];
            if (in_array($sortField, $allowedSortFields)) {
                $query->orderBy($sortField, $sortDirection);
            } else {
                $query->orderBy('created_at', 'desc');
            }
            
            $totalTasks = $query->count();
            
            $perPage = $request->get('per_page', 15);
            $tasks = $query->paginate($perPage);
            
            $tasks->getCollection()->transform(function($task) {
                $metadata = json_decode($task->metadata, true) ?? [];
                
                return [
                    'id' => $task->id,
                    'name' => $task->name,
                    'details' => Str::limit(strip_tags($task->details), 100),
                    'project_id' => $task->project_id,
                    'project_name' => $task->project->name ?? 'N/A',
                    'user_id' => $task->user_id,
                    'user_name' => $task->user->name ?? 'Non assigné',
                    'creator_name' => $task->creator->name ?? 'Système',
                    'country' => $task->country,
                    'location' => $task->location,
                    'contract_number' => $task->contract_number,
                    'contact_name' => $task->contact_name,
                    'due_date' => $task->due_date ? $task->due_date->format('d/m/Y H:i') : null,
                    'due_date_raw' => $task->due_date,
                    'delivery_date' => $task->delivery_date ? $task->delivery_date->format('d/m/Y H:i') : null,
                    'delivery_date_raw' => $task->delivery_date,
                    'estimated_hours' => $task->estimated_hours,
                    'hourly_rate' => $task->hourly_rate,
                    'estimated_cost' => $task->estimated_cost,
                    'estimated_cost_formatted' => number_format($task->estimated_cost ?? 0, 2, ',', ' ') . ' €',
                    'status' => $task->status,
                    'status_formatted' => $task->formatted_status,
                    'status_color' => $task->status_color,
                    'test_date' => $task->test_date ? $task->test_date->format('d/m/Y H:i') : null,
                    'test_details' => $task->test_details,
                    'integration_date' => $task->integration_date ? $task->integration_date->format('d/m/Y H:i') : null,
                    'push_prod_date' => $task->push_prod_date ? $task->push_prod_date->format('d/m/Y H:i') : null,
                    'module_url' => $task->module_url,
                    'is_approved_by_manager' => $task->is_approved_by_manager,
                    'approved_by_name' => $task->approvedBy->name ?? null,
                    'approved_at' => $task->approved_at ? $task->approved_at->format('d/m/Y H:i') : null,
                    'general_manager' => $task->generalManager ? [
                        'id' => $task->generalManager->id,
                        'name' => $task->generalManager->name,
                        'email' => $task->generalManager->email,
                    ] : null,
                    'client_manager' => $task->clientManager ? [
                        'id' => $task->clientManager->id,
                        'name' => $task->clientManager->name,
                        'email' => $task->clientManager->email,
                    ] : null,
                    'priority' => $metadata['priority'] ?? 'medium',
                    'tags' => $metadata['tags'] ?? [],
                    'is_active' => $task->is_active,
                    'is_overdue' => $task->isOverdue(),
                    'days_remaining' => $task->daysRemaining,
                    'files_count' => $task->files_count,
                    'created_at' => $task->created_at->format('d/m/Y H:i'),
                    'created_at_raw' => $task->created_at,
                    'updated_at' => $task->updated_at->format('d/m/Y H:i'),
                    'urls' => [
                        'show' => route('tasks.show', $task->id),
                        'edit' => route('tasks.edit', $task->id),
                        'delete' => route('tasks.destroy', $task->id),
                        'project' => route('projects.show', $task->project_id),
                    ]
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $tasks->items(),
                'current_page' => $tasks->currentPage(),
                'last_page' => $tasks->lastPage(),
                'per_page' => $tasks->perPage(),
                'total' => $tasks->total(),
                'from' => $tasks->firstItem(),
                'to' => $tasks->lastItem(),
                'prev_page_url' => $tasks->previousPageUrl(),
                'next_page_url' => $tasks->nextPageUrl(),
                'total_tasks' => $totalTasks,
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error loading tasks: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement des tâches',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get task statistics.
     */
    public function statistics(Request $request)
    {
        try {
            $etablissementId = Auth::user()->etablissement_id;
            
            $query = Task::where('etablissement_id', $etablissementId);
            
            if ($request->filled('project_id')) {
                $query->where('project_id', $request->project_id);
            }
            
            $total = $query->count();
            
            $byStatus = $query->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get()
                ->keyBy('status');
            
            $byUser = $query->select('user_id', DB::raw('count(*) as total'))
                ->whereNotNull('user_id')
                ->with('user:id,name')
                ->groupBy('user_id')
                ->orderByDesc('total')
                ->limit(5)
                ->get()
                ->map(function($item) {
                    return [
                        'user_id' => $item->user_id,
                        'user_name' => $item->user->name ?? 'Inconnu',
                        'total' => $item->total,
                    ];
                });
            
            $byProject = $query->select('project_id', DB::raw('count(*) as total'))
                ->whereNotNull('project_id')
                ->with('project:id,name')
                ->groupBy('project_id')
                ->orderByDesc('total')
                ->limit(5)
                ->get()
                ->map(function($item) {
                    return [
                        'project_id' => $item->project_id,
                        'project_name' => $item->project->name ?? 'Inconnu',
                        'total' => $item->total,
                    ];
                });
            
            $overdue = $query->clone()
                ->whereNotNull('due_date')
                ->where('due_date', '<', now())
                ->whereNotIn('status', ['approved', 'cancelled'])
                ->count();
            
            $upcoming = $query->clone()
                ->whereNotNull('due_date')
                ->whereBetween('due_date', [now(), now()->addDays(7)])
                ->whereNotIn('status', ['approved', 'cancelled'])
                ->count();
            
            $completedThisMonth = $query->clone()
                ->where('status', 'approved')
                ->where('updated_at', '>=', now()->startOfMonth())
                ->count();
            
            $createdThisMonth = $query->clone()
                ->where('created_at', '>=', now()->startOfMonth())
                ->count();
            
            $totalHours = $query->sum('estimated_hours');
            $totalCost = $query->sum('estimated_cost');
            
            $monthlyEvolution = collect(range(5, 0))->map(function($monthsAgo) use ($etablissementId, $request) {
                $date = now()->subMonths($monthsAgo);
                $startOfMonth = $date->copy()->startOfMonth();
                $endOfMonth = $date->copy()->endOfMonth();
                
                $taskQuery = Task::where('etablissement_id', $etablissementId)
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
                
                if ($request->filled('project_id')) {
                    $taskQuery->where('project_id', $request->project_id);
                }
                
                $created = $taskQuery->count();
                
                $completed = Task::where('etablissement_id', $etablissementId)
                    ->where('status', 'approved')
                    ->whereBetween('updated_at', [$startOfMonth, $endOfMonth])
                    ->when($request->filled('project_id'), function($q) use ($request) {
                        $q->where('project_id', $request->project_id);
                    })
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
                    'total' => $total,
                    'by_status' => $byStatus,
                    'by_user' => $byUser,
                    'by_project' => $byProject,
                    'overdue' => $overdue,
                    'upcoming' => $upcoming,
                    'completed_this_month' => $completedThisMonth,
                    'created_this_month' => $createdThisMonth,
                    'total_hours' => $totalHours,
                    'total_cost' => $totalCost,
                    'total_cost_formatted' => number_format($totalCost, 2, ',', ' ') . ' €',
                    'monthly_evolution' => $monthlyEvolution,
                    'pending' => $byStatus['pending']->total ?? 0,
                    'in_progress' => $byStatus['in_progress']->total ?? 0,
                    'test' => $byStatus['test']->total ?? 0,
                    'integrated' => $byStatus['integrated']->total ?? 0,
                    'delivered' => $byStatus['delivered']->total ?? 0,
                    'approved' => $byStatus['approved']->total ?? 0,
                    'cancelled' => $byStatus['cancelled']->total ?? 0,
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error loading task statistics: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement des statistiques',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Show the form for creating a new task.
     */
    public function create(Request $request)
    {
        $projectId = $request->get('project_id');
        $project = null;
        
        if ($projectId) {
            $project = Project::where('etablissement_id', Auth::user()->etablissement_id)
                ->findOrFail($projectId);
        }
        
        $projects = Project::where('etablissement_id', Auth::user()->etablissement_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);
        
        $users = User::where('is_active', true)
            ->where('etablissement_id', Auth::user()->etablissement_id)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);
        
        $statuses = [
            'pending' => 'En attente',
            'in_progress' => 'En cours',
            'test' => 'En test',
            'integrated' => 'Intégré',
            'delivered' => 'Livré',
            'approved' => 'Approuvé',
            'cancelled' => 'Annulé',
        ];
        
        return view('tasks.create', compact('projects', 'users', 'statuses', 'project'));
    }

    /**
     * Store a newly created task in storage.
     */
    /**
 * Store a newly created task in storage.
 */
public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'details' => 'nullable|string',
        'project_id' => 'required|exists:projects,id',
        'user_id' => 'required|exists:users,id',
        'country' => 'nullable|string|max:100',
        'location' => 'nullable|string|max:255',
        'contract_number' => 'nullable|string|max:255',
        'contact_name' => 'nullable|string|max:255',
        'due_date' => 'nullable|date',
        'delivery_date' => 'nullable|date|after_or_equal:due_date',
        'estimated_hours' => 'nullable|integer|min:0',
        'hourly_rate' => 'nullable|numeric|min:0',
        'status' => 'required|in:pending,in_progress,test,integrated,delivered,approved,cancelled',
        'test_date' => 'nullable|date',
        'test_details' => 'nullable|string',
        'integration_date' => 'nullable|date',
        'push_prod_date' => 'nullable|date',
        'module_url' => 'nullable|url|max:255',
        'priority' => 'nullable|in:low,medium,high,urgent',
        'tags' => 'nullable|string',
    ], [
        'name.required' => 'Le nom de la tâche est obligatoire',
        'project_id.required' => 'Le projet est obligatoire',
        'user_id.required' => 'L\'utilisateur assigné est obligatoire',
        'delivery_date.after_or_equal' => 'La date de livraison doit être postérieure à la date d\'échéance',
        'module_url.url' => 'L\'URL du module doit être une URL valide',
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
        
        $project = Project::findOrFail($request->project_id);
        
        $estimatedCost = null;
        if ($request->estimated_hours && $request->hourly_rate) {
            $estimatedCost = $request->estimated_hours * $request->hourly_rate;
        }
        
        $metadata = [
            'priority' => $request->priority ?? 'medium',
            'tags' => $request->tags ? explode(',', $request->tags) : [],
            'created_by' => Auth::user()->name,
            'created_at' => now()->toDateTimeString(),
        ];
        
        $task = Task::create([
            'name' => $request->name,
            'details' => $request->details,
            'project_id' => $request->project_id,
            'etablissement_id' => $project->etablissement_id,
            'user_id' => $request->user_id,
            'created_by' => Auth::id(),
            'country' => $request->country,
            'location' => $request->location,
            'contract_number' => $request->contract_number,
            'contact_name' => $request->contact_name,
            'due_date' => $request->due_date,
            'delivery_date' => $request->delivery_date,
            'estimated_hours' => $request->estimated_hours,
            'hourly_rate' => $request->hourly_rate,
            'estimated_cost' => $estimatedCost,
            'status' => $request->status,
            'test_date' => $request->test_date,
            'test_details' => $request->test_details,
            'integration_date' => $request->integration_date,
            'push_prod_date' => $request->push_prod_date,
            'module_url' => $request->module_url,
            'is_active' => true,
            'metadata' => json_encode($metadata),
            'files_count' => 0,
        ]);

        if ($request->hasFile('files')) {
            $uploadedCount = 0;
            
            foreach ($request->file('files') as $file) {
                try {
                    $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                    $filePath = $file->storeAs('tasks/' . $task->id, $fileName, 'public');

                    TaskFile::create([
                        'task_id' => $task->id,
                        'user_id' => Auth::id(),
                        'file_name' => $fileName,
                        'original_name' => $file->getClientOriginalName(),
                        'file_path' => $filePath,
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                        'file_extension' => $file->getClientOriginalExtension(),
                        'storage_disk' => 'public',
                        'description' => null,
                        'is_public' => true,
                        'is_temporary' => false,
                        'expires_at' => null,
                        'custom_properties' => json_encode([
                            'uploaded_at' => now()->toDateTimeString(),
                            'uploaded_by' => Auth::user()->name,
                            'uploaded_by_email' => Auth::user()->email,
                            'ip_address' => $request->ip(),
                        ]),
                    ]);
                    
                    $uploadedCount++;
                    
                } catch (\Exception $e) {
                    \Log::error('Error uploading file during task creation: ' . $e->getMessage());
                }
            }
            
            if ($uploadedCount > 0) {
                $task->files_count = $uploadedCount;
                $task->save();
            }
        }

        // ============================================
        // ENVOI D'EMAIL À L'UTILISATEUR ASSIGNÉ
        // ============================================
        try {
            // Récupérer l'utilisateur assigné
            $assignedUser = User::find($request->user_id);
            
            // Vérifier si l'utilisateur existe et a un email
            if ($assignedUser && $assignedUser->email) {
                // Envoyer l'email
                Mail::to($assignedUser->email)->send(new TaskCreated($task));
                
                \Log::info('Email sent to assigned user', [
                    'task_id' => $task->id,
                    'user_id' => $assignedUser->id,
                    'user_email' => $assignedUser->email,
                    'user_name' => $assignedUser->name
                ]);
            } else {
                \Log::warning('Assigned user has no email', [
                    'task_id' => $task->id,
                    'user_id' => $request->user_id
                ]);
            }
            
        } catch (\Exception $e) {
            // Ne pas bloquer la création de la tâche si l'email échoue
            \Log::error('Failed to send email to assigned user', [
                'task_id' => $task->id,
                'user_id' => $request->user_id,
                'error' => $e->getMessage()
            ]);
        }
        // ============================================
        // FIN DE L'ENVOI D'EMAIL
        // ============================================
        
        DB::commit();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Tâche créée avec succès' . ($task->files_count > 0 ? ' (' . $task->files_count . ' fichier(s))' : ''),
                'data' => $task,
                'redirect' => route('tasks.show', $task->id)
            ]);
        }
        
        return redirect()->route('tasks.show', $task->id)
            ->with('success', 'Tâche créée avec succès');
            
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error creating task: ' . $e->getMessage());
        
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la tâche',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
        
        return redirect()->back()
            ->with('error', 'Erreur lors de la création de la tâche')
            ->withInput();
    }
}

    /**
     * Display the specified task.
     */
    public function show(Task $task)
    {
        $task->load([
            'project',
            'user',
            'creator',
            'generalManager',
            'clientManager',
            'approvedBy',
            'comments.user',
            'files.user'
        ]);
        
        $metadata = json_decode($task->metadata, true) ?? [];
        
        $files = $task->files->map(function($file) {
            return $this->formatFileData($file);
        });
        
        $fileStats = [
            'total' => $files->count(),
            'total_size' => $this->formatBytes($task->files->sum('file_size')),
            'images' => $task->files->filter(function($f) { return $this->isImage($f->file_extension); })->count(),
            'documents' => $task->files->filter(function($f) { 
                return in_array(strtolower($f->file_extension), ['pdf', 'doc', 'docx', 'txt']); 
            })->count(),
        ];
        
        return view('project::tasks.show', compact('task', 'metadata', 'files', 'fileStats'));
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(Task $task, Request $request)
    {
        $projects = Project::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);
        
        $users = User::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);
        
        $statuses = [
            'pending' => 'En attente',
            'in_progress' => 'En cours',
            'test' => 'En test',
            'integrated' => 'Intégré',
            'delivered' => 'Livré',
            'approved' => 'Approuvé',
            'cancelled' => 'Annulé',
        ];
        
        $generalManagers = User::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);
        
        $metadata = json_decode($task->metadata, true) ?? [];
        $tags = isset($metadata['tags']) ? implode(',', $metadata['tags']) : '';
        $priority = $metadata['priority'] ?? 'medium';
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $task->id,
                    'name' => $task->name,
                    'details' => $task->details,
                    'project_id' => $task->project_id,
                    'user_id' => $task->user_id,
                    'country' => $task->country,
                    'location' => $task->location,
                    'contract_number' => $task->contract_number,
                    'contact_name' => $task->contact_name,
                    'due_date' => $task->due_date ? $task->due_date->format('Y-m-d\TH:i') : null,
                    'delivery_date' => $task->delivery_date ? $task->delivery_date->format('Y-m-d\TH:i') : null,
                    'estimated_hours' => $task->estimated_hours,
                    'hourly_rate' => $task->hourly_rate,
                    'status' => $task->status,
                    'test_date' => $task->test_date ? $task->test_date->format('Y-m-d\TH:i') : null,
                    'test_details' => $task->test_details,
                    'integration_date' => $task->integration_date ? $task->integration_date->format('Y-m-d\TH:i') : null,
                    'push_prod_date' => $task->push_prod_date ? $task->push_prod_date->format('Y-m-d\TH:i') : null,
                    'module_url' => $task->module_url,
                    'priority' => $priority,
                    'tags' => $tags,
                    'files_count' => $task->files_count,
                ]
            ]);
        }
        
        return view('tasks.edit', compact('task', 'projects', 'users', 'statuses', 'generalManagers', 'tags', 'priority'));
    }

    /**
 * Update the specified task in storage.
 */
public function update(Request $request, Task $task)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'details' => 'nullable|string',
        'project_id' => 'required|exists:projects,id',
        'user_id' => 'required|exists:users,id',
        'country' => 'nullable|string|max:100',
        'location' => 'nullable|string|max:255',
        'contract_number' => 'nullable|string|max:255',
        'contact_name' => 'nullable|string|max:255',
        'due_date' => 'nullable|date',
        'delivery_date' => 'nullable|date|after_or_equal:due_date',
        'estimated_hours' => 'nullable|integer|min:0',
        'hourly_rate' => 'nullable|numeric|min:0',
        'status' => 'required|in:pending,in_progress,test,integrated,delivered,approved,cancelled',
        'test_date' => 'nullable|date',
        'test_details' => 'nullable|string',
        'integration_date' => 'nullable|date',
        'push_prod_date' => 'nullable|date',
        'module_url' => 'nullable|url|max:255',
        'priority' => 'nullable|in:low,medium,high,urgent',
        'tags' => 'nullable|string',
        'general_manager_id' => 'nullable|exists:users,id',
        'client_manager_id' => 'nullable|exists:users,id',
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
        
        $project = Project::findOrFail($request->project_id);
        
        $estimatedCost = null;
        if ($request->estimated_hours && $request->hourly_rate) {
            $estimatedCost = $request->estimated_hours * $request->hourly_rate;
        }
        
        $metadata = json_decode($task->metadata, true) ?? [];
        $metadata['priority'] = $request->priority ?? $metadata['priority'] ?? 'medium';
        $metadata['tags'] = $request->tags ? explode(',', $request->tags) : ($metadata['tags'] ?? []);
        $metadata['updated_by'] = Auth::user()->name;
        $metadata['updated_at'] = now()->toDateTimeString();
        
        $oldStatus = $task->status;
        
        $task->update([
            'name' => $request->name,
            'details' => $request->details,
            'project_id' => $request->project_id,
            'etablissement_id' => $project->etablissement_id,
            'user_id' => $request->user_id,
            'country' => $request->country,
            'location' => $request->location,
            'contract_number' => $request->contract_number,
            'contact_name' => $request->contact_name,
            'due_date' => $request->due_date,
            'delivery_date' => $request->delivery_date,
            'estimated_hours' => $request->estimated_hours,
            'hourly_rate' => $request->hourly_rate,
            'estimated_cost' => $estimatedCost,
            'status' => $request->status,
            'test_date' => $request->test_date,
            'test_details' => $request->test_details,
            'integration_date' => $request->integration_date,
            'push_prod_date' => $request->push_prod_date,
            'module_url' => $request->module_url,
            'general_manager_id' => $request->general_manager_id,
            'client_manager_id' => $request->client_manager_id,
            'metadata' => json_encode($metadata),
        ]);

        // Gérer les nouveaux fichiers uploadés
        if ($request->hasFile('new_files')) {
            $uploadedCount = 0;
            
            foreach ($request->file('new_files') as $file) {
                try {
                    // Générer un nom unique
                    $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                    $filePath = $file->storeAs('tasks/' . $task->id, $fileName, 'public');

                    // Créer l'enregistrement avec TOUS les champs requis
                    TaskFile::create([
                        'task_id' => $task->id,
                        'user_id' => Auth::id(),
                        'file_name' => $fileName,
                        'original_name' => $file->getClientOriginalName(),
                        'file_path' => $filePath,
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                        'file_extension' => $file->getClientOriginalExtension(),
                        'storage_disk' => 'public',
                        'description' => null,
                        'is_public' => true,
                        'is_temporary' => false,
                        'expires_at' => null,
                        'custom_properties' => json_encode([
                            'uploaded_at' => now()->toDateTimeString(),
                            'uploaded_by' => Auth::user()->name,
                            'uploaded_by_email' => Auth::user()->email,
                            'ip_address' => $request->ip(),
                        ]),
                    ]);
                    
                    $uploadedCount++;
                    
                } catch (\Exception $e) {
                    \Log::error('Error uploading file during task update: ' . $e->getMessage());
                }
            }
            
            // Mettre à jour le compteur
            if ($uploadedCount > 0) {
                $task->increment('files_count', $uploadedCount);
            }
        }
        
        DB::commit();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Tâche mise à jour avec succès' . ($request->hasFile('new_files') ? ' (' . count($request->file('new_files')) . ' nouveau(x) fichier(s))' : ''),
                'data' => $task,
                'files_count' => $task->files_count,
                'redirect' => route('tasks.show', $task->id)
            ]);
        }
        
        return redirect()->route('tasks.show', $task->id)
            ->with('success', 'Tâche mise à jour avec succès');
            
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error updating task: ' . $e->getMessage());
        
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de la tâche',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
        
        return redirect()->back()
            ->with('error', 'Erreur lors de la mise à jour de la tâche')
            ->withInput();
    }
}

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Task $task)
    {
        try {
            DB::beginTransaction();
            
            $taskName = $task->name;
            $projectName = $task->project->name ?? 'N/A';
            $filesCount = $task->files->count();
            
            foreach ($task->files as $file) {
                $path = storage_path('app/' . $file->file_path);
                if (file_exists($path)) {
                    unlink($path);
                }
            }
            
            $taskPath = storage_path('app/public/tasks/' . $task->id);
            if (is_dir($taskPath)) {
                $this->removeDirectory($taskPath);
            }
            
            $task->delete();
            
            DB::commit();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "La tâche '{$taskName}' et ses {$filesCount} fichier(s) ont été supprimés"
                ]);
            }
            
            return redirect()->route('tasks.index')
                ->with('success', "La tâche '{$taskName}' et ses {$filesCount} fichier(s) ont été supprimés");
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting task: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression de la tâche',
                    'error' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression de la tâche');
        }
    }

    /**
     * Toggle task status.
     */
    public function toggleStatus(Request $request, Task $task)
    {
        try {
            $oldStatus = $task->status;
            $newStatus = $request->completed ? 'approved' : 'in_progress';
            
            $task->status = $newStatus;
            $task->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour avec succès',
                'data' => [
                    'status' => $task->status,
                    'formatted_status' => $task->formatted_status
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error toggling task status: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du statut',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Duplicate a task with its files.
     */
    public function duplicate(Task $task)
    {
        try {
            DB::beginTransaction();
            
            $newTask = $task->replicate();
            $newTask->name = $task->name . ' (copie)';
            $newTask->status = 'pending';
            $newTask->created_at = now();
            $newTask->updated_at = now();
            $newTask->files_count = 0;
            
            $metadata = json_decode($task->metadata, true) ?? [];
            $metadata['duplicated_from'] = $task->id;
            $metadata['duplicated_at'] = now()->toDateTimeString();
            $metadata['duplicated_by'] = Auth::user()->name;
            $newTask->metadata = json_encode($metadata);
            
            $newTask->save();
            
            foreach ($task->files as $file) {
                $newPath = 'tasks/' . $newTask->id . '/' . $file->file_name;
                
                Storage::disk('public')->copy($file->file_path, $newPath);
                
                TaskFile::create([
                    'task_id' => $newTask->id,
                    'user_id' => Auth::id(),
                    'file_name' => $file->file_name,
                    'original_name' => $file->original_name,
                    'file_path' => $newPath,
                    'file_size' => $file->file_size,
                    'mime_type' => $file->mime_type,
                    'file_extension' => $file->file_extension,
                    'storage_disk' => 'public',
                    'description' => $file->description,
                    'is_public' => $file->is_public,
                    'is_temporary' => $file->is_temporary,
                    'expires_at' => $file->expires_at,
                    'custom_properties' => json_encode([
                        'duplicated_from' => $file->id,
                        'duplicated_at' => now()->toDateTimeString(),
                        'original_task' => $task->id,
                    ]),
                ]);
                
                $newTask->increment('files_count');
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Tâche dupliquée avec succès (' . $task->files->count() . ' fichier(s) copié(s))',
                'data' => $newTask,
                'redirect' => route('tasks.edit', $newTask->id)
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error duplicating task: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la duplication de la tâche',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Assign task to a user.
     */
    public function assign(Request $request, Task $task)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);
        
        try {
            $oldUser = $task->user->name ?? 'Personne';
            $newUser = User::find($request->user_id)->name;
            
            $task->user_id = $request->user_id;
            $task->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Tâche assignée avec succès',
                'data' => [
                    'user_id' => $task->user_id,
                    'user_name' => $task->user->name
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'assignation'
            ], 500);
        }
    }

    /**
     * Get task comments.
     */
    public function comments(Task $task)
    {
        $comments = $task->comments()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $comments
        ]);
    }

    /**
     * Add a comment to a task.
     */
    public function addComment(Request $request, Task $task)
    {
        $request->validate([
            'content' => 'required|string'
        ]);
        
        try {
            $comment = $task->comments()->create([
                'user_id' => Auth::id(),
                'content' => $request->content
            ]);
            
            $comment->load('user');
            
            return response()->json([
                'success' => true,
                'message' => 'Commentaire ajouté avec succès',
                'data' => $comment
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout du commentaire'
            ], 500);
        }
    }

    /**
     * Update test date.
     */
    public function updateTestDate(Request $request, Task $task)
    {
        $request->validate([
            'test_date' => 'required|date',
            'test_details' => 'nullable|string'
        ]);
        
        try {
            $task->test_date = $request->test_date;
            $task->test_details = $request->test_details;
            $task->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Date de test mise à jour avec succès'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour'
            ], 500);
        }
    }

    /**
     * Update integration date.
     */
    public function updateIntegrationDate(Request $request, Task $task)
    {
        $request->validate([
            'integration_date' => 'required|date'
        ]);
        
        try {
            $task->integration_date = $request->integration_date;
            $task->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Date d\'intégration mise à jour avec succès'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour'
            ], 500);
        }
    }

    /**
     * Update push to production date.
     */
    public function updatePushProdDate(Request $request, Task $task)
    {
        $request->validate([
            'push_prod_date' => 'required|date'
        ]);
        
        try {
            $task->push_prod_date = $request->push_prod_date;
            $task->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Date de mise en production mise à jour avec succès'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour'
            ], 500);
        }
    }

    /**
     * Export tasks to CSV.
     */
    public function export(Request $request)
    {
        try {
            $query = Task::with(['project', 'user', 'creator'])
                ->where('etablissement_id', Auth::user()->etablissement_id);
            
            if ($request->filled('project_id')) {
                $query->where('project_id', $request->project_id);
            }
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }
            
            $tasks = $query->get();
            
            $filename = 'taches_' . now()->format('Y-m-d_His') . '.csv';
            $handle = fopen('php://temp', 'w');
            
            fputs($handle, "\xEF\xBB\xBF");
            
            fputcsv($handle, [
                'ID',
                'Nom',
                'Projet',
                'Assigné à',
                'Statut',
                'Date d\'échéance',
                'Date de livraison',
                'Heures estimées',
                'Coût estimé',
                'Pays',
                'Lieu',
                'N° Contrat',
                'Contact',
                'Date de test',
                'Date d\'intégration',
                'Date de MEP',
                'URL Module',
                'Approuvé',
                'Nb Fichiers',
                'Créé le',
                'Créé par'
            ], ';');
            
            foreach ($tasks as $task) {
                fputcsv($handle, [
                    $task->id,
                    $task->name,
                    $task->project->name ?? 'N/A',
                    $task->user->name ?? 'N/A',
                    $task->formatted_status,
                    $task->due_date ? $task->due_date->format('d/m/Y H:i') : 'N/A',
                    $task->delivery_date ? $task->delivery_date->format('d/m/Y H:i') : 'N/A',
                    $task->estimated_hours ?? 0,
                    number_format($task->estimated_cost ?? 0, 2, ',', ' ') . ' €',
                    $task->country ?? 'N/A',
                    $task->location ?? 'N/A',
                    $task->contract_number ?? 'N/A',
                    $task->contact_name ?? 'N/A',
                    $task->test_date ? $task->test_date->format('d/m/Y H:i') : 'N/A',
                    $task->integration_date ? $task->integration_date->format('d/m/Y H:i') : 'N/A',
                    $task->push_prod_date ? $task->push_prod_date->format('d/m/Y H:i') : 'N/A',
                    $task->module_url ?? 'N/A',
                    $task->is_approved_by_manager ? 'Oui' : 'Non',
                    $task->files_count,
                    $task->created_at->format('d/m/Y H:i'),
                    $task->creator->name ?? 'N/A'
                ], ';');
            }
            
            rewind($handle);
            $content = stream_get_contents($handle);
            fclose($handle);
            
            return response($content)
                ->header('Content-Type', 'text/csv; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
            
        } catch (\Exception $e) {
            \Log::error('Error exporting tasks: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'export des tâches');
        }
    }

    /**
     * Bulk delete tasks.
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:tasks,id'
        ]);
        
        try {
            DB::beginTransaction();
            
            $tasks = Task::whereIn('id', $request->ids)
                ->where('etablissement_id', Auth::user()->etablissement_id)
                ->get();
            
            $count = $tasks->count();
            $totalFiles = 0;
            
            foreach ($tasks as $task) {
                $totalFiles += $task->files->count();
                
                foreach ($task->files as $file) {
                    $path = storage_path('app/' . $file->file_path);
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }
                
                $taskPath = storage_path('app/public/tasks/' . $task->id);
                if (is_dir($taskPath)) {
                    $this->removeDirectory($taskPath);
                }
                
                $task->delete();
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "{$count} tâche(s) et {$totalFiles} fichier(s) supprimé(s) avec succès",
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
     * Get upcoming tasks (for dashboard).
     */
    public function getUpcomingTasks()
    {
        $tasks = Task::where('etablissement_id', Auth::user()->etablissement_id)
            ->whereNotNull('due_date')
            ->where('due_date', '>=', now())
            ->where('due_date', '<=', now()->addDays(7))
            ->whereNotIn('status', ['approved', 'cancelled'])
            ->with(['project', 'user'])
            ->orderBy('due_date')
            ->limit(10)
            ->get()
            ->map(function($task) {
                return [
                    'id' => $task->id,
                    'name' => $task->name,
                    'project_name' => $task->project->name ?? 'N/A',
                    'user_name' => $task->user->name ?? 'Non assigné',
                    'due_date' => $task->due_date->format('d/m/Y H:i'),
                    'days_remaining' => now()->diffInDays($task->due_date, false) + 1,
                    'files_count' => $task->files_count,
                    'url' => route('tasks.show', $task->id)
                ];
            });
        
        return response()->json([
            'success' => true,
            'data' => $tasks
        ]);
    }

    /**
     * Get overdue tasks (for dashboard).
     */
    public function getOverdueTasks()
    {
        $tasks = Task::where('etablissement_id', Auth::user()->etablissement_id)
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->whereNotIn('status', ['approved', 'cancelled'])
            ->with(['project', 'user'])
            ->orderBy('due_date')
            ->limit(10)
            ->get()
            ->map(function($task) {
                return [
                    'id' => $task->id,
                    'name' => $task->name,
                    'project_name' => $task->project->name ?? 'N/A',
                    'user_name' => $task->user->name ?? 'Non assigné',
                    'due_date' => $task->due_date->format('d/m/Y H:i'),
                    'days_overdue' => abs(now()->diffInDays($task->due_date, false)),
                    'files_count' => $task->files_count,
                    'url' => route('tasks.show', $task->id)
                ];
            });
        
        return response()->json([
            'success' => true,
            'data' => $tasks
        ]);
    }

    /**
     * Get tasks by user.
     */
    public function getTasksByUser(User $user)
    {
        $tasks = Task::where('etablissement_id', Auth::user()->etablissement_id)
            ->where('user_id', $user->id)
            ->whereNotIn('status', ['approved', 'cancelled'])
            ->with('project')
            ->orderBy('due_date')
            ->limit(20)
            ->get()
            ->map(function($task) {
                return [
                    'id' => $task->id,
                    'name' => $task->name,
                    'project_name' => $task->project->name ?? 'N/A',
                    'status' => $task->formatted_status,
                    'status_color' => $task->status_color,
                    'due_date' => $task->due_date ? $task->due_date->format('d/m/Y H:i') : null,
                    'is_overdue' => $task->isOverdue(),
                    'files_count' => $task->files_count,
                    'url' => route('tasks.show', $task->id)
                ];
            });
        
        return response()->json([
            'success' => true,
            'data' => $tasks
        ]);
    }

    /**
     * Get task summary for dashboard.
     */
    public function summary()
    {
        $etablissementId = Auth::user()->etablissement_id;
        
        $stats = [
            'total' => Task::where('etablissement_id', $etablissementId)->count(),
            'pending' => Task::where('etablissement_id', $etablissementId)->where('status', 'pending')->count(),
            'in_progress' => Task::where('etablissement_id', $etablissementId)->where('status', 'in_progress')->count(),
            'test' => Task::where('etablissement_id', $etablissementId)->where('status', 'test')->count(),
            'integrated' => Task::where('etablissement_id', $etablissementId)->where('status', 'integrated')->count(),
            'delivered' => Task::where('etablissement_id', $etablissementId)->where('status', 'delivered')->count(),
            'approved' => Task::where('etablissement_id', $etablissementId)->where('status', 'approved')->count(),
            'cancelled' => Task::where('etablissement_id', $etablissementId)->where('status', 'cancelled')->count(),
            'overdue' => Task::where('etablissement_id', $etablissementId)
                ->whereNotNull('due_date')
                ->where('due_date', '<', now())
                ->whereNotIn('status', ['approved', 'cancelled'])
                ->count(),
            'upcoming' => Task::where('etablissement_id', $etablissementId)
                ->whereNotNull('due_date')
                ->where('due_date', '>=', now())
                ->where('due_date', '<=', now()->addDays(7))
                ->whereNotIn('status', ['approved', 'cancelled'])
                ->count(),
            'total_files' => TaskFile::whereIn('task_id', function($q) use ($etablissementId) {
                $q->select('id')->from('tasks')->where('etablissement_id', $etablissementId);
            })->count(),
        ];
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    // ==================== FICHIERS ====================

    /**
     * Upload a file for a task.
     */
    public function uploadFile(Request $request, Task $task)
    {
        try {
            $request->validate([
                'file' => 'required|file|max:10240',
                'description' => 'nullable|string|max:255',
                'is_public' => 'nullable|boolean',
                'expires_at' => 'nullable|date|after:now',
            ]);

            $file = $request->file('file');
            
            $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('tasks/' . $task->id, $fileName, 'public');

            $taskFile = TaskFile::create([
                'task_id' => $task->id,
                'user_id' => Auth::id(),
                'file_name' => $fileName,
                'original_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'file_extension' => $file->getClientOriginalExtension(),
                'storage_disk' => 'public',
                'description' => $request->description,
                'is_public' => $request->boolean('is_public', true),
                'is_temporary' => $request->boolean('is_temporary', false),
                'expires_at' => $request->expires_at,
                'custom_properties' => json_encode([
                    'uploaded_at' => now()->toDateTimeString(),
                    'uploaded_by' => Auth::user()->name,
                    'uploaded_by_email' => Auth::user()->email,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]),
            ]);

            $task->increment('files_count');

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Fichier uploadé avec succès',
                    'data' => $this->formatFileData($taskFile)
                ]);
            }

            return redirect()->back()->with('success', 'Fichier uploadé avec succès');

        } catch (\Exception $e) {
            \Log::error('Error uploading file: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'upload du fichier',
                    'error' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }

            return redirect()->back()->with('error', 'Erreur lors de l\'upload du fichier');
        }
    }

    /**
     * Upload multiple files for a task.
     */
    public function uploadMultipleFiles(Request $request, Task $task)
    {
        try {
            $request->validate([
                'files' => 'required|array',
                'files.*' => 'file|max:10240',
            ]);

            $uploadedFiles = [];
            $errors = [];

            foreach ($request->file('files') as $file) {
                try {
                    $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                    $filePath = $file->storeAs('tasks/' . $task->id, $fileName, 'public');

                    $taskFile = TaskFile::create([
                        'task_id' => $task->id,
                        'user_id' => Auth::id(),
                        'file_name' => $fileName,
                        'original_name' => $file->getClientOriginalName(),
                        'file_path' => $filePath,
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                        'file_extension' => $file->getClientOriginalExtension(),
                        'storage_disk' => 'public',
                        'is_public' => true,
                        'is_temporary' => false,
                        'custom_properties' => json_encode([
                            'uploaded_at' => now()->toDateTimeString(),
                            'uploaded_by' => Auth::user()->name,
                        ]),
                    ]);

                    $uploadedFiles[] = $this->formatFileData($taskFile);

                } catch (\Exception $e) {
                    $errors[] = $file->getClientOriginalName() . ': ' . $e->getMessage();
                }
            }

            if (count($uploadedFiles) > 0) {
                $task->increment('files_count', count($uploadedFiles));
            }

            return response()->json([
                'success' => true,
                'message' => count($uploadedFiles) . ' fichier(s) uploadé(s) avec succès',
                'data' => $uploadedFiles,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            \Log::error('Error uploading multiple files: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'upload des fichiers',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Download a file.
     */
    public function downloadFile(Task $task, TaskFile $file)
    {
        if ($file->task_id !== $task->id) {
            abort(404);
        }

        $path = storage_path('app/' . $file->file_path);

        if (!file_exists($path)) {
            abort(404, 'Fichier non trouvé');
        }

        return response()->download($path, $file->original_name);
    }

    /**
     * Preview a file.
     */
    public function previewFile(Task $task, TaskFile $file)
    {
        if ($file->task_id !== $task->id) {
            abort(404);
        }

        $path = storage_path('app/' . $file->file_path);

        if (!file_exists($path)) {
            abort(404, 'Fichier non trouvé');
        }

        return response()->file($path);
    }

    /**
     * Delete a file.
     */
    public function deleteFile(Request $request, Task $task, TaskFile $file)
    {
        if ($file->task_id !== $task->id) {
            abort(404);
        }

        try {
            DB::beginTransaction();

            $path = storage_path('app/' . $file->file_path);
            if (file_exists($path)) {
                unlink($path);
            }

            $this->removeDirectoryIfEmpty(dirname($path));

            $fileName = $file->original_name;
            $fileId = $file->id;

            $file->delete();

            $task->decrement('files_count');

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Fichier supprimé avec succès',
                    'file_id' => $fileId
                ]);
            }

            return redirect()->back()->with('success', 'Fichier supprimé avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting file: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression du fichier',
                    'error' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }

            return redirect()->back()->with('error', 'Erreur lors de la suppression du fichier');
        }
    }

    /**
     * Get all files for a task.
     */
    public function getFiles(Task $task)
    {
        $files = $task->files()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($file) {
                return $this->formatFileData($file);
            });

        $stats = [
            'total' => $files->count(),
            'total_size' => $this->formatBytes($task->files->sum('file_size')),
            'images' => $task->files()->get()->filter(function($f) { 
                return $this->isImage($f->file_extension); 
            })->count(),
            'documents' => $task->files()->get()->filter(function($f) { 
                return in_array(strtolower($f->file_extension), ['pdf', 'doc', 'docx', 'txt', 'xls', 'xlsx', 'ppt', 'pptx']); 
            })->count(),
            'public' => $task->files()->where('is_public', true)->count(),
            'temporary' => $task->files()->where('is_temporary', true)->count(),
            'expired' => $task->files()->where('expires_at', '<', now())->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $files,
            'stats' => $stats
        ]);
    }

    /**
     * Clean up expired temporary files.
     */
    public function cleanExpiredFiles()
    {
        $expiredFiles = TaskFile::where('is_temporary', true)
            ->where('expires_at', '<', now())
            ->get();

        $count = 0;
        foreach ($expiredFiles as $file) {
            try {
                $path = storage_path('app/' . $file->file_path);
                if (file_exists($path)) {
                    unlink($path);
                }
                
                $file->task->decrement('files_count');
                
                $file->delete();
                $count++;
                
            } catch (\Exception $e) {
                \Log::error('Error cleaning expired file: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$count} fichier(s) temporaire(s) expiré(s) nettoyé(s)"
        ]);
    }

    /**
     * Update file description.
     */
    public function updateFileDescription(Request $request, Task $task, TaskFile $file)
    {
        if ($file->task_id !== $task->id) {
            abort(404);
        }

        $request->validate([
            'description' => 'nullable|string|max:255'
        ]);

        try {
            $file->description = $request->description;
            $file->save();

            return response()->json([
                'success' => true,
                'message' => 'Description mise à jour avec succès',
                'data' => $this->formatFileData($file)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour'
            ], 500);
        }
    }

    /**
     * Toggle file public status.
     */
    public function toggleFilePublic(Task $task, TaskFile $file)
    {
        if ($file->task_id !== $task->id) {
            abort(404);
        }

        try {
            $file->is_public = !$file->is_public;
            $file->save();

            return response()->json([
                'success' => true,
                'message' => $file->is_public ? 'Fichier rendu public' : 'Fichier rendu privé',
                'is_public' => $file->is_public
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour'
            ], 500);
        }
    }

    // ==================== UTILITAIRES ====================

    /**
     * Format file data for API response.
     */
    private function formatFileData($file)
    {
        $customProps = json_decode($file->custom_properties, true) ?? [];
        
        return [
            'id' => $file->id,
            'name' => $file->original_name,
            'size' => $this->formatBytes($file->file_size),
            'icon' => $this->getFileIcon($file->file_extension),
            'extension' => $file->file_extension,
            'uploaded_by' => $file->user->name ?? 'Système',
            'uploaded_at' => $file->created_at->format('d/m/Y H:i'),
            'description' => $file->description,
            'is_public' => $file->is_public,
            'is_temporary' => $file->is_temporary,
            'expires_at' => $file->expires_at ? $file->expires_at->format('d/m/Y H:i') : null,
            'is_expired' => $file->expires_at ? $file->expires_at->isPast() : false,
            'download_url' => route('tasks.files.download', ['task' => $file->task_id, 'file' => $file->id]),
            'preview_url' => $this->canPreview($file->file_extension) ? 
                route('tasks.files.preview', ['task' => $file->task_id, 'file' => $file->id]) : null,
            'can_preview' => $this->canPreview($file->file_extension),
            'is_image' => $this->isImage($file->file_extension),
            'metadata' => $customProps,
        ];
    }

    /**
     * Format bytes to human readable format.
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Get file icon based on extension.
     */
    private function getFileIcon($extension)
    {
        $extension = strtolower($extension);
        
        $icons = [
            'pdf' => 'fas fa-file-pdf text-danger',
            'doc' => 'fas fa-file-word text-primary',
            'docx' => 'fas fa-file-word text-primary',
            'xls' => 'fas fa-file-excel text-success',
            'xlsx' => 'fas fa-file-excel text-success',
            'ppt' => 'fas fa-file-powerpoint text-warning',
            'pptx' => 'fas fa-file-powerpoint text-warning',
            'jpg' => 'fas fa-file-image text-info',
            'jpeg' => 'fas fa-file-image text-info',
            'png' => 'fas fa-file-image text-info',
            'gif' => 'fas fa-file-image text-info',
            'svg' => 'fas fa-file-image text-info',
            'zip' => 'fas fa-file-archive text-secondary',
            'rar' => 'fas fa-file-archive text-secondary',
            '7z' => 'fas fa-file-archive text-secondary',
            'tar' => 'fas fa-file-archive text-secondary',
            'gz' => 'fas fa-file-archive text-secondary',
            'mp3' => 'fas fa-file-audio',
            'wav' => 'fas fa-file-audio',
            'ogg' => 'fas fa-file-audio',
            'mp4' => 'fas fa-file-video',
            'avi' => 'fas fa-file-video',
            'mov' => 'fas fa-file-video',
            'wmv' => 'fas fa-file-video',
            'txt' => 'fas fa-file-alt text-muted',
            'rtf' => 'fas fa-file-alt text-muted',
            'md' => 'fas fa-file-alt text-muted',
            'csv' => 'fas fa-file-csv text-success',
            'xml' => 'fas fa-file-code text-warning',
            'json' => 'fas fa-file-code text-warning',
            'js' => 'fas fa-file-code text-warning',
            'css' => 'fas fa-file-code text-warning',
            'html' => 'fas fa-file-code text-warning',
            'php' => 'fas fa-file-code text-warning',
        ];
        
        return $icons[$extension] ?? 'fas fa-file text-muted';
    }

    /**
     * Check if file can be previewed.
     */
    private function canPreview($extension)
    {
        $previewExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'pdf'];
        return in_array(strtolower($extension), $previewExtensions);
    }

    /**
     * Check if file is an image.
     */
    private function isImage($extension)
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'bmp', 'webp'];
        return in_array(strtolower($extension), $imageExtensions);
    }

    /**
     * Remove directory if empty.
     */
    private function removeDirectoryIfEmpty($path)
    {
        if (is_dir($path) && count(scandir($path)) == 2) {
            rmdir($path);
        }
    }

    /**
     * Remove directory recursively.
     */
    private function removeDirectory($dir)
    {
        if (!is_dir($dir)) {
            return;
        }
        
        $files = array_diff(scandir($dir), ['.', '..']);
        
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->removeDirectory($path) : unlink($path);
        }
        
        return rmdir($dir);
    }
}