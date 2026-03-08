<?php

namespace Vendor\Destination\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Continent;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Country::with(['continent', 'provinces'])
            ->withCount('provinces');
        
        // Recherche
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('iso2', 'like', "%{$search}%")
                  ->orWhere('capital', 'like', "%{$search}%")
                  ->orWhere('currency', 'like', "%{$search}%");
            });
        }
        
        // Filtre par continent
        if ($request->has('continent') && !empty($request->continent)) {
            $query->whereHas('continent', function($q) use ($request) {
                $q->where('code', $request->continent)
                  ->orWhere('name', 'like', "%{$request->continent}%");
            });
        }
        
        // Filtre par région
        if ($request->has('region') && !empty($request->region)) {
            $query->where('region', 'like', "%{$request->region}%");
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
            $countries = $query->paginate($perPage);
            
            return response()->json([
                'success' => true,
                'data' => $countries->items(),
                'current_page' => $countries->currentPage(),
                'last_page' => $countries->lastPage(),
                'per_page' => $countries->perPage(),
                'total' => $countries->total(),
                'prev_page_url' => $countries->previousPageUrl(),
                'next_page_url' => $countries->nextPageUrl(),
            ]);
        }
        
        // Pour la vue normale
        $countries = $query->paginate(10);
        $continents = Continent::orderBy('name')->get();
        
        return view('destination::countries.index', compact('countries', 'continents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $continents = Continent::orderBy('name')->get();
        return view('countries.create', compact('continents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Use fully qualified namespace with backslash
            \Log::info('Starting country store process', [
                'user_id' => auth()->id(),
                'request_data' => $request->except(['image', '_token']),
                'has_file' => $request->hasFile('image'),
                'file_size' => $request->hasFile('image') ? $request->file('image')->getSize() : null,
            ]);

            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:countries',
                'code' => 'required|string|max:3|unique:countries',
                'iso2' => 'nullable|string|max:2|unique:countries',
                'phone_code' => 'nullable|string|max:10',
                'capital' => 'nullable|string|max:255',
                'currency' => 'nullable|string|max:100',
                'currency_symbol' => 'nullable|string|max:10',
                'latitude' => 'nullable|string|max:20',
                'longitude' => 'nullable|string|max:20',
                'description' => 'nullable|string',
                'population' => 'nullable|integer|min:0',
                'area' => 'nullable|numeric|min:0',
                'official_language' => 'nullable|string|max:255',
                'timezones' => 'nullable|array',
                'region' => 'nullable|string|max:255',
                'continent_id' => 'required|exists:continents,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);

            \Log::info('Validation passed for country store', [
                'validated_fields' => array_keys($validated),
                'country_name' => $validated['name'],
                'country_code' => $validated['code'],
            ]);

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                try {
                    $image = $request->file('image');
                    
                    \Log::debug('Image file details', [
                        'original_name' => $image->getClientOriginalName(),
                        'original_extension' => $image->getClientOriginalExtension(),
                        'mime_type' => $image->getMimeType(),
                        'size' => $image->getSize(),
                        'temp_path' => $image->getPathname(),
                        'is_valid' => $image->isValid(),
                    ]);

                    // Generate unique filename with country code and original extension
                    $extension = $image->getClientOriginalExtension();
                    $filename = strtolower($validated['code']) . '_' . time() . '.' . $extension;
                    
                    // Define the storage path
                    $path = 'countries';
                    
                    // Store file in storage/app/public/countries directory
                    \Log::info('Attempting to store image file', [
                        'filename' => $filename,
                        'path' => $path,
                        'disk' => 'public',
                    ]);
                    
                    $storedPath = $image->storeAs($path, $filename, 'public');
                    
                    if (!$storedPath) {
                        throw new \Exception('Failed to store image file');
                    }
                    
                    $imagePath = $storedPath;
                    \Log::info('Image stored successfully', [
                        'stored_path' => $storedPath,
                        'full_url' => Storage::disk('public')->url($storedPath),
                    ]);
                    
                } catch (\Exception $imageException) {
                    \Log::error('Image upload failed', [
                        'error' => $imageException->getMessage(),
                        'trace' => $imageException->getTraceAsString(),
                        'country_code' => $validated['code'] ?? 'unknown',
                    ]);
                    
                    // Continue without image if upload fails
                    $imagePath = $request->input('flag', null);
                }
            } else {
                // Use URL if provided (backward compatibility)
                $imagePath = $request->input('flag', null);
                
                if ($request->hasFile('image') && !$request->file('image')->isValid()) {
                    \Log::warning('Image file exists but is invalid', [
                        'error' => $request->file('image')->getErrorMessage(),
                    ]);
                }
            }

            // Store relative path in database
            $validated['image'] = $imagePath;

            // Convert timezones to JSON if provided
            if ($request->has('timezones')) {
                $validated['timezones'] = json_encode($validated['timezones']);
                \Log::debug('Timezones processed', [
                    'timezones_count' => count($validated['timezones']),
                    'timezones' => $validated['timezones'],
                ]);
            }

            \Log::info('Creating country record', [
                'data' => array_merge($validated, ['flag' => $imagePath]),
            ]);

            $country = Country::create($validated);

            \Log::info('Country created successfully', [
                'country_id' => $country->id,
                'country_name' => $country->name,
                'country_code' => $country->code,
                'created_at' => $country->created_at,
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pays créé avec succès!',
                    'data' => $country->load('continent')
                ]);
            }

            return redirect()->route('countries.index')
                ->with('success', 'Pays créé avec succès!');

        } catch (\Illuminate\Validation\ValidationException $validationException) {
            \Log::error('Validation failed during country store', [
                'errors' => $validationException->errors(),
                'request_data' => $request->except(['image', '_token']),
                'user_id' => auth()->id(),
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validationException->errors()
                ], 422);
            }

            throw $validationException;

        } catch (\Exception $exception) {
            \Log::error('Error storing country', [
                'error' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
                'request_data' => $request->except(['image', '_token']),
                'user_id' => auth()->id(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue lors de la création du pays. Veuillez réessayer.',
                    'error_details' => config('app.debug') ? $exception->getMessage() : null
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création du pays. Veuillez réessayer.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Country $country)
    {
        try {
            \Log::info('Starting country update process', [
                'user_id' => auth()->id(),
                'country_id' => $country->id,
                'country_name' => $country->name,
                'request_data' => $request->except(['image', '_token', '_method']),
                'has_file' => $request->hasFile('image'),
                'has_flag_remove' => $request->has('flag_remove'),
            ]);

            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:countries,name,' . $country->id,
                'code' => 'required|string|max:3|unique:countries,code,' . $country->id,
                'iso2' => 'nullable|string|max:2|unique:countries,iso2,' . $country->id,
                'phone_code' => 'nullable|string|max:10',
                'capital' => 'nullable|string|max:255',
                'currency' => 'nullable|string|max:100',
                'currency_symbol' => 'nullable|string|max:10',
                'latitude' => 'nullable|string|max:20',
                'longitude' => 'nullable|string|max:20',
                'description' => 'nullable|string',
                'population' => 'nullable|integer|min:0',
                'area' => 'nullable|numeric|min:0',
                'official_language' => 'nullable|string|max:255',
                'timezones' => 'nullable|array',
                'region' => 'nullable|string|max:255',
                'continent_id' => 'required|exists:continents,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:20048',
            ]);

            \Log::info('Validation passed for country update', [
                'country_id' => $country->id,
                'validated_fields' => array_keys($validated),
                'new_name' => $validated['name'],
                'new_code' => $validated['code'],
            ]);

            $currentFlag = $country->flag;
            $newImagePath = $currentFlag;
            $imageUploaded = false;

            // Handle image upload if new image is provided
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                try {
                    // Delete old file if it exists and is stored locally
                    if ($currentFlag && !Str::startsWith($currentFlag, ['http://', 'https://'])) {
                        \Log::info('Attempting to delete old image file', [
                            'old_path' => $currentFlag,
                            'exists' => Storage::disk('public')->exists($currentFlag),
                        ]);
                        
                        if (Storage::disk('public')->exists($currentFlag)) {
                            $deleted = Storage::disk('public')->delete($currentFlag);
                            \Log::info('Old image deletion result', [
                                'deleted' => $deleted,
                                'path' => $currentFlag,
                            ]);
                        }
                    }

                    $image = $request->file('image');
                    
                    \Log::debug('New image file details', [
                        'original_name' => $image->getClientOriginalName(),
                        'original_extension' => $image->getClientOriginalExtension(),
                        'mime_type' => $image->getMimeType(),
                        'size' => $image->getSize(),
                        'temp_path' => $image->getPathname(),
                    ]);

                    // Generate unique filename with country code and original extension
                    $extension = $image->getClientOriginalExtension();
                    $filename = strtolower($validated['code']) . '_' . time() . '.' . $extension;
                    
                    // Define the storage path
                    $path = 'countries';
                    
                    // Store file in storage/app/public/countries directory
                    \Log::info('Attempting to store new image file', [
                        'filename' => $filename,
                        'path' => $path,
                        'disk' => 'public',
                    ]);
                    
                    $storedPath = $image->storeAs($path, $filename, 'public');
                    
                    if (!$storedPath) {
                        throw new \Exception('Failed to store new image file');
                    }
                    
                    $newImagePath = $storedPath;
                    $imageUploaded = true;
                    
                    \Log::info('New image stored successfully', [
                        'stored_path' => $storedPath,
                        'full_url' => Storage::disk('public')->url($storedPath),
                        'replaced_old_path' => $currentFlag,
                    ]);
                    
                } catch (\Exception $imageException) {
                    \Log::error('New image upload failed during update', [
                        'error' => $imageException->getMessage(),
                        'trace' => $imageException->getTraceAsString(),
                        'country_id' => $country->id,
                        'country_code' => $validated['code'],
                    ]);
                    
                    // Keep old image if new upload fails
                    $newImagePath = $currentFlag;
                }
            } 
            // Handle flag removal if requested
            elseif ($request->has('flag_remove') && $request->input('flag_remove') == 'true') {
                \Log::info('Flag removal requested', [
                    'country_id' => $country->id,
                    'current_flag' => $currentFlag,
                ]);
                
                if ($currentFlag && !Str::startsWith($currentFlag, ['http://', 'https://'])) {
                    if (Storage::disk('public')->exists($currentFlag)) {
                        $deleted = Storage::disk('public')->delete($currentFlag);
                        \Log::info('Flag file deleted', [
                            'deleted' => $deleted,
                            'path' => $currentFlag,
                        ]);
                    }
                }
                $newImagePath = null;
            }
            // Keep existing flag URL if no new file uploaded
            elseif ($request->has('flag') && $request->input('flag') !== null) {
                $newImagePath = $request->input('flag');
                \Log::debug('Keeping existing flag URL', [
                    'flag_url' => $newImagePath,
                ]);
            }
            // Keep existing flag if no changes
            else {
                $newImagePath = $currentFlag;
                \Log::debug('No image changes, keeping current flag', [
                    'current_flag' => $currentFlag,
                ]);
            }

            // Store relative path in database
            $validated['image'] = $newImagePath;

            // Convert timezones to JSON if provided
            if ($request->has('timezones')) {
                $validated['timezones'] = json_encode($validated['timezones']);
                // \Log::debug('Timezones processed for update', [
                //     'timezones_count' => count($validated['timezones']),
                // ]);
            } elseif ($request->has('timezones') && empty($request->timezones)) {
                $validated['timezones'] = null;
                \Log::debug('Clearing timezones', [
                    'reason' => 'empty timezones array provided',
                ]);
            }

            \Log::info('Updating country record', [
                'country_id' => $country->id,
                'changes' => $validated,
                'image_uploaded' => $imageUploaded,
                'old_flag' => $currentFlag,
                'new_flag' => $newImagePath,
            ]);

            $country->update($validated);

            \Log::info('Country updated successfully', [
                'country_id' => $country->id,
                'country_name' => $country->name,
                'updated_at' => $country->updated_at,
                'image_changed' => $imageUploaded || ($newImagePath !== $currentFlag),
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pays mis à jour avec succès!',
                    'data' => $country->load('continent')
                ]);
            }

            return redirect()->route('countries.index')
                ->with('success', 'Pays mis à jour avec succès!');

        } catch (\Illuminate\Validation\ValidationException $validationException) {
            \Log::error('Validation failed during country update', [
                'country_id' => $country->id,
                'errors' => $validationException->errors(),
                'request_data' => $request->except(['image', '_token', '_method']),
                'user_id' => auth()->id(),
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validationException->errors()
                ], 422);
            }

            throw $validationException;

        } catch (\Exception $exception) {
            \Log::error('Error updating country', [
                'error' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
                'country_id' => $country->id,
                'request_data' => $request->except(['image', '_token', '_method']),
                'user_id' => auth()->id(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue lors de la mise à jour du pays. Veuillez réessayer.',
                    'error_details' => config('app.debug') ? $exception->getMessage() : null
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour du pays. Veuillez réessayer.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Country $country)
    {
        $country->load(['continent', 'provinces' => function($query) {
            $query->orderBy('name');
        }]);
        
        $statistics = [
            'total_provinces' => $country->provinces_count,
            'total_population' => $country->provinces()->sum('population'),
            'total_area' => $country->provinces()->sum('area'),
            'most_populous_province' => $country->provinces()->orderBy('population', 'desc')->first(),
            'largest_province' => $country->provinces()->orderBy('area', 'desc')->first(),
        ];
        
        return view('countries.show', compact('country', 'statistics'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Country $country)
    {
        $continents = Continent::orderBy('name')->get();
        return view('countries.edit', compact('country', 'continents'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $country)
    {
        try {
            \Log::info('Starting country deletion', [
                'user_id' => auth()->id(),
                'country_id' => $country->id,
                'country_name' => $country->name,
                'country_code' => $country->code,
                'flag_path' => $country->flag,
            ]);

            // Delete associated image file if it exists and is stored locally
            if ($country->flag && !Str::startsWith($country->flag, ['http://', 'https://'])) {
                \Log::info('Attempting to delete country image file', [
                    'flag_path' => $country->flag,
                    'exists' => Storage::disk('public')->exists($country->flag),
                ]);
                
                if (Storage::disk('public')->exists($country->flag)) {
                    $deleted = Storage::disk('public')->delete($country->flag);
                    \Log::info('Country image file deletion result', [
                        'deleted' => $deleted,
                        'path' => $country->flag,
                    ]);
                }
            }

            \Log::info('Deleting country record from database', [
                'country_id' => $country->id,
            ]);
            
            $country->delete();

            \Log::info('Country deleted successfully', [
                'country_id' => $country->id,
                'country_name' => $country->name,
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pays supprimé avec succès!'
                ]);
            }

            return redirect()->route('countries.index')
                ->with('success', 'Pays supprimé avec succès!');

        } catch (\Exception $exception) {
            \Log::error('Error deleting country', [
                'error' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
                'country_id' => $country->id,
                'country_name' => $country->name,
                'user_id' => auth()->id(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue lors de la suppression du pays. Veuillez réessayer.',
                    'error_details' => config('app.debug') ? $exception->getMessage() : null
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la suppression du pays. Veuillez réessayer.');
        }
    }
    
    /**
     * Get statistics for dashboard
     */
    public function getStatistics(Request $request)
    {
        try {
            $stats = [
                'total_countries' => Country::count(),
                'total_population' => (int)Country::sum('population'),
                'total_area' => (float)Country::sum('area'),
                'by_continent' => Continent::withCount('countries')
                    ->withSum('countries', 'population')
                    ->withSum('countries', 'area')
                    ->orderBy('countries_sum_population', 'desc')
                    ->get()
                    ->map(function($continent) {
                        return [
                            'id' => $continent->id,
                            'name' => $continent->name,
                            'code' => $continent->code,
                            'countries_count' => $continent->countries_count,
                            'total_population' => $continent->countries_sum_population,
                            'total_area' => $continent->countries_sum_area,
                        ];
                    }),
                'most_populous' => Country::with('continent')
                    ->select('name', 'code', 'continent_id', 'population')
                    ->orderBy('population', 'desc')
                    ->first(),
                'largest' => Country::with('continent')
                    ->select('name', 'code', 'continent_id', 'area')
                    ->orderBy('area', 'desc')
                    ->first(),
                'smallest' => Country::with('continent')
                    ->select('name', 'code', 'continent_id', 'area')
                    ->where('area', '>', 0)
                    ->orderBy('area', 'asc')
                    ->first(),
                'average_population' => (int)Country::avg('population'),
                'average_area' => (float)Country::avg('area'),
                'regions' => Country::select('region')
                    ->whereNotNull('region')
                    ->distinct()
                    ->count(),
            ];

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $stats,
                    'message' => 'Statistiques des pays récupérées avec succès'
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
     * Get provinces for a specific country
     */
    public function getProvinces(Country $country)
    {
        $provinces = $country->provinces()
            ->orderBy('name')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $provinces
        ]);
    }

    /**
     * Get countries by continent
     */
    public function getByContinent($continentCode)
    {
        $countries = Country::whereHas('continent', function($query) use ($continentCode) {
            $query->where('code', $continentCode);
        })
        ->orderBy('name')
        ->get();

        return response()->json([
            'success' => true,
            'data' => $countries
        ]);
    }

    /**
     * Search countries (autocomplete)
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        $countries = Country::where('name', 'like', "%{$query}%")
            ->orWhere('code', 'like', "%{$query}%")
            ->orWhere('iso2', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'name', 'code', 'iso2', 'flag']);
            
        return response()->json([
            'success' => true,
            'data' => $countries
        ]);
    }

    public function toggleStatus(Request $request, Country $country)
    {
        try {
            \Log::info('ToggleStatus Country - Début', [
                'country_id' => $country->id,
                'country_name' => $country->name,
                'input_data' => $request->all(),
                'current_status' => $country->is_active
            ]);
            
            $validated = $request->validate([
                'is_active' => 'required'
            ]);
            
            \Log::info('ToggleStatus Country - Données validées', [
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
            
            \Log::info('ToggleStatus Country - Nouveau statut déterminé', [
                'new_status_bool' => $newStatus,
                'new_status_int' => $newStatus ? 1 : 0
            ]);
            
            // Mettre à jour le pays
            $country->update([
                'is_active' => $newStatus ? 1 : 0
            ]);
            
            // Recharger le modèle pour vérifier
            $country->refresh();
            
            \Log::info('ToggleStatus Country - Mise à jour réussie', [
                'updated_status' => $country->is_active,
                'updated_at' => $country->updated_at
            ]);
            
            return response()->json([
                'success' => true,
                'message' => $newStatus ? 'Pays activé avec succès' : 'Pays désactivé avec succès',
                'data' => $country
            ]);
            
        } catch (\Exception $e) {
            \Log::error('ToggleStatus Country - Erreur', [
                'country_id' => $country->id ?? 'N/A',
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
}