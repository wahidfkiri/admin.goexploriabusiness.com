<?php

namespace Vendor\Users\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeUserMail;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        // Get all roles for filter dropdown
        $roles = Role::all();
        
        // If AJAX request, return JSON
        if ($request->ajax()) {
            $query = User::with('roles', 'etablissement');
            
            // Apply search filter
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%");
                });
            }
            
            // Apply role filter
            if ($request->has('role') && !empty($request->role)) {
                $query->whereHas('roles', function($q) use ($request) {
                    $q->where('name', $request->role);
                });
            }
            
            // Apply status filter
            if ($request->has('status') && !empty($request->status)) {
                $query->where('is_active', $request->status === 'active');
            }
            
            // Apply sorting
            $sortBy = $request->get('sort_by', 'name');
            $sortDirection = $request->get('sort_direction', 'asc');
            $query->orderBy($sortBy, $sortDirection);
            
            // Paginate results
            $perPage = $request->get('per_page', 10);
            $users = $query->paginate($perPage);
            
            return response()->json([
                'success' => true,
                'data' => $users->items(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'prev_page_url' => $users->previousPageUrl(),
                'next_page_url' => $users->nextPageUrl(),
            ]);
        }
        
        // Return view for non-AJAX requests
        return view('users::users.index', compact('roles'));
    }

    /**
     * Store a newly created user.
     */

public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'is_active' => 'nullable',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation error',
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        DB::beginTransaction();
        
        // Garder le mot de passe en clair pour l'email
        $passwordPlain = $request->password;

        // Créer l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($passwordPlain),
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Envoyer email de bienvenue
        Mail::to($user->email)->send(new WelcomeUserMail($user, $passwordPlain));
        
        DB::commit();
        
        return response()->json([
            'success' => true,
            'message' => 'Utilisateur créé avec succès !',
            'user' => $user->load('roles')
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la création de l\'utilisateur',
            'error' => $e->getMessage()
        ], 500);
    }
}


    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'is_active' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            // Update user
            $user->name = $request->name;
            $user->email = $request->email;
            $user->is_active = $request->boolean('is_active', $user->is_active);
            
            // Update password if provided
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            
            $user->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Utilisateur mis à jour avec succès !',
                'user' => $user->load('roles')
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de l\'utilisateur',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        try {
            // Prevent deleting own account
            if ($user->id === auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous ne pouvez pas supprimer votre propre compte !'
                ], 403);
            }
            
            $user->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Utilisateur supprimé avec succès !'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de l\'utilisateur',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle user active status.
     */
    public function toggleStatus(User $user)
    {
        try {
            // Prevent deactivating own account
            if ($user->id === auth()->id() && $user->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous ne pouvez pas désactiver votre propre compte !'
                ], 403);
            }
            
            $user->is_active = !$user->is_active;
            $user->save();
            
            $status = $user->is_active ? 'activé' : 'désactivé';
            
            return response()->json([
                'success' => true,
                'message' => "Utilisateur {$status} avec succès !",
                'is_active' => $user->is_active
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du changement de statut',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user roles.
     */
    public function updateRoles(Request $request, User $user)
    {
        try {
            $request->validate([
                'roles' => 'array',
                'roles.*' => 'exists:roles,id'
            ]);
            
            // Prevent removing all roles from own account
            if ($user->id === auth()->id() && empty($request->roles)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous devez avoir au moins un rôle !'
                ], 403);
            }
            
            // Sync roles
            $roleIds = $request->input('roles', []);
            $user->roles()->sync($roleIds);
            
            return response()->json([
                'success' => true,
                'message' => 'Rôles mis à jour avec succès !',
                'user' => $user->load('roles')
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour des rôles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all available roles.
     */
    public function getRoles()
    {
        $roles = Role::all(['id', 'name']);
        
        return response()->json($roles);
    }

    /**
     * Bulk update users.
     */
    public function bulkUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:users,id',
            'action' => 'required|in:activate,deactivate,delete'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            $userIds = $request->ids;
            $action = $request->action;
            $message = '';
            
            // Prevent bulk operations on own account
            if (in_array(auth()->id(), $userIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous ne pouvez pas effectuer cette action sur votre propre compte !'
                ], 403);
            }
            
            switch ($action) {
                case 'activate':
                    User::whereIn('id', $userIds)->update(['is_active' => true]);
                    $message = count($userIds) . ' utilisateur(s) activé(s) avec succès !';
                    break;
                    
                case 'deactivate':
                    User::whereIn('id', $userIds)->update(['is_active' => false]);
                    $message = count($userIds) . ' utilisateur(s) désactivé(s) avec succès !';
                    break;
                    
                case 'delete':
                    User::whereIn('id', $userIds)->delete();
                    $message = count($userIds) . ' utilisateur(s) supprimé(s) avec succès !';
                    break;
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'opération groupée',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user statistics.
     */
    public function statistics()
    {
        try {
            $totalUsers = User::count();
            $activeUsers = User::where('is_active', true)->count();
            $inactiveUsers = User::where('is_active', false)->count();
            
            // Get admin count (users with admin role)
            $adminCount = User::whereHas('roles', function($query) {
                $query->where('name', 'admin');
            })->count();
            
            // New users in last 7 days
            $newLast7Days = User::where('created_at', '>=', now()->subDays(7))->count();
            
            // Active percentage
            $activePercentage = $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 2) : 0;
            
            // Get users by role
            $usersByRole = Role::withCount('users')->get()->map(function($role) {
                return [
                    'name' => $role->name,
                    'count' => $role->users_count
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => [
                    'total_users' => $totalUsers,
                    'active_users' => $activeUsers,
                    'inactive_users' => $inactiveUsers,
                    'admin_count' => $adminCount,
                    'new_last_7_days' => $newLast7Days,
                    'active_percentage' => $activePercentage,
                    'users_by_role' => $usersByRole,
                    'most_recent_user' => User::latest()->first()?->only(['id', 'name', 'email', 'created_at'])
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement des statistiques',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show user details.
     */
    public function show(User $user)
    {
        $user->load('roles', 'etablissement');
        
        // Return JSON for AJAX requests
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        }
        
        // Return view for regular requests
        return view('users.show', compact('user'));
    }

    /**
     * Search users.
     */
    public function search(Request $request)
    {
        $query = User::with('roles');
        
        if ($request->has('q') && !empty($request->q)) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->has('role') && !empty($request->role)) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }
        
        $users = $query->limit(20)->get();
        
        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }

    /**
     * Export users to CSV.
     */
    public function export(Request $request)
    {
        $users = User::with('roles')->get();
        
        $fileName = 'users_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];
        
        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Add headers
            fputcsv($file, [
                'ID',
                'Nom',
                'Email',
                'Rôles',
                'Statut',
                'Date de création',
                'Dernière modification'
            ], ';');
            
            // Add data
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->roles->pluck('name')->implode(', '),
                    $user->is_active ? 'Actif' : 'Inactif',
                    $user->created_at->format('d/m/Y H:i'),
                    $user->updated_at->format('d/m/Y H:i')
                ], ';');
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
 * Get user statistics (alias for statistics method)
 */
public function getStatistics()
{
    return $this->statistics();
}
}