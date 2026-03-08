<?php

namespace Vendor\Destination\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\Region;
use App\Models\Secteur;
use App\Models\Ville;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Region::with(['province', 'cities', 'secteurs'])
            ->withCount(['cities', 'secteurs']);
        
        // Recherche
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('capital', 'like', "%{$search}%")
                  ->orWhere('largest_city', 'like', "%{$search}%")
                  ->orWhere('classification', 'like', "%{$search}%");
            });
        }
        
        // Filtre par province
        if ($request->has('province') && !empty($request->province)) {
            $query->whereHas('province', function($q) use ($request) {
                $q->where('code', $request->province)
                  ->orWhere('name', 'like', "%{$request->province}%");
            });
        }
        
        // Filtre par classification
        if ($request->has('classification') && !empty($request->classification)) {
            $query->where('classification', 'like', "%{$request->classification}%");
        }
        
        // Tri
        if ($request->has('sort_by') && !empty($request->sort_by)) {
            $sortable = ['name', 'code', 'population', 'area', 'municipalities_count', 'created_at'];
            if (in_array($request->sort_by, $sortable)) {
                $query->orderBy($request->sort_by, $request->sort_direction ?? 'asc');
            }
        } else {
            $query->orderBy('name');
        }
        
        // Si requête AJAX
        if ($request->ajax()) {
            $perPage = $request->per_page ?? 10;
            $regions = $query->paginate($perPage);
            
            return response()->json([
                'success' => true,
                'data' => $regions->items(),
                'current_page' => $regions->currentPage(),
                'last_page' => $regions->lastPage(),
                'per_page' => $regions->perPage(),
                'total' => $regions->total(),
                'prev_page_url' => $regions->previousPageUrl(),
                'next_page_url' => $regions->nextPageUrl(),
            ]);
        }
        
        // Pour la vue normale
        $regions = $query->paginate(10);
        $provinces = Province::orderBy('name')->get();
        $classifications = Region::select('classification')->distinct()->pluck('classification');
        
        return view('destination::regions.index', compact('regions', 'provinces', 'classifications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $provinces = Province::orderBy('name')->get();
        return view('regions.create', compact('provinces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:10|unique:regions,code',
            'capital' => 'nullable|string|max:255',
            'largest_city' => 'nullable|string|max:255',
            'classification' => 'nullable|string|max:100',
            'population' => 'nullable|integer|min:0',
            'area' => 'nullable|numeric|min:0',
            'municipalities_count' => 'nullable|integer|min:0',
            'timezone' => 'nullable|string|max:50',
            'flag' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'geography' => 'nullable|string',
            'economy' => 'nullable|string',
            'tourism' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'province_id' => 'required|exists:provinces,id',
        ]);

        $region = Region::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Région créée avec succès!',
                'data' => $region->load('province')
            ]);
        }

        return redirect()->route('regions.index')
            ->with('success', 'Région créée avec succès!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $region)
    {
        $region->load([
            'province', 
            'cities' => function($query) {
                $query->orderBy('population', 'desc')->limit(10);
            }, 
            'secteurs' => function($query) {
                $query->orderBy('name')->limit(10);
            }
        ]);
        
        $statistics = [
            'total_cities' => $region->cities()->count(),
            'total_secteurs' => $region->secteurs()->count(),
            'population_density' => $region->population_density,
            'total_municipalities' => $region->municipalities_count,
            'largest_city' => $region->cities()->orderBy('population', 'desc')->first(),
            'most_touristic_secteur' => $region->secteurs()->first(),
        ];
        
        return view('destination::regions.show', compact('region', 'statistics'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Region $region)
    {
        $provinces = Province::orderBy('name')->get();
        return view('regions.edit', compact('region', 'provinces'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Region $region)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:10|unique:regions,code,' . $region->id,
            'capital' => 'nullable|string|max:255',
            'largest_city' => 'nullable|string|max:255',
            'classification' => 'nullable|string|max:100',
            'population' => 'nullable|integer|min:0',
            'area' => 'nullable|numeric|min:0',
            'municipalities_count' => 'nullable|integer|min:0',
            'timezone' => 'nullable|string|max:50',
            'flag' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'geography' => 'nullable|string',
            'economy' => 'nullable|string',
            'tourism' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'province_id' => 'required|exists:provinces,id',
        ]);

        $region->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Région mise à jour avec succès!',
                'data' => $region->load('province')
            ]);
        }

        return redirect()->route('regions.index')
            ->with('success', 'Région mise à jour avec succès!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Region $region)
    {
        try {
            DB::beginTransaction();
            
            // Supprimer les villes associées
            $region->cities()->delete();
            
            // Supprimer les secteurs associés
            $region->secteurs()->delete();
            
            // Supprimer la région
            $region->delete();
            
            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Région supprimée avec succès!'
                ]);
            }

            return redirect()->route('regions.index')
                ->with('success', 'Région supprimée avec succès!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression de la région: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('regions.index')
                ->with('error', 'Erreur lors de la suppression de la région: ' . $e->getMessage());
        }
    }

    /**
     * Get statistics for dashboard
     */
    public function getStatistics(Request $request)
    {
        try {
            $stats = [
                'total_regions' => Region::count(),
                'total_population' => (int)Region::sum('population'),
                'total_area' => (float)Region::sum('area'),
                'by_province' => Province::withCount('regions')
                    ->withSum('regions', 'population')
                    ->withSum('regions', 'area')
                    ->orderBy('regions_sum_population', 'desc')
                    ->get()
                    ->map(function($province) {
                        return [
                            'id' => $province->id,
                            'name' => $province->name,
                            'code' => $province->code,
                            'regions_count' => $province->regions_count,
                            'total_population' => $province->regions_sum_population,
                            'total_area' => $province->regions_sum_area,
                        ];
                    }),
                'by_classification' => Region::select('classification', DB::raw('COUNT(*) as count'))
                    ->groupBy('classification')
                    ->orderBy('count', 'desc')
                    ->get(),
                'most_populous' => Region::with('province')
                    ->select('name', 'code', 'province_id', 'population')
                    ->orderBy('population', 'desc')
                    ->first(),
                'largest' => Region::with('province')
                    ->select('name', 'code', 'province_id', 'area')
                    ->orderBy('area', 'desc')
                    ->first(),
                'smallest' => Region::with('province')
                    ->select('name', 'code', 'province_id', 'area')
                    ->where('area', '>', 0)
                    ->orderBy('area', 'asc')
                    ->first(),
                'average_population' => (int)Region::avg('population'),
                'average_area' => (float)Region::avg('area'),
                'total_municipalities' => (int)Region::sum('municipalities_count'),
                'density' => [
                    'highest' => Region::select('name', 'code', 'province_id', 
                        DB::raw('population/area as density'),
                        DB::raw('FORMAT(population/area, 2) as formatted_density')
                    )
                        ->where('area', '>', 0)
                        ->where('population', '>', 0)
                        ->orderBy('density', 'desc')
                        ->first(),
                    'lowest' => Region::select('name', 'code', 'province_id',
                        DB::raw('population/area as density'),
                        DB::raw('FORMAT(population/area, 2) as formatted_density')
                    )
                        ->where('area', '>', 0)
                        ->where('population', '>', 0)
                        ->orderBy('density', 'asc')
                        ->first(),
                ],
                'timezones' => Region::select('timezone', DB::raw('COUNT(*) as count'))
                    ->whereNotNull('timezone')
                    ->groupBy('timezone')
                    ->orderBy('count', 'desc')
                    ->get(),
                'latest_regions' => Region::with('province')
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get()
            ];

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $stats,
                    'message' => 'Statistiques des régions récupérées avec succès'
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
     * Get cities for a specific region
     */
    public function getCities(Region $region)
    {
        $cities = $region->cities()
            ->orderBy('name')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $cities
        ]);
    }

    /**
     * Get secteurs for a specific region
     */
    public function getSecteurs(Region $region)
    {
        $secteurs = $region->secteurs()
            ->orderBy('name')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $secteurs
        ]);
    }

    /**
     * Get regions by province
     */
    public function getByProvince($provinceCode)
    {
        $regions = Region::whereHas('province', function($query) use ($provinceCode) {
            $query->where('code', $provinceCode);
        })
        ->orderBy('name')
        ->get();

        return response()->json([
            'success' => true,
            'data' => $regions
        ]);
    }

    /**
     * Search regions (autocomplete)
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        $regions = Region::where('name', 'like', "%{$query}%")
            ->orWhere('code', 'like', "%{$query}%")
            ->orWhere('capital', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'name', 'code', 'province_id'])
            ->load('province');
            
        return response()->json([
            'success' => true,
            'data' => $regions
        ]);
    }

    /**
     * Get regions by classification
     */
    public function getByClassification($classification)
    {
        $regions = Region::where('classification', 'like', "%{$classification}%")
            ->orderBy('name')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $regions
        ]);
    }

    /**
     * Export regions data
     */
    public function export(Request $request)
    {
        $regions = Region::with('province')
            ->orderBy('name')
            ->get();

        // Vous pouvez adapter ceci pour exporter en CSV, Excel, etc.
        // Pour l'instant, on retourne du JSON
        if ($request->format === 'csv') {
            // Logique pour exporter en CSV
            return response()->json([
                'success' => true,
                'message' => 'Export CSV non implémenté',
                'data' => $regions
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $regions,
            'total' => $regions->count()
        ]);
    }

    /**
     * Restore soft deleted region
     */
    public function restore($id)
    {
        try {
            $region = Region::withTrashed()->findOrFail($id);
            $region->restore();

            return response()->json([
                'success' => true,
                'message' => 'Région restaurée avec succès!',
                'data' => $region
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la restauration: ' . $e->getMessage()
            ], 500);
        }
    }
}