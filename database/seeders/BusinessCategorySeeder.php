<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CategorieType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BusinessCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $businessType = CategorieType::withTrashed()->firstOrCreate(
            ['slug' => Str::slug('Business')],
            [
                'name' => 'Business',
                'is_active' => true,
            ]
        );

        if ($businessType->trashed()) {
            $businessType->restore();
        }

        $businessType->update([
            'name' => 'Business',
            'is_active' => true,
        ]);

        $categories = [
            'Agricoles',
            'Appareils électroniques',
            'Associations',
            'Automobiles',
            'Commerces à vendre',
            'Design Réno Déco Maison',
            'Developpement Économique',
            'Établissements d\'enseignements',
            'Go Exploria Business',
            'Go Prêt Argent',
            'Habitation et services',
            'industries et commerces',
            'Multi-Média',
            'Organismes et Services Sociaux',
            'Professionnels',
            'Projets immobiliers',
            'Transport Services',
            'Travailleurs autonomes',
            'Villes et Municipalités',
        ];

        foreach ($categories as $name) {
            $slug = Str::slug($name);

            $category = Category::firstOrNew(['slug' => $slug]);
            $category->name = $name;
            $category->categorie_type_id = $businessType->id;
            $category->is_active = true;

            if (!$category->exists) {
                $category->description = null;
            }

            $category->save();
        }

        $this->command?->info(count($categories) . ' categories Business ont ete traitees avec succes.');
    }
}

