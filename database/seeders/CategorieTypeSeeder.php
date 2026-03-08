<?php

namespace Database\Seeders;

use App\Models\CategorieType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorieTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Business',
                'slug' => Str::slug('Business'),
                'is_active' => true,
            ],
            [
                'name' => 'Local',
                'slug' => Str::slug('Local'),
                'is_active' => true,
            ],
            [
                'name' => 'Affaire',
                'slug' => Str::slug('Affaire'),
                'is_active' => true,
            ],
            [
                'name' => 'Prime Time',
                'slug' => Str::slug('Prime Time'),
                'is_active' => true,
            ],
            [
                'name' => 'Web TV',
                'slug' => Str::slug('Web TV'),
                'is_active' => true,
            ],
            [
                'name' => 'Photos',
                'slug' => Str::slug('Photos'),
                'is_active' => true,
            ],
            [
                'name' => 'Certificats Cadeaux Québec',
                'slug' => Str::slug('Certificats Cadeaux Québec'),
                'is_active' => true,
            ],
            [
                'name' => 'Marketplace',
                'slug' => Str::slug('Marketplace'),
                'is_active' => true,
            ],
            [
                'name' => 'Book Direct',
                'slug' => Str::slug('Book Direct'),
                'is_active' => true,
            ],
        ];

        foreach ($types as $type) {
            CategorieType::create($type);
        }

        $this->command->info(count($types) . ' types de catégories ont été créés avec succès !');
    }
}