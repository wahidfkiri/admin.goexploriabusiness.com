<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Section;

class SectionSeeder extends Seeder
{
    public function run()
    {
        $sections = [
            [
                'name' => 'Hero Sections',
                'slug' => 'hero-sections',
                'description' => 'Sections hero pour pages d\'accueil',
                'icon' => 'fa-star',
                'order' => 1
            ],
            [
                'name' => 'Headers',
                'slug' => 'headers',
                'description' => 'En-têtes de navigation',
                'icon' => 'fa-bars',
                'order' => 2
            ],
            [
                'name' => 'Features',
                'slug' => 'features',
                'description' => 'Sections de fonctionnalités',
                'icon' => 'fa-th-large',
                'order' => 3
            ],
            [
                'name' => 'Testimonials',
                'slug' => 'testimonials',
                'description' => 'Avis clients et témoignages',
                'icon' => 'fa-comment',
                'order' => 4
            ],
            [
                'name' => 'Contact Forms',
                'slug' => 'contact-forms',
                'description' => 'Formulaires de contact',
                'icon' => 'fa-envelope',
                'order' => 5
            ],
            [
                'name' => 'Footers',
                'slug' => 'footers',
                'description' => 'Pieds de page',
                'icon' => 'fa-window-minimize',
                'order' => 6
            ],
            [
                'name' => 'Pricing',
                'slug' => 'pricing',
                'description' => 'Tableaux de prix',
                'icon' => 'fa-tag',
                'order' => 7
            ],
            [
                'name' => 'Teams',
                'slug' => 'teams',
                'description' => 'Présentation d\'équipes',
                'icon' => 'fa-users',
                'order' => 8
            ],
            [
                'name' => 'Blog',
                'slug' => 'blog',
                'description' => 'Sections pour articles de blog',
                'icon' => 'fa-blog',
                'order' => 9
            ],
            [
                'name' => 'Call to Action',
                'slug' => 'call-to-action',
                'description' => 'Sections d\'appel à l\'action',
                'icon' => 'fa-bullhorn',
                'order' => 10
            ]
        ];

        foreach ($sections as $section) {
            Section::create($section);
        }
    }
}