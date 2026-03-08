<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductCatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Début du seeding du catalogue produits...');
        
        $this->command->newLine();
        $this->command->info('📦 Familles de produits');
        $this->command->line('═══════════════════════');
        $this->call(ProductFamilySeeder::class);
        
        $this->command->newLine();
        $this->command->info('📂 Catégories de produits');
        $this->command->line('════════════════════════');
        $this->call(ProductCategorySeeder::class);
        
        $this->command->newLine();
        $this->command->info('✅ Seeding du catalogue produits terminé!');
        
        // Afficher les statistiques finales
        $this->command->newLine();
        $this->command->table(
            ['Type', 'Nombre'],
            [
                ['Familles de produits', \App\Models\ProductFamily::count()],
                ['Catégories principales', \App\Models\ProductCategory::whereNull('parent_id')->count()],
                ['Sous-catégories', \App\Models\ProductCategory::whereNotNull('parent_id')->count()],
                ['Total catégories', \App\Models\ProductCategory::count()],
            ]
        );
    }
}