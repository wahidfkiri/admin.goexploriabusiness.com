<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CategorieType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MarketplaceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $marketplaceType = CategorieType::withTrashed()->firstOrCreate(
            ['slug' => Str::slug('Marketplace')],
            [
                'name' => 'Marketplace',
                'is_active' => true,
            ]
        );

        if ($marketplaceType->trashed()) {
            $marketplaceType->restore();
        }

        $marketplaceType->update([
            'name' => 'Marketplace',
            'is_active' => true,
        ]);

        $categories = [
            'Matériel de Bureaux',
            'Mini Maison a vendre avec terrain',
            'Nautiques',
            'Passeports Rabais au Québec',
            'Sports et Plein-Air',
        ];

        foreach ($categories as $name) {
            $slug = Str::slug($name);

            $category = Category::firstOrNew(['slug' => $slug]);
            $category->name = $name;
            $category->categorie_type_id = $marketplaceType->id;
            $category->is_active = true;

            if (!$category->exists) {
                $category->description = null;
            }

            $category->save();
        }

        $this->command?->info(count($categories) . ' categories Marketplace ont ete traitees avec succes.');
    }
}

