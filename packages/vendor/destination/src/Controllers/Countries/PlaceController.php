<?php

namespace Vendor\Destination\Controllers\Countries;

use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\Country;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Place::query();
            
            // Filtres
            if ($request->has('country_id')) {
                $query->where('country_id', $request->country_id);
            }
            
            if ($request->has('activity_id')) {
                $query->where('activity_id', $request->activity_id);
            }
            
            if ($request->has('category')) {
                $query->where('category', $request->category);
            }
            
            if ($request->has('is_active')) {
                $query->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN));
            }
            
            if ($request->has('is_featured')) {
                $query->where('is_featured', filter_var($request->is_featured, FILTER_VALIDATE_BOOLEAN));
            }
            
            if ($request->has('search')) {
                $query->search($request->search);
            }
            
            // Filtre par proximité géographique
            if ($request->has(['latitude', 'longitude'])) {
                $radius = $request->get('radius', 10); // 10km par défaut
                $query->nearby($request->latitude, $request->longitude, $radius);
            }
            
            // Tri
            $sortBy = $request->get('sort_by', 'sort_order');
            $sortOrder = $request->get('sort_order', 'asc');
            
            // Tri spécial pour la proximité
            if ($sortBy === 'distance' && $request->has(['latitude', 'longitude'])) {
                // Déjà géré par le scope nearby
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }
            
            // Pagination
            $perPage = $request->get('per_page', 20);
            $places = $query->with(['country', 'activity'])->paginate($perPage);
            
            return response()->json([
                'success' => true,
                'data' => $places,
                'message' => 'Lieux récupérés avec succès'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des lieux', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des lieux'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Log::info('Début création de lieu', ['request' => $request->all()]);
            
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'category' => 'required|string|max:100',
                'images.*' => 'nullable|image|max:51200', // 50MB max par image
                'video_url' => 'nullable|url',
                'country_id' => 'required|exists:countries,id',
                'activity_id' => 'nullable|exists:activities,id',
                'address' => 'nullable|string|max:500',
                'phone' => 'nullable|string|max:50',
                'website' => 'nullable|url|max:255',
                'email' => 'nullable|email|max:255',
                'opening_hours' => 'nullable|date_format:H:i',
                'closing_hours' => 'nullable|date_format:H:i',
                'price_range' => 'nullable|numeric|min:0',
                'rating' => 'nullable|integer|min:1|max:5',
                'is_featured' => 'boolean',
                'is_active' => 'boolean',
                'tags' => 'nullable|string',
                'amenities' => 'nullable|string'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Erreur de validation'
                ], 422);
            }
            
            $data = $request->only([
                'name', 'description', 'latitude', 'longitude', 'category',
                'video_url', 'country_id', 'activity_id', 'address', 'phone',
                'website', 'email', 'opening_hours', 'closing_hours',
                'price_range', 'rating', 'is_featured', 'is_active'
            ]);
            
            $data['is_featured'] = $request->boolean('is_featured', false);
            $data['is_active'] = $request->boolean('is_active', true);
            
            // Extraire l'ID de la vidéo YouTube si présent
            if ($request->has('video_url') && Str::contains($request->video_url, 'youtube.com')) {
                $videoId = $this->extractYoutubeId($request->video_url);
                if ($videoId) {
                    $data['video_id'] = $videoId;
                }
            }
            
            // Gérer les tags
            if ($request->has('tags')) {
                $tags = array_map('trim', explode(',', $request->tags));
                $data['tags'] = json_encode($tags);
            }
            
            // Gérer les équipements
            if ($request->has('amenities')) {
                $amenities = array_map('trim', explode(',', $request->amenities));
                $data['amenities'] = json_encode($amenities);
            }
            
            // Gérer les images
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('places/images', 'public');
                    $imagePaths[] = $path;
                }
                $data['images'] = json_encode($imagePaths);
            }
            
            // Créer le lieu
            $place = Place::create($data);
            
            Log::info('Lieu créé avec succès', ['place_id' => $place->id]);
            
            return response()->json([
                'success' => true,
                'message' => 'Lieu créé avec succès',
                'data' => $place->load(['country', 'activity'])
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du lieu', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->except(['images'])
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du lieu'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $place = Place::with(['country', 'activity'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $place,
                'message' => 'Lieu récupéré avec succès'
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lieu non trouvé'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération du lieu', [
                'place_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du lieu'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $place = Place::findOrFail($id);
            
            Log::info('Début mise à jour de lieu', [
                'place_id' => $id,
                'request' => $request->all()
            ]);
            
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'category' => 'sometimes|string|max:100',
                'images.*' => 'nullable|image|max:51200',
                'video_url' => 'nullable|url',
                'activity_id' => 'nullable|exists:activities,id',
                'address' => 'nullable|string|max:500',
                'phone' => 'nullable|string|max:50',
                'website' => 'nullable|url|max:255',
                'email' => 'nullable|email|max:255',
                'opening_hours' => 'nullable|date_format:H:i',
                'closing_hours' => 'nullable|date_format:H:i',
                'price_range' => 'nullable|numeric|min:0',
                'rating' => 'nullable|integer|min:1|max:5',
                'is_featured' => 'boolean',
                'is_active' => 'boolean',
                'tags' => 'nullable|string',
                'amenities' => 'nullable|string',
                'remove_images' => 'nullable|array',
                'remove_images.*' => 'string'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Erreur de validation'
                ], 422);
            }
            
            $data = $request->only([
                'name', 'description', 'latitude', 'longitude', 'category',
                'video_url', 'activity_id', 'address', 'phone', 'website',
                'email', 'opening_hours', 'closing_hours', 'price_range',
                'rating', 'is_featured', 'is_active'
            ]);
            
            if ($request->has('is_featured')) {
                $data['is_featured'] = $request->boolean('is_featured');
            }
            
            if ($request->has('is_active')) {
                $data['is_active'] = $request->boolean('is_active');
            }
            
            // Extraire l'ID de la vidéo YouTube
            if ($request->has('video_url') && Str::contains($request->video_url, 'youtube.com')) {
                $videoId = $this->extractYoutubeId($request->video_url);
                if ($videoId) {
                    $data['video_id'] = $videoId;
                }
            }
            
            // Gérer les tags
            if ($request->has('tags')) {
                $tags = array_map('trim', explode(',', $request->tags));
                $data['tags'] = json_encode($tags);
            }
            
            // Gérer les équipements
            if ($request->has('amenities')) {
                $amenities = array_map('trim', explode(',', $request->amenities));
                $data['amenities'] = json_encode($amenities);
            }
            
            // Gérer les images à supprimer
            $currentImages = $place->images ?: [];
            if ($request->has('remove_images')) {
                foreach ($request->remove_images as $imageToRemove) {
                    // Supprimer le fichier physique
                    if (Storage::disk('public')->exists($imageToRemove)) {
                        Storage::disk('public')->delete($imageToRemove);
                    }
                    
                    // Retirer de la liste
                    $currentImages = array_filter($currentImages, function ($image) use ($imageToRemove) {
                        return $image !== $imageToRemove;
                    });
                }
                $currentImages = array_values($currentImages); // Réindexer
            }
            
            // Gérer les nouvelles images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('places/images', 'public');
                    $currentImages[] = $path;
                }
            }
            
            $data['images'] = json_encode($currentImages);
            
            // Mettre à jour le lieu
            $place->update($data);
            
            Log::info('Lieu mis à jour avec succès', ['place_id' => $place->id]);
            
            return response()->json([
                'success' => true,
                'message' => 'Lieu mis à jour avec succès',
                'data' => $place->load(['country', 'activity'])
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lieu non trouvé'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du lieu', [
                'place_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du lieu'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $place = Place::findOrFail($id);
            
            // Supprimer les images physiques
            if ($place->images) {
                foreach ($place->images as $image) {
                    if (Storage::disk('public')->exists($image)) {
                        Storage::disk('public')->delete($image);
                    }
                }
            }
            
            $place->delete();
            
            Log::info('Lieu supprimé avec succès', ['place_id' => $id]);
            
            return response()->json([
                'success' => true,
                'message' => 'Lieu supprimé avec succès'
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lieu non trouvé'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du lieu', [
                'place_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du lieu'
            ], 500);
        }
    }

    /**
     * Toggle active status
     */
    public function toggleStatus($id)
    {
        try {
            $place = Place::findOrFail($id);
            $place->is_active = !$place->is_active;
            $place->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour avec succès',
                'data' => [
                    'is_active' => $place->is_active
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors du changement de statut', [
                'place_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du changement de statut'
            ], 500);
        }
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured($id)
    {
        try {
            $place = Place::findOrFail($id);
            $place->is_featured = !$place->is_featured;
            $place->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Statut "À la une" mis à jour avec succès',
                'data' => [
                    'is_featured' => $place->is_featured
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors du changement de statut "À la une"', [
                'place_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du changement de statut "À la une"'
            ], 500);
        }
    }

    /**
     * Get statistics for places
     */
    public function statistics(Request $request)
    {
        try {
            $countryId = $request->get('country_id');
            
            $query = Place::query();
            
            if ($countryId) {
                $query->where('country_id', $countryId);
            }
            
            $stats = [
                'total' => $query->count(),
                'active' => $query->clone()->where('is_active', true)->count(),
                'featured' => $query->clone()->where('is_featured', true)->count(),
                'by_category' => $query->clone()
                    ->selectRaw('category, COUNT(*) as count')
                    ->groupBy('category')
                    ->get()
                    ->pluck('count', 'category')
                    ->toArray(),
                'with_images' => $query->clone()->whereNotNull('images')->count(),
                'with_video' => $query->clone()->whereNotNull('video_url')->count(),
            ];
            
            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Statistiques récupérées avec succès'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des statistiques', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques'
            ], 500);
        }
    }

    /**
     * Get categories list
     */
    public function categories()
    {
        try {
            $categories = Place::select('category')
                ->distinct()
                ->whereNotNull('category')
                ->orderBy('category')
                ->pluck('category');
            
            return response()->json([
                'success' => true,
                'data' => $categories,
                'message' => 'Catégories récupérées avec succès'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des catégories', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des catégories'
            ], 500);
        }
    }

    /**
     * Extract YouTube ID from URL
     */
    private function extractYoutubeId($url)
    {
        $patterns = [
            '/youtube\.com\/watch\?v=([^&]+)/',
            '/youtu\.be\/([^?]+)/',
            '/youtube\.com\/embed\/([^?]+)/'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }
        
        return null;
    }

    /**
     * Get places for map
     */
    public function mapData(Request $request)
    {
        try {
            $query = Place::query();
            
            if ($request->has('country_id')) {
                $query->where('country_id', $request->country_id);
            }
            
            if ($request->has('category')) {
                $query->where('category', $request->category);
            }
            
            if ($request->has('is_active')) {
                $query->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN));
            }
            
            $places = $query->whereNotNull('latitude')
                           ->whereNotNull('longitude')
                           ->select(['id', 'name', 'category', 'latitude', 'longitude', 'is_active', 'is_featured'])
                           ->get();
            
            $mapData = $places->map(function ($place) {
                return [
                    'id' => $place->id,
                    'name' => $place->name,
                    'category' => $place->category,
                    'position' => [
                        'lat' => $place->latitude,
                        'lng' => $place->longitude
                    ],
                    'is_active' => $place->is_active,
                    'is_featured' => $place->is_featured,
                    'icon' => $place->category_icon
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $mapData,
                'message' => 'Données carte récupérées avec succès'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des données carte', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des données carte'
            ], 500);
        }
    }
}