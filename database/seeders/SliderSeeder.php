<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sliders = [
            [
                'name' => 'Bienvenue au Canada',
                'description' => 'Découvrez la beauté naturelle du Canada',
                'type' => 'image',
                'image_path' => 'sliders/canada-welcome.jpg',
                'order' => 1,
                'is_active' => true,
                'button_text' => 'Découvrir',
                'button_url' => '/destinations',
            ],
            [
                'name' => 'Aurores Boréales',
                'description' => 'Spectacle magique dans le ciel canadien',
                'type' => 'video',
                'video_type' => 'youtube',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'image_path' => 'sliders/aurora-thumbnail.jpg',
                'order' => 2,
                'is_active' => true,
                'button_text' => 'Regarder',
                'button_url' => '#',
            ],
            [
                'name' => 'Montagnes Rocheuses',
                'description' => 'Paysages à couper le souffle',
                'type' => 'image',
                'image_path' => 'sliders/rocky-mountains.jpg',
                'order' => 3,
                'is_active' => true,
                'button_text' => 'Explorer',
                'button_url' => '/montagnes',
            ],
            [
                'name' => 'Culture Autochtone',
                'description' => 'Rencontrez les premières nations du Canada',
                'type' => 'video',
                'video_type' => 'vimeo',
                'video_url' => 'https://vimeo.com/123456789',
                'image_path' => 'sliders/indigenous-culture.jpg',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Villes Canadiennes',
                'description' => 'Découvrez les métropoles dynamiques',
                'type' => 'image',
                'image_path' => 'sliders/canadian-cities.jpg',
                'order' => 5,
                'is_active' => false,
                'button_text' => 'Visiter',
                'button_url' => '/villes',
            ],
            [
                'name' => 'Aventure Hivernale',
                'description' => 'Ski, motoneige et sports d\'hiver',
                'type' => 'video',
                'video_type' => 'upload',
                'video_path' => 'sliders/winter-adventure.mp4',
                'image_path' => 'sliders/winter-thumbnail.jpg',
                'order' => 6,
                'is_active' => true,
                'button_text' => 'Réserver',
                'button_url' => '/activites',
            ],
        ];

        foreach ($sliders as $slider) {
            Slider::create($slider);
        }
    }
}