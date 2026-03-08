<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductFamily;
use Illuminate\Support\Str;

class ProductFamilySeeder extends Seeder
{
    /**
     * Familles de produits par défaut
     */
    protected $families = [
        [
            'name' => 'Développement Web',
            'code' => 'DEV-WEB',
            'description' => 'Services de développement de sites web et applications',
            'order' => 10,
            'is_active' => true,
        ],
        [
            'name' => 'Design & Création',
            'code' => 'DESIGN',
            'description' => 'Services de design graphique, UI/UX et création',
            'order' => 20,
            'is_active' => true,
        ],
        [
            'name' => 'Marketing Digital',
            'code' => 'MARKETING',
            'description' => 'Services de marketing en ligne, SEO, publicité',
            'order' => 30,
            'is_active' => true,
        ],
        [
            'name' => 'Hébergement & Infrastructure',
            'code' => 'HOSTING',
            'description' => 'Services d\'hébergement, serveurs et infrastructure',
            'order' => 40,
            'is_active' => true,
        ],
        [
            'name' => 'Maintenance & Support',
            'code' => 'SUPPORT',
            'description' => 'Services de maintenance, support technique et SAV',
            'order' => 50,
            'is_active' => true,
        ],
        [
            'name' => 'Formation & Consulting',
            'code' => 'FORMATION',
            'description' => 'Services de formation, conseil et expertise',
            'order' => 60,
            'is_active' => true,
        ],
        [
            'name' => 'Licences & Logiciels',
            'code' => 'LICENCES',
            'description' => 'Vente de licences logicielles et produits numériques',
            'order' => 70,
            'is_active' => true,
        ],
        [
            'name' => 'Produits Physiques',
            'code' => 'PHYSICAL',
            'description' => 'Produits physiques, matériel et équipement',
            'order' => 80,
            'is_active' => true,
        ],
        [
            'name' => 'Services Bureautiques',
            'code' => 'BUREAU',
            'description' => 'Services administratifs et bureautiques',
            'order' => 90,
            'is_active' => true,
        ],
        [
            'name' => 'Abonnements & Forfaits',
            'code' => 'SUBSCRIPTION',
            'description' => 'Forfaits récurrents et abonnements',
            'order' => 100,
            'is_active' => true,
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Début du seeding des familles de produits...');
        
        foreach ($this->families as $index => $familyData) {
            // Vérifier si la famille existe déjà
            $existing = ProductFamily::where('code', $familyData['code'])->first();
            
            if (!$existing) {
                // Créer la famille
                $family = ProductFamily::create([
                    'name' => $familyData['name'],
                    'code' => $familyData['code'],
                    'description' => $familyData['description'],
                    'order' => $familyData['order'],
                    'is_active' => $familyData['is_active'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $this->command->info("✓ Famille créée: {$family->name}");
            } else {
                $this->command->warn("⚠ Famille déjà existante: {$familyData['name']}");
            }
        }
        
        // Ajouter quelques familles personnalisées pour les tests
        if (app()->environment('local', 'testing')) {
            $this->seedTestFamilies();
        }
        
        $this->command->info('✓ Seeding des familles de produits terminé!');
        
        // Afficher le résumé
        $total = ProductFamily::count();
        $active = ProductFamily::where('is_active', true)->count();
        $this->command->table(
            ['Total', 'Actives', 'Inactives'],
            [[$total, $active, $total - $active]]
        );
    }

    /**
     * Seed des familles supplémentaires pour l'environnement de test
     */
    private function seedTestFamilies(): void
    {
        $testFamilies = [
            [
                'name' => 'E-commerce',
                'code' => 'ECOMM',
                'description' => 'Solutions e-commerce et boutiques en ligne',
                'order' => 110,
                'is_active' => true,
            ],
            [
                'name' => 'Mobile Apps',
                'code' => 'MOBILE',
                'description' => 'Développement d\'applications mobiles',
                'order' => 115,
                'is_active' => true,
            ],
            [
                'name' => 'Réseaux Sociaux',
                'code' => 'SOCIAL',
                'description' => 'Gestion des réseaux sociaux et community management',
                'order' => 120,
                'is_active' => true,
            ],
        ];

        foreach ($testFamilies as $familyData) {
            if (!ProductFamily::where('code', $familyData['code'])->exists()) {
                ProductFamily::create($familyData);
                $this->command->info("  ✓ Famille de test créée: {$familyData['name']}");
            }
        }
    }
}