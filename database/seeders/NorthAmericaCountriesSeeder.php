<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Continent;

class NorthAmericaCountriesSeeder extends Seeder
{
    public function run()
    {
        $na = Continent::where('code', 'NA')->first();
        
        $countries = [
            [
                'name' => 'États-Unis',
                'code' => 'USA',
                'iso2' => 'US',
                'phone_code' => '+1',
                'capital' => 'Washington D.C.',
                'currency' => 'Dollar américain',
                'currency_symbol' => '$',
                'flag' => 'us.svg',
                'latitude' => '37.0902',
                'longitude' => '-95.7129',
                'description' => 'Pays d\'Amérique du Nord',
                'population' => 331000000,
                'area' => 9833517,
                'official_language' => 'Anglais',
                'timezones' => ['UTC-05:00', 'UTC-06:00', 'UTC-07:00', 'UTC-08:00', 'UTC-09:00', 'UTC-10:00'],
                'region' => 'Amérique du Nord',
                'continent_id' => $na->id
            ],
            [
                'name' => 'Canada',
                'code' => 'CAN',
                'iso2' => 'CA',
                'phone_code' => '+1',
                'capital' => 'Ottawa',
                'currency' => 'Dollar canadien',
                'currency_symbol' => '$',
                'flag' => 'ca.svg',
                'latitude' => '56.1304',
                'longitude' => '-106.3468',
                'description' => 'Deuxième plus grand pays du monde',
                'population' => 38000000,
                'area' => 9984670,
                'official_language' => 'Anglais, Français',
                'timezones' => ['UTC-03:30', 'UTC-04:00', 'UTC-05:00', 'UTC-06:00', 'UTC-07:00'],
                'region' => 'Amérique du Nord',
                'continent_id' => $na->id
            ],
            [
                'name' => 'Mexique',
                'code' => 'MEX',
                'iso2' => 'MX',
                'phone_code' => '+52',
                'capital' => 'Mexico',
                'currency' => 'Peso mexicain',
                'currency_symbol' => '$',
                'flag' => 'mx.svg',
                'latitude' => '23.6345',
                'longitude' => '-102.5528',
                'description' => 'Pays d\'Amérique du Nord',
                'population' => 128000000,
                'area' => 1964375,
                'official_language' => 'Espagnol',
                'timezones' => ['UTC-06:00', 'UTC-07:00', 'UTC-08:00'],
                'region' => 'Amérique du Nord',
                'continent_id' => $na->id
            ]
        ];

        foreach ($countries as $country) {
            Country::create($country);
        }
        
        $this->command->info(count($countries) . ' pays d\'Amérique du Nord créés avec succès!');
    }
}