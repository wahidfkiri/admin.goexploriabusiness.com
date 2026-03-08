<?php 

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Restaurant & Alimentation',
            'E-commerce & Vente',
            'Entreprises & Services',
            'Santé & Bien-être',
            'Éducation & Formation',
            'Voyage & Tourisme',
            'Créatifs & Médias',
            'Freelance & Personnel',
            'Institutions & ONG',
            'Événementiel & Divertissement',
            'Transport & Logistique',
            'Technologie & SaaS',
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => Str::slug($category)],
                [
                    'name' => $category,
                    'description' => null,
                    'is_active' => true,
                ]
            );
        }
    }
}
