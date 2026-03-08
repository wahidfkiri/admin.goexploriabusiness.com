<?php
// database/seeders/RolePermissionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ==================== DÉFINITION DES PERMISSIONS ====================
        
        $permissions = [
            // ===== PERMISSIONS EXISTANTES (Anciennes) =====
            
            // Permissions Utilisateurs
            ['name' => 'view users', 'group' => 'Users', 'guard_name' => 'web'],
            ['name' => 'create users', 'group' => 'Users', 'guard_name' => 'web'],
            ['name' => 'edit users', 'group' => 'Users', 'guard_name' => 'web'],
            ['name' => 'delete users', 'group' => 'Users', 'guard_name' => 'web'],
            ['name' => 'manage user roles', 'group' => 'Users', 'guard_name' => 'web'],
            
            // Permissions Rôles
            ['name' => 'view roles', 'group' => 'Roles', 'guard_name' => 'web'],
            ['name' => 'create roles', 'group' => 'Roles', 'guard_name' => 'web'],
            ['name' => 'edit roles', 'group' => 'Roles', 'guard_name' => 'web'],
            ['name' => 'delete roles', 'group' => 'Roles', 'guard_name' => 'web'],
            
            // Permissions Permissions
            ['name' => 'view permissions', 'group' => 'Permissions', 'guard_name' => 'web'],
            ['name' => 'create permissions', 'group' => 'Permissions', 'guard_name' => 'web'],
            ['name' => 'delete permissions', 'group' => 'Permissions', 'guard_name' => 'web'],
            
            // Permissions Dashboard
            ['name' => 'view dashboard', 'group' => 'Dashboard', 'guard_name' => 'web'],
            ['name' => 'view statistics', 'group' => 'Dashboard', 'guard_name' => 'web'],
            ['name' => 'export data', 'group' => 'Dashboard', 'guard_name' => 'web'],
            
            // Permissions Paramètres
            ['name' => 'manage settings', 'group' => 'Settings', 'guard_name' => 'web'],
            ['name' => 'view logs', 'group' => 'Settings', 'guard_name' => 'web'],
            
            // ===== NOUVELLES PERMISSIONS =====
            
            // Permissions Templates
            ['name' => 'view templates', 'group' => 'Templates', 'guard_name' => 'web'],
            ['name' => 'create templates', 'group' => 'Templates', 'guard_name' => 'web'],
            ['name' => 'edit templates', 'group' => 'Templates', 'guard_name' => 'web'],
            ['name' => 'delete templates', 'group' => 'Templates', 'guard_name' => 'web'],
            ['name' => 'duplicate templates', 'group' => 'Templates', 'guard_name' => 'web'],
            ['name' => 'export templates', 'group' => 'Templates', 'guard_name' => 'web'],
            ['name' => 'import templates', 'group' => 'Templates', 'guard_name' => 'web'],
            ['name' => 'assign templates', 'group' => 'Templates', 'guard_name' => 'web'],
            
            // Permissions Établissements
            ['name' => 'view etablissements', 'group' => 'Etablissements', 'guard_name' => 'web'],
            ['name' => 'create etablissements', 'group' => 'Etablissements', 'guard_name' => 'web'],
            ['name' => 'edit etablissements', 'group' => 'Etablissements', 'guard_name' => 'web'],
            ['name' => 'delete etablissements', 'group' => 'Etablissements', 'guard_name' => 'web'],
            ['name' => 'manage etablissement users', 'group' => 'Etablissements', 'guard_name' => 'web'],
            ['name' => 'view etablissement stats', 'group' => 'Etablissements', 'guard_name' => 'web'],
            ['name' => 'export etablissements', 'group' => 'Etablissements', 'guard_name' => 'web'],
            ['name' => 'import etablissements', 'group' => 'Etablissements', 'guard_name' => 'web'],
            
            // Permissions Destinations
            ['name' => 'view destinations', 'group' => 'Destinations', 'guard_name' => 'web'],
            ['name' => 'create destinations', 'group' => 'Destinations', 'guard_name' => 'web'],
            ['name' => 'edit destinations', 'group' => 'Destinations', 'guard_name' => 'web'],
            ['name' => 'delete destinations', 'group' => 'Destinations', 'guard_name' => 'web'],
            ['name' => 'manage destination routes', 'group' => 'Destinations', 'guard_name' => 'web'],
            ['name' => 'view destination stats', 'group' => 'Destinations', 'guard_name' => 'web'],
            ['name' => 'export destinations', 'group' => 'Destinations', 'guard_name' => 'web'],
            ['name' => 'import destinations', 'group' => 'Destinations', 'guard_name' => 'web'],
            
            // Permissions Commandes Artisan
            ['name' => 'view artisan commands', 'group' => 'Artisan', 'guard_name' => 'web'],
            ['name' => 'run artisan commands', 'group' => 'Artisan', 'guard_name' => 'web'],
            ['name' => 'schedule artisan commands', 'group' => 'Artisan', 'guard_name' => 'web'],
            ['name' => 'view command history', 'group' => 'Artisan', 'guard_name' => 'web'],
            ['name' => 'clear cache', 'group' => 'Artisan', 'guard_name' => 'web'],
            ['name' => 'run migrations', 'group' => 'Artisan', 'guard_name' => 'web'],
            ['name' => 'run seeders', 'group' => 'Artisan', 'guard_name' => 'web'],
            ['name' => 'optimize application', 'group' => 'Artisan', 'guard_name' => 'web'],
            ['name' => 'view logs', 'group' => 'Artisan', 'guard_name' => 'web'],
            ['name' => 'clear logs', 'group' => 'Artisan', 'guard_name' => 'web'],
            
            // Permissions API
            ['name' => 'view api keys', 'group' => 'API', 'guard_name' => 'web'],
            ['name' => 'create api keys', 'group' => 'API', 'guard_name' => 'web'],
            ['name' => 'revoke api keys', 'group' => 'API', 'guard_name' => 'web'],
            ['name' => 'view api logs', 'group' => 'API', 'guard_name' => 'web'],
            
            // Permissions Rapports
            ['name' => 'view reports', 'group' => 'Reports', 'guard_name' => 'web'],
            ['name' => 'create reports', 'group' => 'Reports', 'guard_name' => 'web'],
            ['name' => 'export reports', 'group' => 'Reports', 'guard_name' => 'web'],
            ['name' => 'schedule reports', 'group' => 'Reports', 'guard_name' => 'web'],
            ['name' => 'view analytics', 'group' => 'Reports', 'guard_name' => 'web'],
        ];

        // ==================== CRÉATION DES PERMISSIONS ====================
        
        echo "Création des permissions...\n";
        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name'], 'guard_name' => $permission['guard_name']],
                ['group' => $permission['group']]
            );
        }
        echo "✓ " . count($permissions) . " permissions créées/mises à jour\n";

        // ==================== CRÉATION DES RÔLES ====================
        
        echo "\nCréation des rôles...\n";

        // Rôle Super Admin (toutes les permissions)
        $superAdmin = Role::updateOrCreate(
            ['name' => 'super-admin', 'guard_name' => 'web'],
            ['description' => 'Super administrateur - Accès complet à toutes les fonctionnalités']
        );
        $superAdmin->syncPermissions(Permission::all());
        echo "✓ Rôle 'super-admin' créé avec " . $superAdmin->permissions()->count() . " permissions\n";

        // Rôle Admin (presque toutes les permissions sauf quelques-unes sensibles)
        $admin = Role::updateOrCreate(
            ['name' => 'admin', 'guard_name' => 'web'],
            ['description' => 'Administrateur - Gestion complète sauf opérations système critiques']
        );
        $adminPermissions = Permission::whereNotIn('name', [
            'delete roles',
            'delete permissions',
            'run migrations',
            'run seeders',
            'view artisan commands',
            'run artisan commands',
            'schedule artisan commands',
            'view command history'
        ])->pluck('id');
        $admin->syncPermissions($adminPermissions);
        echo "✓ Rôle 'admin' créé avec " . $admin->permissions()->count() . " permissions\n";

        // Rôle Manager (gestion opérationnelle)
        $manager = Role::updateOrCreate(
            ['name' => 'manager', 'guard_name' => 'web'],
            ['description' => 'Manager - Gestion des opérations courantes']
        );
        $managerPermissions = Permission::whereIn('name', [
            // Dashboard
            'view dashboard', 'view statistics', 'export data',
            
            // Users (lecture seulement)
            'view users',
            
            // Templates
            'view templates', 'create templates', 'edit templates', 'duplicate templates',
            
            // Établissements
            'view etablissements', 'create etablissements', 'edit etablissements',
            'view etablissement stats',
            
            // Destinations
            'view destinations', 'create destinations', 'edit destinations',
            'view destination stats',
            
            // Rapports
            'view reports', 'create reports', 'export reports',
            
            // API (lecture seulement)
            'view api keys', 'view api logs',
        ])->pluck('id');
        $manager->syncPermissions($managerPermissions);
        echo "✓ Rôle 'manager' créé avec " . $manager->permissions()->count() . " permissions\n";

        // Rôle Utilisateur Standard
        $user = Role::updateOrCreate(
            ['name' => 'user', 'guard_name' => 'web'],
            ['description' => 'Utilisateur standard - Accès limité']
        );
        $userPermissions = Permission::whereIn('name', [
            'view dashboard',
            'view templates',
            'view etablissements',
            'view destinations',
        ])->pluck('id');
        $user->syncPermissions($userPermissions);
        echo "✓ Rôle 'user' créé avec " . $user->permissions()->count() . " permissions\n";

        // Rôle Client
        $client = Role::updateOrCreate(
            ['name' => 'client', 'guard_name' => 'web'],
            ['description' => 'Client - Accès limité à ses propres données']
        );
        $clientPermissions = Permission::whereIn('name', [
            'view dashboard',
            'view templates',
            'view etablissements',
        ])->pluck('id');
        $client->syncPermissions($clientPermissions);
        echo "✓ Rôle 'client' créé avec " . $client->permissions()->count() . " permissions\n";

        // Rôle Invité
        $guest = Role::updateOrCreate(
            ['name' => 'guest', 'guard_name' => 'web'],
            ['description' => 'Invité - Accès minimal']
        );
        $guestPermissions = Permission::whereIn('name', [
            'view dashboard',
        ])->pluck('id');
        $guest->syncPermissions($guestPermissions);
        echo "✓ Rôle 'guest' créé avec " . $guest->permissions()->count() . " permissions\n";

        // ==================== CRÉATION DES UTILISATEURS DE TEST ====================
        
        echo "\nCréation des utilisateurs de test...\n";

        // Super Admin
        $superAdminUser = User::updateOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $superAdminUser->assignRole($superAdmin);
        echo "✓ Utilisateur 'superadmin@example.com' créé (mot de passe: password)\n";

        // Admin
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $adminUser->assignRole($admin);
        echo "✓ Utilisateur 'admin@example.com' créé\n";

        // Manager
        $managerUser = User::updateOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Manager User',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $managerUser->assignRole($manager);
        echo "✓ Utilisateur 'manager@example.com' créé\n";

        // User Standard
        $standardUser = User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Standard User',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $standardUser->assignRole($user);
        echo "✓ Utilisateur 'user@example.com' créé\n";

        // Client
        $clientUser = User::updateOrCreate(
            ['email' => 'client@example.com'],
            [
                'name' => 'Client User',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $clientUser->assignRole($client);
        echo "✓ Utilisateur 'client@example.com' créé\n";

        // ==================== AFFICHAGE DU RÉCAPITULATIF ====================
        
        echo "\n" . str_repeat('=', 50) . "\n";
        echo "RÉCAPITULATIF FINAL\n";
        echo str_repeat('=', 50) . "\n\n";
        
        echo "Rôles créés:\n";
        foreach (Role::all() as $role) {
            echo "  • {$role->name}: {$role->permissions()->count()} permissions\n";
        }
        
        echo "\nPermissions par groupe:\n";
        $permissionsByGroup = Permission::all()->groupBy('group');
        foreach ($permissionsByGroup as $group => $perms) {
            echo "  • {$group}: " . count($perms) . " permissions\n";
        }
        
        echo "\nUtilisateurs créés:\n";
        foreach (User::all() as $user) {
            echo "  • {$user->name} ({$user->email}): " . $user->getRoleNames()->implode(', ') . "\n";
        }
        
        echo "\n" . str_repeat('=', 50) . "\n";
        echo "✅ Seeder exécuté avec succès !\n";
        echo str_repeat('=', 50) . "\n";
    }
}