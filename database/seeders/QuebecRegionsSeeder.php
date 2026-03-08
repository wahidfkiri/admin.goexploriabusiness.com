<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Region;
use App\Models\Province;

class QuebecRegionsSeeder extends Seeder
{
    public function run()
    {
        // Récupérer la province de Québec
        $quebec = Province::where('code', 'QC')->first();
        
        if (!$quebec) {
            $this->command->error('La province de Québec n\'existe pas dans la base de données!');
            return;
        }

        // 17 régions administratives du Québec
        $regions = [
            // Région 01 - Bas-Saint-Laurent
            [
                'name' => 'Bas-Saint-Laurent',
                'code' => '01',
                'capital' => 'Rimouski',
                'largest_city' => 'Rimouski',
                'classification' => 'Région administrative',
                'population' => 199300,
                'area' => 22232,
                'municipalities_count' => 114,
                'timezone' => 'UTC-05:00',
                'flag' => '01-flag.svg',
                'description' => 'Région située sur la rive sud du fleuve Saint-Laurent, entre la Côte-Nord et la Gaspésie.',
                'geography' => 'Caractérisée par ses montagnes, ses forêts et son littoral maritime.',
                'economy' => 'Agriculture, foresterie, pêche, tourisme, transport maritime.',
                'tourism' => 'Parc national du Bic, Îles du Pot à l\'Eau-de-Vie, Route des Navigateurs.',
                'latitude' => '48.4481',
                'longitude' => '-68.5910',
                'province_id' => $quebec->id
            ],

            // Région 02 - Saguenay–Lac-Saint-Jean
            [
                'name' => 'Saguenay–Lac-Saint-Jean',
                'code' => '02',
                'capital' => 'Saguenay',
                'largest_city' => 'Saguenay',
                'classification' => 'Région administrative',
                'population' => 275600,
                'area' => 95694,
                'municipalities_count' => 69,
                'timezone' => 'UTC-05:00',
                'flag' => '02-flag.svg',
                'description' => 'Région du Saguenay connue pour son lac immense et sa forte identité culturelle.',
                'geography' => 'Vallée du Saguenay, Lac Saint-Jean, forêts boréales.',
                'economy' => 'Aluminium, foresterie, agriculture, tourisme.',
                'tourism' => 'Fjord du Saguenay, Zoo sauvage de Saint-Félicien, Village historique de Val-Jalbert.',
                'latitude' => '48.4275',
                'longitude' => '-71.0689',
                'province_id' => $quebec->id
            ],

            // Région 03 - Capitale-Nationale
            [
                'name' => 'Capitale-Nationale',
                'code' => '03',
                'capital' => 'Québec',
                'largest_city' => 'Québec',
                'classification' => 'Région administrative',
                'population' => 745100,
                'area' => 18529,
                'municipalities_count' => 60,
                'timezone' => 'UTC-05:00',
                'flag' => '03-flag.svg',
                'description' => 'Région de la capitale québécoise, riche en histoire et patrimoine.',
                'geography' => 'Fleuve Saint-Laurent, collines de Québec, île d\'Orléans.',
                'economy' => 'Administration publique, technologies, tourisme, éducation.',
                'tourism' => 'Vieux-Québec (patrimoine mondial de l\'UNESCO), Château Frontenac, Plaines d\'Abraham.',
                'latitude' => '46.8299',
                'longitude' => '-71.2540',
                'province_id' => $quebec->id
            ],

            // Région 04 - Mauricie
            [
                'name' => 'Mauricie',
                'code' => '04',
                'capital' => 'Trois-Rivières',
                'largest_city' => 'Trois-Rivières',
                'classification' => 'Région administrative',
                'population' => 266100,
                'area' => 35152,
                'municipalities_count' => 49,
                'timezone' => 'UTC-05:00',
                'flag' => '04-flag.svg',
                'description' => 'Région du cœur du Québec, berceau de l\'industrie forestière.',
                'geography' => 'Rivière Saint-Maurice, forêts, lacs, Parc national de la Mauricie.',
                'economy' => 'Pâtes et papiers, aluminium, énergie, tourisme.',
                'tourism' => 'Parc national de la Mauricie, Sanctuaire Notre-Dame-du-Cap, Village québécois d\'antan.',
                'latitude' => '46.5834',
                'longitude' => '-72.7490',
                'province_id' => $quebec->id
            ],

            // Région 05 - Estrie
            [
                'name' => 'Estrie',
                'code' => '05',
                'capital' => 'Sherbrooke',
                'largest_city' => 'Sherbrooke',
                'classification' => 'Région administrative',
                'population' => 330300,
                'area' => 10495,
                'municipalities_count' => 89,
                'timezone' => 'UTC-05:00',
                'flag' => '05-flag.svg',
                'description' => 'Région des Cantons-de-l\'Est, à la frontière avec les États-Unis.',
                'geography' => 'Montagnes, collines, lacs, frontière américaine.',
                'economy' => 'Manufacturier, éducation, agriculture, tourisme.',
                'tourism' => 'Station touristique du Mont-Orford, vignobles, villages pittoresques.',
                'latitude' => '45.4000',
                'longitude' => '-71.9000',
                'province_id' => $quebec->id
            ],

            // Région 06 - Montréal
            [
                'name' => 'Montréal',
                'code' => '06',
                'capital' => 'Montréal',
                'largest_city' => 'Montréal',
                'classification' => 'Région administrative',
                'population' => 2033000,
                'area' => 500,
                'municipalities_count' => 16,
                'timezone' => 'UTC-05:00',
                'flag' => '06-flag.svg',
                'description' => 'Métropole du Québec, ville la plus peuplée de la province.',
                'geography' => 'Île de Montréal, Mont Royal, fleuve Saint-Laurent.',
                'economy' => 'Finance, technologies, aérospatiale, pharmaceutique, culture.',
                'tourism' => 'Vieux-Montréal, Oratoire Saint-Joseph, Quartier des spectacles, Biodôme.',
                'latitude' => '45.5017',
                'longitude' => '-73.5673',
                'province_id' => $quebec->id
            ],

            // Région 07 - Outaouais
            [
                'name' => 'Outaouais',
                'code' => '07',
                'capital' => 'Gatineau',
                'largest_city' => 'Gatineau',
                'classification' => 'Région administrative',
                'population' => 394700,
                'area' => 30687,
                'municipalities_count' => 66,
                'timezone' => 'UTC-05:00',
                'flag' => '07-flag.svg',
                'description' => 'Région à la frontière de l\'Ontario, face à la capitale nationale Ottawa.',
                'geography' => 'Rivière des Outaouais, collines, forêts.',
                'economy' => 'Fonction publique, technologies, tourisme.',
                'tourism' => 'Musée canadien de l\'histoire, Parc de la Gatineau, Casino du Lac-Leamy.',
                'latitude' => '45.4765',
                'longitude' => '-75.7013',
                'province_id' => $quebec->id
            ],

            // Région 08 - Abitibi-Témiscamingue
            [
                'name' => 'Abitibi-Témiscamingue',
                'code' => '08',
                'capital' => 'Rouyn-Noranda',
                'largest_city' => 'Rouyn-Noranda',
                'classification' => 'Région administrative',
                'population' => 147500,
                'area' => 57604,
                'municipalities_count' => 79,
                'timezone' => 'UTC-05:00',
                'flag' => '08-flag.svg',
                'description' => 'Région minière et forestière du nord-ouest du Québec.',
                'geography' => 'Forêts boréales, lacs, mines à ciel ouvert.',
                'economy' => 'Mines, foresterie, agriculture, énergie.',
                'tourism' => 'Cité de l\'Or, Festival du cinéma international en Abitibi-Témiscamingue.',
                'latitude' => '48.2348',
                'longitude' => '-79.0210',
                'province_id' => $quebec->id
            ],

            // Région 09 - Côte-Nord
            [
                'name' => 'Côte-Nord',
                'code' => '09',
                'capital' => 'Baie-Comeau',
                'largest_city' => 'Sept-Îles',
                'classification' => 'Région administrative',
                'population' => 92000,
                'area' => 236700,
                'municipalities_count' => 52,
                'timezone' => 'UTC-05:00',
                'flag' => '09-flag.svg',
                'description' => 'Région côtière du golfe du Saint-Laurent, la deuxième plus grande région du Québec.',
                'geography' => 'Côte maritime, fjord du Saguenay, archipels.',
                'economy' => 'Mines, aluminium, hydroélectricité, pêche.',
                'tourism' => 'Parc national de l\'Archipel-de-Mingan, Réserve de parc national de l\'Île-Bonaventure-et-du-Rocher-Percé.',
                'latitude' => '50.2241',
                'longitude' => '-66.3771',
                'province_id' => $quebec->id
            ],

            // Région 10 - Nord-du-Québec
            [
                'name' => 'Nord-du-Québec',
                'code' => '10',
                'capital' => 'Chibougamau',
                'largest_city' => 'Chibougamau',
                'classification' => 'Région administrative',
                'population' => 45000,
                'area' => 718000,
                'municipalities_count' => 34,
                'timezone' => 'UTC-05:00',
                'flag' => '10-flag.svg',
                'description' => 'Plus grande région du Québec, territoire principalement autochtone.',
                'geography' => 'Forêts boréales, toundra, baie James, baie d\'Hudson.',
                'economy' => 'Mines, hydroélectricité, activités autochtones.',
                'tourism' => 'Communautés cries et inuites, projets hydroélectriques.',
                'latitude' => '53.7500',
                'longitude' => '-73.0000',
                'province_id' => $quebec->id
            ],

            // Région 11 - Gaspésie–Îles-de-la-Madeleine
            [
                'name' => 'Gaspésie–Îles-de-la-Madeleine',
                'code' => '11',
                'capital' => 'Gaspé',
                'largest_city' => 'Gaspé',
                'classification' => 'Région administrative',
                'population' => 90600,
                'area' => 20732,
                'municipalities_count' => 54,
                'timezone' => 'UTC-05:00',
                'flag' => '11-flag.svg',
                'description' => 'Péninsule gaspésienne et archipel des Îles-de-la-Madeleine.',
                'geography' => 'Montagnes, falaises, mer, îles sablonneuses.',
                'economy' => 'Pêche, tourisme, énergie éolienne.',
                'tourism' => 'Rocher Percé, Parc national de la Gaspésie, Îles-de-la-Madeleine.',
                'latitude' => '48.8297',
                'longitude' => '-64.4839',
                'province_id' => $quebec->id
            ],

            // Région 12 - Chaudière-Appalaches
            [
                'name' => 'Chaudière-Appalaches',
                'code' => '12',
                'capital' => 'Montmagny',
                'largest_city' => 'Lévis',
                'classification' => 'Région administrative',
                'population' => 424500,
                'area' => 15071,
                'municipalities_count' => 136,
                'timezone' => 'UTC-05:00',
                'flag' => '12-flag.svg',
                'description' => 'Région au sud de Québec, le long de la rivière Chaudière.',
                'geography' => 'Appalaches, rivière Chaudière, fleuve Saint-Laurent.',
                'economy' => 'Agriculture, manufacturier, services, tourisme.',
                'tourism' => 'Parc national de Frontenac, Route des Navigateurs, érablières.',
                'latitude' => '46.5550',
                'longitude' => '-70.8200',
                'province_id' => $quebec->id
            ],

            // Région 13 - Laval
            [
                'name' => 'Laval',
                'code' => '13',
                'capital' => 'Laval',
                'largest_city' => 'Laval',
                'classification' => 'Région administrative',
                'population' => 438400,
                'area' => 247,
                'municipalities_count' => 1,
                'timezone' => 'UTC-05:00',
                'flag' => '13-flag.svg',
                'description' => 'Île et ville située entre Montréal et la Rive-Nord.',
                'geography' => 'Île de Laval, rivière des Prairies, rivière des Mille-Îles.',
                'economy' => 'Technologies, santé, commerce, services.',
                'tourism' => 'Cosmodôme, Centre de la nature, Centropolis.',
                'latitude' => '45.5830',
                'longitude' => '-73.7490',
                'province_id' => $quebec->id
            ],

            // Région 14 - Lanaudière
            [
                'name' => 'Lanaudière',
                'code' => '14',
                'capital' => 'Joliette',
                'largest_city' => 'Terrebonne',
                'classification' => 'Région administrative',
                'population' => 511800,
                'area' => 12760,
                'municipalities_count' => 64,
                'timezone' => 'UTC-05:00',
                'flag' => '14-flag.svg',
                'description' => 'Région située au nord de Montréal, entre les Laurentides et la Mauricie.',
                'geography' => 'Collines, rivières, forêts, terres agricoles.',
                'economy' => 'Agriculture, manufacturier, tourisme.',
                'tourism' => 'Parc régional de la Forêt Ouareau, Festival de Lanaudière, villages historiques.',
                'latitude' => '46.2833',
                'longitude' => '-73.6167',
                'province_id' => $quebec->id
            ],

            // Région 15 - Laurentides
            [
                'name' => 'Laurentides',
                'code' => '15',
                'capital' => 'Saint-Jérôme',
                'largest_city' => 'Saint-Jérôme',
                'classification' => 'Région administrative',
                'population' => 589400,
                'area' => 20078,
                'municipalities_count' => 83,
                'timezone' => 'UTC-05:00',
                'flag' => '15-flag.svg',
                'description' => 'Région touristique au nord de Montréal, dans les contreforts des Laurentides.',
                'geography' => 'Montagnes, lacs, forêts, stations de ski.',
                'economy' => 'Tourisme, agriculture, services, technologies.',
                'tourism' => 'Station Tremblant, Parc national du Mont-Tremblant, villages de Sainte-Adèle et Saint-Sauveur.',
                'latitude' => '46.0333',
                'longitude' => '-74.0833',
                'province_id' => $quebec->id
            ],

            // Région 16 - Montérégie
            [
                'name' => 'Montérégie',
                'code' => '16',
                'capital' => 'Longueuil',
                'largest_city' => 'Longueuil',
                'classification' => 'Région administrative',
                'population' => 1557700,
                'area' => 11131,
                'municipalities_count' => 177,
                'timezone' => 'UTC-05:00',
                'flag' => '16-flag.svg',
                'description' => 'Région la plus peuplée après Montréal, ceinture verte de la métropole.',
                'geography' => 'Plaines du Saint-Laurent, montérégiennes (collines), fleuve Saint-Laurent.',
                'economy' => 'Agriculture, industrie, technologies, services.',
                'tourism' => 'Vieux-Saint-Eustache, Îles de Boucherville, vignobles, vergers.',
                'latitude' => '45.4333',
                'longitude' => '-73.2500',
                'province_id' => $quebec->id
            ],

            // Région 17 - Centre-du-Québec
            [
                'name' => 'Centre-du-Québec',
                'code' => '17',
                'capital' => 'Drummondville',
                'largest_city' => 'Drummondville',
                'classification' => 'Région administrative',
                'population' => 247700,
                'area' => 6924,
                'municipalities_count' => 80,
                'timezone' => 'UTC-05:00',
                'flag' => '17-flag.svg',
                'description' => 'Région centrale située entre Montréal et Québec.',
                'geography' => 'Plaines, rivières Saint-François et Nicolet.',
                'economy' => 'Manufacturier, agriculture, services.',
                'tourism' => 'Village québécois d\'antan de Drummondville, parc des Voltigeurs.',
                'latitude' => '46.0500',
                'longitude' => '-72.3333',
                'province_id' => $quebec->id
            ]
        ];

        $count = 0;
        foreach ($regions as $data) {
            // Vérifier si la région existe déjà
            $exists = Region::where('code', $data['code'])
                ->where('province_id', $quebec->id)
                ->exists();
            
            if (!$exists) {
                Region::create($data);
                $count++;
                $this->command->info("Région {$data['name']} (code {$data['code']}) créée.");
            } else {
                Region::where('code', $data['code'])
                    ->where('province_id', $quebec->id)
                    ->update($data);
                $this->command->info("Région {$data['name']} (code {$data['code']}) mise à jour.");
            }
        }
        
        $this->command->info("\n✅ {$count} régions administratives du Québec ont été créées/mises à jour!");
        
        // Afficher un tableau récapitulatif
        $this->command->info("\n📊 RÉCAPITULATIF DES RÉGIONS ADMINISTRATIVES DU QUÉBEC:");
        $this->command->table(
            ['Code', 'Région', 'Capitale', 'Population', 'Superficie (km²)', 'Densité'],
            Region::where('province_id', $quebec->id)
                ->orderBy('code')
                ->get()
                ->map(function ($region) {
                    $density = $region->area > 0 
                        ? round($region->population / $region->area, 2)
                        : 0;
                    
                    return [
                        'code' => $region->code,
                        'name' => $region->name,
                        'capital' => $region->capital,
                        'population' => number_format($region->population),
                        'area' => number_format($region->area),
                        'density' => $density > 0 ? number_format($density, 2) . ' hab/km²' : 'N/A'
                    ];
                })->toArray()
        );
        
        // Statistiques
        $totalPopulation = Region::where('province_id', $quebec->id)->sum('population');
        $totalArea = Region::where('province_id', $quebec->id)->sum('area');
        $totalMunicipalities = Region::where('province_id', $quebec->id)->sum('municipalities_count');
        
        $this->command->info("\n📈 STATISTIQUES GÉNÉRALES:");
        $this->command->table(
            ['Métrique', 'Valeur'],
            [
                ['Nombre total de régions', $count],
                ['Population totale', number_format($totalPopulation) . ' habitants'],
                ['Superficie totale', number_format($totalArea) . ' km²'],
                ['Nombre total de municipalités', $totalMunicipalities],
                ['Densité moyenne', round($totalPopulation / $totalArea, 2) . ' hab/km²'],
                ['Région la plus peuplée', 'Montréal (06)'],
                ['Région la plus grande', 'Nord-du-Québec (10)'],
                ['Région la plus petite', 'Laval (13)']
            ]
        );
        
        // Classement par population
        $this->command->info("\n🏆 CLASSEMENT PAR POPULATION:");
        $this->command->table(
            ['Rang', 'Région', 'Population', '% du total'],
            Region::where('province_id', $quebec->id)
                ->orderBy('population', 'desc')
                ->get()
                ->map(function ($region, $index) use ($totalPopulation) {
                    $percentage = $totalPopulation > 0 
                        ? round(($region->population / $totalPopulation) * 100, 2)
                        : 0;
                    
                    return [
                        'rank' => $index + 1,
                        'name' => $region->name . ' (' . $region->code . ')',
                        'population' => number_format($region->population),
                        'percentage' => $percentage . '%'
                    ];
                })->toArray()
        );
        
        $this->command->info("\n📍 COORDONNÉES GÉOGRAPHIQUES:");
        $this->command->table(
            ['Région', 'Latitude', 'Longitude', 'Lien Google Maps'],
            Region::where('province_id', $quebec->id)
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->orderBy('name')
                ->get()
                ->take(5)
                ->map(function ($region) {
                    return [
                        'name' => $region->name,
                        'latitude' => $region->latitude,
                        'longitude' => $region->longitude,
                        'maps' => "https://maps.google.com/?q={$region->latitude},{$region->longitude}"
                    ];
                })->toArray()
        );
    }
}