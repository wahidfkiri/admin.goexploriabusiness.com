<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CanadaGeoCompleteSeeder extends Seeder
{
    /**
     * Seed provinces, regions, villes, secteurs (data-only seeder).
     */
    public function run(): void
    {
        $this->call([
            CanadaGeoGeneratedSeeder::class,
        ]);
    }
}
