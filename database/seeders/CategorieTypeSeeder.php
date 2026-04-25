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
        $typeNames = [
            'Business',
            'Local',
            'Affaire',
            'Prime Time',
            'Web TV',
            'Photos',
            'Certificats Cadeaux Quebec',
            'Marketplace',
            'Book Direct',
            'Espace Entreprise',
            'Billets Avion',
            'Location Vehicule',
        ];

        foreach ($typeNames as $typeName) {
            $slug = Str::slug($typeName);

            $type = CategorieType::withTrashed()->firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $typeName,
                    'is_active' => true,
                ]
            );

            if ($type->trashed()) {
                $type->restore();
            }

            $type->update([
                'name' => $typeName,
                'is_active' => true,
            ]);
        }

        $this->command?->info(count($typeNames) . ' types de categories ont ete traites avec succes.');
    }
}

