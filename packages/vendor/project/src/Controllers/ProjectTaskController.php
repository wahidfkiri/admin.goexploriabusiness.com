<?php

namespace Vendor\Project\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Etablissement;
use Illuminate\Http\Request;

class ProjectTaskController extends Controller
{
    public function index(Project $project, Request $request)
    {
        $query = $project->tasks()->with(['user', 'etablissement', 'creator']);
        
        // Filtres
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('contract_number', 'like', '%' . $request->search . '%')
                  ->orWhere('contact_name', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('etablissement_id')) {
            $query->where('etablissement_id', $request->etablissement_id);
        }
        
        if ($request->filled('date_range')) {
            switch($request->date_range) {
                case 'today':
                    $query->whereDate('due_date', today());
                    break;
                case 'week':
                    $query->whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('due_date', now()->month);
                    break;
                case 'overdue':
                    $query->where('due_date', '<', now())
                          ->whereNotIn('status', ['approved', 'delivered', 'cancelled']);
                    break;
            }
        }
        
        // Tri
        $query->orderBy('due_date')->orderBy('created_at', 'desc');
        
        $tasks = $query->paginate(15);
        
        // Statistiques
        $allTasks = $project->tasks;
        $completedTasks = $allTasks->whereIn('status', ['approved', 'delivered'])->count();
        $inProgressTasks = $allTasks->where('status', 'in_progress')->count();
        $pendingTasks = $allTasks->where('status', 'pending')->count();
        $overdueTasks = $allTasks->filter(function($task) {
            return $task->isOverdue();
        })->count();
        
        $projectProgress = $allTasks->count() > 0 
            ? round(($completedTasks / $allTasks->count()) * 100) 
            : 0;
        
        // Données pour les filtres
        $users = User::all();
        $etablissements = Etablissement::all();
        $statuses = [
            'pending' => 'En attente',
            'in_progress' => 'En cours',
            'test' => 'En test',
            'integrated' => 'Intégré',
            'delivered' => 'Livré',
            'approved' => 'Approuvé',
            'cancelled' => 'Annulé'
        ];
        
        // Couleur du projet (basée sur son nom)
        $projectColor = $this->getProjectColor($project->name);
        
        return view('project::projects.tasks.index', compact(
            'project',
            'tasks',
            'completedTasks',
            'inProgressTasks',
            'pendingTasks',
            'overdueTasks',
            'projectProgress',
            'users',
            'etablissements',
            'statuses',
            'projectColor'
        ));
    }
    
    public function create(Project $project)
    {
        return view('tasks.create', compact('project'));
    }
    
    public function store(Request $request, Project $project)
    {
        // Validation et création
    }
    
    public function show(Project $project, Task $task)
    {
        return response()->json([
            'success' => true,
            'task' => $task->load(['user', 'etablissement', 'creator', 'generalManager', 'clientManager', 'approvedBy'])
        ]);
    }
    
    public function edit(Project $project, Task $task)
    {
        return view('tasks.edit', compact('project', 'task'));
    }
    
    public function update(Request $request, Project $project, Task $task)
    {
        // Validation et mise à jour
    }
    
    public function destroy(Project $project, Task $task)
    {
        $task->delete();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Tâche supprimée avec succès'
            ]);
        }
        
        return redirect()->route('projects.tasks.index', $project)
            ->with('success', 'Tâche supprimée avec succès');
    }
    
    private function getProjectColor($projectName)
    {
        $colors = [
            '#45b7d1', '#96ceb4', '#feca57', '#ff6b6b',
            '#9b59b6', '#3498db', '#1abc9c', '#e74c3c',
            '#34495e', '#f1c40f', '#2ecc71', '#e67e22'
        ];
        
        $hash = 0;
        for ($i = 0; $i < strlen($projectName); $i++) {
            $hash = ord($projectName[$i]) + (($hash << 5) - $hash);
        }
        
        return $colors[abs($hash) % count($colors)];
    }
}