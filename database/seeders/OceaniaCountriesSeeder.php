<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Continent;

class OceaniaCountriesSeeder extends Seeder
{
    public function run()
    {
        $oceania = Continent::where('code', 'OC')->first();
        
        $countries = [
            [
                'name' => 'Australie',
                'code' => 'AUS',
                'iso2' => 'AU',
                'phone_code' => '+61',
                'capital' => 'Canberra',
                'currency' => 'Dollar australien',
                'currency_symbol' => 'A$',
                'flag' => 'au.svg',
                'latitude' => '-25.2744',
                'longitude' => '133.7751',
                'description' => 'Plus grand pays d\'Océanie',
                'population' => 25600000,
                'area' => 7692024,
                'official_language' => 'Anglais',
                'timezones' => ['UTC+08:00', 'UTC+09:30', 'UTC+10:00'],
                'region' => 'Océanie',
                'continent_id' => $oceania->id
            ],
            [
                'name' => 'Nouvelle-Zélande',
                'code' => 'NZL',
                'iso2' => 'NZ',
                'phone_code' => '+64',
                'capital' => 'Wellington',
                'currency' => 'Dollar néo-zélandais',
                'currency_symbol' => 'NZ$',
                'flag' => 'nz.svg',
                'latitude' => '-40.9006',
                'longitude' => '174.8860',
                'description' => 'Pays insulaire d\'Océanie',
                'population' => 5000000,
                'area' => 268021,
                'official_language' => 'Anglais, Maori',
                'timezones' => ['UTC+12:00', 'UTC+13:00'],
                'region' => 'Océanie',
                'continent_id' => $oceania->id
            ]
        ];

        foreach ($countries as $country) {
            Country::create($country);
        }
        
        $this->command->info(count($countries) . ' pays d\'Océanie créés avec succès!');
    }
}