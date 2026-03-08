<?php

namespace Vendor\Destination\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\Country;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Province::with(['country', 'regions'])
            ->withCount(['regions', 'villes']);
        
        // Recherche
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('capital', 'like', "%{$search}%")
                  ->orWhere('largest_city', 'like', "%{$search}%");
            });
        }
        
        // Filtre par pays
        if ($request->has('country') && !empty($request->country)) {
            $query->whereHas('country', function($q) use ($request) {
                $q->where('code', $request->country)
                  ->orWhere('name', 'like', "%{$request->country}%");
            });
        }
        
        // Tri
        if ($request->has('sort_by') && !empty($request->sort_by)) {
            $sortable = ['name', 'code', 'population', 'area', 'created_at'];
            if (in_array($request->sort_by, $sortable)) {
                $query->orderBy($request->sort_by, $request->sort_direction ?? 'asc');
            }
        } else {
            $query->orderBy('name');
        }
        
        // Si requête AJAX
        if ($request->ajax()) {
            $perPage = $request->per_page ?? 10;
            $provinces = $query->paginate($perPage);
            
            return response()->json([
                'success' => true,
                'data' => $provinces->items(),
                'current_page' => $provinces->currentPage(),
                'last_page' => $provinces->lastPage(),
                'per_page' => $provinces->perPage(),
                'total' => $provinces->total(),
                'prev_page_url' => $provinces->previousPageUrl(),
                'next_page_url' => $provinces->nextPageUrl(),
            ]);
        }
        
        // Pour la vue normale
        $provinces = $query->paginate(10);
        $countries = Country::orderBy('name')->get();
        
        return view('destination::provinces.index', compact('provinces', 'countries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::orderBy('name')->get();
        return view('provinces.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:10',
            'capital' => 'nullable|string|max:255',
            'largest_city' => 'nullable|string|max:255',
            'official_language' => 'nullable|string|max:255',
            'area_rank' => 'nullable|string|max:10',
            'population' => 'nullable|integer|min:0',
            'area' => 'nullable|numeric|min:0',
            'timezone' => 'nullable|string|max:50',
            'flag' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'latitude' => 'nullable|string|max:20',
            'longitude' => 'nullable|string|max:20',
            'country_id' => 'required|exists:countries,id',
        ]);

        $province = Province::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Province créée avec succès!',
                'data' => $province->load('country')
            ]);
        }

        return redirect()->route('provinces.index')
            ->with('success', 'Province créée avec succès!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Province $province)
    {
        $province->load(['country', 'regions' => function($query) {
            $query->orderBy('name');
        }, 'villes' => function($query) {
            $query->orderBy('name')->limit(10);
        }]);
        
        $statistics = [
            'total_regions' => $province->regions_count,
            'total_villes' => $province->villes_count,
            'total_population' => $province->regions()->sum('population'),
            'total_area' => $province->regions()->sum('area'),
            'most_populous_region' => $province->regions()->orderBy('population', 'desc')->first(),
            'largest_region' => $province->regions()->orderBy('area', 'desc')->first(),
        ];
        
        return view('provinces.show', compact('province', 'statistics'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Province $province)
    {
        $countries = Country::orderBy('name')->get();
        return view('provinces.edit', compact('province', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Province $province)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:10',
            'capital' => 'nullable|string|max:255',
            'largest_city' => 'nullable|string|max:255',
            'official_language' => 'nullable|string|max:255',
            'area_rank' => 'nullable|string|max:10',
            'population' => 'nullable|integer|min:0',
            'area' => 'nullable|numeric|min:0',
            'timezone' => 'nullable|string|max:50',
            'flag' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'latitude' => 'nullable|string|max:20',
            'longitude' => 'nullable|string|max:20',
            'country_id' => 'required|exists:countries,id',
        ]);

        $province->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Province mise à jour avec succès!',
                'data' => $province->load('country')
            ]);
        }

        return redirect()->route('provinces.index')
            ->with('success', 'Province mise à jour avec succès!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Province $province)
    {
        try {
            DB::beginTransaction();
            
            // Supprimer les régions associées
            $province->regions()->delete();
            
            // Supprimer les villes associées
            $province->villes()->delete();
            
            // Supprimer la province
            $province->delete();
            
            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Province supprimée avec succès!'
                ]);
            }

            return redirect()->route('provinces.index')
                ->with('success', 'Province supprimée avec succès!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression de la province: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('provinces.index')
                ->with('error', 'Erreur lors de la suppression de la province: ' . $e->getMessage());
        }
    }

    /**
     * Get statistics for dashboard
     */
    public function getStatistics(Request $request)
    {
        try {
            $stats = [
                'total_provinces' => Province::count(),
                'total_population' => (int)Province::sum('population'),
                'total_area' => (float)Province::sum('area'),
                'by_country' => Country::withCount('provinces')
                    ->withSum('provinces', 'population')
                    ->withSum('provinces', 'area')
                    ->orderBy('provinces_sum_population', 'desc')
                    ->get()
                    ->map(function($country) {
                        return [
                            'id' => $country->id,
                            'name' => $country->name,
                            'code' => $country->code,
                            'provinces_count' => $country->provinces_count,
                            'total_population' => $country->provinces_sum_population,
                            'total_area' => $country->provinces_sum_area,
                        ];
                    }),
                'most_populous' => Province::with('country')
                    ->select('name', 'code', 'country_id', 'population')
                    ->orderBy('population', 'desc')
                    ->first(),
                'largest' => Province::with('country')
                    ->select('name', 'code', 'country_id', 'area')
                    ->orderBy('area', 'desc')
                    ->first(),
                'smallest' => Province::with('country')
                    ->select('name', 'code', 'country_id', 'area')
                    ->where('area', '>', 0)
                    ->orderBy('area', 'asc')
                    ->first(),
                'average_population' => (int)Province::avg('population'),
                'average_area' => (float)Province::avg('area'),
                'density' => [
                    'highest' => Province::select('name', 'code', 'country_id', DB::raw('population/area as density'))
                        ->where('area', '>', 0)
                        ->orderBy('density', 'desc')
                        ->first(),
                    'lowest' => Province::select('name', 'code', 'country_id', DB::raw('population/area as density'))
                        ->where('area', '>', 0)
                        ->orderBy('density', 'asc')
                        ->first(),
                ]
            ];

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $stats,
                    'message' => 'Statistiques des provinces récupérées avec succès'
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
     * Get regions for a specific province
     */
    public function getRegions(Province $province)
    {
        $regions = $province->regions()
            ->orderBy('name')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $regions
        ]);
    }

    /**
     * Get villes for a specific province
     */
    public function getVilles(Province $province)
    {
        $villes = $province->villes()
            ->orderBy('name')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $villes
        ]);
    }

    /**
     * Get provinces by country
     */
    public function getByCountry($countryCode)
    {
        $provinces = Province::whereHas('country', function($query) use ($countryCode) {
            $query->where('code', $countryCode);
        })
        ->orderBy('name')
        ->get();

        return response()->json([
            'success' => true,
            'data' => $provinces
        ]);
    }

    /**
     * Search provinces (autocomplete)
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        $provinces = Province::where('name', 'like', "%{$query}%")
            ->orWhere('code', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'name', 'code', 'country_id'])
            ->load('country');
            
        return response()->json([
            'success' => true,
            'data' => $provinces
        ]);
    }

    public function toggleStatus(Request $request, Province $province)
{
    try {
        \Log::info('ToggleStatus Province - Début', [
            'province_id' => $province->id,
            'province_name' => $province->name,
            'input_data' => $request->all(),
            'current_status' => $province->is_active
        ]);
        
        $validated = $request->validate([
            'is_active' => 'required'
        ]);
        
        \Log::info('ToggleStatus Province - Données validées', [
            'validated_data' => $validated,
            'validated_is_active_type' => gettype($validated['is_active']),
            'validated_is_active_value' => $validated['is_active']
        ]);
        
        // Déterminer la nouvelle valeur
        $newStatus = false;
        
        if ($validated['is_active'] === true || 
            $validated['is_active'] === 'true' || 
            $validated['is_active'] === 1 || 
            $validated['is_active'] === '1') {
            $newStatus = true;
        }
        
        \Log::info('ToggleStatus Province - Nouveau statut déterminé', [
            'new_status_bool' => $newStatus,
            'new_status_int' => $newStatus ? 1 : 0
        ]);
        
        // Mettre à jour la province
        $province->update([
            'is_active' => $newStatus ? 1 : 0
        ]);
        
        // Recharger le modèle pour vérifier
        $province->refresh();
        
        \Log::info('ToggleStatus Province - Mise à jour réussie', [
            'updated_status' => $province->is_active,
            'updated_at' => $province->updated_at
        ]);
        
        return response()->json([
            'success' => true,
            'message' => $newStatus ? 'Province activée avec succès' : 'Province désactivée avec succès',
            'data' => $province
        ]);
        
    } catch (\Exception $e) {
        \Log::error('ToggleStatus Province - Erreur', [
            'province_id' => $province->id ?? 'N/A',
            'error_message' => $e->getMessage(),
            'error_trace' => $e->getTraceAsString(),
            'request_data' => $request->all()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la mise à jour du statut: ' . $e->getMessage(),
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function page($provinceId)
    {
        $pages = \App\Models\Page::where('pageable_type', 'App\Models\Province')
            ->where('pageable_id', $provinceId)
            ->with('pageable') // Charge la relation province
            ->get();
        return view('destination::provinces.pages.index', compact('pages'));
    }
}