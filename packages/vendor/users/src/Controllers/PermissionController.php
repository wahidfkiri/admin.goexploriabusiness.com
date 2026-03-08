<?php
// Vendor/Users/Controllers/PermissionController.php

namespace Vendor\Users\Controllers;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    /**
     * Display a listing of the permissions.
     */
    public function index(Request $request)
    {
        // If AJAX request, return JSON
        if ($request->ajax()) {
            $query = Permission::query();
            
            // Apply search filter
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where('name', 'LIKE', "%{$search}%");
            }
            
            // Apply guard filter
            if ($request->has('guard') && !empty($request->guard)) {
                $query->where('guard_name', $request->guard);
            }
            
            // Apply group filter
            if ($request->has('group') && !empty($request->group)) {
                $query->where('group', $request->group);
            }
            
            // Apply sorting
            $sortBy = $request->get('sort_by', 'group');
            $sortDirection = $request->get('sort_direction', 'asc');
            
            $query->orderBy($sortBy, $sortDirection)
                  ->orderBy('name', 'asc');
            
            // Get all permissions (no pagination for permissions as they're usually fewer)
            if ($request->has('all') && $request->all) {
                $permissions = $query->get();
                
                // Group permissions
                $groupedPermissions = $permissions->groupBy(function($permission) {
                    return $permission->group ?? 'Autres';
                });
                
                return response()->json([
                    'success' => true,
                    'data' => $groupedPermissions
                ]);
            }
            
            // Paginate results
            $perPage = $request->get('per_page', 50);
            $permissions = $query->paginate($perPage);
            
            return response()->json([
                'success' => true,
                'data' => $permissions->items(),
                'current_page' => $permissions->currentPage(),
                'last_page' => $permissions->lastPage(),
                'per_page' => $permissions->perPage(),
                'total' => $permissions->total()
            ]);
        }
        
        // Return view for non-AJAX requests
        return view('users::permissions.index');
    }

    /**
     * Store a newly created permission.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:permissions,name',
            'guard_name' => 'required|in:web,api',
            'group' => 'nullable|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Format permission name (replace spaces with hyphens)
            $name = strtolower(str_replace(' ', '-', $request->name));

            $permission = Permission::create([
                'name' => $name,
                'guard_name' => $request->guard_name,
                'group' => $request->group ?? 'Autres'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permission créée avec succès !',
                'data' => $permission
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la permission',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified permission.
     */
    public function destroy(Permission $permission)
    {
        // Check if permission is used by any roles
        if ($permission->roles()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cette permission est attribuée à des rôles et ne peut pas être supprimée'
            ], 403);
        }

        try {
            $permission->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Permission supprimée avec succès !'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de la permission',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all permission groups
     */
    public function getGroups()
    {
        try {
            $groups = Permission::distinct()
                ->whereNotNull('group')
                ->orderBy('group')
                ->pluck('group')
                ->values();
            
            return response()->json([
                'success' => true,
                'data' => $groups
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement des groupes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get permissions by group
     */
    public function getByGroup(Request $request)
    {
        try {
            $query = Permission::query();
            
            if ($request->has('guard') && !empty($request->guard)) {
                $query->where('guard_name', $request->guard);
            }
            
            $permissions = $query->orderBy('group')->orderBy('name')->get();
            
            $groupedPermissions = $permissions->groupBy(function($permission) {
                return $permission->group ?? 'Autres';
            });
            
            return response()->json([
                'success' => true,
                'data' => $groupedPermissions
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement des permissions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get permissions statistics
     */
    public function statistics()
    {
        try {
            $totalPermissions = Permission::count();
            
            $permissionsByGuard = Permission::select('guard_name', DB::raw('count(*) as total'))
                ->groupBy('guard_name')
                ->get();
            
            $permissionsByGroup = Permission::select('group', DB::raw('count(*) as total'))
                ->whereNotNull('group')
                ->groupBy('group')
                ->orderBy('total', 'desc')
                ->get();
            
            $mostUsedPermissions = Permission::withCount('roles')
                ->having('roles_count', '>', 0)
                ->orderBy('roles_count', 'desc')
                ->limit(10)
                ->get()
                ->map(function($permission) {
                    return [
                        'name' => $permission->name,
                        'roles_count' => $permission->roles_count
                    ];
                });
            
            $unusedPermissions = Permission::doesntHave('roles')->count();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'total_permissions' => $totalPermissions,
                    'permissions_by_guard' => $permissionsByGuard,
                    'permissions_by_group' => $permissionsByGroup,
                    'most_used_permissions' => $mostUsedPermissions,
                    'unused_permissions' => $unusedPermissions
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
     * Export permissions to CSV
     */
    public function export()
    {
        $permissions = Permission::withCount('roles')->get();
        
        $fileName = 'permissions_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];
        
        $callback = function() use ($permissions) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Add headers
            fputcsv($file, [
                'ID',
                'Nom',
                'Guard',
                'Groupe',
                'Nombre de rôles',
                'Date de création',
                'Dernière modification'
            ], ';');
            
            // Add data
            foreach ($permissions as $permission) {
                fputcsv($file, [
                    $permission->id,
                    $permission->name,
                    $permission->guard_name,
                    $permission->group ?? 'Non groupé',
                    $permission->roles_count,
                    $permission->created_at->format('d/m/Y H:i'),
                    $permission->updated_at->format('d/m/Y H:i')
                ], ';');
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Bulk delete permissions
     */
    public function bulkDestroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:permissions,id'
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
            
            $permissionIds = $request->ids;
            $deletedCount = 0;
            $skippedCount = 0;
            
            foreach ($permissionIds as $id) {
                $permission = Permission::find($id);
                
                // Skip if permission is used by roles
                if ($permission && $permission->roles()->count() === 0) {
                    $permission->delete();
                    $deletedCount++;
                } else {
                    $skippedCount++;
                }
            }
            
            DB::commit();
            
            $message = $deletedCount . ' permission(s) supprimée(s) avec succès.';
            if ($skippedCount > 0) {
                $message .= ' ' . $skippedCount . ' permission(s) non supprimée(s) car utilisée(s) par des rôles.';
            }
            
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression groupée',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sync permissions for multiple roles
     */
    public function syncForRoles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_ids' => 'required|array',
            'role_ids.*' => 'exists:roles,id',
            'permission_ids' => 'required|array',
            'permission_ids.*' => 'exists:permissions,id',
            'action' => 'required|in:add,remove,sync'
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
            
            $roles = Role::whereIn('id', $request->role_ids)->get();
            $permissions = Permission::whereIn('id', $request->permission_ids)->get();
            
            foreach ($roles as $role) {
                // Skip system roles
                if (in_array($role->name, ['super-admin', 'admin'])) {
                    continue;
                }
                
                switch ($request->action) {
                    case 'add':
                        $role->givePermissionTo($permissions);
                        break;
                    case 'remove':
                        $role->revokePermissionTo($permissions);
                        break;
                    case 'sync':
                        $role->syncPermissions($permissions);
                        break;
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Permissions synchronisées avec succès !'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la synchronisation',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}