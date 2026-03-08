<?php
// app/Console/Commands/ManagePermissionGroups.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ManagePermissionGroups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:groups
                            {action? : Action à effectuer (list, assign, sync, reset)}
                            {--group= : Nom du groupe}
                            {--permission= : Nom de la permission}
                            {--role= : Nom du rôle}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gérer les groupes de permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action') ?? 'list';

        switch ($action) {
            case 'list':
                $this->listGroups();
                break;
            case 'assign':
                $this->assignToGroup();
                break;
            case 'sync':
                $this->syncPermissions();
                break;
            case 'reset':
                $this->resetGroups();
                break;
            default:
                $this->error("Action non reconnue: {$action}");
                $this->info("Actions disponibles: list, assign, sync, reset");
        }
    }

    /**
     * Lister tous les groupes et leurs permissions
     */
    protected function listGroups()
    {
        $this->info('Liste des groupes de permissions:');
        $this->newLine();

        // Récupérer toutes les permissions groupées
        $permissions = Permission::all()->groupBy(function($permission) {
            return $permission->group ?? 'Non groupé';
        });

        foreach ($permissions as $group => $groupPermissions) {
            $this->line("📁 <fg=yellow>{$group}</> (" . count($groupPermissions) . " permissions)");
            
            foreach ($groupPermissions as $permission) {
                $this->line("   ├─ <fg=green>{$permission->name}</>");
            }
            $this->newLine();
        }

        // Statistiques
        $this->table(
            ['Statistique', 'Valeur'],
            [
                ['Total permissions', Permission::count()],
                ['Permissions groupées', Permission::whereNotNull('group')->count()],
                ['Permissions non groupées', Permission::whereNull('group')->count()],
                ['Nombre de groupes', $permissions->count()]
            ]
        );
    }

    /**
     * Assigner une permission à un groupe
     */
    protected function assignToGroup()
    {
        $permissionName = $this->option('permission');
        $groupName = $this->option('group');

        if (!$permissionName || !$groupName) {
            $this->error('Vous devez spécifier --permission et --group');
            $this->line('Exemple: php artisan permission:groups assign --permission="view users" --group="Users"');
            return;
        }

        $permission = Permission::where('name', $permissionName)->first();

        if (!$permission) {
            $this->error("Permission '{$permissionName}' non trouvée");
            return;
        }

        $oldGroup = $permission->group ?? 'aucun';
        $permission->group = $groupName;
        $permission->save();

        $this->info("✅ Permission '{$permissionName}' assignée au groupe '{$groupName}' (était: {$oldGroup})");
    }

    /**
     * Synchroniser les permissions par groupe
     */
    protected function syncPermissions()
    {
        $this->info('Synchronisation des permissions par groupe...');
        $this->newLine();

        // Définition des groupes par défaut
        $defaultGroups = [
            'Users' => [
                'view users',
                'create users',
                'edit users',
                'delete users',
                'manage user roles',
            ],
            'Roles' => [
                'view roles',
                'create roles',
                'edit roles',
                'delete roles',
            ],
            'Permissions' => [
                'view permissions',
                'create permissions',
                'delete permissions',
            ],
            'Dashboard' => [
                'view dashboard',
                'view statistics',
                'export data',
            ],
            'Settings' => [
                'manage settings',
                'view logs',
            ],
            'Templates' => [
                'view templates',
                'create templates',
                'edit templates',
                'delete templates',
                'duplicate templates',
                'export templates',
                'import templates',
                'assign templates',
            ],
            'Etablissements' => [
                'view etablissements',
                'create etablissements',
                'edit etablissements',
                'delete etablissements',
                'manage etablissement users',
                'view etablissement stats',
                'export etablissements',
                'import etablissements',
            ],
            'Destinations' => [
                'view destinations',
                'create destinations',
                'edit destinations',
                'delete destinations',
                'manage destination routes',
                'view destination stats',
                'export destinations',
                'import destinations',
            ],
            'Artisan' => [
                'view artisan commands',
                'run artisan commands',
                'schedule artisan commands',
                'view command history',
                'clear cache',
                'run migrations',
                'run seeders',
                'optimize application',
                'view logs',
                'clear logs',
            ],
            'API' => [
                'view api keys',
                'create api keys',
                'revoke api keys',
                'view api logs',
            ],
            'Reports' => [
                'view reports',
                'create reports',
                'export reports',
                'schedule reports',
                'view analytics',
            ],
        ];

        $updated = 0;
        $notFound = 0;

        foreach ($defaultGroups as $group => $permissions) {
            foreach ($permissions as $permissionName) {
                $permission = Permission::where('name', $permissionName)->first();
                
                if ($permission) {
                    $permission->group = $group;
                    $permission->save();
                    $updated++;
                } else {
                    $notFound++;
                }
            }
        }

        $this->info("✅ Synchronisation terminée !");
        $this->table(
            ['Résultat', 'Nombre'],
            [
                ['Permissions mises à jour', $updated],
                ['Permissions non trouvées', $notFound],
            ]
        );
    }

    /**
     * Réinitialiser tous les groupes
     */
    protected function resetGroups()
    {
        if (!$this->confirm('Êtes-vous sûr de vouloir réinitialiser tous les groupes ?')) {
            $this->info('Opération annulée.');
            return;
        }

        $count = Permission::whereNotNull('group')->count();
        Permission::whereNotNull('group')->update(['group' => null]);

        $this->info("✅ {$count} permissions ont été retirées de leurs groupes");
    }
}