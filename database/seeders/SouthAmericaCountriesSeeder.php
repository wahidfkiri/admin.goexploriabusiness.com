<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Continent;

class SouthAmericaCountriesSeeder extends Seeder
{
    public function run()
    {
        $sa = Continent::where('code', 'SA')->first();
        
        $countries = [
            [
                'name' => 'Brésil',
                'code' => 'BRA',
                'iso2' => 'BR',
                'phone_code' => '+55',
                'capital' => 'Brasilia',
                'currency' => 'Real brésilien',
                'currency_symbol' => 'R$',
                'flag' => 'br.svg',
                'latitude' => '-14.2350',
                'longitude' => '-51.9253',
                'description' => 'Plus grand pays d\'Amérique du Sud',
                'population' => 213000000,
                'area' => 8515767,
                'official_language' => 'Portugais',
                'timezones' => ['UTC-02:00', 'UTC-03:00', 'UTC-04:00', 'UTC-05:00'],
                'region' => 'Amérique du Sud',
                'continent_id' => $sa->id
            ],
            [
                'name' => 'Argentine',
                'code' => 'ARG',
                'iso2' => 'AR',
                'phone_code' => '+54',
                'capital' => 'Buenos Aires',
                'currency' => 'Peso argentin',
                'currency_symbol' => '$',
                'flag' => 'ar.svg',
                'latitude' => '-38.4161',
                'longitude' => '-63.6167',
                'description' => 'Deuxième plus grand pays d\'Amérique du Sud',
                'population' => 45500000,
                'area' => 2780400,
                'official_language' => 'Espagnol',
                'timezones' => ['UTC-03:00'],
                'region' => 'Amérique du Sud',
                'continent_id' => $sa->id
            ],
            [
                'name' => 'Colombie',
                'code' => 'COL',
                'iso2' => 'CO',
                'phone_code' => '+57',
                'capital' => 'Bogota',
                'currency' => 'Peso colombien',
                'currency_symbol' => '$',
                'flag' => 'co.svg',
                'latitude' => '4.5709',
                'longitude' => '-74.2973',
                'description' => 'Pays du nord-ouest de l\'Amérique du Sud',
                'population' => 51000000,
                'area' => 1141748,
                'official_language' => 'Espagnol',
                'timezones' => ['UTC-05:00'],
                'region' => 'Amérique du Sud',
                'continent_id' => $sa->id
            ]
        ];

        foreach ($countries as $country) {
            Country::create($country);
        }
        
        $this->command->info(count($countries) . ' pays d\'Amérique du Sud créés avec succès!');
    }
}