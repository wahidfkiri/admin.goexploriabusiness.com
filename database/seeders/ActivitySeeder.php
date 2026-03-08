<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity;
use App\Models\Categorie;
use Illuminate\Support\Facades\DB;

class ActivitySeeder extends Seeder
{
    public function run()
    {
        // Vider la table avant de la remplir
        DB::table('activities')->truncate();

        // Données de démonstration
        $activities = [
            ['name' => 'Randonnée en montagne', 'categorie_id' => 1],
            ['name' => 'Visite de musée', 'categorie_id' => 2],
            ['name' => 'Cours de cuisine', 'categorie_id' => 3],
            ['name' => 'Concert de jazz', 'categorie_id' => 4],
            ['name' => 'Séance de yoga', 'categorie_id' => 5],
            ['name' => 'Surf à la plage', 'categorie_id' => 1],
            ['name' => 'Atelier de poterie', 'categorie_id' => 3],
            ['name' => 'Observation des étoiles', 'categorie_id' => 1],
        ];

        foreach ($activities as $activity) {
            Activity::create([
                'name' => $activity['name'],
                'categorie_id' => $activity['categorie_id'],
            ]);
        }

        // Ou utiliser des données factices avec Factory (optionnel)
        // Activity::factory(20)->create();
    }
}