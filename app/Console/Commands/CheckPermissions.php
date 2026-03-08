<?php
// app/Console/Commands/CheckPermissions.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class CheckPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:check
                            {--user= : Vérifier les permissions d\'un utilisateur}
                            {--role= : Vérifier les permissions d\'un rôle}
                            {--missing : Lister les permissions manquantes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifier les permissions et rôles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('user')) {
            $this->checkUserPermissions($this->option('user'));
        } elseif ($this->option('role')) {
            $this->checkRolePermissions($this->option('role'));
        } elseif ($this->option('missing')) {
            $this->checkMissingPermissions();
        } else {
            $this->showSummary();
        }
    }

    /**
     * Vérifier les permissions d'un utilisateur
     */
    protected function checkUserPermissions($userEmail)
    {
        $user = User::where('email', $userEmail)->first();

        if (!$user) {
            $this->error("Utilisateur non trouvé: {$userEmail}");
            return;
        }

        $this->info("Permissions de l'utilisateur: {$user->name} ({$user->email})");
        $this->newLine();

        $this->line("Rôles: " . $user->getRoleNames()->implode(', '));
        $this->newLine();

        $permissions = $user->getAllPermissions()->groupBy(function($perm) {
            return $perm->group ?? 'Non groupé';
        });

        foreach ($permissions as $group => $groupPermissions) {
            $this->line("📁 {$group}:");
            foreach ($groupPermissions as $perm) {
                $this->line("   ├─ {$perm->name}");
            }
        }

        $this->newLine();
        $this->table(
            ['Total', 'Directes', 'Via rôles'],
            [
                [
                    $user->getAllPermissions()->count(),
                    $user->getDirectPermissions()->count(),
                    $user->getPermissionsViaRoles()->count()
                ]
            ]
        );
    }

    /**
     * Vérifier les permissions d'un rôle
     */
    protected function checkRolePermissions($roleName)
    {
        $role = Role::where('name', $roleName)->first();

        if (!$role) {
            $this->error("Rôle non trouvé: {$roleName}");
            return;
        }

        $this->info("Permissions du rôle: {$role->name}");
        $this->newLine();

        $permissions = $role->permissions()->get()->groupBy(function($perm) {
            return $perm->group ?? 'Non groupé';
        });

        foreach ($permissions as $group => $groupPermissions) {
            $this->line("📁 {$group}:");
            foreach ($groupPermissions as $perm) {
                $this->line("   ├─ {$perm->name}");
            }
        }

        $this->newLine();
        $this->table(
            ['Total permissions', 'Utilisateurs avec ce rôle'],
            [
                [
                    $role->permissions()->count(),
                    $role->users()->count()
                ]
            ]
        );
    }

    /**
     * Vérifier les permissions manquantes
     */
    protected function checkMissingPermissions()
    {
        $requiredPermissions = [
            'view users', 'create users', 'edit users', 'delete users',
            'view templates', 'create templates', 'edit templates', 'delete templates',
            'view etablissements', 'create etablissements', 'edit etablissements', 'delete etablissements',
            'view destinations', 'create destinations', 'edit destinations', 'delete destinations',
            'view dashboard', 'view statistics',
        ];

        $missing = [];
        foreach ($requiredPermissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                $missing[] = $permission;
            }
        }

        if (empty($missing)) {
            $this->info('✅ Toutes les permissions requises sont présentes !');
        } else {
            $this->warn('Permissions manquantes:');
            foreach ($missing as $perm) {
                $this->line("   • {$perm}");
            }
        }
    }

    /**
     * Afficher un résumé général
     */
    protected function showSummary()
    {
        $this->info('RÉSUMÉ DES PERMISSIONS');
        $this->newLine();

        // Statistiques générales
        $this->table(
            ['Type', 'Total'],
            [
                ['Permissions', Permission::count()],
                ['Rôles', Role::count()],
                ['Utilisateurs', User::count()],
            ]
        );

        $this->newLine();

        // Permissions par groupe
        $permissionsByGroup = Permission::all()->groupBy('group');
        $groupData = [];
        foreach ($permissionsByGroup as $group => $perms) {
            $groupData[] = [$group ?? 'Non groupé', count($perms)];
        }
        
        $this->info('Permissions par groupe:');
        $this->table(['Groupe', 'Nombre'], $groupData);

        $this->newLine();

        // Rôles et leurs permissions
        $roleData = [];
        foreach (Role::all() as $role) {
            $roleData[] = [$role->name, $role->permissions()->count()];
        }
        
        $this->info('Rôles:');
        $this->table(['Rôle', 'Permissions'], $roleData);
    }
}