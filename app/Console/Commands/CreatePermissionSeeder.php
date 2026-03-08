<?php
// app/Console/Commands/CreatePermissionSeeder.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreatePermissionSeeder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:permission-seeder
                            {name=RolePermissionSeeder : Nom du seeder}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Créer un seeder pour les permissions et rôles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $seederPath = database_path("seeders/{$name}.php");

        $stub = $this->getStub();

        if (File::exists($seederPath)) {
            if (!$this->confirm("Le fichier {$seederPath} existe déjà. Voulez-vous l'écraser ?")) {
                $this->info('Opération annulée.');
                return;
            }
        }

        File::put($seederPath, $stub);
        $this->info("✅ Seeder créé avec succès : {$seederPath}");
    }

    /**
     * Get the stub content
     */
    protected function getStub()
    {
        return <<<'PHP'
<?php

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
            // Permissions Utilisateurs
            ['name' => 'view users', 'group' => 'Users', 'guard_name' => 'web'],
            ['name' => 'create users', 'group' => 'Users', 'guard_name' => 'web'],
            ['name' => 'edit users', 'group' => 'Users', 'guard_name' => 'web'],
            ['name' => 'delete users', 'group' => 'Users', 'guard_name' => 'web'],
            
            // Permissions Templates
            ['name' => 'view templates', 'group' => 'Templates', 'guard_name' => 'web'],
            ['name' => 'create templates', 'group' => 'Templates', 'guard_name' => 'web'],
            ['name' => 'edit templates', 'group' => 'Templates', 'guard_name' => 'web'],
            ['name' => 'delete templates', 'group' => 'Templates', 'guard_name' => 'web'],
            
            // Permissions Établissements
            ['name' => 'view etablissements', 'group' => 'Etablissements', 'guard_name' => 'web'],
            ['name' => 'create etablissements', 'group' => 'Etablissements', 'guard_name' => 'web'],
            ['name' => 'edit etablissements', 'group' => 'Etablissements', 'guard_name' => 'web'],
            ['name' => 'delete etablissements', 'group' => 'Etablissements', 'guard_name' => 'web'],
            
            // Permissions Destinations
            ['name' => 'view destinations', 'group' => 'Destinations', 'guard_name' => 'web'],
            ['name' => 'create destinations', 'group' => 'Destinations', 'guard_name' => 'web'],
            ['name' => 'edit destinations', 'group' => 'Destinations', 'guard_name' => 'web'],
            ['name' => 'delete destinations', 'group' => 'Destinations', 'guard_name' => 'web'],
            
            // Permissions Dashboard
            ['name' => 'view dashboard', 'group' => 'Dashboard', 'guard_name' => 'web'],
            ['name' => 'view statistics', 'group' => 'Dashboard', 'guard_name' => 'web'],
        ];

        // ==================== CRÉATION DES PERMISSIONS ====================
        
        $this->command->info('Création des permissions...');
        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name'], 'guard_name' => $permission['guard_name']],
                ['group' => $permission['group']]
            );
        }

        // ==================== CRÉATION DES RÔLES ====================
        
        $this->command->info('Création des rôles...');

        // Rôle Super Admin
        $superAdmin = Role::updateOrCreate(
            ['name' => 'super-admin', 'guard_name' => 'web']
        );
        $superAdmin->syncPermissions(Permission::all());

        // Rôle Admin
        $admin = Role::updateOrCreate(
            ['name' => 'admin', 'guard_name' => 'web']
        );
        $admin->syncPermissions(Permission::all());

        // Rôle User
        $user = Role::updateOrCreate(
            ['name' => 'user', 'guard_name' => 'web']
        );
        $user->syncPermissions([
            'view dashboard',
            'view templates',
            'view etablissements',
            'view destinations',
        ]);

        // Rôle Client
        $client = Role::updateOrCreate(
            ['name' => 'client', 'guard_name' => 'web']
        );
        $client->syncPermissions([
            'view dashboard',
            'view etablissements',
        ]);

        // ==================== CRÉATION DES UTILISATEURS ====================
        
        $this->command->info('Création des utilisateurs de test...');

        // Super Admin
        $superAdminUser = User::updateOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        $superAdminUser->assignRole($superAdmin);

        // Admin
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        $adminUser->assignRole($admin);

        // User
        $standardUser = User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Standard User',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        $standardUser->assignRole($user);

        // Client
        $clientUser = User::updateOrCreate(
            ['email' => 'client@example.com'],
            [
                'name' => 'Client User',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        $clientUser->assignRole($client);

        $this->command->info('✅ Seeder exécuté avec succès !');
    }
}
PHP;
    }
}