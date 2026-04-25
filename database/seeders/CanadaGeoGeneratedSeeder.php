<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CanadaGeoGeneratedSeeder extends Seeder
{
    public function run(): void
    {
        DB::beginTransaction();

        try {
            $now = Carbon::now();

            $continentId = $this->upsertContinent($now);
            $countryId = $this->upsertCountry($continentId, $now);

            $provinceIds = $this->seedProvinces($countryId, $now);
            $regionIds = $this->seedRegions($provinceIds, $now);
            $secteurIds = $this->seedSecteurs($regionIds, $now);
            $cityCount = $this->seedVilles($countryId, $provinceIds, $regionIds, $secteurIds, $now);

            DB::commit();

            $this->command?->info('Seeder CanadaGeoGeneratedSeeder termine (data-only).');
            $this->command?->line('- Provinces/Territoires: ' . count($provinceIds));
            $this->command?->line('- Regions: ' . count($regionIds));
            $this->command?->line('- Secteurs: ' . count($secteurIds));
            $this->command?->line('- Villes: ' . $cityCount);
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->command?->error('Erreur CanadaGeoGeneratedSeeder: ' . $e->getMessage());
        }
    }

    private function upsertContinent(Carbon $now): int
    {
        DB::table('continents')->updateOrInsert(
            ['code' => 'NA'],
            [
                'name' => 'Amerique du Nord',
                'description' => 'Continent nord-americain.',
                'population' => 602000000,
                'area' => 24709000,
                'countries_count' => 23,
                'languages' => json_encode(['anglais', 'francais', 'espagnol'], JSON_UNESCAPED_UNICODE),
                'is_active' => 1,
                'updated_at' => $now,
                'created_at' => DB::raw('COALESCE(created_at, NOW())'),
            ]
        );

        return (int) DB::table('continents')->where('code', 'NA')->value('id');
    }

    private function upsertCountry(int $continentId, Carbon $now): int
    {
        DB::table('countries')->updateOrInsert(
            ['code' => 'CAN'],
            [
                'name' => 'Canada',
                'iso2' => 'CA',
                'phone_code' => '+1',
                'capital' => 'Ottawa',
                'currency' => 'Dollar canadien',
                'currency_symbol' => '$',
                'latitude' => '56.1304',
                'longitude' => '-106.3468',
                'description' => 'Pays d\'Amerique du Nord.',
                'population' => 41000000,
                'area' => 9984670,
                'official_language' => 'Anglais, Francais',
                'timezones' => json_encode(['UTC-08:00', 'UTC-07:00', 'UTC-06:00', 'UTC-05:00', 'UTC-04:00', 'UTC-03:30']),
                'region' => 'Amerique du Nord',
                'continent_id' => $continentId,
                'is_active' => 1,
                'updated_at' => $now,
                'created_at' => DB::raw('COALESCE(created_at, NOW())'),
            ]
        );

        return (int) DB::table('countries')->where('code', 'CAN')->value('id');
    }

    private function seedProvinces(int $countryId, Carbon $now): array
    {
        $provinces = [
            ['code' => 'AB', 'name' => 'Alberta', 'capital' => 'Edmonton', 'largest_city' => 'Calgary', 'official_language' => 'Anglais', 'area_rank' => '4', 'population' => 4800000, 'area' => 661848, 'timezone' => 'UTC-07:00', 'latitude' => '53.9333', 'longitude' => '-116.5765'],
            ['code' => 'BC', 'name' => 'Colombie-Britannique', 'capital' => 'Victoria', 'largest_city' => 'Vancouver', 'official_language' => 'Anglais', 'area_rank' => '5', 'population' => 5600000, 'area' => 944735, 'timezone' => 'UTC-08:00', 'latitude' => '53.7267', 'longitude' => '-127.6476'],
            ['code' => 'MB', 'name' => 'Manitoba', 'capital' => 'Winnipeg', 'largest_city' => 'Winnipeg', 'official_language' => 'Anglais', 'area_rank' => '8', 'population' => 1500000, 'area' => 647797, 'timezone' => 'UTC-06:00', 'latitude' => '53.7609', 'longitude' => '-98.8139'],
            ['code' => 'NB', 'name' => 'Nouveau-Brunswick', 'capital' => 'Fredericton', 'largest_city' => 'Moncton', 'official_language' => 'Anglais, Francais', 'area_rank' => '11', 'population' => 850000, 'area' => 72908, 'timezone' => 'UTC-04:00', 'latitude' => '46.5653', 'longitude' => '-66.4619'],
            ['code' => 'NL', 'name' => 'Terre-Neuve-et-Labrador', 'capital' => 'Saint-Jean de Terre-Neuve', 'largest_city' => 'Saint-Jean de Terre-Neuve', 'official_language' => 'Anglais', 'area_rank' => '10', 'population' => 540000, 'area' => 405212, 'timezone' => 'UTC-03:30', 'latitude' => '53.1355', 'longitude' => '-57.6604'],
            ['code' => 'NS', 'name' => 'Nouvelle-Ecosse', 'capital' => 'Halifax', 'largest_city' => 'Halifax', 'official_language' => 'Anglais', 'area_rank' => '12', 'population' => 1080000, 'area' => 55284, 'timezone' => 'UTC-04:00', 'latitude' => '44.6819', 'longitude' => '-63.7443'],
            ['code' => 'ON', 'name' => 'Ontario', 'capital' => 'Toronto', 'largest_city' => 'Toronto', 'official_language' => 'Anglais, Francais', 'area_rank' => '2', 'population' => 16000000, 'area' => 1076395, 'timezone' => 'UTC-05:00', 'latitude' => '51.2538', 'longitude' => '-85.3232'],
            ['code' => 'PE', 'name' => 'Ile-du-Prince-Edouard', 'capital' => 'Charlottetown', 'largest_city' => 'Charlottetown', 'official_language' => 'Anglais', 'area_rank' => '13', 'population' => 180000, 'area' => 5660, 'timezone' => 'UTC-04:00', 'latitude' => '46.5107', 'longitude' => '-63.4168'],
            ['code' => 'QC', 'name' => 'Quebec', 'capital' => 'Quebec', 'largest_city' => 'Montreal', 'official_language' => 'Francais', 'area_rank' => '1', 'population' => 9100000, 'area' => 1542056, 'timezone' => 'UTC-05:00', 'latitude' => '52.9399', 'longitude' => '-73.5491'],
            ['code' => 'SK', 'name' => 'Saskatchewan', 'capital' => 'Regina', 'largest_city' => 'Saskatoon', 'official_language' => 'Anglais', 'area_rank' => '7', 'population' => 1240000, 'area' => 651036, 'timezone' => 'UTC-06:00', 'latitude' => '52.9399', 'longitude' => '-106.4509'],
            ['code' => 'NT', 'name' => 'Territoires du Nord-Ouest', 'capital' => 'Yellowknife', 'largest_city' => 'Yellowknife', 'official_language' => 'Multilingue', 'area_rank' => '3', 'population' => 45000, 'area' => 1346106, 'timezone' => 'UTC-07:00', 'latitude' => '64.8255', 'longitude' => '-124.8457'],
            ['code' => 'NU', 'name' => 'Nunavut', 'capital' => 'Iqaluit', 'largest_city' => 'Iqaluit', 'official_language' => 'Inuktitut, Anglais, Francais', 'area_rank' => '0', 'population' => 41000, 'area' => 2093190, 'timezone' => 'UTC-05:00', 'latitude' => '70.2998', 'longitude' => '-83.1076'],
            ['code' => 'YT', 'name' => 'Yukon', 'capital' => 'Whitehorse', 'largest_city' => 'Whitehorse', 'official_language' => 'Anglais, Francais', 'area_rank' => '9', 'population' => 46000, 'area' => 482443, 'timezone' => 'UTC-07:00', 'latitude' => '64.2823', 'longitude' => '-135.0000'],
        ];

        $ids = [];
        foreach ($provinces as $p) {
            DB::table('provinces')->updateOrInsert(
                ['code' => $p['code'], 'country_id' => $countryId],
                [
                    'name' => $p['name'],
                    'capital' => $p['capital'],
                    'largest_city' => $p['largest_city'],
                    'official_language' => $p['official_language'],
                    'area_rank' => $p['area_rank'],
                    'population' => $p['population'],
                    'area' => $p['area'],
                    'timezone' => $p['timezone'],
                    'description' => $p['name'] . ' - subdivision administrative du Canada.',
                    'latitude' => $p['latitude'],
                    'longitude' => $p['longitude'],
                    'is_active' => 1,
                    'updated_at' => $now,
                    'created_at' => DB::raw('COALESCE(created_at, NOW())'),
                ]
            );

            $ids[$p['code']] = (int) DB::table('provinces')
                ->where('code', $p['code'])
                ->where('country_id', $countryId)
                ->value('id');
        }

        return $ids;
    }

    private function seedRegions(array $provinceIds, Carbon $now): array
    {
        $regions = [
            ['province' => 'AB', 'code' => 'AB-CAL', 'name' => 'Region de Calgary', 'capital' => 'Calgary', 'largest_city' => 'Calgary', 'classification' => 'Region metropolitaine', 'population' => 1700000, 'area' => 5107],
            ['province' => 'AB', 'code' => 'AB-EDM', 'name' => 'Region d Edmonton', 'capital' => 'Edmonton', 'largest_city' => 'Edmonton', 'classification' => 'Region metropolitaine', 'population' => 1500000, 'area' => 9416],
            ['province' => 'AB', 'code' => 'AB-NOR', 'name' => 'Nord de l Alberta', 'capital' => 'Fort McMurray', 'largest_city' => 'Fort McMurray', 'classification' => 'Region economique', 'population' => 160000, 'area' => 250000],
            ['province' => 'BC', 'code' => 'BC-MVR', 'name' => 'Metro Vancouver', 'capital' => 'Vancouver', 'largest_city' => 'Vancouver', 'classification' => 'Region metropolitaine', 'population' => 2800000, 'area' => 2883],
            ['province' => 'BC', 'code' => 'BC-VIS', 'name' => 'Ile de Vancouver', 'capital' => 'Victoria', 'largest_city' => 'Victoria', 'classification' => 'Region insulaire', 'population' => 900000, 'area' => 31285],
            ['province' => 'BC', 'code' => 'BC-OKN', 'name' => 'Okanagan', 'capital' => 'Kelowna', 'largest_city' => 'Kelowna', 'classification' => 'Region economique', 'population' => 430000, 'area' => 62276],
            ['province' => 'BC', 'code' => 'BC-NOR', 'name' => 'Nord de la Colombie-Britannique', 'capital' => 'Prince George', 'largest_city' => 'Prince George', 'classification' => 'Region economique', 'population' => 290000, 'area' => 500000],
            ['province' => 'MB', 'code' => 'MB-WPG', 'name' => 'Region de Winnipeg', 'capital' => 'Winnipeg', 'largest_city' => 'Winnipeg', 'classification' => 'Region metropolitaine', 'population' => 900000, 'area' => 5306],
            ['province' => 'MB', 'code' => 'MB-NOR', 'name' => 'Nord du Manitoba', 'capital' => 'Thompson', 'largest_city' => 'Thompson', 'classification' => 'Region economique', 'population' => 90000, 'area' => 396000],
            ['province' => 'NB', 'code' => 'NB-SWE', 'name' => 'Sud-Ouest du Nouveau-Brunswick', 'capital' => 'Saint John', 'largest_city' => 'Moncton', 'classification' => 'Region economique', 'population' => 420000, 'area' => 28000],
            ['province' => 'NB', 'code' => 'NB-CAP', 'name' => 'Region de la Capitale', 'capital' => 'Fredericton', 'largest_city' => 'Fredericton', 'classification' => 'Region economique', 'population' => 150000, 'area' => 12000],
            ['province' => 'NL', 'code' => 'NL-AVA', 'name' => 'Peninsule d Avalon', 'capital' => 'Saint-Jean de Terre-Neuve', 'largest_city' => 'Saint-Jean de Terre-Neuve', 'classification' => 'Region economique', 'population' => 280000, 'area' => 21000],
            ['province' => 'NL', 'code' => 'NL-LAB', 'name' => 'Labrador', 'capital' => 'Happy Valley-Goose Bay', 'largest_city' => 'Happy Valley-Goose Bay', 'classification' => 'Region nordique', 'population' => 28000, 'area' => 294330],
            ['province' => 'NS', 'code' => 'NS-HFX', 'name' => 'Region de Halifax', 'capital' => 'Halifax', 'largest_city' => 'Halifax', 'classification' => 'Region metropolitaine', 'population' => 510000, 'area' => 5490],
            ['province' => 'NS', 'code' => 'NS-CBR', 'name' => 'Ile du Cap-Breton', 'capital' => 'Sydney', 'largest_city' => 'Sydney', 'classification' => 'Region insulaire', 'population' => 125000, 'area' => 10155],
            ['province' => 'NS', 'code' => 'NS-VAL', 'name' => 'Vallee de l Annapolis', 'capital' => 'Kentville', 'largest_city' => 'New Minas', 'classification' => 'Region economique', 'population' => 160000, 'area' => 8500],
            ['province' => 'ON', 'code' => 'ON-GTA', 'name' => 'Grand Toronto', 'capital' => 'Toronto', 'largest_city' => 'Toronto', 'classification' => 'Region metropolitaine', 'population' => 7000000, 'area' => 7124],
            ['province' => 'ON', 'code' => 'ON-OTT', 'name' => 'Region d Ottawa', 'capital' => 'Ottawa', 'largest_city' => 'Ottawa', 'classification' => 'Region metropolitaine', 'population' => 1500000, 'area' => 6279],
            ['province' => 'ON', 'code' => 'ON-SWO', 'name' => 'Sud-Ouest de l Ontario', 'capital' => 'London', 'largest_city' => 'London', 'classification' => 'Region economique', 'population' => 2800000, 'area' => 102000],
            ['province' => 'ON', 'code' => 'ON-NOR', 'name' => 'Nord de l Ontario', 'capital' => 'Thunder Bay', 'largest_city' => 'Sudbury', 'classification' => 'Region nordique', 'population' => 780000, 'area' => 802000],
            ['province' => 'PE', 'code' => 'PE-QUE', 'name' => 'Region de Queens', 'capital' => 'Charlottetown', 'largest_city' => 'Charlottetown', 'classification' => 'Comte', 'population' => 90000, 'area' => 1980],
            ['province' => 'PE', 'code' => 'PE-PRI', 'name' => 'Region de Prince', 'capital' => 'Summerside', 'largest_city' => 'Summerside', 'classification' => 'Comte', 'population' => 50000, 'area' => 2000],
            ['province' => 'QC', 'code' => 'QC-01', 'name' => 'Bas-Saint-Laurent', 'capital' => 'Rimouski', 'largest_city' => 'Rimouski', 'classification' => 'Region administrative', 'population' => 199000, 'area' => 22232],
            ['province' => 'QC', 'code' => 'QC-02', 'name' => 'Saguenay-Lac-Saint-Jean', 'capital' => 'Saguenay', 'largest_city' => 'Saguenay', 'classification' => 'Region administrative', 'population' => 276000, 'area' => 95694],
            ['province' => 'QC', 'code' => 'QC-03', 'name' => 'Capitale-Nationale', 'capital' => 'Quebec', 'largest_city' => 'Quebec', 'classification' => 'Region administrative', 'population' => 780000, 'area' => 18639],
            ['province' => 'QC', 'code' => 'QC-04', 'name' => 'Mauricie', 'capital' => 'Trois-Rivieres', 'largest_city' => 'Trois-Rivieres', 'classification' => 'Region administrative', 'population' => 275000, 'area' => 35152],
            ['province' => 'QC', 'code' => 'QC-05', 'name' => 'Estrie', 'capital' => 'Sherbrooke', 'largest_city' => 'Sherbrooke', 'classification' => 'Region administrative', 'population' => 360000, 'area' => 10495],
            ['province' => 'QC', 'code' => 'QC-06', 'name' => 'Montreal', 'capital' => 'Montreal', 'largest_city' => 'Montreal', 'classification' => 'Region administrative', 'population' => 2100000, 'area' => 500],
            ['province' => 'QC', 'code' => 'QC-07', 'name' => 'Outaouais', 'capital' => 'Gatineau', 'largest_city' => 'Gatineau', 'classification' => 'Region administrative', 'population' => 430000, 'area' => 30787],
            ['province' => 'QC', 'code' => 'QC-08', 'name' => 'Abitibi-Temiscamingue', 'capital' => 'Rouyn-Noranda', 'largest_city' => 'Rouyn-Noranda', 'classification' => 'Region administrative', 'population' => 150000, 'area' => 57638],
            ['province' => 'QC', 'code' => 'QC-09', 'name' => 'Cote-Nord', 'capital' => 'Baie-Comeau', 'largest_city' => 'Sept-Iles', 'classification' => 'Region administrative', 'population' => 93000, 'area' => 247655],
            ['province' => 'QC', 'code' => 'QC-10', 'name' => 'Nord-du-Quebec', 'capital' => 'Chibougamau', 'largest_city' => 'Chibougamau', 'classification' => 'Region administrative', 'population' => 46000, 'area' => 747161],
            ['province' => 'QC', 'code' => 'QC-11', 'name' => 'Gaspesie-Iles-de-la-Madeleine', 'capital' => 'Gaspe', 'largest_city' => 'Gaspe', 'classification' => 'Region administrative', 'population' => 91000, 'area' => 78587],
            ['province' => 'QC', 'code' => 'QC-12', 'name' => 'Chaudiere-Appalaches', 'capital' => 'Levis', 'largest_city' => 'Levis', 'classification' => 'Region administrative', 'population' => 450000, 'area' => 15410],
            ['province' => 'QC', 'code' => 'QC-13', 'name' => 'Laval', 'capital' => 'Laval', 'largest_city' => 'Laval', 'classification' => 'Region administrative', 'population' => 450000, 'area' => 247],
            ['province' => 'QC', 'code' => 'QC-14', 'name' => 'Lanaudiere', 'capital' => 'Joliette', 'largest_city' => 'Terrebonne', 'classification' => 'Region administrative', 'population' => 560000, 'area' => 12673],
            ['province' => 'QC', 'code' => 'QC-15', 'name' => 'Laurentides', 'capital' => 'Saint-Jerome', 'largest_city' => 'Saint-Jerome', 'classification' => 'Region administrative', 'population' => 650000, 'area' => 22826],
            ['province' => 'QC', 'code' => 'QC-16', 'name' => 'Monteregie', 'capital' => 'Longueuil', 'largest_city' => 'Longueuil', 'classification' => 'Region administrative', 'population' => 1600000, 'area' => 11453],
            ['province' => 'QC', 'code' => 'QC-17', 'name' => 'Centre-du-Quebec', 'capital' => 'Drummondville', 'largest_city' => 'Drummondville', 'classification' => 'Region administrative', 'population' => 260000, 'area' => 6942],
            ['province' => 'SK', 'code' => 'SK-SAS', 'name' => 'Region de Saskatoon', 'capital' => 'Saskatoon', 'largest_city' => 'Saskatoon', 'classification' => 'Region economique', 'population' => 420000, 'area' => 31800],
            ['province' => 'SK', 'code' => 'SK-REG', 'name' => 'Region de Regina', 'capital' => 'Regina', 'largest_city' => 'Regina', 'classification' => 'Region economique', 'population' => 290000, 'area' => 24000],
            ['province' => 'SK', 'code' => 'SK-NOR', 'name' => 'Nord de la Saskatchewan', 'capital' => 'Prince Albert', 'largest_city' => 'Prince Albert', 'classification' => 'Region nordique', 'population' => 140000, 'area' => 400000],
            ['province' => 'NT', 'code' => 'NT-NSL', 'name' => 'North Slave', 'capital' => 'Yellowknife', 'largest_city' => 'Yellowknife', 'classification' => 'Region territoriale', 'population' => 22000, 'area' => 184000],
            ['province' => 'NT', 'code' => 'NT-SSL', 'name' => 'South Slave', 'capital' => 'Hay River', 'largest_city' => 'Hay River', 'classification' => 'Region territoriale', 'population' => 8000, 'area' => 321000],
            ['province' => 'NT', 'code' => 'NT-INV', 'name' => 'Region d Inuvik', 'capital' => 'Inuvik', 'largest_city' => 'Inuvik', 'classification' => 'Region territoriale', 'population' => 9000, 'area' => 515000],
            ['province' => 'NU', 'code' => 'NU-QIK', 'name' => 'Qikiqtaaluk', 'capital' => 'Iqaluit', 'largest_city' => 'Iqaluit', 'classification' => 'Region territoriale', 'population' => 21000, 'area' => 1039000],
            ['province' => 'NU', 'code' => 'NU-KIV', 'name' => 'Kivalliq', 'capital' => 'Rankin Inlet', 'largest_city' => 'Rankin Inlet', 'classification' => 'Region territoriale', 'population' => 11000, 'area' => 444621],
            ['province' => 'NU', 'code' => 'NU-KIT', 'name' => 'Kitikmeot', 'capital' => 'Cambridge Bay', 'largest_city' => 'Cambridge Bay', 'classification' => 'Region territoriale', 'population' => 8000, 'area' => 208000],
            ['province' => 'YT', 'code' => 'YT-WHI', 'name' => 'Region de Whitehorse', 'capital' => 'Whitehorse', 'largest_city' => 'Whitehorse', 'classification' => 'Region territoriale', 'population' => 34000, 'area' => 416000],
            ['province' => 'YT', 'code' => 'YT-KLO', 'name' => 'Klondike', 'capital' => 'Dawson', 'largest_city' => 'Dawson', 'classification' => 'Region historique', 'population' => 2500, 'area' => 70000],
        ];

        $ids = [];
        foreach ($regions as $r) {
            $provinceId = $provinceIds[$r['province']] ?? null;
            if (!$provinceId) {
                continue;
            }

            DB::table('regions')->updateOrInsert(
                ['code' => $r['code'], 'province_id' => $provinceId],
                [
                    'name' => $r['name'],
                    'capital' => $r['capital'],
                    'largest_city' => $r['largest_city'],
                    'classification' => $r['classification'],
                    'population' => $r['population'],
                    'area' => $r['area'],
                    'municipalities_count' => null,
                    'timezone' => null,
                    'description' => $r['name'] . ' - subdivision regionale au Canada.',
                    'latitude' => null,
                    'longitude' => null,
                    'is_active' => 1,
                    'updated_at' => $now,
                    'created_at' => DB::raw('COALESCE(created_at, NOW())'),
                ]
            );

            $ids[$r['code']] = (int) DB::table('regions')->where('code', $r['code'])->where('province_id', $provinceId)->value('id');
        }

        return $ids;
    }

    private function seedSecteurs(array $regionIds, Carbon $now): array
    {
        $secteurs = [
            ['region' => 'QC-06', 'code' => 'MTL-VIM', 'name' => 'Ville-Marie', 'classification' => 'Arrondissement'],
            ['region' => 'QC-06', 'code' => 'MTL-PLT', 'name' => 'Le Plateau-Mont-Royal', 'classification' => 'Arrondissement'],
            ['region' => 'QC-06', 'code' => 'MTL-ROS', 'name' => 'Rosemont-La Petite-Patrie', 'classification' => 'Arrondissement'],
            ['region' => 'QC-06', 'code' => 'MTL-VER', 'name' => 'Verdun', 'classification' => 'Arrondissement'],
            ['region' => 'QC-06', 'code' => 'MTL-CDN', 'name' => 'Cote-des-Neiges-Notre-Dame-de-Grace', 'classification' => 'Arrondissement'],
            ['region' => 'QC-03', 'code' => 'QBC-VIE', 'name' => 'Vieux-Quebec', 'classification' => 'Quartier'],
            ['region' => 'QC-03', 'code' => 'QBC-SFO', 'name' => 'Sainte-Foy', 'classification' => 'Quartier'],
            ['region' => 'ON-GTA', 'code' => 'TOR-DWT', 'name' => 'Downtown Toronto', 'classification' => 'District'],
            ['region' => 'ON-GTA', 'code' => 'TOR-NYK', 'name' => 'North York', 'classification' => 'District'],
            ['region' => 'ON-GTA', 'code' => 'TOR-SCB', 'name' => 'Scarborough', 'classification' => 'District'],
            ['region' => 'BC-MVR', 'code' => 'VAN-DWT', 'name' => 'Downtown Vancouver', 'classification' => 'District'],
            ['region' => 'BC-MVR', 'code' => 'VAN-KIT', 'name' => 'Kitsilano', 'classification' => 'District'],
            ['region' => 'AB-CAL', 'code' => 'CAL-DWT', 'name' => 'Downtown Calgary', 'classification' => 'District'],
            ['region' => 'AB-CAL', 'code' => 'CAL-BLT', 'name' => 'Beltline', 'classification' => 'District'],
            ['region' => 'AB-EDM', 'code' => 'EDM-DWT', 'name' => 'Downtown Edmonton', 'classification' => 'District'],
            ['region' => 'AB-EDM', 'code' => 'EDM-OST', 'name' => 'Old Strathcona', 'classification' => 'District'],
            ['region' => 'ON-OTT', 'code' => 'OTT-BWM', 'name' => 'ByWard Market', 'classification' => 'District'],
            ['region' => 'ON-OTT', 'code' => 'OTT-KAN', 'name' => 'Kanata', 'classification' => 'District'],
            ['region' => 'MB-WPG', 'code' => 'WPG-DWT', 'name' => 'Downtown Winnipeg', 'classification' => 'District'],
            ['region' => 'MB-WPG', 'code' => 'WPG-SBF', 'name' => 'Saint-Boniface', 'classification' => 'District'],
            ['region' => 'NS-HFX', 'code' => 'HFX-DWT', 'name' => 'Downtown Halifax', 'classification' => 'District'],
        ];

        $ids = [];
        foreach ($secteurs as $s) {
            $regionId = $regionIds[$s['region']] ?? null;
            if (!$regionId) {
                continue;
            }

            DB::table('secteurs')->updateOrInsert(
                ['code' => $s['code'], 'region_id' => $regionId],
                [
                    'name' => $s['name'],
                    'classification' => $s['classification'],
                    'description' => $s['name'] . ' - secteur urbain.',
                    'population' => null,
                    'area' => null,
                    'households' => null,
                    'density' => null,
                    'is_active' => 1,
                    'updated_at' => $now,
                    'created_at' => DB::raw('COALESCE(created_at, NOW())'),
                ]
            );

            $ids[$s['code']] = (int) DB::table('secteurs')->where('code', $s['code'])->where('region_id', $regionId)->value('id');
        }

        return $ids;
    }

    private function seedVilles(int $countryId, array $provinceIds, array $regionIds, array $secteurIds, Carbon $now): int
    {
        $cities = [
            ['code' => 'AB-CGY', 'name' => 'Calgary', 'province' => 'AB', 'region' => 'AB-CAL', 'secteur' => 'CAL-DWT', 'status' => 'Metropole', 'population' => 1600000, 'area' => 825.56, 'lat' => '51.0447', 'lng' => '-114.0719'],
            ['code' => 'AB-EDM', 'name' => 'Edmonton', 'province' => 'AB', 'region' => 'AB-EDM', 'secteur' => 'EDM-DWT', 'status' => 'Capitale provinciale', 'population' => 1150000, 'area' => 765.61, 'lat' => '53.5461', 'lng' => '-113.4938'],
            ['code' => 'AB-RDR', 'name' => 'Red Deer', 'province' => 'AB', 'region' => 'AB-CAL', 'secteur' => null, 'status' => 'Ville', 'population' => 110000, 'area' => 104.34, 'lat' => '52.2681', 'lng' => '-113.8112'],
            ['code' => 'BC-VAN', 'name' => 'Vancouver', 'province' => 'BC', 'region' => 'BC-MVR', 'secteur' => 'VAN-DWT', 'status' => 'Metropole', 'population' => 706000, 'area' => 115.18, 'lat' => '49.2827', 'lng' => '-123.1207'],
            ['code' => 'BC-VIC', 'name' => 'Victoria', 'province' => 'BC', 'region' => 'BC-VIS', 'secteur' => null, 'status' => 'Capitale provinciale', 'population' => 98000, 'area' => 19.47, 'lat' => '48.4284', 'lng' => '-123.3656'],
            ['code' => 'BC-KEL', 'name' => 'Kelowna', 'province' => 'BC', 'region' => 'BC-OKN', 'secteur' => null, 'status' => 'Ville', 'population' => 160000, 'area' => 211.82, 'lat' => '49.8879', 'lng' => '-119.4960'],
            ['code' => 'MB-WPG', 'name' => 'Winnipeg', 'province' => 'MB', 'region' => 'MB-WPG', 'secteur' => 'WPG-DWT', 'status' => 'Capitale provinciale', 'population' => 840000, 'area' => 464.08, 'lat' => '49.8951', 'lng' => '-97.1384'],
            ['code' => 'MB-BRN', 'name' => 'Brandon', 'province' => 'MB', 'region' => 'MB-WPG', 'secteur' => null, 'status' => 'Ville', 'population' => 56000, 'area' => 77.41, 'lat' => '49.8469', 'lng' => '-99.9531'],
            ['code' => 'NB-MCT', 'name' => 'Moncton', 'province' => 'NB', 'region' => 'NB-SWE', 'secteur' => null, 'status' => 'Ville', 'population' => 158000, 'area' => 142.0, 'lat' => '46.0878', 'lng' => '-64.7782'],
            ['code' => 'NB-FRD', 'name' => 'Fredericton', 'province' => 'NB', 'region' => 'NB-CAP', 'secteur' => null, 'status' => 'Capitale provinciale', 'population' => 65000, 'area' => 133.0, 'lat' => '45.9636', 'lng' => '-66.6431'],
            ['code' => 'NB-STJ', 'name' => 'Saint John', 'province' => 'NB', 'region' => 'NB-SWE', 'secteur' => null, 'status' => 'Ville', 'population' => 71000, 'area' => 315.96, 'lat' => '45.2733', 'lng' => '-66.0633'],
            ['code' => 'NL-STJ', 'name' => 'Saint-Jean de Terre-Neuve', 'province' => 'NL', 'region' => 'NL-AVA', 'secteur' => null, 'status' => 'Capitale provinciale', 'population' => 114000, 'area' => 446.0, 'lat' => '47.5615', 'lng' => '-52.7126'],
            ['code' => 'NL-CBN', 'name' => 'Corner Brook', 'province' => 'NL', 'region' => 'NL-AVA', 'secteur' => null, 'status' => 'Ville', 'population' => 19000, 'area' => 148.0, 'lat' => '48.9482', 'lng' => '-57.9500'],
            ['code' => 'NS-HFX', 'name' => 'Halifax', 'province' => 'NS', 'region' => 'NS-HFX', 'secteur' => 'HFX-DWT', 'status' => 'Capitale provinciale', 'population' => 510000, 'area' => 5490.0, 'lat' => '44.6488', 'lng' => '-63.5752'],
            ['code' => 'NS-SYD', 'name' => 'Sydney', 'province' => 'NS', 'region' => 'NS-CBR', 'secteur' => null, 'status' => 'Ville', 'population' => 32000, 'area' => 31.0, 'lat' => '46.1368', 'lng' => '-60.1942'],
            ['code' => 'ON-TOR', 'name' => 'Toronto', 'province' => 'ON', 'region' => 'ON-GTA', 'secteur' => 'TOR-DWT', 'status' => 'Capitale provinciale', 'population' => 3000000, 'area' => 630.2, 'lat' => '43.6532', 'lng' => '-79.3832'],
            ['code' => 'ON-OTT', 'name' => 'Ottawa', 'province' => 'ON', 'region' => 'ON-OTT', 'secteur' => 'OTT-BWM', 'status' => 'Capitale federale', 'population' => 1080000, 'area' => 2790.3, 'lat' => '45.4215', 'lng' => '-75.6972'],
            ['code' => 'ON-HAM', 'name' => 'Hamilton', 'province' => 'ON', 'region' => 'ON-GTA', 'secteur' => null, 'status' => 'Ville', 'population' => 620000, 'area' => 1138.0, 'lat' => '43.2557', 'lng' => '-79.8711'],
            ['code' => 'ON-LON', 'name' => 'London', 'province' => 'ON', 'region' => 'ON-SWO', 'secteur' => null, 'status' => 'Ville', 'population' => 430000, 'area' => 420.0, 'lat' => '42.9849', 'lng' => '-81.2453'],
            ['code' => 'ON-SBY', 'name' => 'Greater Sudbury', 'province' => 'ON', 'region' => 'ON-NOR', 'secteur' => null, 'status' => 'Ville', 'population' => 170000, 'area' => 3227.0, 'lat' => '46.4917', 'lng' => '-80.9930'],
            ['code' => 'PE-CHT', 'name' => 'Charlottetown', 'province' => 'PE', 'region' => 'PE-QUE', 'secteur' => null, 'status' => 'Capitale provinciale', 'population' => 41000, 'area' => 44.34, 'lat' => '46.2382', 'lng' => '-63.1311'],
            ['code' => 'PE-SUM', 'name' => 'Summerside', 'province' => 'PE', 'region' => 'PE-PRI', 'secteur' => null, 'status' => 'Ville', 'population' => 17000, 'area' => 28.5, 'lat' => '46.3959', 'lng' => '-63.7876'],
            ['code' => 'QC-MTL', 'name' => 'Montreal', 'province' => 'QC', 'region' => 'QC-06', 'secteur' => 'MTL-VIM', 'status' => 'Metropole', 'population' => 1780000, 'area' => 431.5, 'lat' => '45.5019', 'lng' => '-73.5674'],
            ['code' => 'QC-QBC', 'name' => 'Quebec', 'province' => 'QC', 'region' => 'QC-03', 'secteur' => 'QBC-VIE', 'status' => 'Capitale provinciale', 'population' => 560000, 'area' => 485.8, 'lat' => '46.8139', 'lng' => '-71.2080'],
            ['code' => 'QC-LVL', 'name' => 'Laval', 'province' => 'QC', 'region' => 'QC-13', 'secteur' => null, 'status' => 'Ville', 'population' => 450000, 'area' => 267.0, 'lat' => '45.6066', 'lng' => '-73.7124'],
            ['code' => 'QC-GAT', 'name' => 'Gatineau', 'province' => 'QC', 'region' => 'QC-07', 'secteur' => null, 'status' => 'Ville', 'population' => 300000, 'area' => 342.98, 'lat' => '45.4765', 'lng' => '-75.7013'],
            ['code' => 'QC-SBK', 'name' => 'Sherbrooke', 'province' => 'QC', 'region' => 'QC-05', 'secteur' => null, 'status' => 'Ville', 'population' => 180000, 'area' => 367.1, 'lat' => '45.4042', 'lng' => '-71.8929'],
            ['code' => 'QC-TRR', 'name' => 'Trois-Rivieres', 'province' => 'QC', 'region' => 'QC-04', 'secteur' => null, 'status' => 'Ville', 'population' => 142000, 'area' => 289.9, 'lat' => '46.3430', 'lng' => '-72.5430'],
            ['code' => 'QC-SAG', 'name' => 'Saguenay', 'province' => 'QC', 'region' => 'QC-02', 'secteur' => null, 'status' => 'Ville', 'population' => 150000, 'area' => 1136.0, 'lat' => '48.4284', 'lng' => '-71.0689'],
            ['code' => 'QC-RIM', 'name' => 'Rimouski', 'province' => 'QC', 'region' => 'QC-01', 'secteur' => null, 'status' => 'Ville', 'population' => 50000, 'area' => 332.0, 'lat' => '48.4517', 'lng' => '-68.5239'],
            ['code' => 'QC-RYN', 'name' => 'Rouyn-Noranda', 'province' => 'QC', 'region' => 'QC-08', 'secteur' => null, 'status' => 'Ville', 'population' => 44000, 'area' => 6441.0, 'lat' => '48.2366', 'lng' => '-79.0231'],
            ['code' => 'QC-SEI', 'name' => 'Sept-Iles', 'province' => 'QC', 'region' => 'QC-09', 'secteur' => null, 'status' => 'Ville', 'population' => 26000, 'area' => 2242.0, 'lat' => '50.2234', 'lng' => '-66.3821'],
            ['code' => 'QC-LEV', 'name' => 'Levis', 'province' => 'QC', 'region' => 'QC-12', 'secteur' => null, 'status' => 'Ville', 'population' => 152000, 'area' => 449.0, 'lat' => '46.7387', 'lng' => '-71.2465'],
            ['code' => 'QC-SJR', 'name' => 'Saint-Jerome', 'province' => 'QC', 'region' => 'QC-15', 'secteur' => null, 'status' => 'Ville', 'population' => 84000, 'area' => 90.0, 'lat' => '45.7804', 'lng' => '-74.0036'],
            ['code' => 'QC-LNG', 'name' => 'Longueuil', 'province' => 'QC', 'region' => 'QC-16', 'secteur' => null, 'status' => 'Ville', 'population' => 254000, 'area' => 116.0, 'lat' => '45.5312', 'lng' => '-73.5181'],
            ['code' => 'QC-DRM', 'name' => 'Drummondville', 'province' => 'QC', 'region' => 'QC-17', 'secteur' => null, 'status' => 'Ville', 'population' => 83000, 'area' => 247.0, 'lat' => '45.8800', 'lng' => '-72.4820'],
            ['code' => 'SK-REG', 'name' => 'Regina', 'province' => 'SK', 'region' => 'SK-REG', 'secteur' => null, 'status' => 'Capitale provinciale', 'population' => 255000, 'area' => 179.97, 'lat' => '50.4452', 'lng' => '-104.6189'],
            ['code' => 'SK-SAS', 'name' => 'Saskatoon', 'province' => 'SK', 'region' => 'SK-SAS', 'secteur' => null, 'status' => 'Ville', 'population' => 335000, 'area' => 228.13, 'lat' => '52.1579', 'lng' => '-106.6702'],
            ['code' => 'SK-PRA', 'name' => 'Prince Albert', 'province' => 'SK', 'region' => 'SK-NOR', 'secteur' => null, 'status' => 'Ville', 'population' => 38000, 'area' => 67.7, 'lat' => '53.2033', 'lng' => '-105.7531'],
            ['code' => 'NT-YLK', 'name' => 'Yellowknife', 'province' => 'NT', 'region' => 'NT-NSL', 'secteur' => null, 'status' => 'Capitale territoriale', 'population' => 21000, 'area' => 136.0, 'lat' => '62.4540', 'lng' => '-114.3718'],
            ['code' => 'NT-INV', 'name' => 'Inuvik', 'province' => 'NT', 'region' => 'NT-INV', 'secteur' => null, 'status' => 'Ville', 'population' => 3200, 'area' => 62.0, 'lat' => '68.3609', 'lng' => '-133.7230'],
            ['code' => 'NU-IQA', 'name' => 'Iqaluit', 'province' => 'NU', 'region' => 'NU-QIK', 'secteur' => null, 'status' => 'Capitale territoriale', 'population' => 8000, 'area' => 52.50, 'lat' => '63.7467', 'lng' => '-68.5170'],
            ['code' => 'NU-RIN', 'name' => 'Rankin Inlet', 'province' => 'NU', 'region' => 'NU-KIV', 'secteur' => null, 'status' => 'Ville', 'population' => 3200, 'area' => 12.0, 'lat' => '62.8081', 'lng' => '-92.0853'],
            ['code' => 'YT-WHI', 'name' => 'Whitehorse', 'province' => 'YT', 'region' => 'YT-WHI', 'secteur' => null, 'status' => 'Capitale territoriale', 'population' => 32000, 'area' => 416.0, 'lat' => '60.7212', 'lng' => '-135.0568'],
            ['code' => 'YT-DAW', 'name' => 'Dawson', 'province' => 'YT', 'region' => 'YT-KLO', 'secteur' => null, 'status' => 'Ville', 'population' => 1600, 'area' => 31.77, 'lat' => '64.0601', 'lng' => '-139.4328'],
        ];

        $count = 0;
        foreach ($cities as $c) {
            $provinceId = $provinceIds[$c['province']] ?? null;
            $regionId = $regionIds[$c['region']] ?? null;
            if (!$provinceId || !$regionId) {
                continue;
            }

            $secteurId = $c['secteur'] ? ($secteurIds[$c['secteur']] ?? null) : null;

            DB::table('villes')->updateOrInsert(
                ['code' => $c['code'], 'country_id' => $countryId],
                [
                    'name' => $c['name'],
                    'classification' => 'Ville',
                    'status' => $c['status'],
                    'population' => $c['population'],
                    'area' => $c['area'],
                    'households' => null,
                    'density' => null,
                    'altitude' => null,
                    'founding_year' => null,
                    'description' => $c['name'] . ' - ville du Canada.',
                    'postal_code_prefix' => null,
                    'latitude' => $c['lat'],
                    'longitude' => $c['lng'],
                    'is_active' => 1,
                    'secteur_id' => $secteurId,
                    'region_id' => $regionId,
                    'province_id' => $provinceId,
                    'updated_at' => $now,
                    'created_at' => DB::raw('COALESCE(created_at, NOW())'),
                ]
            );

            $count++;
        }

        return $count;
    }
}
