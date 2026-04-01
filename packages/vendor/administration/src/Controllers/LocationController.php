<?php

namespace Vendor\Administration\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Province;
use App\Models\Region;
use App\Models\Ville;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getCountries()
    {
        return response()->json([
            'success' => true,
            'data' => Country::orderBy('name')->get(['id', 'name', 'code'])
        ]);
    }

    public function getProvinces($countryId)
    {
        $provinces = Province::where('country_id', $countryId)
            ->orderBy('name')
            ->get(['id', 'name', 'code']);
        
        return response()->json([
            'success' => true,
            'data' => $provinces
        ]);
    }

    public function getRegions($provinceId)
    {
        $regions = Region::where('province_id', $provinceId)
            ->orderBy('name')
            ->get(['id', 'name']);
        
        return response()->json([
            'success' => true,
            'data' => $regions
        ]);
    }

    public function getVilles($regionId)
    {
        $villes = Ville::where('region_id', $regionId)
            ->orderBy('name')
            ->get(['id', 'name']);
        
        return response()->json([
            'success' => true,
            'data' => $villes
        ]);
    }

    public function search(Request $request)
    {
        $keyword = $request->get('q', '');
        
        if (empty($keyword)) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $results = [];

        // Recherche dans les pays
        $countries = Country::where('name', 'LIKE', "%{$keyword}%")
            ->limit(5)->get()->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'type' => 'country',
                    'type_label' => 'Pays',
                    'hierarchy' => $item->name,
                    'level' => 1
                ];
            });

        // Recherche dans les provinces
        $provinces = Province::where('name', 'LIKE', "%{$keyword}%")
            ->with('country')->limit(5)->get()->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'type' => 'province',
                    'type_label' => 'Province',
                    'hierarchy' => ($item->country->name ?? '') . ' › ' . $item->name,
                    'level' => 2
                ];
            });

        // Recherche dans les régions
        $regions = Region::where('name', 'LIKE', "%{$keyword}%")
            ->with(['province.country'])->limit(5)->get()->map(function($item) {
                $hierarchy = '';
                if ($item->province) {
                    if ($item->province->country) {
                        $hierarchy .= $item->province->country->name . ' › ';
                    }
                    $hierarchy .= $item->province->name . ' › ';
                }
                $hierarchy .= $item->name;
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'type' => 'region',
                    'type_label' => 'Région',
                    'hierarchy' => $hierarchy,
                    'level' => 3
                ];
            });

        // Recherche dans les villes
        $villes = Ville::where('name', 'LIKE', "%{$keyword}%")
            ->with(['region.province.country'])->limit(5)->get()->map(function($item) {
                $hierarchy = '';
                if ($item->region) {
                    if ($item->region->province) {
                        if ($item->region->province->country) {
                            $hierarchy .= $item->region->province->country->name . ' › ';
                        }
                        $hierarchy .= $item->region->province->name . ' › ';
                    }
                    $hierarchy .= $item->region->name . ' › ';
                }
                $hierarchy .= $item->name;
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'type' => 'ville',
                    'type_label' => 'Ville',
                    'hierarchy' => $hierarchy,
                    'level' => 4
                ];
            });

        $results = $countries->merge($provinces)->merge($regions)->merge($villes)
            ->sortBy('level')->values();

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }
}