<?php

namespace Vendor\Destination\Controllers\Countries;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Continent;
use App\Models\Country;
use App\Models\Category;
use App\Models\CategorieType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ActivityController extends Controller
{
    public function index(Request $request, $countryId = null)
{
    // Récupérer les données nécessaires pour les filtres
    $continents = Continent::all();
    $countries = Country::all();
    $categorie_types = CategorieType::all();
    $categories = Category::all();
    $activities = Activity::all();
    
    // Obtenir le pays si l'ID est fourni
    $country = null;
    if ($countryId) {
        $country = Country::findOrFail($countryId);
    }
    
    // Si c'est une requête AJAX, retourner JSON
    if ($request->ajax()) {
        $query = Activity::with(['continents', 'countries'])
            ->withCount(['continents', 'countries']);
        
        // FILTRE PRINCIPAL : Si un pays spécifique est sélectionné
        if ($countryId) {
            $query->whereHas('countries', function ($q) use ($countryId) {
                $q->where('countries.id', $countryId);
            });
        }
        
        // Appliquer les autres filtres
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        // if ($request->has('categorie') && !empty($request->categorie)) {
        //     $query->where('categorie_id', $request->category);
        // }
        
        // Si on filtre par continent (uniquement si pas de pays spécifique)
        if ($request->has('continent') && !empty($request->continent) && !$countryId) {
            $query->whereHas('continents', function ($q) use ($request) {
                $q->where('continents.id', $request->continent);
            });
        }
        
        // Si on filtre par pays (uniquement si pas de pays spécifique)
        if ($request->has('country') && !empty($request->country) && !$countryId) {
            $query->whereHas('countries', function ($q) use ($request) {
                $q->where('countries.id', $request->country);
            });
        }
        
        if ($request->has('status') && !empty($request->status)) {
            $isActive = $request->status === 'active' ? 1 : 0;
            $query->where('is_active', $isActive);
        }
        
        if ($request->has('difficulty') && !empty($request->difficulty)) {
            $query->where('difficulty', $request->difficulty);
        }
        
        if ($request->has('season') && !empty($request->season)) {
            $query->where('season', 'like', '%' . $request->season . '%');
        }
        
        if ($request->has('price_range') && !empty($request->price_range)) {
            $query->where('price_range', $request->price_range);
        }
        
        // Trier
        $sortBy = $request->get('sort_by', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortBy, $sortDirection);
        
        // Paginer
        $activities = $query->paginate(10);
        
        return response()->json([
            'success' => true,
            'data' => $activities->items(),
            'current_page' => $activities->currentPage(),
            'last_page' => $activities->lastPage(),
            'per_page' => $activities->perPage(),
            'total' => $activities->total(),
            'prev_page_url' => $activities->previousPageUrl(),
            'next_page_url' => $activities->nextPageUrl(),
            'country' => $country ? [
                'id' => $country->id,
                'name' => $country->name,
                'code' => $country->code
            ] : null
        ]);
    }
    
    // Retourner la vue normale
    return view('destination::countries.pages.index', compact('continents', 'countries', 'country', 'categories','categorie_types', 'activities'));
}
    
    public function create()
    {
        $continents = Continent::all();
        $countries = Country::all();
        return view('activities.create', compact('continents', 'countries'));
    }
    

public function store(Request $request)
{
        Log::info('Début création d\'activité', $request->all());
    try {
        
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|integer|exists:categorie_types,id',
            'categorie_id' => 'required|integer|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:20480', // 20MB
            'tags' => 'nullable|string',
            'slug' => 'required|string|unique:activities,slug',
            'is_active' => 'boolean',
            'country_id' => 'nullable|integer|exists:countries,id',
            'continents' => 'nullable|array',
            'continents.*' => 'integer|exists:continents,id',
            'countries' => 'nullable|array',
            'countries.*' => 'integer|exists:countries,id'
        ]);
        
        Log::debug('Validation réussie pour création activité');
        
        // Créer l'activité
        $activityData = [
            'name' => $request->name,
            'type' => $request->type,
            'slug' => $request->slug,
            'categorie_id' => $request->categorie_id,
            'description' => $request->description,
            'tags' => $request->tags,
            'is_active' => $request->boolean('is_active')
        ];
        
        Log::debug('Données activité préparées', $activityData);
        
        $activity = Activity::create($activityData);
        Log::info('Activité créée avec succès', ['activity_id' => $activity->id]);
        
        // Gérer l'image
        if ($request->hasFile('image')) {
            try {
                $imagePath = $request->file('image')->store('activities', 'public');
                $activity->update(['image' => $imagePath]);
                Log::info('Image téléchargée avec succès', ['path' => $imagePath]);
            } catch (\Exception $imageException) {
                Log::error('Erreur lors du téléchargement de l\'image', [
                    'activity_id' => $activity->id,
                    'error' => $imageException->getMessage()
                ]);
                // On continue même si l'image échoue
            }
        }
        
        // Associer les continents (si non dans un contexte pays spécifique)
        if (!$request->has('country_id') && $request->has('continents')) {
            try {
                $activity->continents()->sync($request->continents);
                Log::info('Continents associés', [
                    'activity_id' => $activity->id,
                    'continents' => $request->continents
                ]);
            } catch (\Exception $continentException) {
                Log::error('Erreur lors de l\'association des continents', [
                    'activity_id' => $activity->id,
                    'error' => $continentException->getMessage()
                ]);
            }
        }
        
        // Associer les pays
        if ($request->has('countries')) {
            try {
                $activity->countries()->sync($request->countries);
                Log::info('Pays associés', [
                    'activity_id' => $activity->id,
                    'countries' => $request->countries
                ]);
            } catch (\Exception $countryException) {
                Log::error('Erreur lors de l\'association des pays', [
                    'activity_id' => $activity->id,
                    'error' => $countryException->getMessage()
                ]);
            }
        } elseif ($request->has('country_id')) {
            try {
                $activity->countries()->sync([$request->country_id]);
                Log::info('Pays spécifique associé', [
                    'activity_id' => $activity->id,
                    'country_id' => $request->country_id
                ]);
            } catch (\Exception $singleCountryException) {
                Log::error('Erreur lors de l\'association du pays spécifique', [
                    'activity_id' => $activity->id,
                    'error' => $singleCountryException->getMessage()
                ]);
            }
        }
        
        // Charger les relations pour la réponse
        $activity->load(['continents', 'countries', 'categoryRelation', 'typeRelation']);
        
        Log::info('Activité finalisée avec succès', ['activity_id' => $activity->id]);
        
        return response()->json([
            'success' => true,
            'message' => 'Activité créée avec succès!',
            'data' => $activity
        ], 201);
        
    } catch (\Illuminate\Validation\ValidationException $validationException) {
        Log::error('Erreur de validation lors de la création d\'activité', [
            'errors' => $validationException->errors(),
            'request' => $request->except(['image'])
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Erreur de validation',
            'errors' => $validationException->errors()
        ], 422);
        
    } catch (\Exception $generalException) {
        Log::error('Erreur générale lors de la création d\'activité', [
            'error' => $generalException->getMessage(),
            'trace' => $generalException->getTraceAsString(),
            'request' => $request->except(['image'])
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Une erreur est survenue lors de la création de l\'activité',
            'error' => env('APP_DEBUG') ? $generalException->getMessage() : 'Erreur interne du serveur'
        ], 500);
    }
}

public function update(Request $request, $id)
{
    try {
        Log::info('Début mise à jour d\'activité', [
            'activity_id' => $id,
            'request_data' => $request->except(['image'])
        ]);
        
        $activity = Activity::findOrFail($id);
        Log::debug('Activité trouvée', ['activity' => $activity->toArray()]);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|integer|exists:categorie_types,id',
            'categorie_id' => 'required|integer|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:20480', // 20MB
            'tags' => 'nullable|string',
            'slug' => 'required|string|unique:activities,slug,' . $id,
            'is_active' => 'boolean',
            'continents' => 'nullable|array',
            'continents.*' => 'integer|exists:continents,id',
            'countries' => 'nullable|array',
            'countries.*' => 'integer|exists:countries,id'
        ]);
        
        Log::debug('Validation réussie pour mise à jour activité');
        
        // Mettre à jour l'activité
        $activityData = [
            'name' => $request->name,
            'type' => $request->type,
            'categorie_id' => $request->categorie_id,
            'description' => $request->description,
            'tags' => $request->tags,
            'slug' => $request->slug,
            'is_active' => $request->boolean('is_active')
        ];
        
        Log::debug('Données activité à mettre à jour', $activityData);
        
        $activity->update($activityData);
        Log::info('Activité mise à jour', ['activity_id' => $activity->id]);
        
        // Gérer l'image
        if ($request->hasFile('image')) {
            try {
                // Supprimer l'ancienne image si elle existe
                if ($activity->image && Storage::disk('public')->exists($activity->image)) {
                    Storage::disk('public')->delete($activity->image);
                    Log::info('Ancienne image supprimée', ['old_path' => $activity->image]);
                }
                
                $imagePath = $request->file('image')->store('activities', 'public');
                $activity->update(['image' => $imagePath]);
                Log::info('Nouvelle image téléchargée', ['path' => $imagePath]);
            } catch (\Exception $imageException) {
                Log::error('Erreur lors du traitement de l\'image', [
                    'activity_id' => $activity->id,
                    'error' => $imageException->getMessage()
                ]);
                throw $imageException; // On relance l'exception car l'image est importante
            }
        } elseif ($request->has('remove_image') && $request->remove_image == true) {
            // Option pour supprimer l'image sans en uploader une nouvelle
            try {
                if ($activity->image && Storage::disk('public')->exists($activity->image)) {
                    Storage::disk('public')->delete($activity->image);
                    $activity->update(['image' => null]);
                    Log::info('Image supprimée sur demande', ['activity_id' => $activity->id]);
                }
            } catch (\Exception $removeImageException) {
                Log::error('Erreur lors de la suppression de l\'image', [
                    'activity_id' => $activity->id,
                    'error' => $removeImageException->getMessage()
                ]);
            }
        }
        
        // Mettre à jour les continents
        $continents = $request->has('continents') ? $request->continents : [];
        try {
            $activity->continents()->sync($continents);
            Log::info('Continents mis à jour', [
                'activity_id' => $activity->id,
                'continents' => $continents
            ]);
        } catch (\Exception $continentException) {
            Log::error('Erreur lors de la mise à jour des continents', [
                'activity_id' => $activity->id,
                'error' => $continentException->getMessage()
            ]);
        }
        
        // Mettre à jour les pays
        $countries = $request->has('countries') ? $request->countries : [];
        try {
            $activity->countries()->sync($countries);
            Log::info('Pays mis à jour', [
                'activity_id' => $activity->id,
                'countries' => $countries
            ]);
        } catch (\Exception $countryException) {
            Log::error('Erreur lors de la mise à jour des pays', [
                'activity_id' => $activity->id,
                'error' => $countryException->getMessage()
            ]);
        }
        
        // Charger les relations pour la réponse
        $activity->load(['continents', 'countries', 'category', 'typeRelation']);
        
        Log::info('Activité mise à jour avec succès', ['activity_id' => $activity->id]);
        
        return response()->json([
            'success' => true,
            'message' => 'Activité mise à jour avec succès!',
            'data' => $activity
        ]);
        
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $modelException) {
        Log::error('Activité non trouvée pour mise à jour', [
            'activity_id' => $id,
            'error' => $modelException->getMessage()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Activité non trouvée'
        ], 404);
        
    } catch (\Illuminate\Validation\ValidationException $validationException) {
        Log::error('Erreur de validation lors de la mise à jour d\'activité', [
            'activity_id' => $id,
            'errors' => $validationException->errors(),
            'request' => $request->except(['image'])
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Erreur de validation',
            'errors' => $validationException->errors()
        ], 422);
        
    } catch (\Exception $generalException) {
        Log::error('Erreur générale lors de la mise à jour d\'activité', [
            'activity_id' => $id,
            'error' => $generalException->getMessage(),
            'trace' => $generalException->getTraceAsString(),
            'request' => $request->except(['image'])
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Une erreur est survenue lors de la mise à jour de l\'activité',
            'error' => env('APP_DEBUG') ? $generalException->getMessage() : 'Erreur interne du serveur'
        ], 500);
    }
}
    
    public function show($id)
    {
        $activity = Activity::with(['continents', 'countries'])->findOrFail($id);
        return view('activities.show', compact('activity'));
    }
    
    public function edit($id)
    {
        $activity = Activity::with(['continents', 'countries'])->findOrFail($id);
        $continents = Continent::all();
        $countries = Country::all();
        
        return view('activities.edit', compact('activity', 'continents', 'countries'));
    }
    
   
    
    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);
        
        // Supprimer l'image si elle existe
        if ($activity->image && Storage::disk('public')->exists($activity->image)) {
            Storage::disk('public')->delete($activity->image);
        }
        
        // Détacher toutes les relations
        $activity->continents()->detach();
        $activity->countries()->detach();
        $activity->regions()->detach();
        $activity->provinces()->detach();
        $activity->cities()->detach();
        
        // Supprimer l'activité
        $activity->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Activité supprimée avec succès!'
        ]);
    }
    
    public function toggleStatus($id)
    {
        $activity = Activity::findOrFail($id);
        
        $activity->update([
            'is_active' => !$activity->is_active
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour avec succès!',
            'data' => [
                'is_active' => $activity->is_active
            ]
        ]);
    }
    
  public function statistics($countrieId = null)
{
    // Vérifier si le pays existe
    if ($countrieId !== null) {
        $country = \App\Models\Country::find($countrieId);
        if (!$country) {
            return response()->json([
                'success' => false,
                'message' => 'Pays non trouvé'
            ], 404);
        }
    }

    // Si un pays est spécifié
    if ($countrieId !== null) {
        $totalActivities = Activity::whereHas('countries', function ($q) use ($countrieId) {
            $q->where('countries.id', $countrieId);
        })->count();
        
        $activeActivities = Activity::whereHas('countries', function ($q) use ($countrieId) {
            $q->where('countries.id', $countrieId);
        })->where('is_active', true)->count();
        
        $categoriesCount = Activity::whereHas('countries', function ($q) use ($countrieId) {
            $q->where('countries.id', $countrieId);
        })->distinct('categorie_id')->count('categorie_id');
        
        // Pour un pays spécifique, compter le nombre d'autres pays liés à ces activités
        $activityIds = Activity::whereHas('countries', function ($q) use ($countrieId) {
            $q->where('countries.id', $countrieId);
        })->pluck('id');
        
        $countriesWithActivities = \DB::table('activity_country')
            ->whereIn('activity_id', $activityIds)
            ->distinct('country_id')
            ->count('country_id');
    } else {
        // Si aucun pays n'est spécifié, statistiques globales
        $totalActivities = Activity::count();
        $activeActivities = Activity::where('is_active', true)->count();
        $categoriesCount = Activity::distinct('categorie_id')->count('categorie_id');
        $countriesWithActivities = \DB::table('activity_country')
            ->distinct('countrie_id')
            ->count('countrie_id');
    }

    return response()->json([
        'success' => true,
        'data' => [
            'total_activities' => $totalActivities,
            'active_activities' => $activeActivities,
            'categories_count' => $categoriesCount,
            'countries_with_activities' => $countriesWithActivities
        ]
    ]);
}
}