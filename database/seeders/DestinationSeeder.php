<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $destinations = [
            [
                'name' => 'Toronto',
                'image' => 'toronto.jpg',
                'description' => 'La plus grande ville du Canada, centre économique et culturel.',
                'is_active' => true,
            ],
            [
                'name' => 'Montréal',
                'image' => 'montreal.jpg',
                'description' => 'Ville francophone riche en culture, gastronomie et histoire.',
                'is_active' => true,
            ],
            [
                'name' => 'Vancouver',
                'image' => 'vancouver.jpg',
                'description' => 'Cité côtière entourée de montagnes, idéale pour les amoureux de la nature.',
                'is_active' => true,
            ],
            [
                'name' => 'Québec',
                'image' => 'quebec.jpg',
                'description' => 'Ville historique fortifiée, berceau de la civilisation française en Amérique.',
                'is_active' => true,
            ],
            [
                'name' => 'Calgary',
                'image' => 'calgary.jpg',
                'description' => 'Porte d\'entrée des Rocheuses canadiennes et célèbre pour son Stampede.',
                'is_active' => true,
            ],
            [
                'name' => 'Ottawa',
                'image' => 'ottawa.jpg',
                'description' => 'Capitale du Canada, riche en musées et institutions nationales.',
                'is_active' => true,
            ],
            [
                'name' => 'Banff',
                'image' => 'banff.jpg',
                'description' => 'Station de montagne renommée dans le parc national de Banff.',
                'is_active' => true,
            ],
            [
                'name' => 'Niagara Falls',
                'image' => 'niagara.jpg',
                'description' => 'Célèbres chutes d\'eau à la frontière Canada-États-Unis.',
                'is_active' => true,
            ],
        ];

        foreach ($destinations as $destination) {
            Destination::create([
                'name' => $destination['name'],
                'image' => $destination['image'],
                'description' => $destination['description'],
                'is_active' => $destination['is_active'],
                'slug' => Str::slug($destination['name']),
            ]);
        }

        // Créer quelques destinations inactives
        Destination::create([
            'name' => 'Winnipeg',
            'image' => 'winnipeg.jpg',
            'description' => 'Ville des prairies canadiennes.',
            'is_active' => false,
            'slug' => 'winnipeg',
        ]);

        Destination::create([
            'name' => 'Halifax',
            'image' => 'halifax.jpg',
            'description' => 'Port historique sur la côte atlantique.',
            'is_active' => false,
            'slug' => 'halifax',
        ]);
    }
}