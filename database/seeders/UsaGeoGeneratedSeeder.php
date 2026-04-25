<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsaGeoGeneratedSeeder extends Seeder
{
    public function run(): void
    {
        DB::beginTransaction();

        try {
            $now = Carbon::now();

            $continentId = $this->upsertNorthAmerica($now);
            $countryId = $this->upsertUnitedStates($continentId, $now);

            $states = $this->statesData();
            $provinceIds = $this->seedStatesAsProvinces($countryId, $states, $now);
            $regionIds = $this->seedStateRegions($provinceIds, $states, $now);
            $secteurIds = $this->seedMetroSecteurs($regionIds, $now);
            $cityCount = $this->seedStateCities($countryId, $provinceIds, $regionIds, $secteurIds, $states, $now);

            DB::commit();

            $this->command?->info('Seeder UsaGeoGeneratedSeeder termine (data-only).');
            $this->command?->line('- Etats / District: ' . count($states));
            $this->command?->line('- Regions: ' . count($regionIds));
            $this->command?->line('- Secteurs: ' . count($secteurIds));
            $this->command?->line('- Villes: ' . $cityCount);
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->command?->error('Erreur UsaGeoGeneratedSeeder: ' . $e->getMessage());
        }
    }

    private function upsertNorthAmerica(Carbon $now): int
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

    private function upsertUnitedStates(int $continentId, Carbon $now): int
    {
        DB::table('countries')->updateOrInsert(
            ['code' => 'USA'],
            [
                'name' => 'Etats-Unis',
                'iso2' => 'US',
                'phone_code' => '+1',
                'capital' => 'Washington',
                'currency' => 'Dollar americain',
                'currency_symbol' => '$',
                'latitude' => '37.0902',
                'longitude' => '-95.7129',
                'description' => 'Pays d Amerique du Nord.',
                'population' => 340000000,
                'area' => 9833517,
                'official_language' => 'Anglais',
                'timezones' => json_encode(['UTC-10:00', 'UTC-09:00', 'UTC-08:00', 'UTC-07:00', 'UTC-06:00', 'UTC-05:00']),
                'region' => 'Amerique du Nord',
                'continent_id' => $continentId,
                'is_active' => 1,
                'updated_at' => $now,
                'created_at' => DB::raw('COALESCE(created_at, NOW())'),
            ]
        );

        return (int) DB::table('countries')->where('code', 'USA')->value('id');
    }

    private function seedStatesAsProvinces(int $countryId, array $states, Carbon $now): array
    {
        $ids = [];

        foreach ($states as $s) {
            DB::table('provinces')->updateOrInsert(
                ['code' => $s['code'], 'country_id' => $countryId],
                [
                    'name' => $s['name'],
                    'capital' => $s['capital'],
                    'largest_city' => $s['largest_city'],
                    'official_language' => 'Anglais',
                    'area_rank' => (string) $s['area_rank'],
                    'population' => $s['population'],
                    'area' => $s['area_km2'],
                    'timezone' => $s['timezone'],
                    'description' => $s['name'] . ' - etat / district des Etats-Unis.',
                    'latitude' => null,
                    'longitude' => null,
                    'is_active' => 1,
                    'updated_at' => $now,
                    'created_at' => DB::raw('COALESCE(created_at, NOW())'),
                ]
            );

            $ids[$s['code']] = (int) DB::table('provinces')
                ->where('code', $s['code'])
                ->where('country_id', $countryId)
                ->value('id');
        }

        return $ids;
    }

    private function seedStateRegions(array $provinceIds, array $states, Carbon $now): array
    {
        $ids = [];

        foreach ($states as $s) {
            $provinceId = $provinceIds[$s['code']] ?? null;
            if (!$provinceId) {
                continue;
            }

            $regionCode = 'US-' . $s['code'];

            DB::table('regions')->updateOrInsert(
                ['code' => $regionCode, 'province_id' => $provinceId],
                [
                    'name' => $s['name'],
                    'capital' => $s['capital'],
                    'largest_city' => $s['largest_city'],
                    'classification' => $s['classification'],
                    'population' => $s['population'],
                    'area' => $s['area_km2'],
                    'municipalities_count' => null,
                    'timezone' => $s['timezone'],
                    'description' => $s['name'] . ' - region etatique.',
                    'latitude' => null,
                    'longitude' => null,
                    'is_active' => 1,
                    'updated_at' => $now,
                    'created_at' => DB::raw('COALESCE(created_at, NOW())'),
                ]
            );

            $ids[$regionCode] = (int) DB::table('regions')
                ->where('code', $regionCode)
                ->where('province_id', $provinceId)
                ->value('id');
        }

        return $ids;
    }

    private function seedMetroSecteurs(array $regionIds, Carbon $now): array
    {
        $rows = [
            ['region' => 'US-CA', 'code' => 'US-CA-LA', 'name' => 'Downtown Los Angeles'],
            ['region' => 'US-NY', 'code' => 'US-NY-NYC', 'name' => 'Manhattan'],
            ['region' => 'US-IL', 'code' => 'US-IL-CHI', 'name' => 'The Loop'],
            ['region' => 'US-TX', 'code' => 'US-TX-HOU', 'name' => 'Downtown Houston'],
            ['region' => 'US-AZ', 'code' => 'US-AZ-PHX', 'name' => 'Downtown Phoenix'],
            ['region' => 'US-PA', 'code' => 'US-PA-PHL', 'name' => 'Center City'],
            ['region' => 'US-TX', 'code' => 'US-TX-SAT', 'name' => 'Downtown San Antonio'],
            ['region' => 'US-CA', 'code' => 'US-CA-SD', 'name' => 'Gaslamp Quarter'],
            ['region' => 'US-TX', 'code' => 'US-TX-DAL', 'name' => 'Downtown Dallas'],
            ['region' => 'US-CA', 'code' => 'US-CA-SJ', 'name' => 'Downtown San Jose'],
            ['region' => 'US-FL', 'code' => 'US-FL-MIA', 'name' => 'Downtown Miami'],
            ['region' => 'US-WA', 'code' => 'US-WA-SEA', 'name' => 'Downtown Seattle'],
            ['region' => 'US-CO', 'code' => 'US-CO-DEN', 'name' => 'Downtown Denver'],
            ['region' => 'US-MA', 'code' => 'US-MA-BOS', 'name' => 'Downtown Boston'],
            ['region' => 'US-GA', 'code' => 'US-GA-ATL', 'name' => 'Downtown Atlanta'],
            ['region' => 'US-MN', 'code' => 'US-MN-MPL', 'name' => 'Downtown Minneapolis'],
        ];

        $ids = [];

        foreach ($rows as $r) {
            $regionId = $regionIds[$r['region']] ?? null;
            if (!$regionId) {
                continue;
            }

            DB::table('secteurs')->updateOrInsert(
                ['code' => $r['code'], 'region_id' => $regionId],
                [
                    'name' => $r['name'],
                    'classification' => 'District',
                    'description' => $r['name'] . ' - secteur metropolitain.',
                    'population' => null,
                    'area' => null,
                    'households' => null,
                    'density' => null,
                    'is_active' => 1,
                    'updated_at' => $now,
                    'created_at' => DB::raw('COALESCE(created_at, NOW())'),
                ]
            );

            $ids[$r['code']] = (int) DB::table('secteurs')
                ->where('code', $r['code'])
                ->where('region_id', $regionId)
                ->value('id');
        }

        return $ids;
    }

    private function seedStateCities(
        int $countryId,
        array $provinceIds,
        array $regionIds,
        array $secteurIds,
        array $states,
        Carbon $now
    ): int {
        $metroMap = [
            'Los Angeles' => 'US-CA-LA',
            'New York City' => 'US-NY-NYC',
            'Chicago' => 'US-IL-CHI',
            'Houston' => 'US-TX-HOU',
            'Phoenix' => 'US-AZ-PHX',
            'Philadelphia' => 'US-PA-PHL',
            'San Antonio' => 'US-TX-SAT',
            'San Diego' => 'US-CA-SD',
            'Dallas' => 'US-TX-DAL',
            'San Jose' => 'US-CA-SJ',
            'Miami' => 'US-FL-MIA',
            'Seattle' => 'US-WA-SEA',
            'Denver' => 'US-CO-DEN',
            'Boston' => 'US-MA-BOS',
            'Atlanta' => 'US-GA-ATL',
            'Minneapolis' => 'US-MN-MPL',
        ];

        $count = 0;

        foreach ($states as $s) {
            $provinceId = $provinceIds[$s['code']] ?? null;
            $regionId = $regionIds['US-' . $s['code']] ?? null;

            if (!$provinceId || !$regionId) {
                continue;
            }

            $capitalCode = 'US-' . $s['code'] . '-CAP';
            DB::table('villes')->updateOrInsert(
                ['code' => $capitalCode, 'country_id' => $countryId],
                [
                    'name' => $s['capital'],
                    'classification' => 'City',
                    'status' => $s['capital_status'],
                    'population' => null,
                    'area' => null,
                    'households' => null,
                    'density' => null,
                    'altitude' => null,
                    'founding_year' => null,
                    'description' => $s['capital'] . ' - capitale de ' . $s['name'] . '.',
                    'postal_code_prefix' => null,
                    'latitude' => null,
                    'longitude' => null,
                    'is_active' => 1,
                    'secteur_id' => null,
                    'region_id' => $regionId,
                    'province_id' => $provinceId,
                    'updated_at' => $now,
                    'created_at' => DB::raw('COALESCE(created_at, NOW())'),
                ]
            );
            $count++;

            if ($s['largest_city'] !== $s['capital']) {
                $largestCode = 'US-' . $s['code'] . '-LRG';
                $secteurKey = $metroMap[$s['largest_city']] ?? null;
                $secteurId = $secteurKey ? ($secteurIds[$secteurKey] ?? null) : null;

                DB::table('villes')->updateOrInsert(
                    ['code' => $largestCode, 'country_id' => $countryId],
                    [
                        'name' => $s['largest_city'],
                        'classification' => 'City',
                        'status' => 'Plus grande ville',
                        'population' => null,
                        'area' => null,
                        'households' => null,
                        'density' => null,
                        'altitude' => null,
                        'founding_year' => null,
                        'description' => $s['largest_city'] . ' - plus grande ville de ' . $s['name'] . '.',
                        'postal_code_prefix' => null,
                        'latitude' => null,
                        'longitude' => null,
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
        }

        return $count;
    }

    private function statesData(): array
    {
        return [
            ['code' => 'AL', 'name' => 'Alabama', 'capital' => 'Montgomery', 'largest_city' => 'Birmingham', 'population' => 5108468, 'area_km2' => 135767, 'area_rank' => 30, 'timezone' => 'UTC-06:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'AK', 'name' => 'Alaska', 'capital' => 'Juneau', 'largest_city' => 'Anchorage', 'population' => 733406, 'area_km2' => 1723337, 'area_rank' => 1, 'timezone' => 'UTC-09:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'AZ', 'name' => 'Arizona', 'capital' => 'Phoenix', 'largest_city' => 'Phoenix', 'population' => 7431344, 'area_km2' => 295234, 'area_rank' => 6, 'timezone' => 'UTC-07:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'AR', 'name' => 'Arkansas', 'capital' => 'Little Rock', 'largest_city' => 'Little Rock', 'population' => 3067732, 'area_km2' => 137732, 'area_rank' => 29, 'timezone' => 'UTC-06:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'CA', 'name' => 'California', 'capital' => 'Sacramento', 'largest_city' => 'Los Angeles', 'population' => 38965193, 'area_km2' => 423967, 'area_rank' => 3, 'timezone' => 'UTC-08:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'CO', 'name' => 'Colorado', 'capital' => 'Denver', 'largest_city' => 'Denver', 'population' => 5877610, 'area_km2' => 269601, 'area_rank' => 8, 'timezone' => 'UTC-07:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'CT', 'name' => 'Connecticut', 'capital' => 'Hartford', 'largest_city' => 'Bridgeport', 'population' => 3617176, 'area_km2' => 14357, 'area_rank' => 48, 'timezone' => 'UTC-05:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'DE', 'name' => 'Delaware', 'capital' => 'Dover', 'largest_city' => 'Wilmington', 'population' => 1031890, 'area_km2' => 6446, 'area_rank' => 49, 'timezone' => 'UTC-05:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'FL', 'name' => 'Florida', 'capital' => 'Tallahassee', 'largest_city' => 'Jacksonville', 'population' => 22610726, 'area_km2' => 170312, 'area_rank' => 22, 'timezone' => 'UTC-05:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'GA', 'name' => 'Georgia', 'capital' => 'Atlanta', 'largest_city' => 'Atlanta', 'population' => 11029227, 'area_km2' => 153910, 'area_rank' => 24, 'timezone' => 'UTC-05:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'HI', 'name' => 'Hawaii', 'capital' => 'Honolulu', 'largest_city' => 'Honolulu', 'population' => 1435138, 'area_km2' => 28313, 'area_rank' => 43, 'timezone' => 'UTC-10:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'ID', 'name' => 'Idaho', 'capital' => 'Boise', 'largest_city' => 'Boise', 'population' => 1964726, 'area_km2' => 216443, 'area_rank' => 14, 'timezone' => 'UTC-07:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'IL', 'name' => 'Illinois', 'capital' => 'Springfield', 'largest_city' => 'Chicago', 'population' => 12549689, 'area_km2' => 149995, 'area_rank' => 25, 'timezone' => 'UTC-06:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'IN', 'name' => 'Indiana', 'capital' => 'Indianapolis', 'largest_city' => 'Indianapolis', 'population' => 6862199, 'area_km2' => 94326, 'area_rank' => 38, 'timezone' => 'UTC-05:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'IA', 'name' => 'Iowa', 'capital' => 'Des Moines', 'largest_city' => 'Des Moines', 'population' => 3207004, 'area_km2' => 145746, 'area_rank' => 26, 'timezone' => 'UTC-06:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'KS', 'name' => 'Kansas', 'capital' => 'Topeka', 'largest_city' => 'Wichita', 'population' => 2940546, 'area_km2' => 213100, 'area_rank' => 15, 'timezone' => 'UTC-06:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'KY', 'name' => 'Kentucky', 'capital' => 'Frankfort', 'largest_city' => 'Louisville', 'population' => 4526154, 'area_km2' => 104656, 'area_rank' => 37, 'timezone' => 'UTC-05:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'LA', 'name' => 'Louisiana', 'capital' => 'Baton Rouge', 'largest_city' => 'New Orleans', 'population' => 4573749, 'area_km2' => 135659, 'area_rank' => 31, 'timezone' => 'UTC-06:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'ME', 'name' => 'Maine', 'capital' => 'Augusta', 'largest_city' => 'Portland', 'population' => 1395722, 'area_km2' => 91633, 'area_rank' => 39, 'timezone' => 'UTC-05:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'MD', 'name' => 'Maryland', 'capital' => 'Annapolis', 'largest_city' => 'Baltimore', 'population' => 6180253, 'area_km2' => 32131, 'area_rank' => 42, 'timezone' => 'UTC-05:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'MA', 'name' => 'Massachusetts', 'capital' => 'Boston', 'largest_city' => 'Boston', 'population' => 7001399, 'area_km2' => 27336, 'area_rank' => 44, 'timezone' => 'UTC-05:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'MI', 'name' => 'Michigan', 'capital' => 'Lansing', 'largest_city' => 'Detroit', 'population' => 10037261, 'area_km2' => 250487, 'area_rank' => 11, 'timezone' => 'UTC-05:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'MN', 'name' => 'Minnesota', 'capital' => 'Saint Paul', 'largest_city' => 'Minneapolis', 'population' => 5737915, 'area_km2' => 225163, 'area_rank' => 12, 'timezone' => 'UTC-06:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'MS', 'name' => 'Mississippi', 'capital' => 'Jackson', 'largest_city' => 'Jackson', 'population' => 2939690, 'area_km2' => 125438, 'area_rank' => 32, 'timezone' => 'UTC-06:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'MO', 'name' => 'Missouri', 'capital' => 'Jefferson City', 'largest_city' => 'Kansas City', 'population' => 6196156, 'area_km2' => 180560, 'area_rank' => 21, 'timezone' => 'UTC-06:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'MT', 'name' => 'Montana', 'capital' => 'Helena', 'largest_city' => 'Billings', 'population' => 1132812, 'area_km2' => 380831, 'area_rank' => 4, 'timezone' => 'UTC-07:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'NE', 'name' => 'Nebraska', 'capital' => 'Lincoln', 'largest_city' => 'Omaha', 'population' => 1978379, 'area_km2' => 200330, 'area_rank' => 16, 'timezone' => 'UTC-06:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'NV', 'name' => 'Nevada', 'capital' => 'Carson City', 'largest_city' => 'Las Vegas', 'population' => 3194176, 'area_km2' => 286380, 'area_rank' => 7, 'timezone' => 'UTC-08:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'NH', 'name' => 'New Hampshire', 'capital' => 'Concord', 'largest_city' => 'Manchester', 'population' => 1402054, 'area_km2' => 24214, 'area_rank' => 46, 'timezone' => 'UTC-05:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'NJ', 'name' => 'New Jersey', 'capital' => 'Trenton', 'largest_city' => 'Newark', 'population' => 9290841, 'area_km2' => 22591, 'area_rank' => 47, 'timezone' => 'UTC-05:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'NM', 'name' => 'New Mexico', 'capital' => 'Santa Fe', 'largest_city' => 'Albuquerque', 'population' => 2114371, 'area_km2' => 314917, 'area_rank' => 5, 'timezone' => 'UTC-07:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'NY', 'name' => 'New York', 'capital' => 'Albany', 'largest_city' => 'New York City', 'population' => 19571216, 'area_km2' => 141297, 'area_rank' => 27, 'timezone' => 'UTC-05:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'NC', 'name' => 'North Carolina', 'capital' => 'Raleigh', 'largest_city' => 'Charlotte', 'population' => 10835491, 'area_km2' => 139391, 'area_rank' => 28, 'timezone' => 'UTC-05:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'ND', 'name' => 'North Dakota', 'capital' => 'Bismarck', 'largest_city' => 'Fargo', 'population' => 783926, 'area_km2' => 183108, 'area_rank' => 19, 'timezone' => 'UTC-06:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'OH', 'name' => 'Ohio', 'capital' => 'Columbus', 'largest_city' => 'Columbus', 'population' => 11785935, 'area_km2' => 116098, 'area_rank' => 34, 'timezone' => 'UTC-05:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'OK', 'name' => 'Oklahoma', 'capital' => 'Oklahoma City', 'largest_city' => 'Oklahoma City', 'population' => 4053824, 'area_km2' => 181037, 'area_rank' => 20, 'timezone' => 'UTC-06:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'OR', 'name' => 'Oregon', 'capital' => 'Salem', 'largest_city' => 'Portland', 'population' => 4233358, 'area_km2' => 254806, 'area_rank' => 9, 'timezone' => 'UTC-08:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'PA', 'name' => 'Pennsylvania', 'capital' => 'Harrisburg', 'largest_city' => 'Philadelphia', 'population' => 12961683, 'area_km2' => 119280, 'area_rank' => 33, 'timezone' => 'UTC-05:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'RI', 'name' => 'Rhode Island', 'capital' => 'Providence', 'largest_city' => 'Providence', 'population' => 1095962, 'area_km2' => 4001, 'area_rank' => 50, 'timezone' => 'UTC-05:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'SC', 'name' => 'South Carolina', 'capital' => 'Columbia', 'largest_city' => 'Charleston', 'population' => 5373555, 'area_km2' => 82933, 'area_rank' => 40, 'timezone' => 'UTC-05:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'SD', 'name' => 'South Dakota', 'capital' => 'Pierre', 'largest_city' => 'Sioux Falls', 'population' => 919318, 'area_km2' => 199729, 'area_rank' => 17, 'timezone' => 'UTC-06:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'TN', 'name' => 'Tennessee', 'capital' => 'Nashville', 'largest_city' => 'Nashville', 'population' => 7126489, 'area_km2' => 109153, 'area_rank' => 36, 'timezone' => 'UTC-06:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'TX', 'name' => 'Texas', 'capital' => 'Austin', 'largest_city' => 'Houston', 'population' => 30503301, 'area_km2' => 695662, 'area_rank' => 2, 'timezone' => 'UTC-06:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'UT', 'name' => 'Utah', 'capital' => 'Salt Lake City', 'largest_city' => 'Salt Lake City', 'population' => 3417734, 'area_km2' => 219882, 'area_rank' => 13, 'timezone' => 'UTC-07:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'VT', 'name' => 'Vermont', 'capital' => 'Montpelier', 'largest_city' => 'Burlington', 'population' => 647818, 'area_km2' => 24906, 'area_rank' => 45, 'timezone' => 'UTC-05:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'VA', 'name' => 'Virginia', 'capital' => 'Richmond', 'largest_city' => 'Virginia Beach', 'population' => 8715698, 'area_km2' => 110787, 'area_rank' => 35, 'timezone' => 'UTC-05:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'WA', 'name' => 'Washington', 'capital' => 'Olympia', 'largest_city' => 'Seattle', 'population' => 7812880, 'area_km2' => 184661, 'area_rank' => 18, 'timezone' => 'UTC-08:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'WV', 'name' => 'West Virginia', 'capital' => 'Charleston', 'largest_city' => 'Charleston', 'population' => 1770071, 'area_km2' => 62756, 'area_rank' => 41, 'timezone' => 'UTC-05:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'WI', 'name' => 'Wisconsin', 'capital' => 'Madison', 'largest_city' => 'Milwaukee', 'population' => 5910955, 'area_km2' => 169635, 'area_rank' => 23, 'timezone' => 'UTC-06:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'WY', 'name' => 'Wyoming', 'capital' => 'Cheyenne', 'largest_city' => 'Cheyenne', 'population' => 584057, 'area_km2' => 253335, 'area_rank' => 10, 'timezone' => 'UTC-07:00', 'classification' => 'State', 'capital_status' => 'Capitale d etat'],
            ['code' => 'DC', 'name' => 'District of Columbia', 'capital' => 'Washington', 'largest_city' => 'Washington', 'population' => 678972, 'area_km2' => 177, 'area_rank' => 51, 'timezone' => 'UTC-05:00', 'classification' => 'Federal district', 'capital_status' => 'Capitale federale'],
        ];
    }
}

