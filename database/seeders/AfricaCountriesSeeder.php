<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Continent;

class AfricaCountriesSeeder extends Seeder
{
    public function run()
    {
        $africa = Continent::where('code', 'AF')->first();
        
        $countries = [
            [
                'name' => 'Maroc',
                'code' => 'MAR',
                'iso2' => 'MA',
                'phone_code' => '+212',
                'capital' => 'Rabat',
                'currency' => 'Dirham marocain',
                'currency_symbol' => 'د.م.',
                'flag' => 'ma.svg',
                'latitude' => '31.7917',
                'longitude' => '-7.0926',
                'description' => 'Royaume d\'Afrique du Nord',
                'population' => 37000000,
                'area' => 710850,
                'official_language' => 'Arabe',
                'timezones' => ['UTC+00:00'],
                'region' => 'Afrique du Nord',
                'continent_id' => $africa->id
            ],
            [
                'name' => 'Algérie',
                'code' => 'DZA',
                'iso2' => 'DZ',
                'phone_code' => '+213',
                'capital' => 'Alger',
                'currency' => 'Dinar algérien',
                'currency_symbol' => 'د.ج',
                'flag' => 'dz.svg',
                'latitude' => '28.0339',
                'longitude' => '1.6596',
                'description' => 'Plus grand pays d\'Afrique',
                'population' => 44000000,
                'area' => 2381741,
                'official_language' => 'Arabe',
                'timezones' => ['UTC+01:00'],
                'region' => 'Afrique du Nord',
                'continent_id' => $africa->id
            ],
            [
                'name' => 'Tunisie',
                'code' => 'TUN',
                'iso2' => 'TN',
                'phone_code' => '+216',
                'capital' => 'Tunis',
                'currency' => 'Dinar tunisien',
                'currency_symbol' => 'د.ت',
                'flag' => 'tn.svg',
                'latitude' => '33.8869',
                'longitude' => '9.5375',
                'description' => 'Pays d\'Afrique du Nord',
                'population' => 11900000,
                'area' => 163610,
                'official_language' => 'Arabe',
                'timezones' => ['UTC+01:00'],
                'region' => 'Afrique du Nord',
                'continent_id' => $africa->id
            ],
            [
                'name' => 'Sénégal',
                'code' => 'SEN',
                'iso2' => 'SN',
                'phone_code' => '+221',
                'capital' => 'Dakar',
                'currency' => 'Franc CFA',
                'currency_symbol' => 'CFA',
                'flag' => 'sn.svg',
                'latitude' => '14.4974',
                'longitude' => '-14.4524',
                'description' => 'Pays d\'Afrique de l\'Ouest',
                'population' => 17000000,
                'area' => 196722,
                'official_language' => 'Français',
                'timezones' => ['UTC+00:00'],
                'region' => 'Afrique de l\'Ouest',
                'continent_id' => $africa->id
            ],
            [
                'name' => 'Côte d\'Ivoire',
                'code' => 'CIV',
                'iso2' => 'CI',
                'phone_code' => '+225',
                'capital' => 'Yamoussoukro',
                'currency' => 'Franc CFA',
                'currency_symbol' => 'CFA',
                'flag' => 'ci.svg',
                'latitude' => '7.5400',
                'longitude' => '-5.5471',
                'description' => 'Pays d\'Afrique de l\'Ouest',
                'population' => 27000000,
                'area' => 322463,
                'official_language' => 'Français',
                'timezones' => ['UTC+00:00'],
                'region' => 'Afrique de l\'Ouest',
                'continent_id' => $africa->id
            ],
            [
                'name' => 'Nigeria',
                'code' => 'NGA',
                'iso2' => 'NG',
                'phone_code' => '+234',
                'capital' => 'Abuja',
                'currency' => 'Naira',
                'currency_symbol' => '₦',
                'flag' => 'ng.svg',
                'latitude' => '9.0820',
                'longitude' => '8.6753',
                'description' => 'Pays le plus peuplé d\'Afrique',
                'population' => 211000000,
                'area' => 923768,
                'official_language' => 'Anglais',
                'timezones' => ['UTC+01:00'],
                'region' => 'Afrique de l\'Ouest',
                'continent_id' => $africa->id
            ],
            [
                'name' => 'Égypte',
                'code' => 'EGY',
                'iso2' => 'EG',
                'phone_code' => '+20',
                'capital' => 'Le Caire',
                'currency' => 'Livre égyptienne',
                'currency_symbol' => '£',
                'flag' => 'eg.svg',
                'latitude' => '26.8206',
                'longitude' => '30.8025',
                'description' => 'Pays d\'Afrique du Nord',
                'population' => 104000000,
                'area' => 1001450,
                'official_language' => 'Arabe',
                'timezones' => ['UTC+02:00'],
                'region' => 'Afrique du Nord',
                'continent_id' => $africa->id
            ],
            [
                'name' => 'Afrique du Sud',
                'code' => 'ZAF',
                'iso2' => 'ZA',
                'phone_code' => '+27',
                'capital' => 'Pretoria',
                'currency' => 'Rand',
                'currency_symbol' => 'R',
                'flag' => 'za.svg',
                'latitude' => '-30.5595',
                'longitude' => '22.9375',
                'description' => 'Pays le plus au sud de l\'Afrique',
                'population' => 60000000,
                'area' => 1219090,
                'official_language' => '11 langues officielles',
                'timezones' => ['UTC+02:00'],
                'region' => 'Afrique australe',
                'continent_id' => $africa->id
            ],
            [
                'name' => 'Kenya',
                'code' => 'KEN',
                'iso2' => 'KE',
                'phone_code' => '+254',
                'capital' => 'Nairobi',
                'currency' => 'Shilling kényan',
                'currency_symbol' => 'KSh',
                'flag' => 'ke.svg',
                'latitude' => '-1.2864',
                'longitude' => '36.8172',
                'description' => 'Pays d\'Afrique de l\'Est',
                'population' => 54000000,
                'area' => 580367,
                'official_language' => 'Swahili, Anglais',
                'timezones' => ['UTC+03:00'],
                'region' => 'Afrique de l\'Est',
                'continent_id' => $africa->id
            ],
            [
                'name' => 'Mali',
                'code' => 'MLI',
                'iso2' => 'ML',
                'phone_code' => '+223',
                'capital' => 'Bamako',
                'currency' => 'Franc CFA',
                'currency_symbol' => 'CFA',
                'flag' => 'ml.svg',
                'latitude' => '17.5707',
                'longitude' => '-3.9962',
                'description' => 'Pays d\'Afrique de l\'Ouest',
                'population' => 21000000,
                'area' => 1240192,
                'official_language' => 'Français',
                'timezones' => ['UTC+00:00'],
                'region' => 'Afrique de l\'Ouest',
                'continent_id' => $africa->id
            ]
        ];

        foreach ($countries as $country) {
            Country::create($country);
        }
        
        $this->command->info(count($countries) . ' pays africains créés avec succès!');
    }
}