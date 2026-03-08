<?php
// Vendor/Users/Controllers/RoleController.php

namespace Vendor\Users\Controllers;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the roles.
     */
    public function index(Request $request)
    {
        // If AJAX request, return JSON
        if ($request->ajax()) {
            $query = Role::with('permissions')->withCount('users');
            
            // Apply search filter
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where('name', 'LIKE', "%{$search}%");
            }
            
            // Apply guard filter
            if ($request->has('guard') && !empty($request->guard)) {
                $query->where('guard_name', $request->guard);
            }
            
            // Apply sorting
            $sortBy = $request->get('sort_by', 'name');
            $sortDirection = $request->get('sort_direction', 'asc');
            
            if ($sortBy === 'permissions_count') {
                $query->orderBy('permissions_count', $sortDirection);
            } else {
                $query->orderBy($sortBy, $sortDirection);
            }
            
            // Paginate results
            $perPage = $request->get('per_page', 15);
            $roles = $query->paginate($perPage);
            
            return response()->json([
                'success' => true,
                'data' => $roles->items(),
                'current_page' => $roles->currentPage(),
                'last_page' => $roles->lastPage(),
                'per_page' => $roles->perPage(),
                'total' => $roles->total(),
                'prev_page_url' => $roles->previousPageUrl(),
                'next_page_url' => $roles->nextPageUrl(),
            ]);
        }
        
        // Return view for non-AJAX requests
        return view('users::roles.index');
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name',
            'guard_name' => 'required|in:web,api',
            'permissions' => 'sometimes|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            // Create role
            $role = Role::create([
                'name' => $request->name,
                'guard_name' => $request->guard_name
            ]);

            // Sync permissions if provided
            if ($request->has('permissions')) {
                $permissions = Permission::whereIn('id', $request->permissions)->get();
                $role->syncPermissions($permissions);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Rôle créé avec succès !',
                'data' => $role->load('permissions')
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du rôle',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, Role $role)
    {
        // Prevent modification of system roles
        if (in_array($role->name, ['super-admin', 'admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Ce rôle système ne peut pas être modifié'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'guard_name' => 'required|in:web,api',
            'permissions' => 'sometimes|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            // Update role
            $role->update([
                'name' => $request->name,
                'guard_name' => $request->guard_name
            ]);

            // Sync permissions if provided
            if ($request->has('permissions')) {
                $permissions = Permission::whereIn('id', $request->permissions)->get();
                $role->syncPermissions($permissions);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Rôle mis à jour avec succès !',
                'data' => $role->load('permissions')
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du rôle',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified role.
     */
    public function destroy(Role $role)
    {
        // Prevent deletion of system roles
        if (in_array($role->name, ['super-admin', 'admin', 'user'])) {
            return response()->json([
                'success' => false,
                'message' => 'Ce rôle système ne peut pas être supprimé'
            ], 403);
        }

        // Check if role is assigned to any users
        if ($role->users()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Ce rôle est attribué à des utilisateurs et ne peut pas être supprimé'
            ], 403);
        }

        try {
            $role->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Rôle supprimé avec succès !'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du rôle',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all permissions (for selectors)
     */
    public function getPermissions(Request $request)
    {
        $query = Permission::query();
        
        // Filter by guard if provided
        if ($request->has('guard') && !empty($request->guard)) {
            $query->where('guard_name', $request->guard);
        }
        
        // Group by 'group' column if it exists
        if ($request->has('grouped') && $request->grouped) {
            $permissions = $query->orderBy('group')->orderBy('name')->get();
            
            // Group permissions
            $groupedPermissions = $permissions->groupBy(function($permission) {
                return $permission->group ?? 'Autres';
            });
            
            return response()->json([
                'success' => true,
                'data' => $groupedPermissions
            ]);
        }
        
        $permissions = $query->orderBy('name')->get();
        
        return response()->json([
            'success' => true,
            'data' => $permissions
        ]);
    }

    /**
     * Get role details with permissions
     */
    public function show(Role $role)
    {
        $role->load('permissions');
        
        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }

    /**
     * Duplicate a role
     */
    public function duplicate(Role $role)
    {
        try {
            DB::beginTransaction();
            
            // Create new role with copy name
            $newRole = Role::create([
                'name' => $role->name . '_copy_' . uniqid(),
                'guard_name' => $role->guard_name
            ]);
            
            // Copy permissions
            $permissions = $role->permissions()->get();
            $newRole->syncPermissions($permissions);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Rôle dupliqué avec succès !',
                'data' => $newRole->load('permissions')
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la duplication du rôle',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get roles statistics
     */
    public function statistics()
    {
        try {
            $totalRoles = Role::count();
            
            $rolesByGuard = Role::select('guard_name', DB::raw('count(*) as total'))
                ->groupBy('guard_name')
                ->get();
            
            $mostAssignedRole = Role::withCount('users')
                ->orderBy('users_count', 'desc')
                ->first();
            
            $rolesWithPermissions = Role::has('permissions')->count();
            $rolesWithoutPermissions = Role::doesntHave('permissions')->count();
            
            // Get permissions distribution
            $permissionsPerRole = Role::withCount('permissions')
                ->orderBy('permissions_count', 'desc')
                ->limit(5)
                ->get()
                ->map(function($role) {
                    return [
                        'name' => $role->name,
                        'permissions_count' => $role->permissions_count
                    ];
                });
            
            return response()->json([
                'success' => true,
                'data' => [
                    'total_roles' => $totalRoles,
                    'roles_by_guard' => $rolesByGuard,
                    'most_assigned_role' => $mostAssignedRole ? [
                        'name' => $mostAssignedRole->name,
                        'users_count' => $mostAssignedRole->users_count
                    ] : null,
                    'roles_with_permissions' => $rolesWithPermissions,
                    'roles_without_permissions' => $rolesWithoutPermissions,
                    'permissions_per_role' => $permissionsPerRole
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
     * Export roles to CSV
     */
    public function export()
    {
        $roles = Role::withCount('users')->with('permissions')->get();
        
        $fileName = 'roles_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];
        
        $callback = function() use ($roles) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Add headers
            fputcsv($file, [
                'ID',
                'Nom',
                'Guard',
                'Nombre d\'utilisateurs',
                'Nombre de permissions',
                'Liste des permissions',
                'Date de création',
                'Dernière modification'
            ], ';');
            
            // Add data
            foreach ($roles as $role) {
                fputcsv($file, [
                    $role->id,
                    $role->name,
                    $role->guard_name,
                    $role->users_count,
                    $role->permissions->count(),
                    $role->permissions->pluck('name')->implode(', '),
                    $role->created_at->format('d/m/Y H:i'),
                    $role->updated_at->format('d/m/Y H:i')
                ], ';');
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}