<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Continent;

class EuropeCountriesSeeder extends Seeder
{
    public function run()
    {
        $europe = Continent::where('code', 'EU')->first();
        
        $countries = [
            [
                'name' => 'France',
                'code' => 'FRA',
                'iso2' => 'FR',
                'phone_code' => '+33',
                'capital' => 'Paris',
                'currency' => 'Euro',
                'currency_symbol' => '€',
                'flag' => 'fr.svg',
                'latitude' => '46.2276',
                'longitude' => '2.2137',
                'description' => 'Pays d\'Europe occidentale',
                'population' => 67750000,
                'area' => 551695,
                'official_language' => 'Français',
                'timezones' => ['UTC+01:00'],
                'region' => 'Europe de l\'Ouest',
                'continent_id' => $europe->id
            ],
            [
                'name' => 'Allemagne',
                'code' => 'DEU',
                'iso2' => 'DE',
                'phone_code' => '+49',
                'capital' => 'Berlin',
                'currency' => 'Euro',
                'currency_symbol' => '€',
                'flag' => 'de.svg',
                'latitude' => '51.1657',
                'longitude' => '10.4515',
                'description' => 'Pays d\'Europe centrale',
                'population' => 83100000,
                'area' => 357022,
                'official_language' => 'Allemand',
                'timezones' => ['UTC+01:00'],
                'region' => 'Europe centrale',
                'continent_id' => $europe->id
            ],
            [
                'name' => 'Espagne',
                'code' => 'ESP',
                'iso2' => 'ES',
                'phone_code' => '+34',
                'capital' => 'Madrid',
                'currency' => 'Euro',
                'currency_symbol' => '€',
                'flag' => 'es.svg',
                'latitude' => '40.4637',
                'longitude' => '-3.7492',
                'description' => 'Pays du sud-ouest de l\'Europe',
                'population' => 47400000,
                'area' => 505990,
                'official_language' => 'Espagnol',
                'timezones' => ['UTC+01:00'],
                'region' => 'Europe du Sud',
                'continent_id' => $europe->id
            ],
            [
                'name' => 'Italie',
                'code' => 'ITA',
                'iso2' => 'IT',
                'phone_code' => '+39',
                'capital' => 'Rome',
                'currency' => 'Euro',
                'currency_symbol' => '€',
                'flag' => 'it.svg',
                'latitude' => '41.8719',
                'longitude' => '12.5674',
                'description' => 'Pays du sud de l\'Europe',
                'population' => 59500000,
                'area' => 301340,
                'official_language' => 'Italien',
                'timezones' => ['UTC+01:00'],
                'region' => 'Europe du Sud',
                'continent_id' => $europe->id
            ],
            [
                'name' => 'Royaume-Uni',
                'code' => 'GBR',
                'iso2' => 'GB',
                'phone_code' => '+44',
                'capital' => 'Londres',
                'currency' => 'Livre sterling',
                'currency_symbol' => '£',
                'flag' => 'gb.svg',
                'latitude' => '55.3781',
                'longitude' => '-3.4360',
                'description' => 'Pays insulaire d\'Europe du Nord-Ouest',
                'population' => 67200000,
                'area' => 243610,
                'official_language' => 'Anglais',
                'timezones' => ['UTC+00:00'],
                'region' => 'Europe du Nord',
                'continent_id' => $europe->id
            ],
            [
                'name' => 'Belgique',
                'code' => 'BEL',
                'iso2' => 'BE',
                'phone_code' => '+32',
                'capital' => 'Bruxelles',
                'currency' => 'Euro',
                'currency_symbol' => '€',
                'flag' => 'be.svg',
                'latitude' => '50.8503',
                'longitude' => '4.3517',
                'description' => 'Pays d\'Europe occidentale',
                'population' => 11500000,
                'area' => 30528,
                'official_language' => 'Néerlandais, Français, Allemand',
                'timezones' => ['UTC+01:00'],
                'region' => 'Europe de l\'Ouest',
                'continent_id' => $europe->id
            ],
            [
                'name' => 'Portugal',
                'code' => 'PRT',
                'iso2' => 'PT',
                'phone_code' => '+351',
                'capital' => 'Lisbonne',
                'currency' => 'Euro',
                'currency_symbol' => '€',
                'flag' => 'pt.svg',
                'latitude' => '39.3999',
                'longitude' => '-8.2245',
                'description' => 'Pays du sud-ouest de l\'Europe',
                'population' => 10200000,
                'area' => 92212,
                'official_language' => 'Portugais',
                'timezones' => ['UTC+00:00'],
                'region' => 'Europe du Sud',
                'continent_id' => $europe->id
            ],
            [
                'name' => 'Pays-Bas',
                'code' => 'NLD',
                'iso2' => 'NL',
                'phone_code' => '+31',
                'capital' => 'Amsterdam',
                'currency' => 'Euro',
                'currency_symbol' => '€',
                'flag' => 'nl.svg',
                'latitude' => '52.1326',
                'longitude' => '5.2913',
                'description' => 'Pays d\'Europe occidentale',
                'population' => 17400000,
                'area' => 41543,
                'official_language' => 'Néerlandais',
                'timezones' => ['UTC+01:00'],
                'region' => 'Europe de l\'Ouest',
                'continent_id' => $europe->id
            ],
            [
                'name' => 'Suisse',
                'code' => 'CHE',
                'iso2' => 'CH',
                'phone_code' => '+41',
                'capital' => 'Berne',
                'currency' => 'Franc suisse',
                'currency_symbol' => 'CHF',
                'flag' => 'ch.svg',
                'latitude' => '46.8182',
                'longitude' => '8.2275',
                'description' => 'Pays alpin d\'Europe centrale',
                'population' => 8700000,
                'area' => 41284,
                'official_language' => 'Allemand, Français, Italien, Romanche',
                'timezones' => ['UTC+01:00'],
                'region' => 'Europe centrale',
                'continent_id' => $europe->id
            ],
            [
                'name' => 'Suède',
                'code' => 'SWE',
                'iso2' => 'SE',
                'phone_code' => '+46',
                'capital' => 'Stockholm',
                'currency' => 'Couronne suédoise',
                'currency_symbol' => 'kr',
                'flag' => 'se.svg',
                'latitude' => '60.1282',
                'longitude' => '18.6435',
                'description' => 'Pays scandinave',
                'population' => 10400000,
                'area' => 450295,
                'official_language' => 'Suédois',
                'timezones' => ['UTC+01:00'],
                'region' => 'Europe du Nord',
                'continent_id' => $europe->id
            ]
        ];

        foreach ($countries as $country) {
            Country::create($country);
        }
        
        $this->command->info(count($countries) . ' pays européens créés avec succès!');
    }
}