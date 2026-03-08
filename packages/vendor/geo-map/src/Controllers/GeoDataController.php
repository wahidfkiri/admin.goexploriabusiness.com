<?php

namespace Vendor\GeoMap\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class GeoDataController extends Controller
{
    /**
     * Récupère les données dynamiques pour le header
     */
    public function getHeaderData($countryCode)
    {
        $country = Country::where('code', strtolower($countryCode))->first();
        
        if (!$country) {
            return response()->json([
                'error' => 'Pays non trouvé'
            ], 404);
        }
        
        $data = [
            'country' => [
                'name' => $country->name,
                'code' => $country->code,
                'currency' => $country->currency_code ?? 'CAD',
                'timezone' => $country->timezone ?? 'America/Toronto'
            ],
            'weather' => $this->getWeatherData($country),
            'stock_market' => $this->getStockMarketData($country),
            'traffic' => $this->getTrafficData($country),
            'time' => $this->getCurrentTime($country),
            'exchange_rate' => $this->getExchangeRate($country)
        ];
        
        return response()->json($data);
    }
    
    /**
     * Récupère les données météo
     */
    private function getWeatherData($country)
    {
        try {
            // Utilisez une API météo (ex: OpenWeatherMap)
            // Vous aurez besoin d'une clé API
            $apiKey = config('services.weather.api_key');
            
            if (!$apiKey) {
                return $this->getMockWeatherData($country);
            }
            
            $response = Http::get("https://api.openweathermap.org/data/2.5/weather", [
                'lat' => $country->latitude,
                'lon' => $country->longitude,
                'appid' => $apiKey,
                'units' => 'metric',
                'lang' => 'fr'
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'temperature' => round($data['main']['temp']),
                    'feels_like' => round($data['main']['feels_like']),
                    'condition' => $data['weather'][0]['description'],
                    'icon' => $data['weather'][0]['icon'],
                    'humidity' => $data['main']['humidity'],
                    'wind_speed' => round($data['wind']['speed'] * 3.6, 1) // Convertir en km/h
                ];
            }
        } catch (\Exception $e) {
            \Log::error('Erreur API météo: ' . $e->getMessage());
        }
        
        return $this->getMockWeatherData($country);
    }
    
    /**
     * Données météo de démonstration
     */
    private function getMockWeatherData($country)
    {
        // Données simulées basées sur le pays
        $mockData = [
            'ca' => ['temp' => -5, 'condition' => 'Ensoleillé', 'icon' => '01d'],
            'us' => ['temp' => 15, 'condition' => 'Nuageux', 'icon' => '03d'],
            'fr' => ['temp' => 12, 'condition' => 'Pluie légère', 'icon' => '10d'],
            'gb' => ['temp' => 8, 'condition' => 'Brouillard', 'icon' => '50d']
        ];
        
        $code = strtolower($country->code);
        $default = ['temp' => 20, 'condition' => 'Clair', 'icon' => '01d'];
        
        $data = $mockData[$code] ?? $default;
        
        return [
            'temperature' => $data['temp'],
            'condition' => $data['condition'],
            'icon' => $data['icon'],
            'humidity' => 65,
            'wind_speed' => 15
        ];
    }
    
    /**
     * Récupère les données boursières
     */
    private function getStockMarketData($country)
    {
        try {
            // API Alpha Vantage pour les données boursières (gratuit avec limitation)
            $apiKey = config('services.stocks.api_key');
            
            if (!$apiKey) {
                return $this->getMockStockData($country);
            }
            
            // Index boursier par pays
            $indices = [
                'ca' => '^GSPTSE', // TSX
                'us' => '^GSPC',   // S&P 500
                'fr' => '^FCHI',   // CAC 40
                'gb' => '^FTSE',   // FTSE 100
                'default' => '^GSPC'
            ];
            
            $symbol = $indices[strtolower($country->code)] ?? $indices['default'];
            
            $response = Http::get("https://www.alphavantage.co/query", [
                'function' => 'GLOBAL_QUOTE',
                'symbol' => $symbol,
                'apikey' => $apiKey
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['Global Quote'])) {
                    $quote = $data['Global Quote'];
                    
                    return [
                        'symbol' => $symbol,
                        'price' => round(floatval($quote['05. price']), 2),
                        'change' => round(floatval($quote['09. change']), 2),
                        'change_percent' => round(floatval($quote['10. change percent']), 2),
                        'volume' => intval($quote['06. volume'])
                    ];
                }
            }
        } catch (\Exception $e) {
            \Log::error('Erreur API boursière: ' . $e->getMessage());
        }
        
        return $this->getMockStockData($country);
    }
    
    /**
     * Données boursières de démonstration
     */
    private function getMockStockData($country)
    {
        $mockData = [
            'ca' => ['symbol' => 'TSX', 'price' => 21450.12, 'change' => 254.32, 'percent' => 1.2],
            'us' => ['symbol' => 'S&P 500', 'price' => 4958.61, 'change' => 52.79, 'percent' => 1.08],
            'fr' => ['symbol' => 'CAC 40', 'price' => 7684.75, 'change' => 23.29, 'percent' => 0.3],
            'gb' => ['symbol' => 'FTSE 100', 'price' => 7935.09, 'change' => 45.98, 'percent' => 0.58]
        ];
        
        $code = strtolower($country->code);
        $default = ['symbol' => 'INDEX', 'price' => 10000, 'change' => 100, 'percent' => 1.0];
        
        $data = $mockData[$code] ?? $default;
        
        return [
            'symbol' => $data['symbol'],
            'price' => $data['price'],
            'change' => $data['change'],
            'change_percent' => $data['percent'],
            'volume' => rand(1000000, 5000000)
        ];
    }
    
    /**
     * Récupère les données de trafic
     */
    private function getTrafficData($country)
    {
        // Pour le trafic, on peut utiliser Google Maps API ou TomTom
        // Ici, on simule des données
        
        $trafficLevels = ['Dégagé', 'Fluide', 'Modéré', 'Dense', 'Saturé'];
        
        // Simuler différentes conditions selon l'heure
        $hour = Carbon::now($country->timezone ?? 'UTC')->hour;
        
        if ($hour >= 7 && $hour <= 9) {
            $traffic = $trafficLevels[3]; // Heure de pointe matin
        } elseif ($hour >= 16 && $hour <= 19) {
            $traffic = $trafficLevels[4]; // Heure de pointe soir
        } else {
            $traffic = $trafficLevels[rand(0, 2)]; // Aléatoire
        }
        
        return [
            'level' => $traffic,
            'description' => "Trafic $traffic",
            'updated_at' => Carbon::now()->toDateTimeString()
        ];
    }
    
    /**
     * Heure actuelle dans le fuseau horaire du pays
     */
    private function getCurrentTime($country)
    {
        $timezone = $country->timezone ?? 'UTC';
        
        return [
            'time' => Carbon::now($timezone)->format('H:i'),
            'date' => Carbon::now($timezone)->isoFormat('dddd D MMMM YYYY'),
            'timezone' => $timezone,
            'timezone_abbr' => Carbon::now($timezone)->format('T')
        ];
    }
    
    /**
     * Taux de change
     */
    private function getExchangeRate($country)
    {
        try {
            // Utiliser une API de taux de change gratuite
            $response = Http::get("https://api.exchangerate-api.com/v4/latest/{$country->currency_code}");
            
            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'base' => $data['base'],
                    'rates' => [
                        'USD' => $data['rates']['USD'] ?? 1,
                        'EUR' => $data['rates']['EUR'] ?? 1,
                        'CAD' => $data['rates']['CAD'] ?? 1
                    ],
                    'date' => $data['date']
                ];
            }
        } catch (\Exception $e) {
            \Log::error('Erreur API taux de change: ' . $e->getMessage());
        }
        
        // Données simulées
        $rates = [
            'CAD' => ['USD' => 0.74, 'EUR' => 0.68],
            'USD' => ['CAD' => 1.35, 'EUR' => 0.92],
            'EUR' => ['CAD' => 1.47, 'USD' => 1.09],
            'default' => ['USD' => 1, 'EUR' => 1, 'CAD' => 1]
        ];
        
        $currency = $country->currency_code ?? 'USD';
        $rateData = $rates[$currency] ?? $rates['default'];
        
        return [
            'base' => $currency,
            'rates' => $rateData,
            'date' => Carbon::now()->toDateString()
        ];
    }
    
    /**
     * Récupère les provinces pour un pays
     */
    public function getProvinces($countryCode)
    {
        $country = Country::where('code', strtolower($countryCode))->first();
        
        if (!$country) {
            return response()->json([
                'error' => 'Pays non trouvé'
            ], 404);
        }
        
        $provinces = Province::where('country_id', $country->id)
            ->select('id', 'name', 'code', 'latitude', 'longitude')
            ->orderBy('name')
            ->get();
        
        return response()->json([
            'country' => $country->name,
            'provinces' => $provinces
        ]);
    }

    public function getRegions($provinceId)
    {
        $province = Province::find($provinceId);
        
        if (!$province) {
            return response()->json([
                'error' => 'Province non trouvée'
            ], 404);
        }
        
        $regions = $province->regions()
            ->select('id', 'name', 'code', 'latitude', 'longitude')
            ->orderBy('name')
            ->get();
        
        return response()->json([
            'province' => $province->name,
            'regions' => $regions
        ]);
    }
}