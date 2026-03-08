<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Secteur;
use App\Models\Region;

class MontrealSecteursSeeder extends Seeder
{
    public function run()
    {
        // Récupérer la région de Montréal (code 06)
        $montrealRegion = Region::where('code', '06')
            ->whereHas('province', function ($query) {
                $query->where('code', 'QC');
            })
            ->first();
        
        if (!$montrealRegion) {
            $this->command->error('La région de Montréal (code 06) n\'existe pas dans la base de données!');
            return;
        }

        // Les 19 arrondissements de Montréal (2024)
        $arrondissements = [
            [
                'name' => 'Ahuntsic-Cartierville',
                'code' => 'ACT',
                'classification' => 'Arrondissement',
                'population' => 134245,
                'area' => 24.2,
                'households' => 58365,
                'mayor' => 'Émilie Thuillier',
                'website' => 'https://montreal.ca/ahuntsic-cartierville',
                'description' => 'Arrondissement résidentiel du nord de Montréal avec plusieurs parcs et institutions.',
                'history' => 'Fondé en 1910 par la fusion des villages d\'Ahuntsic et de Cartierville.',
                'attractions' => 'Parc-nature de l\'Île-de-la-Visitation, Cosmodôme, Collège de Bois-de-Boulogne.',
                'transport' => 'Stations de métro: Henri-Bourassa, Sauvé, Cartier; Lignes d\'autobus: 31, 48, 49, 121.',
                'education' => 'Collège de Bois-de-Boulogne, École secondaire Sophie-Barat, Université de Montréal (campus MIL).',
                'parks' => 'Parc Ahuntsic, Parc Saint-Benoît, Parc Nicolas-Viel, Parc du Pélican.',
                'latitude' => '45.5517',
                'longitude' => '-73.6639',
                'region_id' => $montrealRegion->id
            ],
            [
                'name' => 'Anjou',
                'code' => 'ANJ',
                'classification' => 'Arrondissement',
                'population' => 43485,
                'area' => 13.7,
                'households' => 18785,
                'mayor' => 'Luis Miranda',
                'website' => 'https://montreal.ca/anju',
                'description' => 'Arrondissement de l\'est de Montréal connu pour son centre commercial Les Galeries d\'Anjou.',
                'history' => 'Érigé en municipalité en 1956, fusionné à Montréal en 2002.',
                'attractions' => 'Galeries d\'Anjou, Aréna Maurice-Richard, Bibliothèque d\'Anjou.',
                'transport' => 'Station de métro: Radisson; Lignes d\'autobus: 33, 44, 189.',
                'education' => 'École secondaire d\'Anjou, École primaire Saint-Enfant-Jésus.',
                'parks' => 'Parc des Roseraies, Parc des Lilas, Parc Saint-Charles.',
                'latitude' => '45.6083',
                'longitude' => '-73.5694',
                'region_id' => $montrealRegion->id
            ],
            [
                'name' => 'Côte-des-Neiges–Notre-Dame-de-Grâce',
                'code' => 'CDN',
                'classification' => 'Arrondissement',
                'population' => 166520,
                'area' => 21.4,
                'households' => 73660,
                'mayor' => 'Gracia Kasoki Katahwa',
                'website' => 'https://montreal.ca/cdn-ndg',
                'description' => 'Arrondissement multiculturel et étudiant, siège de plusieurs universités.',
                'history' => 'Fusion de Côte-des-Neiges (1910) et Notre-Dame-de-Grâce (1906) en 2002.',
                'attractions' => 'Oratoire Saint-Joseph, Université de Montréal, Université Concordia (campus Loyola).',
                'transport' => 'Stations de métro: Côte-des-Neiges, Snowdon, Vendôme, Villa-Maria; Lignes d\'autobus: 51, 105, 161, 166.',
                'education' => 'Université de Montréal, Université Concordia, Collège Marianopolis.',
                'parks' => 'Parc Kent, Parc Girouard, Parc Mackenzie-King, Parc Benny.',
                'latitude' => '45.4900',
                'longitude' => '-73.6250',
                'region_id' => $montrealRegion->id
            ],
            [
                'name' => 'Lachine',
                'code' => 'LCH',
                'classification' => 'Arrondissement',
                'population' => 44685,
                'area' => 17.7,
                'households' => 20505,
                'mayor' => 'Maja Vodanovic',
                'website' => 'https://montreal.ca/lachine',
                'description' => 'Arrondissement historique avec son canal et son port industriel.',
                'history' => 'Fondée en 1667 par René-Robert Cavelier de La Salle, fusionnée à Montréal en 2002.',
                'attractions' => 'Canal de Lachine, Musée de Lachine, Marché de Lachine.',
                'transport' => 'Station de métro: Angrignon, Lachine; Lignes d\'autobus: 90, 110, 191.',
                'education' => 'Collège Saint-Louis, École secondaire Dalbé-Viau.',
                'parks' => 'Parc René-Lévesque, Parc Duff Court, Parc Lasalle.',
                'latitude' => '45.4411',
                'longitude' => '-73.6800',
                'region_id' => $montrealRegion->id
            ],
            [
                'name' => 'LaSalle',
                'code' => 'LAS',
                'classification' => 'Arrondissement',
                'population' => 79115,
                'area' => 16.3,
                'households' => 32740,
                'mayor' => 'Nancy Blanchet',
                'website' => 'https://montreal.ca/lasalle',
                'description' => 'Arrondissement riverain avec des parcs industriels et résidentiels.',
                'history' => 'Fondée en 1912, fusionnée à Montréal en 2002.',
                'attractions' => 'Parc des Rapides, Aréna Dollard-St-Laurent, Bibliothèque de LaSalle.',
                'transport' => 'Station de métro: De l\'Église, LaSalle; Lignes d\'autobus: 12, 58, 106.',
                'education' => 'Collège Sainte-Catherine-de-Sienne, École secondaire Cavelier-De LaSalle.',
                'parks' => 'Parc des Rapides, Parc Hayward, Parc Jean-Brillon.',
                'latitude' => '45.4289',
                'longitude' => '-73.6500',
                'region_id' => $montrealRegion->id
            ],
            [
                'name' => 'Le Plateau-Mont-Royal',
                'code' => 'PLT',
                'classification' => 'Arrondissement',
                'population' => 106115,
                'area' => 8.1,
                'households' => 57295,
                'mayor' => 'Luc Rabouin',
                'website' => 'https://montreal.ca/plateau-mont-royal',
                'description' => 'Arrondissement branché et artistique, l\'un des plus denses d\'Amérique du Nord.',
                'history' => 'Développé au 19e siècle, connu pour son architecture victorienne.',
                'attractions' => 'Avenue du Mont-Royal, Carré Saint-Louis, Parc La Fontaine.',
                'transport' => 'Stations de métro: Mont-Royal, Sherbrooke, Laurier; Lignes d\'autobus: 11, 14, 55.',
                'education' => 'Collège de Maisonneuve, Université du Québec à Montréal (UQAM).',
                'parks' => 'Parc La Fontaine, Parc Sir-Wilfrid-Laurier, Parc Baldwin.',
                'latitude' => '45.5208',
                'longitude' => '-73.5833',
                'region_id' => $montrealRegion->id
            ],
            [
                'name' => 'Le Sud-Ouest',
                'code' => 'SUD',
                'classification' => 'Arrondissement',
                'population' => 79605,
                'area' => 15.7,
                'households' => 40995,
                'mayor' => 'Benoit Dorais',
                'website' => 'https://montreal.ca/sud-ouest',
                'description' => 'Arrondissement en pleine revitalisation avec anciens quartiers industriels.',
                'history' => 'Ancienne zone industrielle transformée en quartier résidentiel.',
                'attractions' => 'Canal Lachine, Marché Atwater, Écluses Saint-Gabriel.',
                'transport' => 'Stations de métro: Lionel-Groulx, Georges-Vanier, Charlevoix; Lignes d\'autobus: 36, 57, 71.',
                'education' => 'Collège de l\'Ouest-de-l\'Île, École secondaire Saint-Henri.',
                'parks' => 'Parc du Canal-Lachine, Parc Marguerite-Bourgeoys, Parc de la Petite-Bourgogne.',
                'latitude' => '45.4789',
                'longitude' => '-73.5808',
                'region_id' => $montrealRegion->id
            ],
            [
                'name' => 'L\'Île-Bizard–Sainte-Geneviève',
                'code' => 'IBG',
                'classification' => 'Arrondissement',
                'population' => 18795,
                'area' => 23.6,
                'households' => 7765,
                'mayor' => 'Stéphane Côté',
                'website' => 'https://montreal.ca/ile-bizard-sainte-genevieve',
                'description' => 'Arrondissement insulaire et naturel avec beaucoup d\'espaces verts.',
                'history' => 'Développé au 18e siècle, fusionné à Montréal en 2002.',
                'attractions' => 'Club de golf de l\'Île-Bizard, Parc-nature du Bois-de-l\'Île-Bizard.',
                'transport' => 'Lignes d\'autobus: 68, 69, 201, 405.',
                'education' => 'École primaire du Bois-de-Liesse, École secondaire du Grand-Chêne.',
                'parks' => 'Parc-nature du Bois-de-l\'Île-Bizard, Parc de la Pointe-aux-Carrières.',
                'latitude' => '45.5000',
                'longitude' => '-73.9000',
                'region_id' => $montrealRegion->id
            ],
            [
                'name' => 'Mercier–Hochelaga-Maisonneuve',
                'code' => 'MHM',
                'classification' => 'Arrondissement',
                'population' => 139115,
                'area' => 25.4,
                'households' => 61345,
                'mayor' => 'Pierre Lessard-Blais',
                'website' => 'https://montreal.ca/mercier-hochelaga-maisonneuve',
                'description' => 'Arrondissement ouvrier historique en transformation.',
                'history' => 'Développé au 19e siècle autour des industries.',
                'attractions' => 'Stade olympique, Biodôme, Jardin botanique, Planétarium Rio Tinto Alcan.',
                'transport' => 'Stations de métro: Pie-IX, Viau, Préfontaine, Joliette; Lignes d\'autobus: 25, 85, 185.',
                'education' => 'Cégep de Maisonneuve, Collège de Rosemont.',
                'parks' => 'Parc Maisonneuve, Parc Morgan, Parc de la Petite-Patrie.',
                'latitude' => '45.5556',
                'longitude' => '-73.5444',
                'region_id' => $montrealRegion->id
            ],
            [
                'name' => 'Montréal-Nord',
                'code' => 'MNO',
                'classification' => 'Arrondissement',
                'population' => 86585,
                'area' => 11.1,
                'households' => 34910,
                'mayor' => 'Christine Black',
                'website' => 'https://montreal.ca/montreal-nord',
                'description' => 'Arrondissement multiculturel du nord de l\'île.',
                'history' => 'Fondé en 1915, fusionné à Montréal en 2002.',
                'attractions' => 'Aréna François-Léger, Bibliothèque de Montréal-Nord.',
                'transport' => 'Lignes d\'autobus: 31, 44, 48, 164.',
                'education' => 'École secondaire Calixa-Lavallée, Collège de l\'Assomption.',
                'parks' => 'Parc Henri-Bourassa, Parc Wilfrid-Bastien, Parc Rolland.',
                'latitude' => '45.6028',
                'longitude' => '-73.6319',
                'region_id' => $montrealRegion->id
            ],
            [
                'name' => 'Outremont',
                'code' => 'OUT',
                'classification' => 'Arrondissement',
                'population' => 24125,
                'area' => 3.9,
                'households' => 10590,
                'mayor' => 'Laurent Desbois',
                'website' => 'https://montreal.ca/outremont',
                'description' => 'Arrondissement aisé avec architecture historique et communauté universitaire.',
                'history' => 'Fondé en 1875, fusionné à Montréal en 2002.',
                'attractions' => 'Université de Montréal (campus principal), Cinéma Outremont.',
                'transport' => 'Stations de métro: Outremont, Édouard-Montpetit; Lignes d\'autobus: 51, 80, 161.',
                'education' => 'Université de Montréal, Pensionnat du Saint-Nom-de-Marie.',
                'parks' => 'Parc Outremont, Parc Pratt, Parge Beaubien.',
                'latitude' => '45.5167',
                'longitude' => '-73.6167',
                'region_id' => $montrealRegion->id
            ],
            [
                'name' => 'Pierrefonds-Roxboro',
                'code' => 'PIR',
                'classification' => 'Arrondissement',
                'population' => 69515,
                'area' => 27.1,
                'households' => 26955,
                'mayor' => 'Dimitrios (Jim) Beis',
                'website' => 'https://montreal.ca/pierrefonds-roxboro',
                'description' => 'Arrondissement de l\'ouest avec maisons unifamiliales et espaces naturels.',
                'history' => 'Fusion de Pierrefonds (1958) et Roxboro (1914) en 2002.',
                'attractions' => 'Parc-nature du Cap-Saint-Jacques, Centre récréatif Pierrefonds.',
                'transport' => 'Lignes d\'autobus: 68, 69, 205, 207.',
                'education' => 'Collège Sainte-Marcelline, École secondaire Saint-Georges.',
                'parks' => 'Parc-nature du Cap-Saint-Jacques, Parc de la Rivière-des-Prairies.',
                'latitude' => '45.5000',
                'longitude' => '-73.8500',
                'region_id' => $montrealRegion->id
            ],
            [
                'name' => 'Rivière-des-Prairies–Pointe-aux-Trembles',
                'code' => 'RDP',
                'classification' => 'Arrondissement',
                'population' => 111185,
                'area' => 42.3,
                'households' => 43245,
                'mayor' => 'Caroline Bourgeois',
                'website' => 'https://montreal.ca/riviere-des-prairies-pointe-aux-trembles',
                'description' => 'Le plus grand arrondissement de Montréal par superficie.',
                'history' => 'Anciennes municipalités fusionnées à Montréal en 2002.',
                'attractions' => 'Parc-nature de la Pointe-aux-Prairies, Ferme pédagogique.',
                'transport' => 'Station de métro: Honoré-Beaugrand; Lignes d\'autobus: 40, 185, 186.',
                'education' => 'Collège de Pointe-aux-Trembles, École secondaire Daniel-Johnson.',
                'parks' => 'Parc-nature de la Pointe-aux-Prairies, Parc de la Rivière-des-Prairies.',
                'latitude' => '45.6667',
                'longitude' => '-73.5333',
                'region_id' => $montrealRegion->id
            ],
            [
                'name' => 'Rosemont–La Petite-Patrie',
                'code' => 'ROS',
                'classification' => 'Arrondissement',
                'population' => 143850,
                'area' => 15.9,
                'households' => 67245,
                'mayor' => 'François Limoges',
                'website' => 'https://montreal.ca/rosemont-la-petite-patrie',
                'description' => 'Arrondissement familial avec marché public et vie de quartier active.',
                'history' => 'Développé au début du 20e siècle pour loger les ouvriers.',
                'attractions' => 'Marché Jean-Talon, Cinéma Beaubien, Parc Maisonneuve.',
                'transport' => 'Stations de métro: Beaubien, Rosemont, Jean-Talon, Jarry; Lignes d\'autobus: 18, 30, 95.',
                'education' => 'Cégep de Rosemont, Université de Montréal (campus MIL).',
                'parks' => 'Parc Maisonneuve, Parc Molson, Parc Pélican.',
                'latitude' => '45.5500',
                'longitude' => '-73.6000',
                'region_id' => $montrealRegion->id
            ],
            [
                'name' => 'Saint-Laurent',
                'code' => 'STL',
                'classification' => 'Arrondissement',
                'population' => 99840,
                'area' => 42.8,
                'households' => 41305,
                'mayor' => 'Alan DeSousa',
                'website' => 'https://montreal.ca/saint-laurent',
                'description' => 'Arrondissement mixte avec zones industrielles, commerciales et résidentielles.',
                'history' => 'Fondé en 1893, fusionné à Montréal en 2002.',
                'attractions' => 'Centre commercial Place Vertu, Cité du commerce électronique.',
                'transport' => 'Station de métro: Du Collège, Côte-Vertu; Lignes d\'autobus: 17, 64, 121, 171.',
                'education' => 'Collège Vanier, Université Concordia (campus Loyola).',
                'parks' => 'Parc Marcel-Laurin, Parc Beaudet, Parc Cousineau.',
                'latitude' => '45.5167',
                'longitude' => '-73.7000',
                'region_id' => $montrealRegion->id
            ],
            [
                'name' => 'Saint-Léonard',
                'code' => 'STL',
                'classification' => 'Arrondissement',
                'population' => 79395,
                'area' => 13.5,
                'households' => 31805,
                'mayor' => 'Michel Bissonnet',
                'website' => 'https://montreal.ca/saint-leonard',
                'description' => 'Arrondissement à forte communauté italienne.',
                'history' => 'Fondé en 1886, fusionné à Montréal en 2002.',
                'attractions' => 'Centre Leonardo da Vinci, Aréna Martin-Brodeur.',
                'transport' => 'Stations de métro: Saint-Michel, d\'Iberville; Lignes d\'autobus: 31, 141, 193.',
                'education' => 'Collège Saint-Léonard, École secondaire Saint-Léonard.',
                'parks' => 'Parc Wilfrid-Bastien, Parc Delorme, Parc Giovanni-Palante.',
                'latitude' => '45.5833',
                'longitude' => '-73.5833',
                'region_id' => $montrealRegion->id
            ],
            [
                'name' => 'Verdun',
                'code' => 'VER',
                'classification' => 'Arrondissement',
                'population' => 71530,
                'area' => 9.7,
                'households' => 37510,
                'mayor' => 'Marie-Andrée Mauger',
                'website' => 'https://montreal.ca/verdun',
                'description' => 'Arrondissement riverain en pleine revitalisation.',
                'history' => 'Fondé en 1671, l\'un des plus anciens quartiers de Montréal.',
                'attractions' => 'Plage de Verdun, Promenade Wellington, Marché de Verdun.',
                'transport' => 'Stations de métro: De l\'Église, Verdun, Jolicoeur; Lignes d\'autobus: 12, 58, 107.',
                'education' => 'École secondaire Monseigneur-Richard, Collège Sainte-Marcelline.',
                'parks' => 'Parc Arthur-Therrien, Parc de l\'Honorable-George-O\'Reilly.',
                'latitude' => '45.4583',
                'longitude' => '-73.5708',
                'region_id' => $montrealRegion->id
            ],
            [
                'name' => 'Ville-Marie',
                'code' => 'VIM',
                'classification' => 'Arrondissement',
                'population' => 92155,
                'area' => 16.5,
                'households' => 58090,
                'mayor' => 'Valérie Plante',
                'website' => 'https://montreal.ca/ville-marie',
                'description' => 'Centre-ville de Montréal avec gratte-ciels et Vieux-Montréal.',
                'history' => 'Cœur historique de Montréal fondé en 1642.',
                'attractions' => 'Vieux-Montréal, Quartier des spectacles, Centre-ville, Vieux-Port.',
                'transport' => 'Stations de métro: Place-des-Arts, McGill, Square-Victoria, Champ-de-Mars; Lignes d\'autobus: 15, 55, 80, 427.',
                'education' => 'Université McGill, Université du Québec à Montréal (UQAM), Université Concordia.',
                'parks' => 'Parc du Mont-Royal, Square Dorchester, Place Vauquelin.',
                'latitude' => '45.5089',
                'longitude' => '-73.5617',
                'region_id' => $montrealRegion->id
            ],
            [
                'name' => 'Villeray–Saint-Michel–Parc-Extension',
                'code' => 'VSP',
                'classification' => 'Arrondissement',
                'population' => 146115,
                'area' => 16.2,
                'households' => 63215,
                'mayor' => 'Laurence Lavigne Lalonde',
                'website' => 'https://montreal.ca/villeray-saint-michel-parc-extension',
                'description' => 'Arrondissement multiculturel avec plusieurs communautés immigrantes.',
                'history' => 'Développé au début du 20e siècle pour loger les ouvriers.',
                'attractions' => 'Marché Jean-Talon, Jardin botanique (partie nord), Parc Jarry.',
                'transport' => 'Stations de métro: Jean-Talon, Jarry, Fabre, d\'Iberville; Lignes d\'autobus: 30, 92, 93.',
                'education' => 'Cégep de Saint-Laurent, Université de Montréal (campus MIL).',
                'parks' => 'Parc Jarry, Parc Frédéric-Back, Parc Saint-Michel.',
                'latitude' => '45.5500',
                'longitude' => '-73.6167',
                'region_id' => $montrealRegion->id
            ]
        ];

        $count = 0;
        foreach ($arrondissements as $data) {
            // Vérifier si le secteur existe déjà
            $exists = Secteur::where('code', $data['code'])
                ->where('region_id', $montrealRegion->id)
                ->exists();
            
            if (!$exists) {
                Secteur::create($data);
                $count++;
                $this->command->info("Secteur {$data['name']} créé.");
            } else {
                Secteur::where('code', $data['code'])
                    ->where('region_id', $montrealRegion->id)
                    ->update($data);
                $this->command->info("Secteur {$data['name']} mis à jour.");
            }
        }
        
        $this->command->info("\n✅ {$count} arrondissements de Montréal ont été créés/mis à jour!");
        
        // Afficher un tableau récapitulatif
        $this->command->info("\n📊 RÉCAPITULATIF DES ARRONDISSEMENTS DE MONTRÉAL:");
        $this->command->table(
            ['Arrondissement', 'Code', 'Population', 'Superficie (km²)', 'Densité (hab/km²)', 'Maire'],
            Secteur::where('region_id', $montrealRegion->id)
                ->orderBy('population', 'desc')
                ->get()
                ->map(function ($secteur) {
                    return [
                        'name' => $secteur->name,
                        'code' => $secteur->code,
                        'population' => number_format($secteur->population),
                        'area' => number_format($secteur->area, 1),
                        'density' => number_format($secteur->density, 1),
                        'mayor' => $secteur->mayor
                    ];
                })->toArray()
        );
        
        // Statistiques
        $totalPopulation = Secteur::where('region_id', $montrealRegion->id)->sum('population');
        $totalArea = Secteur::where('region_id', $montrealRegion->id)->sum('area');
        $totalHouseholds = Secteur::where('region_id', $montrealRegion->id)->sum('households');
        
        $this->command->info("\n📈 STATISTIQUES GÉNÉRALES:");
        $this->command->table(
            ['Métrique', 'Valeur'],
            [
                ['Nombre total d\'arrondissements', $count],
                ['Population totale', number_format($totalPopulation) . ' habitants'],
                ['Superficie totale', number_format($totalArea, 1) . ' km²'],
                ['Nombre total de ménages', number_format($totalHouseholds)],
                ['Densité moyenne', number_format($totalPopulation / $totalArea, 1) . ' hab/km²'],
                ['Arrondissement le plus peuplé', 'Côte-des-Neiges–Notre-Dame-de-Grâce'],
                ['Arrondissement le plus dense', 'Le Plateau-Mont-Royal'],
                ['Arrondissement le plus grand', 'Rivière-des-Prairies–Pointe-aux-Trembles']
            ]
        );
    }
}