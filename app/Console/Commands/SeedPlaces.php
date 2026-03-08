<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Place;

class SeedPlaces extends Command
{
    protected $signature = 'places:seed';
    protected $description = 'Génère des données de test pour les lieux';

    public function handle()
    {
        $places = [
            [
                'name' => 'Tour Eiffel',
                'description' => 'Monument emblématique de Paris, construite par Gustave Eiffel pour l\'Exposition universelle de 1889.',
                'latitude' => 48.8584,
                'longitude' => 2.2945,
                'category' => 'monument',
                'images' => [
                    'https://images.unsplash.com/photo-1511739001486-6bfe10ce785f',
                    'https://images.unsplash.com/photo-1543349689-9a4d426bee8e'
                ],
                'video_url' => 'https://www.youtube.com/watch?v=z5dSx3GVNhk',
                'address' => 'Champ de Mars, 5 Avenue Anatole France, 75007 Paris',
                'phone' => '+33 1 44 11 23 23',
                'website' => 'https://www.toureiffel.paris'
            ],
            [
                'name' => 'Louvre Museum',
                'description' => 'Le plus grand musée d\'art et d\'antiquités au monde, situé au centre de Paris.',
                'latitude' => 48.8606,
                'longitude' => 2.3376,
                'category' => 'museum',
                'images' => [
                    'https://images.unsplash.com/photo-1548013146-72479768bada',
                    'https://images.unsplash.com/photo-1587602050163-ea89216c355e'
                ],
                'video_url' => 'https://www.youtube.com/watch?v=UdnOufKt8E4',
                'address' => 'Rue de Rivoli, 75001 Paris',
                'phone' => '+33 1 40 20 50 50',
                'website' => 'https://www.louvre.fr'
            ],
            [
                'name' => 'Le Café de Flore',
                'description' => 'Café historique de Paris, rendez-vous des intellectuels et artistes depuis la fin du XIXe siècle.',
                'latitude' => 48.8540,
                'longitude' => 2.3327,
                'category' => 'restaurant',
                'images' => [
                    'https://images.unsplash.com/photo-1515669097368-22e68427d265'
                ],
                'video_url' => 'https://www.youtube.com/watch?v=VVUv8XpB0c4',
                'address' => '172 Boulevard Saint-Germain, 75006 Paris',
                'phone' => '+33 1 45 48 55 26',
                'website' => 'https://cafedeflore.fr'
            ],
            [
                'name' => 'Jardin du Luxembourg',
                'description' => 'Jardin public situé dans le 6e arrondissement de Paris, créé en 1612 à la demande de Marie de Médicis.',
                'latitude' => 48.8462,
                'longitude' => 2.3372,
                'category' => 'park',
                'images' => [
                    'https://images.unsplash.com/photo-1521341057461-6eb5f40b07ab'
                ],
                'video_url' => null,
                'address' => '75006 Paris',
                'phone' => '+33 1 42 34 23 89',
                'website' => 'https://www.senat.fr/visite/jardin'
            ],
            [
                'name' => 'Galeries Lafayette',
                'description' => 'Grand magasin parisien situé boulevard Haussmann dans le 9e arrondissement.',
                'latitude' => 48.8738,
                'longitude' => 2.3320,
                'category' => 'shopping',
                'images' => [
                    'https://images.unsplash.com/photo-1540959733332-eab4deabeeaf'
                ],
                'video_url' => 'https://www.youtube.com/watch?v=HmVhZQeW8_M',
                'address' => '40 Boulevard Haussmann, 75009 Paris',
                'phone' => '+33 1 42 82 34 56',
                'website' => 'https://www.galerieslafayette.com'
            ],
            [
                'name' => 'Hôtel Ritz Paris',
                'description' => 'Hôtel de luxe situé place Vendôme, célèbre pour son bar Hemingway et son restaurant étoilé.',
                'latitude' => 48.8674,
                'longitude' => 2.3294,
                'category' => 'hotel',
                'images' => [
                    'https://images.unsplash.com/photo-1566073771259-6a8506099945'
                ],
                'video_url' => 'https://www.youtube.com/watch?v=oiKj0Z_Xnjc',
                'address' => '15 Place Vendôme, 75001 Paris',
                'phone' => '+33 1 43 16 30 70',
                'website' => 'https://www.ritzparis.com'
            ]
        ];

        foreach ($places as $placeData) {
            Place::create($placeData);
        }

        $this->info('6 lieux de test ont été créés avec succès !');
    }
}