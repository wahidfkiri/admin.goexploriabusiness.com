<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductFamily;
use App\Models\ProductCategory;
use Illuminate\Support\Str;

class ProductCategorySeeder extends Seeder
{
    /**
     * Structure des catégories par famille
     */
    protected $categoriesByFamily = [
        'DEV-WEB' => [
            [
                'name' => 'Sites Vitrines',
                'description' => 'Sites web présentant une entreprise ou ses activités',
                'order' => 10,
                'subcategories' => [
                    ['name' => 'Site vitrine simple', 'order' => 10],
                    ['name' => 'Site vitrine avec blog', 'order' => 20],
                    ['name' => 'Site vitrine multilingue', 'order' => 30],
                ]
            ],
            [
                'name' => 'Sites E-commerce',
                'description' => 'Boutiques en ligne avec catalogue et paiement',
                'order' => 20,
                'subcategories' => [
                    ['name' => 'Petite boutique (<50 produits)', 'order' => 10],
                    ['name' => 'Moyenne boutique (50-500 produits)', 'order' => 20],
                    ['name' => 'Grande boutique (>500 produits)', 'order' => 30],
                    ['name' => 'Marketplace', 'order' => 40],
                ]
            ],
            [
                'name' => 'Applications Web',
                'description' => 'Applications métier sur mesure',
                'order' => 30,
                'subcategories' => [
                    ['name' => 'CRM sur mesure', 'order' => 10],
                    ['name' => 'ERP personnalisé', 'order' => 20],
                    ['name' => 'Portail client', 'order' => 30],
                    ['name' => 'Intranet', 'order' => 40],
                ]
            ],
            [
                'name' => 'API & Backend',
                'description' => 'Développement d\'APIs et services backend',
                'order' => 40,
                'subcategories' => [
                    ['name' => 'API REST', 'order' => 10],
                    ['name' => 'API GraphQL', 'order' => 20],
                    ['name' => 'Microservices', 'order' => 30],
                ]
            ],
        ],
        
        'DESIGN' => [
            [
                'name' => 'Design Graphique',
                'description' => 'Création d\'identités visuelles et supports print',
                'order' => 10,
                'subcategories' => [
                    ['name' => 'Logo & Identité visuelle', 'order' => 10],
                    ['name' => 'Charte graphique', 'order' => 20],
                    ['name' => 'Brochures & Flyers', 'order' => 30],
                    ['name' => 'Cartes de visite', 'order' => 40],
                ]
            ],
            [
                'name' => 'UI/UX Design',
                'description' => 'Conception d\'interfaces et expérience utilisateur',
                'order' => 20,
                'subcategories' => [
                    ['name' => 'Maquettes UI', 'order' => 10],
                    ['name' => 'Prototypes interactifs', 'order' => 20],
                    ['name' => 'Audit UX', 'order' => 30],
                    ['name' => 'Tests utilisateurs', 'order' => 40],
                ]
            ],
            [
                'name' => 'Motion Design',
                'description' => 'Création de vidéos et animations',
                'order' => 30,
                'subcategories' => [
                    ['name' => 'Vidéos explicatives', 'order' => 10],
                    ['name' => 'Animations logo', 'order' => 20],
                    ['name' => 'Motion pour réseaux sociaux', 'order' => 30],
                ]
            ],
        ],
        
        'MARKETING' => [
            [
                'name' => 'SEO',
                'description' => 'Optimisation pour les moteurs de recherche',
                'order' => 10,
                'subcategories' => [
                    ['name' => 'Audit SEO', 'order' => 10],
                    ['name' => 'Optimisation on-page', 'order' => 20],
                    ['name' => 'Netlinking', 'order' => 30],
                    ['name' => 'SEO local', 'order' => 40],
                ]
            ],
            [
                'name' => 'SEA',
                'description' => 'Publicité sur les moteurs de recherche',
                'order' => 20,
                'subcategories' => [
                    ['name' => 'Campagnes Google Ads', 'order' => 10],
                    ['name' => 'Campagnes Bing Ads', 'order' => 20],
                    ['name' => 'Remarketing', 'order' => 30],
                ]
            ],
            [
                'name' => 'Réseaux Sociaux',
                'description' => 'Gestion des médias sociaux',
                'order' => 30,
                'subcategories' => [
                    ['name' => 'Community management', 'order' => 10],
                    ['name' => 'Publicité Facebook/Instagram', 'order' => 20],
                    ['name' => 'Publicité LinkedIn', 'order' => 30],
                    ['name' => 'Publicité TikTok', 'order' => 40],
                ]
            ],
            [
                'name' => 'Email Marketing',
                'description' => 'Campagnes d\'emails et newsletters',
                'order' => 40,
                'subcategories' => [
                    ['name' => 'Newsletters', 'order' => 10],
                    ['name' => 'Campagnes automation', 'order' => 20],
                    ['name' => 'Emailing transactionnel', 'order' => 30],
                ]
            ],
        ],
        
        'HOSTING' => [
            [
                'name' => 'Hébergement Web',
                'description' => 'Solutions d\'hébergement pour sites web',
                'order' => 10,
                'subcategories' => [
                    ['name' => 'Hébergement mutualisé', 'order' => 10],
                    ['name' => 'VPS', 'order' => 20],
                    ['name' => 'Serveur dédié', 'order' => 30],
                    ['name' => 'Cloud hosting', 'order' => 40],
                ]
            ],
            [
                'name' => 'Domaines',
                'description' => 'Gestion de noms de domaine',
                'order' => 20,
                'subcategories' => [
                    ['name' => 'Enregistrement domaine', 'order' => 10],
                    ['name' => 'Transfert domaine', 'order' => 20],
                    ['name' => 'Protection WHOIS', 'order' => 30],
                ]
            ],
            [
                'name' => 'Emails',
                'description' => 'Services de messagerie',
                'order' => 30,
                'subcategories' => [
                    ['name' => 'Email professionnel', 'order' => 10],
                    ['name' => 'Google Workspace', 'order' => 20],
                    ['name' => 'Microsoft 365', 'order' => 30],
                ]
            ],
        ],
        
        'SUPPORT' => [
            [
                'name' => 'Maintenance Technique',
                'description' => 'Maintenance préventive et corrective',
                'order' => 10,
                'subcategories' => [
                    ['name' => 'Forfait maintenance mensuel', 'order' => 10],
                    ['name' => 'Maintenance urgente', 'order' => 20],
                    ['name' => 'Mises à jour sécurité', 'order' => 30],
                ]
            ],
            [
                'name' => 'Support Client',
                'description' => 'Assistance aux utilisateurs',
                'order' => 20,
                'subcategories' => [
                    ['name' => 'Support technique', 'order' => 10],
                    ['name' => 'Hotline', 'order' => 20],
                    ['name' => 'Assistance à distance', 'order' => 30],
                ]
            ],
            [
                'name' => 'Sauvegarde',
                'description' => 'Solutions de backup',
                'order' => 30,
                'subcategories' => [
                    ['name' => 'Sauvegarde quotidienne', 'order' => 10],
                    ['name' => 'Sauvegarde externalisée', 'order' => 20],
                    ['name' => 'Plan de reprise', 'order' => 30],
                ]
            ],
        ],
        
        'FORMATION' => [
            [
                'name' => 'Formations Techniques',
                'description' => 'Formations aux technologies et outils',
                'order' => 10,
                'subcategories' => [
                    ['name' => 'Formation PHP/Laravel', 'order' => 10],
                    ['name' => 'Formation JavaScript/Vue.js', 'order' => 20],
                    ['name' => 'Formation Python', 'order' => 30],
                    ['name' => 'Formation DevOps', 'order' => 40],
                ]
            ],
            [
                'name' => 'Formations Marketing',
                'description' => 'Formations aux stratégies marketing',
                'order' => 20,
                'subcategories' => [
                    ['name' => 'Formation SEO', 'order' => 10],
                    ['name' => 'Formation Google Ads', 'order' => 20],
                    ['name' => 'Formation réseaux sociaux', 'order' => 30],
                ]
            ],
            [
                'name' => 'Consulting',
                'description' => 'Prestations de conseil',
                'order' => 30,
                'subcategories' => [
                    ['name' => 'Audit technique', 'order' => 10],
                    ['name' => 'Stratégie digitale', 'order' => 20],
                    ['name' => 'Accompagnement projet', 'order' => 30],
                ]
            ],
        ],
        
        'LICENCES' => [
            [
                'name' => 'Logiciels',
                'description' => 'Licences logicielles',
                'order' => 10,
                'subcategories' => [
                    ['name' => 'Licence perpétuelle', 'order' => 10],
                    ['name' => 'Licence annuelle', 'order' => 20],
                    ['name' => 'Licence mensuelle', 'order' => 30],
                ]
            ],
            [
                'name' => 'Thèmes & Templates',
                'description' => 'Templates et thèmes pour sites web',
                'order' => 20,
                'subcategories' => [
                    ['name' => 'Thème WordPress', 'order' => 10],
                    ['name' => 'Template HTML/CSS', 'order' => 20],
                    ['name' => 'Template email', 'order' => 30],
                ]
            ],
        ],
        
        'PHYSICAL' => [
            [
                'name' => 'Matériel Informatique',
                'description' => 'Ordinateurs et composants',
                'order' => 10,
                'subcategories' => [
                    ['name' => 'Ordinateurs portables', 'order' => 10],
                    ['name' => 'Ordinateurs fixes', 'order' => 20],
                    ['name' => 'Serveurs', 'order' => 30],
                    ['name' => 'Périphériques', 'order' => 40],
                ]
            ],
            [
                'name' => 'Réseau',
                'description' => 'Équipements réseau',
                'order' => 20,
                'subcategories' => [
                    ['name' => 'Routeurs', 'order' => 10],
                    ['name' => 'Switches', 'order' => 20],
                    ['name' => 'Câbles et connectique', 'order' => 30],
                ]
            ],
        ],
        
        'SUBSCRIPTION' => [
            [
                'name' => 'Forfaits Maintenance',
                'description' => 'Forfaits de maintenance récurrents',
                'order' => 10,
                'subcategories' => [
                    ['name' => 'Forfait Basic', 'order' => 10],
                    ['name' => 'Forfait Standard', 'order' => 20],
                    ['name' => 'Forfait Premium', 'order' => 30],
                ]
            ],
            [
                'name' => 'Forfaits Hébergement',
                'description' => 'Forfaits d\'hébergement mensuels/annuels',
                'order' => 20,
                'subcategories' => [
                    ['name' => 'Hébergement Bronze', 'order' => 10],
                    ['name' => 'Hébergement Silver', 'order' => 20],
                    ['name' => 'Hébergement Gold', 'order' => 30],
                ]
            ],
            [
                'name' => 'Abonnements SaaS',
                'description' => 'Abonnements aux applications en ligne',
                'order' => 30,
                'subcategories' => [
                    ['name' => 'Abonnement mensuel', 'order' => 10],
                    ['name' => 'Abonnement annuel', 'order' => 20],
                ]
            ],
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Début du seeding des catégories de produits...');
        
        $totalCreated = 0;
        $totalSubCreated = 0;
        
        foreach ($this->categoriesByFamily as $familyCode => $categories) {
            // Récupérer la famille
            $family = ProductFamily::where('code', $familyCode)->first();
            
            if (!$family) {
                $this->command->warn("⚠ Famille non trouvée: {$familyCode}");
                continue;
            }
            
            $this->command->info("  Famille: {$family->name}");
            
            foreach ($categories as $categoryData) {
                // Vérifier si la catégorie existe déjà
                $existingCategory = ProductCategory::where('name', $categoryData['name'])
                    ->where('product_family_id', $family->id)
                    ->first();
                
                if (!$existingCategory) {
                    // Créer la catégorie principale
                    $category = ProductCategory::create([
                        'name' => $categoryData['name'],
                        'slug' => Str::slug($categoryData['name']),
                        'description' => $categoryData['description'] ?? null,
                        'product_family_id' => $family->id,
                        'order' => $categoryData['order'],
                        'is_active' => true,
                        'metadata' => json_encode([
                            'level' => 'main',
                            'family_code' => $familyCode
                        ]),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    $this->command->info("    ✓ Catégorie: {$category->name}");
                    $totalCreated++;
                    
                    // Créer les sous-catégories
                    if (isset($categoryData['subcategories'])) {
                        foreach ($categoryData['subcategories'] as $subData) {
                            $existingSub = ProductCategory::where('name', $subData['name'])
                                ->where('parent_id', $category->id)
                                ->first();
                            
                            if (!$existingSub) {
                                ProductCategory::create([
                                    'name' => $subData['name'],
                                    'slug' => Str::slug($subData['name']),
                                    'description' => $subData['description'] ?? null,
                                    'parent_id' => $category->id,
                                    'product_family_id' => $family->id,
                                    'order' => $subData['order'],
                                    'is_active' => true,
                                    'metadata' => json_encode([
                                        'level' => 'sub',
                                        'parent' => $category->name,
                                        'family_code' => $familyCode
                                    ]),
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
                                
                                $totalSubCreated++;
                            }
                        }
                    }
                } else {
                    $this->command->warn("    ⚠ Catégorie déjà existante: {$categoryData['name']}");
                }
            }
        }
        
        $this->command->info('✓ Seeding des catégories de produits terminé!');
        
        // Afficher le résumé
        $total = ProductCategory::count();
        $mainCategories = ProductCategory::whereNull('parent_id')->count();
        $subCategories = ProductCategory::whereNotNull('parent_id')->count();
        
        $this->command->table(
            ['Total', 'Catégories principales', 'Sous-catégories'],
            [[$total, $mainCategories, $subCategories]]
        );
        
        $this->command->info("Nouvelles catégories créées: {$totalCreated}");
        $this->command->info("Nouvelles sous-catégories créées: {$totalSubCreated}");
    }
}