<?php

namespace Vendor\Destination\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ville;
use App\Models\Region;
use App\Models\Province;
use App\Models\Country;
use App\Models\Secteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VilleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Ville::with(['country', 'province', 'region', 'secteur'])
            ->withCount([]); // Ajoutez des relations count si nécessaire
        
        // Recherche
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('classification', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhere('mayor', 'like', "%{$search}%");
            });
        }
        
        // Filtre par pays
        if ($request->has('country') && !empty($request->country)) {
            $query->whereHas('country', function($q) use ($request) {
                $q->where('code', $request->country)
                  ->orWhere('name', 'like', "%{$request->country}%");
            });
        }
        
        // Filtre par province
        if ($request->has('province') && !empty($request->province)) {
            $query->whereHas('province', function($q) use ($request) {
                $q->where('code', $request->province)
                  ->orWhere('name', 'like', "%{$request->province}%");
            });
        }
        
        // Filtre par région
        if ($request->has('region') && !empty($request->region)) {
            $query->whereHas('region', function($q) use ($request) {
                $q->where('code', $request->region)
                  ->orWhere('name', 'like', "%{$request->region}%");
            });
        }
        
        // Filtre par secteur
        if ($request->has('secteur') && !empty($request->secteur)) {
            $query->whereHas('secteur', function($q) use ($request) {
                $q->where('name', 'like', "%{$request->secteur}%");
            });
        }
        
        // Filtre par statut
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', 'like', "%{$request->status}%");
        }
        
        // Filtre par classification
        if ($request->has('classification') && !empty($request->classification)) {
            $query->where('classification', 'like', "%{$request->classification}%");
        }
        
        // Tri
        if ($request->has('sort_by') && !empty($request->sort_by)) {
            $sortable = ['name', 'population', 'area', 'density', 'created_at'];
            if (in_array($request->sort_by, $sortable)) {
                $query->orderBy($request->sort_by, $request->sort_direction ?? 'asc');
            }
        } else {
            $query->orderBy('name');
        }
        
        // Si requête AJAX
        if ($request->ajax()) {
            $perPage = $request->per_page ?? 10;
            $villes = $query->paginate($perPage);
            
            return response()->json([
                'success' => true,
                'data' => $villes->items(),
                'current_page' => $villes->currentPage(),
                'last_page' => $villes->lastPage(),
                'per_page' => $villes->perPage(),
                'total' => $villes->total(),
                'prev_page_url' => $villes->previousPageUrl(),
                'next_page_url' => $villes->nextPageUrl(),
            ]);
        }
        
        // Pour la vue normale
        $villes = $query->paginate(10);
        $countries = Country::orderBy('name')->get();
        $provinces = Province::orderBy('name')->get();
        $regions = Region::orderBy('name')->get();
        $secteurs = Secteur::orderBy('name')->get();
        $statuses = Ville::select('status')->distinct()->whereNotNull('status')->pluck('status');
        $classifications = Ville::select('classification')->distinct()->whereNotNull('classification')->pluck('classification');
        
        return view('destination::villes.index', compact('villes', 'countries', 'provinces', 'regions', 'secteurs', 'statuses', 'classifications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::orderBy('name')->get();
        $provinces = Province::orderBy('name')->get();
        $regions = Region::orderBy('name')->get();
        $secteurs = Secteur::orderBy('name')->get();
        
        return view('destination::villes.create', compact('countries', 'provinces', 'regions', 'secteurs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:20|unique:villes,code',
            'classification' => 'nullable|string|max:100',
            'status' => 'nullable|string|max:100',
            'population' => 'nullable|integer|min:0',
            'area' => 'nullable|numeric|min:0',
            'households' => 'nullable|integer|min:0',
            'altitude' => 'nullable|integer',
            'founding_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'mayor' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'history' => 'nullable|string',
            'economy' => 'nullable|string',
            'attractions' => 'nullable|string',
            'transport' => 'nullable|string',
            'education' => 'nullable|string',
            'culture' => 'nullable|string',
            'postal_code_prefix' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'country_id' => 'required|exists:countries,id',
            'province_id' => 'required|exists:provinces,id',
            'region_id' => 'nullable|exists:regions,id',
            'secteur_id' => 'nullable|exists:secteurs,id',
        ]);

        $ville = Ville::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Ville créée avec succès!',
                'data' => $ville->load(['country', 'province', 'region', 'secteur'])
            ]);
        }

        return redirect()->route('villes.index')
            ->with('success', 'Ville créée avec succès!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ville $ville)
    {
        $ville->load(['country', 'province', 'region', 'secteur']);
        
        // Statistiques liées (si vous avez d'autres modèles liés)
        $statistics = [
            'total_population' => $ville->population,
            'total_area' => $ville->area,
            'density' => $ville->density,
            'households' => $ville->households,
        ];
        
        return view('villes.show', compact('ville', 'statistics'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ville $ville)
    {
        $countries = Country::orderBy('name')->get();
        $provinces = Province::orderBy('name')->get();
        $regions = Region::orderBy('name')->get();
        $secteurs = Secteur::orderBy('name')->get();
        
        return view('villes.edit', compact('ville', 'countries', 'provinces', 'regions', 'secteurs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ville $ville)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:20|unique:villes,code,' . $ville->id,
            'classification' => 'nullable|string|max:100',
            'status' => 'nullable|string|max:100',
            'population' => 'nullable|integer|min:0',
            'area' => 'nullable|numeric|min:0',
            'households' => 'nullable|integer|min:0',
            'altitude' => 'nullable|integer',
            'founding_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'mayor' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'history' => 'nullable|string',
            'economy' => 'nullable|string',
            'attractions' => 'nullable|string',
            'transport' => 'nullable|string',
            'education' => 'nullable|string',
            'culture' => 'nullable|string',
            'postal_code_prefix' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'country_id' => 'required|exists:countries,id',
            'province_id' => 'required|exists:provinces,id',
            'region_id' => 'nullable|exists:regions,id',
            'secteur_id' => 'nullable|exists:secteurs,id',
        ]);

        $ville->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Ville mise à jour avec succès!',
                'data' => $ville->load(['country', 'province', 'region', 'secteur'])
            ]);
        }

        return redirect()->route('villes.index')
            ->with('success', 'Ville mise à jour avec succès!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Ville $ville)
    {
        try {
            DB::beginTransaction();
            
            // Supprimer la ville
            $ville->delete();
            
            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ville supprimée avec succès!'
                ]);
            }

            return redirect()->route('villes.index')
                ->with('success', 'Ville supprimée avec succès!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression de la ville: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('villes.index')
                ->with('error', 'Erreur lors de la suppression de la ville: ' . $e->getMessage());
        }
    }

    /**
     * Get statistics for dashboard
     */
    public function getStatistics(Request $request)
    {
        try {
            $stats = [
                'total_villes' => Ville::count(),
                'total_population' => (int)Ville::sum('population'),
                'total_area' => (float)Ville::sum('area'),
                'by_country' => Country::withCount('villes')
                    ->withSum('villes', 'population')
                    ->withSum('villes', 'area')
                    ->orderBy('villes_sum_population', 'desc')
                    ->get()
                    ->map(function($country) {
                        return [
                            'id' => $country->id,
                            'name' => $country->name,
                            'code' => $country->code,
                            'villes_count' => $country->villes_count,
                            'total_population' => $country->villes_sum_population,
                            'total_area' => $country->villes_sum_area,
                        ];
                    }),
                'by_province' => Province::withCount('villes')
                    ->withSum('villes', 'population')
                    ->withSum('villes', 'area')
                    ->orderBy('villes_sum_population', 'desc')
                    ->limit(10)
                    ->get()
                    ->map(function($province) {
                        return [
                            'id' => $province->id,
                            'name' => $province->name,
                            'code' => $province->code,
                            'villes_count' => $province->villes_count,
                            'total_population' => $province->villes_sum_population,
                            'total_area' => $province->villes_sum_area,
                        ];
                    }),
                'by_region' => Region::withCount('villes')
                    ->withSum('villes', 'population')
                    ->withSum('villes', 'area')
                    ->orderBy('villes_sum_population', 'desc')
                    ->limit(10)
                    ->get()
                    ->map(function($region) {
                        return [
                            'id' => $region->id,
                            'name' => $region->name,
                            'code' => $region->code,
                            'villes_count' => $region->villes_count,
                            'total_population' => $region->villes_sum_population,
                            'total_area' => $region->villes_sum_area,
                        ];
                    }),
                'by_status' => Ville::select('status', DB::raw('COUNT(*) as count'))
                    ->whereNotNull('status')
                    ->groupBy('status')
                    ->orderBy('count', 'desc')
                    ->get(),
                'by_classification' => Ville::select('classification', DB::raw('COUNT(*) as count'))
                    ->whereNotNull('classification')
                    ->groupBy('classification')
                    ->orderBy('count', 'desc')
                    ->get(),
                'most_populous' => Ville::with(['country', 'province'])
                    ->select('name', 'code', 'country_id', 'province_id', 'population')
                    ->orderBy('population', 'desc')
                    ->first(),
                'largest' => Ville::with(['country', 'province'])
                    ->select('name', 'code', 'country_id', 'province_id', 'area')
                    ->orderBy('area', 'desc')
                    ->first(),
                'highest_density' => Ville::with(['country', 'province'])
                    ->select('name', 'code', 'country_id', 'province_id', 'density')
                    ->where('density', '>', 0)
                    ->orderBy('density', 'desc')
                    ->first(),
                'highest_altitude' => Ville::with(['country', 'province'])
                    ->select('name', 'code', 'country_id', 'province_id', 'altitude')
                    ->whereNotNull('altitude')
                    ->orderBy('altitude', 'desc')
                    ->first(),
                'oldest' => Ville::with(['country', 'province'])
                    ->select('name', 'code', 'country_id', 'province_id', 'founding_year')
                    ->whereNotNull('founding_year')
                    ->orderBy('founding_year', 'asc')
                    ->first(),
                'newest' => Ville::with(['country', 'province'])
                    ->select('name', 'code', 'country_id', 'province_id', 'founding_year')
                    ->whereNotNull('founding_year')
                    ->orderBy('founding_year', 'desc')
                    ->first(),
                'average_population' => (int)Ville::avg('population'),
                'average_area' => (float)Ville::avg('area'),
                'average_density' => (float)Ville::avg('density'),
                'total_households' => (int)Ville::sum('households'),
                'capitals_count' => Ville::where('status', 'like', '%capitale%')->count(),
                'metropolis_count' => Ville::where('status', 'like', '%métropole%')->count(),
                'latest_villes' => Ville::with(['country', 'province'])
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get()
            ];

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $stats,
                    'message' => 'Statistiques des villes récupérées avec succès'
                ]);
            }

            return $stats;

        } catch (\Exception $e) {
            \Log::error('Erreur dans getStatistics: ' . $e->getMessage());
            
            $errorResponse = [
                'success' => false,
                'message' => 'Erreur lors du calcul des statistiques',
                'error' => $e->getMessage()
            ];

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json($errorResponse, 500);
            }

            return $errorResponse;
        }
    }

    /**
     * Get villes by country
     */
    public function getByCountry($countryCode)
    {
        $villes = Ville::whereHas('country', function($query) use ($countryCode) {
            $query->where('code', $countryCode);
        })
        ->orderBy('name')
        ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $villes
        ]);
    }

    /**
     * Get villes by province
     */
    public function getByProvince($provinceCode)
    {
        $villes = Ville::whereHas('province', function($query) use ($provinceCode) {
            $query->where('code', $provinceCode);
        })
        ->orderBy('name')
        ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $villes
        ]);
    }

    /**
     * Get villes by region
     */
    public function getByRegion($regionCode)
    {
        $villes = Ville::whereHas('region', function($query) use ($regionCode) {
            $query->where('code', $regionCode);
        })
        ->orderBy('name')
        ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $villes
        ]);
    }

    /**
     * Search villes (autocomplete)
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        $villes = Ville::where('name', 'like', "%{$query}%")
            ->orWhere('code', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'name', 'code', 'country_id', 'province_id', 'region_id'])
            ->load(['country', 'province', 'region']);
            
        return response()->json([
            'success' => true,
            'data' => $villes
        ]);
    }

    /**
     * Get villes by status
     */
    public function getByStatus($status)
    {
        $villes = Ville::where('status', 'like', "%{$status}%")
            ->orderBy('name')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $villes
        ]);
    }

    /**
     * Get villes by classification
     */
    public function getByClassification($classification)
    {
        $villes = Ville::where('classification', 'like', "%{$classification}%")
            ->orderBy('name')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $villes
        ]);
    }

    /**
     * Export villes data
     */
    public function export(Request $request)
    {
        $villes = Ville::with(['country', 'province', 'region'])
            ->orderBy('name')
            ->get();

        // Logique d'export CSV, Excel, etc.
        if ($request->format === 'csv') {
            return response()->json([
                'success' => true,
                'message' => 'Export CSV non implémenté',
                'data' => $villes
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $villes,
            'total' => $villes->count()
        ]);
    }

    /**
     * Restore soft deleted ville
     */
    public function restore($id)
    {
        try {
            $ville = Ville::withTrashed()->findOrFail($id);
            $ville->restore();

            return response()->json([
                'success' => true,
                'message' => 'Ville restaurée avec succès!',
                'data' => $ville
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la restauration: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get provinces for a country (for dropdowns)
     */
    public function getProvincesByCountry($countryId)
    {
        $provinces = Province::where('country_id', $countryId)
            ->orderBy('name')
            ->get(['id', 'name', 'code']);

        return response()->json([
            'success' => true,
            'data' => $provinces
        ]);
    }

    /**
     * Get regions for a province (for dropdowns)
     */
    public function getRegionsByProvince($provinceId)
    {
        $regions = Region::where('province_id', $provinceId)
            ->orderBy('name')
            ->get(['id', 'name', 'code']);

        return response()->json([
            'success' => true,
            'data' => $regions
        ]);
    }

    /**
     * Get secteurs for a region (for dropdowns)
     */
    public function getSecteursByRegion($regionId)
    {
        $secteurs = Secteur::where('region_id', $regionId)
            ->orderBy('name')
            ->get(['id', 'name', 'code']);

        return response()->json([
            'success' => true,
            'data' => $secteurs
        ]);
    }
}