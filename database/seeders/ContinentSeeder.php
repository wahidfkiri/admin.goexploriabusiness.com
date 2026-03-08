<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Continent;

class ContinentSeeder extends Seeder
{
    public function run()
    {
        $continents = [
            [
                'name' => 'Afrique',
                'code' => 'AF',
                'image' => 'africa.jpg',
                'description' => 'Deuxième plus grand continent du monde',
                'population' => 1300000000,
                'area' => 30370000,
                'countries_count' => 54,
                'languages' => ['arabe', 'swahili', 'français', 'anglais']
            ],
            [
                'name' => 'Asie',
                'code' => 'AS',
                'image' => 'asia.jpg',
                'description' => 'Plus grand continent en superficie et population',
                'population' => 4600000000,
                'area' => 44579000,
                'countries_count' => 48,
                'languages' => ['chinois', 'hindi', 'arabe', 'russe', 'japonais']
            ],
            [
                'name' => 'Europe',
                'code' => 'EU',
                'image' => 'europe.jpg',
                'description' => 'Sixième plus grand continent',
                'population' => 747000000,
                'area' => 10180000,
                'countries_count' => 44,
                'languages' => ['anglais', 'français', 'allemand', 'russe', 'espagnol']
            ],
            [
                'name' => 'Amérique du Nord',
                'code' => 'NA',
                'image' => 'north-america.jpg',
                'description' => 'Troisième plus grand continent',
                'population' => 592000000,
                'area' => 24709000,
                'countries_count' => 23,
                'languages' => ['anglais', 'espagnol', 'français']
            ],
            [
                'name' => 'Amérique du Sud',
                'code' => 'SA',
                'image' => 'south-america.jpg',
                'description' => 'Quatrième plus grand continent',
                'population' => 430000000,
                'area' => 17840000,
                'countries_count' => 12,
                'languages' => ['espagnol', 'portugais', 'néerlandais']
            ],
            [
                'name' => 'Océanie',
                'code' => 'OC',
                'image' => 'oceania.jpg',
                'description' => 'Plus petit continent',
                'population' => 45000000,
                'area' => 8525989,
                'countries_count' => 14,
                'languages' => ['anglais', 'français', 'hawaïen', 'maori']
            ],
            [
                'name' => 'Antarctique',
                'code' => 'AN',
                'image' => 'antarctica.jpg',
                'description' => 'Continent le plus froid et le plus sec',
                'population' => 0, // seulement des bases scientifiques
                'area' => 14200000,
                'countries_count' => 0,
                'languages' => ['anglais', 'russe', 'espagnol'] // langues des bases
            ]
        ];

        foreach ($continents as $continent) {
            Continent::create($continent);
        }
    }
}