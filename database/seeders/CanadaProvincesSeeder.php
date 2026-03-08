<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\Country;

class CanadaProvincesSeeder extends Seeder
{
    public function run()
    {
        $canada = Country::where('code', 'CAN')->first();
        
        if (!$canada) {
            $this->command->error('Le Canada n\'existe pas dans la base de données!');
            return;
        }

        $allProvincesAndTerritories = [
            // Provinces (10)
            [
                'name' => 'Alberta',
                'code' => 'AB',
                'capital' => 'Edmonton',
                'largest_city' => 'Calgary',
                'official_language' => 'Anglais',
                'area_rank' => '4',
                'population' => 4413146,
                'area' => 661848,
                'timezone' => 'UTC-07:00',
                'flag' => 'ab-flag.svg',
                'description' => 'Province de l\'Ouest canadien, riche en ressources naturelles et pétrole.',
                'latitude' => '53.9333',
                'longitude' => '-116.5765',
                'country_id' => $canada->id
            ],
            [
                'name' => 'Colombie-Britannique',
                'code' => 'BC',
                'capital' => 'Victoria',
                'largest_city' => 'Vancouver',
                'official_language' => 'Anglais',
                'area_rank' => '5',
                'population' => 5145851,
                'area' => 944735,
                'timezone' => 'UTC-08:00',
                'flag' => 'bc-flag.svg',
                'description' => 'Province la plus à l\'ouest du Canada, connue pour ses montagnes et sa côte Pacifique.',
                'latitude' => '53.7267',
                'longitude' => '-127.6476',
                'country_id' => $canada->id
            ],
            [
                'name' => 'Île-du-Prince-Édouard',
                'code' => 'PE',
                'capital' => 'Charlottetown',
                'largest_city' => 'Charlottetown',
                'official_language' => 'Anglais',
                'area_rank' => '13',
                'population' => 164318,
                'area' => 5660,
                'timezone' => 'UTC-04:00',
                'flag' => 'pe-flag.svg',
                'description' => 'Plus petite province du Canada, connue pour ses plages rouges et son agriculture.',
                'latitude' => '46.5107',
                'longitude' => '-63.4168',
                'country_id' => $canada->id
            ],
            [
                'name' => 'Manitoba',
                'code' => 'MB',
                'capital' => 'Winnipeg',
                'largest_city' => 'Winnipeg',
                'official_language' => 'Anglais',
                'area_rank' => '8',
                'population' => 1379584,
                'area' => 647797,
                'timezone' => 'UTC-06:00',
                'flag' => 'mb-flag.svg',
                'description' => 'Province des prairies, connue comme la "Porte de l\'Ouest".',
                'latitude' => '53.7609',
                'longitude' => '-98.8139',
                'country_id' => $canada->id
            ],
            [
                'name' => 'Nouveau-Brunswick',
                'code' => 'NB',
                'capital' => 'Fredericton',
                'largest_city' => 'Moncton',
                'official_language' => 'Anglais, Français',
                'area_rank' => '11',
                'population' => 789225,
                'area' => 72908,
                'timezone' => 'UTC-04:00',
                'flag' => 'nb-flag.svg',
                'description' => 'Seule province officiellement bilingue du Canada.',
                'latitude' => '46.5653',
                'longitude' => '-66.4619',
                'country_id' => $canada->id
            ],
            [
                'name' => 'Nouvelle-Écosse',
                'code' => 'NS',
                'capital' => 'Halifax',
                'largest_city' => 'Halifax',
                'official_language' => 'Anglais',
                'area_rank' => '12',
                'population' => 979351,
                'area' => 55284,
                'timezone' => 'UTC-04:00',
                'flag' => 'ns-flag.svg',
                'description' => 'Province maritime, entourée par l\'océan Atlantique.',
                'latitude' => '44.6819',
                'longitude' => '-63.7443',
                'country_id' => $canada->id
            ],
            [
                'name' => 'Ontario',
                'code' => 'ON',
                'capital' => 'Toronto',
                'largest_city' => 'Toronto',
                'official_language' => 'Anglais',
                'area_rank' => '4',
                'population' => 14734014,
                'area' => 1076395,
                'timezone' => 'UTC-05:00',
                'flag' => 'on-flag.svg',
                'description' => 'Province la plus peuplée du Canada, centre économique du pays.',
                'latitude' => '51.2538',
                'longitude' => '-85.3232',
                'country_id' => $canada->id
            ],
            [
                'name' => 'Québec',
                'code' => 'QC',
                'capital' => 'Québec',
                'largest_city' => 'Montréal',
                'official_language' => 'Français',
                'area_rank' => '2',
                'population' => 8574571,
                'area' => 1667000,
                'timezone' => 'UTC-05:00',
                'flag' => 'qc-flag.svg',
                'description' => 'Seule province majoritairement francophone, berceau de la Nouvelle-France.',
                'latitude' => '52.9399',
                'longitude' => '-73.5491',
                'country_id' => $canada->id
            ],
            [
                'name' => 'Saskatchewan',
                'code' => 'SK',
                'capital' => 'Regina',
                'largest_city' => 'Saskatoon',
                'official_language' => 'Anglais',
                'area_rank' => '7',
                'population' => 1177884,
                'area' => 651036,
                'timezone' => 'UTC-06:00',
                'flag' => 'sk-flag.svg',
                'description' => 'Province des prairies, connue comme le "Grenier du Canada".',
                'latitude' => '52.9399',
                'longitude' => '-106.4509',
                'country_id' => $canada->id
            ],
            [
                'name' => 'Terre-Neuve-et-Labrador',
                'code' => 'NL',
                'capital' => 'Saint-Jean',
                'largest_city' => 'Saint-Jean',
                'official_language' => 'Anglais',
                'area_rank' => '10',
                'population' => 521365,
                'area' => 405212,
                'timezone' => 'UTC-03:30',
                'flag' => 'nl-flag.svg',
                'description' => 'Province la plus à l\'est, composée de l\'île de Terre-Neuve et du Labrador continental.',
                'latitude' => '53.1355',
                'longitude' => '-57.6604',
                'country_id' => $canada->id
            ],
            
            // Territoires (3)
            [
                'name' => 'Nunavut',
                'code' => 'NU',
                'capital' => 'Iqaluit',
                'largest_city' => 'Iqaluit',
                'official_language' => 'Inuktitut, Inuinnaqtun, Anglais, Français',
                'area_rank' => '1',
                'population' => 39353,
                'area' => 2093190,
                'timezone' => 'UTC-05:00, -06:00, -07:00',
                'flag' => 'nu-flag.svg',
                'description' => 'Territoire le plus récent (1999) et le plus grand, patrie des Inuits.',
                'latitude' => '70.2998',
                'longitude' => '-83.1076',
                'country_id' => $canada->id
            ],
            [
                'name' => 'Territoires du Nord-Ouest',
                'code' => 'NT',
                'capital' => 'Yellowknife',
                'largest_city' => 'Yellowknife',
                'official_language' => 'Anglais, Français, Chipewyan, Cree, etc.',
                'area_rank' => '3',
                'population' => 45074,
                'area' => 1346106,
                'timezone' => 'UTC-07:00',
                'flag' => 'nt-flag.svg',
                'description' => 'Territoire avec une riche diversité culturelle autochtone.',
                'latitude' => '64.8255',
                'longitude' => '-124.8457',
                'country_id' => $canada->id
            ],
            [
                'name' => 'Yukon',
                'code' => 'YT',
                'capital' => 'Whitehorse',
                'largest_city' => 'Whitehorse',
                'official_language' => 'Anglais, Français',
                'area_rank' => '9',
                'population' => 42176,
                'area' => 482443,
                'timezone' => 'UTC-07:00',
                'flag' => 'yt-flag.svg',
                'description' => 'Territoire connu pour la ruée vers l\'or du Klondike.',
                'latitude' => '64.2823',
                'longitude' => '-135.0000',
                'country_id' => $canada->id
            ],
            
            // Ancien territoire (historique - optionnel)
            [
                'name' => 'Nunatsiavut',
                'code' => 'NUA',
                'capital' => 'Nain',
                'largest_city' => 'Nain',
                'official_language' => 'Inuktitut, Anglais',
                'area_rank' => '14',
                'population' => 2368,
                'area' => 72620,
                'timezone' => 'UTC-04:00',
                'flag' => 'nua-flag.svg',
                'description' => 'Région autonome inuite au Labrador (Terre-Neuve-et-Labrador).',
                'latitude' => '56.5333',
                'longitude' => '-61.6833',
                'country_id' => $canada->id
            ],
            [
                'name' => 'Territoire du Nord-Ouest historique',
                'code' => 'NT-H',
                'capital' => 'Regina (ancienne)',
                'largest_city' => 'Regina (ancienne)',
                'official_language' => 'Anglais',
                'area_rank' => '15',
                'population' => 0,
                'area' => 0,
                'timezone' => 'N/A',
                'flag' => 'nth-flag.svg',
                'description' => 'Ancien territoire qui comprenait plusieurs provinces actuelles (1870-1905).',
                'latitude' => '62.4540',
                'longitude' => '-114.3718',
                'country_id' => $canada->id
            ]
        ];

        foreach ($allProvincesAndTerritories as $data) {
            // Vérifier si la province existe déjà
            $exists = Province::where('code', $data['code'])
                ->where('country_id', $canada->id)
                ->exists();
            
            if (!$exists) {
                Province::create($data);
                $this->command->info("Province {$data['name']} créée.");
            } else {
                Province::where('code', $data['code'])
                    ->where('country_id', $canada->id)
                    ->update($data);
                $this->command->info("Province {$data['name']} mise à jour.");
            }
        }
        
        $total = count($allProvincesAndTerritories);
        $this->command->info("\n{$total} provinces et territoires canadiens traités avec succès!");
        
        // Afficher un tableau récapitulatif détaillé
        $this->command->info("\n📊 RÉCAPITULATIF DES PROVINCES ET TERRITOIRES CANADIENS:");
        $this->command->table(
            ['#', 'Province/Territoire', 'Code', 'Capitale', 'Population', 'Superficie (km²)', 'Densité'],
            Province::where('country_id', $canada->id)
                ->orderBy('population', 'desc')
                ->get()
                ->map(function ($province, $index) {
                    $density = $province->area > 0 
                        ? round($province->population / $province->area, 2)
                        : 0;
                    
                    $type = in_array($province->code, ['NU', 'NT', 'YT', 'NUA', 'NT-H']) 
                        ? '🏔️ Territoire' 
                        : '🏛️ Province';
                    
                    return [
                        '#' => $index + 1,
                        'name' => "{$type}\n{$province->name}",
                        'code' => $province->code,
                        'capital' => $province->capital,
                        'population' => number_format($province->population),
                        'area' => number_format($province->area),
                        'density' => $density > 0 ? number_format($density, 2) . ' hab/km²' : 'N/A'
                    ];
                })->toArray()
        );
        
        // Statistiques supplémentaires
        $totalPopulation = Province::where('country_id', $canada->id)->sum('population');
        $totalArea = Province::where('country_id', $canada->id)->sum('area');
        $provincesCount = Province::where('country_id', $canada->id)
            ->whereNotIn('code', ['NU', 'NT', 'YT', 'NUA', 'NT-H'])
            ->count();
        $territoriesCount = Province::where('country_id', $canada->id)
            ->whereIn('code', ['NU', 'NT', 'YT'])
            ->count();
        
        $this->command->info("\n📈 STATISTIQUES GLOBALES:");
        $this->command->table(
            ['Métrique', 'Valeur'],
            [
                ['Population totale', number_format($totalPopulation) . ' habitants'],
                ['Superficie totale', number_format($totalArea) . ' km²'],
                ['Nombre de provinces', $provincesCount],
                ['Nombre de territoires', $territoriesCount],
                ['Densité moyenne', round($totalPopulation / $totalArea, 2) . ' hab/km²'],
                ['Province la plus peuplée', 'Ontario (ON)'],
                ['Province la plus grande', 'Nunavut (NU)'],
                ['Province la plus petite', 'Île-du-Prince-Édouard (PE)']
            ]
        );
        
        // Informations sur les langues
        $this->command->info("\n🗣️ RÉPARTITION DES LANGUES OFFICIELLES:");
        $languages = Province::where('country_id', $canada->id)
            ->selectRaw('official_language, COUNT(*) as count')
            ->groupBy('official_language')
            ->orderBy('count', 'desc')
            ->get();
            
        $this->command->table(
            ['Langue(s) officielle(s)', 'Nombre'],
            $languages->map(function ($lang) {
                return [
                    'language' => $lang->official_language,
                    'count' => $lang->count
                ];
            })->toArray()
        );
        
        // Générer des données pour une carte
        $this->command->info("\n📍 COORDONNÉES POUR CARTE:");
        $this->command->table(
            ['Province/Territoire', 'Latitude', 'Longitude', 'Lien Google Maps'],
            Province::where('country_id', $canada->id)
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->orderBy('name')
                ->get()
                ->take(5)
                ->map(function ($province) {
                    return [
                        'name' => $province->name,
                        'latitude' => $province->latitude,
                        'longitude' => $province->longitude,
                        'maps' => "https://maps.google.com/?q={$province->latitude},{$province->longitude}"
                    ];
                })->toArray()
        );
        
        $this->command->info("\n✅ Toutes les provinces et territoires du Canada ont été créés/mis à jour avec succès!");
    }
}