<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CategorieType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TourismeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tourismeType = CategorieType::withTrashed()->firstOrCreate(
            ['slug' => Str::slug('Tourisme')],
            [
                'name' => 'Tourisme',
                'is_active' => true,
            ]
        );

        if ($tourismeType->trashed()) {
            $tourismeType->restore();
        }

        $tourismeType->update([
            'name' => 'Tourisme',
            'is_active' => true,
        ]);

        $categories = [
            'Activités hivernales',
            'Activités nautiques',
            'Activités plein air',
            'Agrotourisme et terroir',
            'AlimentatIon Bio',
            'Art et culture',
            'Artistes et Artisans',
            'Attraits et divertissements',
            'Chasse et pêche',
            'Clubs sportifs et de loisirs',
            'Designers Québéçois',
            'Forfaits au Québec',
            'Hébergement',
            'Info Touristique Québec',
            'Location d\'équipements récréatifs',
            'Locations de véhicules récréatifs',
            'Magasinez',
            'Multi-média',
            'Parcs et Réserves',
            'Plans de Relance économique',
            'Restaurants et Alimentation',
            'Road Trip au Québec',
            'Santé et ressourcement',
            'Sports motorisés',
            'Transport',
            'Voyages et forfaits',
        ];

        foreach ($categories as $name) {
            $slug = Str::slug($name);

            $category = Category::firstOrNew(['slug' => $slug]);
            $category->name = $name;
            $category->categorie_type_id = $tourismeType->id;
            $category->is_active = true;

            if (!$category->exists) {
                $category->description = null;
            }

            $category->save();
        }

        $this->command?->info(count($categories) . ' categories Tourisme ont ete traitees avec succes.');
    }
}

