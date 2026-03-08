<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Continent;

class AsiaCountriesSeeder extends Seeder
{
    public function run()
    {
        $asia = Continent::where('code', 'AS')->first();
        
        $countries = [
            [
                'name' => 'Chine',
                'code' => 'CHN',
                'iso2' => 'CN',
                'phone_code' => '+86',
                'capital' => 'Pékin',
                'currency' => 'Yuan',
                'currency_symbol' => '¥',
                'flag' => 'cn.svg',
                'latitude' => '35.8617',
                'longitude' => '104.1954',
                'description' => 'Pays le plus peuplé du monde',
                'population' => 1412000000,
                'area' => 9596961,
                'official_language' => 'Mandarin',
                'timezones' => ['UTC+08:00'],
                'region' => 'Asie de l\'Est',
                'continent_id' => $asia->id
            ],
            [
                'name' => 'Inde',
                'code' => 'IND',
                'iso2' => 'IN',
                'phone_code' => '+91',
                'capital' => 'New Delhi',
                'currency' => 'Roupie indienne',
                'currency_symbol' => '₹',
                'flag' => 'in.svg',
                'latitude' => '20.5937',
                'longitude' => '78.9629',
                'description' => 'Deuxième pays le plus peuplé',
                'population' => 1380000000,
                'area' => 3287263,
                'official_language' => 'Hindi, Anglais',
                'timezones' => ['UTC+05:30'],
                'region' => 'Asie du Sud',
                'continent_id' => $asia->id
            ],
            [
                'name' => 'Japon',
                'code' => 'JPN',
                'iso2' => 'JP',
                'phone_code' => '+81',
                'capital' => 'Tokyo',
                'currency' => 'Yen',
                'currency_symbol' => '¥',
                'flag' => 'jp.svg',
                'latitude' => '36.2048',
                'longitude' => '138.2529',
                'description' => 'Pays insulaire d\'Asie de l\'Est',
                'population' => 125000000,
                'area' => 377975,
                'official_language' => 'Japonais',
                'timezones' => ['UTC+09:00'],
                'region' => 'Asie de l\'Est',
                'continent_id' => $asia->id
            ],
            [
                'name' => 'Arabie Saoudite',
                'code' => 'SAU',
                'iso2' => 'SA',
                'phone_code' => '+966',
                'capital' => 'Riyad',
                'currency' => 'Riyal saoudien',
                'currency_symbol' => 'ر.س',
                'flag' => 'sa.svg',
                'latitude' => '23.8859',
                'longitude' => '45.0792',
                'description' => 'Plus grand pays du Moyen-Orient',
                'population' => 35000000,
                'area' => 2149690,
                'official_language' => 'Arabe',
                'timezones' => ['UTC+03:00'],
                'region' => 'Moyen-Orient',
                'continent_id' => $asia->id
            ],
            [
                'name' => 'Corée du Sud',
                'code' => 'KOR',
                'iso2' => 'KR',
                'phone_code' => '+82',
                'capital' => 'Séoul',
                'currency' => 'Won sud-coréen',
                'currency_symbol' => '₩',
                'flag' => 'kr.svg',
                'latitude' => '35.9078',
                'longitude' => '127.7669',
                'description' => 'Pays d\'Asie de l\'Est',
                'population' => 51700000,
                'area' => 100210,
                'official_language' => 'Coréen',
                'timezones' => ['UTC+09:00'],
                'region' => 'Asie de l\'Est',
                'continent_id' => $asia->id
            ],
            [
                'name' => 'Indonésie',
                'code' => 'IDN',
                'iso2' => 'ID',
                'phone_code' => '+62',
                'capital' => 'Jakarta',
                'currency' => 'Roupie indonésienne',
                'currency_symbol' => 'Rp',
                'flag' => 'id.svg',
                'latitude' => '-0.7893',
                'longitude' => '113.9213',
                'description' => 'Archipel d\'Asie du Sud-Est',
                'population' => 276000000,
                'area' => 1904569,
                'official_language' => 'Indonésien',
                'timezones' => ['UTC+07:00', 'UTC+08:00', 'UTC+09:00'],
                'region' => 'Asie du Sud-Est',
                'continent_id' => $asia->id
            ]
        ];

        foreach ($countries as $country) {
            Country::create($country);
        }
        
        $this->command->info(count($countries) . ' pays asiatiques créés avec succès!');
    }
}