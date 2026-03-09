<?php

namespace Vendor\MapMarker\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MapPoint;
use App\Models\MapPointImage;
use App\Models\MapPointVideo;
use App\Models\MapPointDetail;
use App\Models\Etablissement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MapPointController extends Controller
{
    /**
     * Display a listing of the map points with AJAX support.
     */
    public function index(Request $request)
    {
        // Si c'est une requête AJAX
        if ($request->ajax()) {
            $query = MapPoint::with(['images', 'videos', 'details', 'etablissement', 'user'])
                ->where('is_active', true);

            // Filtres
            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }

            if ($request->filled('ville')) {
                $query->where('ville', 'LIKE', '%' . $request->ville . '%');
            }

            if ($request->filled('has_video')) {
                $request->has_video === 'yes' 
                    ? $query->whereNotNull('youtube_id')
                    : $query->whereNull('youtube_id');
            }

            if ($request->filled('has_details')) {
                $request->has_details === 'yes'
                    ? $query->where('has_details_page', true)
                    : $query->where('has_details_page', false);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%")
                      ->orWhere('adresse', 'LIKE', "%{$search}%");
                });
            }

            // Tri
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 10);
            $points = $query->paginate($perPage);

            // Formatage des données pour le tableau
            $points->getCollection()->transform(function($point) {
                return [
                    'id' => $point->id,
                    'title' => $point->title,
                    'description' => Str::limit($point->description, 100),
                    'category' => $point->category,
                    'category_label' => $this->getCategoryLabel($point->category),
                    'category_color' => $this->getCategoryColor($point->category),
                    'category_icon' => $this->getCategoryIcon($point->category),
                    'main_image' => $point->main_image ? asset('storage/' . $point->main_image) : null,
                    'youtube_id' => $point->youtube_id,
                    'has_video' => !is_null($point->youtube_id),
                    'latitude' => $point->latitude,
                    'longitude' => $point->longitude,
                    'adresse' => $point->adresse,
                    'ville' => $point->ville,
                    'code_postal' => $point->code_postal,
                    'has_details_page' => $point->has_details_page,
                    'details_url' => $point->details_url,
                    'etablissement' => $point->etablissement ? [
                        'id' => $point->etablissement->id,
                        'name' => $point->etablissement->name,
                    ] : null,
                    'user' => $point->user ? [
                        'id' => $point->user->id,
                        'name' => $point->user->name,
                        'email' => $point->user->email,
                    ] : null,
                    'is_featured' => $point->is_featured,
                    'views' => $point->views,
                    'images_count' => $point->images->count(),
                    'videos_count' => $point->videos->count(),
                    'created_at' => $point->created_at->format('d/m/Y'),
                    'created_at_full' => $point->created_at->format('d/m/Y H:i'),
                    'updated_at' => $point->updated_at->format('d/m/Y'),
                ];
            });

            // Statistiques
            $stats = [
                'total' => MapPoint::count(),
                'with_video' => MapPoint::whereNotNull('youtube_id')->count(),
                'with_details' => MapPoint::where('has_details_page', true)->count(),
                'featured' => MapPoint::where('is_featured', true)->count(),
                'by_category' => MapPoint::selectRaw('category, count(*) as total')
                    ->groupBy('category')
                    ->get()
                    ->mapWithKeys(function($item) {
                        return [$item->category => $item->total];
                    }),
                'by_ville' => MapPoint::selectRaw('ville, count(*) as total')
                    ->whereNotNull('ville')
                    ->groupBy('ville')
                    ->orderBy('total', 'desc')
                    ->limit(5)
                    ->get()
                    ->mapWithKeys(function($item) {
                        return [$item->ville => $item->total];
                    }),
            ];

            return response()->json([
                'success' => true,
                'data' => $points->items(),
                'stats' => $stats,
                'current_page' => $points->currentPage(),
                'last_page' => $points->lastPage(),
                'per_page' => $points->perPage(),
                'total' => $points->total(),
                'prev_page_url' => $points->previousPageUrl(),
                'next_page_url' => $points->nextPageUrl(),
            ]);
        }

        // Requête normale - retourne la vue
        $categories = MapPoint::select('category')
            ->distinct()
            ->whereNotNull('category')
            ->pluck('category')
            ->mapWithKeys(function($category) {
                return [$category => $this->getCategoryLabel($category)];
            });

        $villes = MapPoint::select('ville')
            ->distinct()
            ->whereNotNull('ville')
            ->pluck('ville');

        $etablissements = Etablissement::select('id', 'name')
            ->where('is_active', true)
            ->get();

        return view('map-marker::index', compact('categories', 'villes', 'etablissements'));
    }

    /**
     * Show the form for creating a new map point.
     */
    public function create()
    {
        $etablissements = Etablissement::where('is_active', true)->get();
        $categories = $this->getCategoriesList();
        
        return view('map-marker::create', compact('etablissements', 'categories'));
    }

    /**
     * Store a newly created map point in storage.
     */

public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:191',
        'description' => 'nullable|string|max:200',
        'category' => 'required|string|max:100',
        'latitude' => 'required|numeric|between:-90,90',
        'longitude' => 'required|numeric|between:-180,180',
        'adresse' => 'nullable|string',
        'ville' => 'nullable|string|max:191',
        'code_postal' => 'nullable|string|max:20',
        'main_image' => 'nullable|image|max:2048',
        'youtube_url' => 'nullable|url',
        'has_details_page' => 'nullable|boolean',
        'etablissement_id' => 'nullable|exists:etablissements,id',
        'is_featured' => 'nullable|boolean',
        'is_active' => 'nullable|boolean',
        'details_url' => 'nullable|string',
        'additional_images.*' => 'nullable|image|max:2048',
        'details.phone' => 'nullable|string|max:50',
        'details.email' => 'nullable|email|max:191',
        'details.website' => 'nullable|url|max:191',
        'details.long_description' => 'nullable|string',
        'details.facebook' => 'nullable|url|max:191',
        'details.instagram' => 'nullable|url|max:191',
    ]);

    // Traitement de l'image principale
    if ($request->hasFile('main_image')) {
        $path = $request->file('main_image')->store('map-points', 'public');
        $validated['main_image'] = $path;
    }

    // Extraction de l'ID YouTube
    if ($request->filled('youtube_url')) {
        $validated['youtube_id'] = $this->extractYoutubeId($request->youtube_url);
    }

    $validated['user_id'] = auth()->id();
    $validated['is_active'] = $request->has('is_active') ? true : false;

    $mapPoint = MapPoint::create($validated);

    // ==================== TRAITEMENT DES IMAGES SUPPLÉMENTAIRES ====================
    if ($request->hasFile('additional_images')) {
        $images = $request->file('additional_images');
        
        foreach ($images as $index => $image) {
            $path = $image->store('map-points/gallery', 'public');
            
            MapPointImage::create([
                'map_point_id' => $mapPoint->id,
                'image' => $path,
                'thumbnail' => $path, // Vous pouvez générer une miniature ici
                'caption' => $request->input('image_captions.' . $index, ''),
                'sort_order' => $index,
            ]);
        }
    }

    // ==================== TRAITEMENT DES VIDÉOS SUPPLÉMENTAIRES ====================
    if ($request->filled('additional_videos')) {
        $videos = $request->input('additional_videos');
        $videoTitles = $request->input('video_titles', []);
        
        foreach ($videos as $index => $videoUrl) {
            if (!empty($videoUrl)) {
                MapPointVideo::create([
                    'map_point_id' => $mapPoint->id,
                    'youtube_url' => $videoUrl,
                    'youtube_id' => $this->extractYoutubeId($videoUrl),
                    'title' => $videoTitles[$index] ?? null,
                    'sort_order' => $index,
                ]);
            }
        }
    }

    // ==================== TRAITEMENT DES DÉTAILS ====================
    if ($request->boolean('has_details_page') && $request->has('details')) {
        $details = $request->input('details');
        $details['map_point_id'] = $mapPoint->id;
        $details['slug'] = $request->input('details_url') ?: Str::slug($mapPoint->title) . '-' . $mapPoint->id;
        
        MapPointDetail::create($details);
    }

    return redirect()->route('map-points.index')
        ->with('success', 'Point sur la carte créé avec succès.');
}

    /**
     * Display the specified map point.
     */
    public function show(MapPoint $mapPoint)
    {
        $mapPoint->load(['images', 'videos', 'details', 'etablissement', 'user']);
        $mapPoint->incrementViews();

        return view('map-marker::show', compact('mapPoint'));
    }

    /**
     * Show the form for editing the specified map point.
     */
    public function edit(MapPoint $mapPoint)
    {
        $etablissements = Etablissement::where('is_active', true)->get();
        $categories = $this->getCategoriesList();
        
        return view('map-marker::edit', compact('mapPoint', 'etablissements', 'categories'));
    }

    /**
     * Update the specified map point in storage.
     */
    public function update(Request $request, MapPoint $mapPoint)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:191',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string|max:191',
            'code_postal' => 'nullable|string|max:20',
            'main_image' => 'nullable|image|max:2048',
            'youtube_url' => 'nullable|url',
            'has_details_page' => 'boolean',
            'etablissement_id' => 'nullable|exists:etablissements,id',
            'is_featured' => 'boolean',
        ]);

        if ($request->hasFile('main_image')) {
            // Supprimer l'ancienne image
            if ($mapPoint->main_image) {
                Storage::disk('public')->delete($mapPoint->main_image);
            }
            
            $path = $request->file('main_image')->store('map-points', 'public');
            $validated['main_image'] = $path;
        }

        if ($request->filled('youtube_url')) {
            $validated['youtube_id'] = $this->extractYoutubeId($request->youtube_url);
        } else {
            $validated['youtube_id'] = null;
        }

        $mapPoint->update($validated);

        return redirect()->route('map-points.index')
            ->with('success', 'Point sur la carte mis à jour avec succès.');
    }

    /**
     * Remove the specified map point from storage.
     */
    public function destroy(MapPoint $mapPoint)
    {
        // Supprimer les images associées
        foreach ($mapPoint->images as $image) {
            Storage::disk('public')->delete($image->image);
            if ($image->thumbnail) {
                Storage::disk('public')->delete($image->thumbnail);
            }
        }

        // Supprimer l'image principale
        if ($mapPoint->main_image) {
            Storage::disk('public')->delete($mapPoint->main_image);
        }

        $mapPoint->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Point supprimé avec succès.'
            ]);
        }

        return redirect()->route('map-points.index')
            ->with('success', 'Point supprimé avec succès.');
    }

    /**
     * Get map points for the map view (API endpoint).
     */
    public function getMapPoints(Request $request)
    {
        $bounds = $request->input('bounds');
        
        $query = MapPoint::with(['images', 'videos'])
            ->where('is_active', true)
            ->select('id', 'title', 'description', 'category', 'latitude', 'longitude', 
                    'main_image', 'youtube_id', 'has_details_page', 'details_url');

        // Filtrer par les limites de la carte
        if ($bounds && isset($bounds['_southWest']) && isset($bounds['_northEast'])) {
            $query->whereBetween('latitude', [$bounds['_southWest']['lat'], $bounds['_northEast']['lat']])
                  ->whereBetween('longitude', [$bounds['_southWest']['lng'], $bounds['_northEast']['lng']]);
        }

        // Filtre par catégorie
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $points = $query->get()->map(function($point) {
            return [
                'id' => $point->id,
                'title' => $point->title,
                'description' => Str::limit($point->description, 150),
                'category' => $point->category,
                'category_label' => $this->getCategoryLabel($point->category),
                'category_color' => $this->getCategoryColor($point->category),
                'category_icon' => $this->getCategoryIcon($point->category),
                'lat' => $point->latitude,
                'lng' => $point->longitude,
                'image' => $point->main_image ? asset('storage/' . $point->main_image) : null,
                'youtube_id' => $point->youtube_id,
                'has_details' => $point->has_details_page,
                'details_url' => $point->details_url,
                'thumbnail' => $point->youtube_id 
                    ? "https://img.youtube.com/vi/{$point->youtube_id}/hqdefault.jpg"
                    : ($point->main_image ? asset('storage/' . $point->main_image) : null),
            ];
        });

        return response()->json([
            'success' => true,
            'points' => $points,
            'total' => $points->count()
        ]);
    }

    public function map()
{
    $totalPoints = MapPoint::count();
    
    $categories = MapPoint::select('category')
        ->selectRaw('count(*) as total')
        ->groupBy('category')
        ->get()
        ->map(function($item) {
            return [
                'category' => $item->category,
                'category_label' => $this->getCategoryLabel($item->category),
                'color' => $this->getCategoryColor($item->category),
                'icon' => $this->getCategoryIcon($item->category),
                'total' => $item->total
            ];
        })->toArray(); // <- Important: toArray() pour être sûr
    
    $villes = MapPoint::select('ville')
        ->selectRaw('count(*) as total')
        ->whereNotNull('ville')
        ->groupBy('ville')
        ->orderBy('total', 'desc')
        ->get()
        ->map(function($item) {
            return [
                'ville' => $item->ville,
                'total' => $item->total
            ];
        })->toArray();
    
    $categoriesCount = count($categories);
    $villesCount = count($villes);
    
    return view('map-marker::map', compact(
        'totalPoints', 
        'categories', 
        'villes', 
        'categoriesCount', 
        'villesCount'
    ));
}

    /**
     * Helper: Extract YouTube ID from URL
     */
    private function extractYoutubeId($url)
    {
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches);
        return $matches[1] ?? null;
    }

    /**
     * Helper: Get category label
     */
    private function getCategoryLabel($category)
    {
        $labels = [
            'restaurant' => 'Restaurant',
            'hotel' => 'Hôtel',
            'commerce' => 'Commerce',
            'sante' => 'Santé',
            'education' => 'Éducation',
            'culture' => 'Culture',
            'sport' => 'Sport',
            'loisirs' => 'Loisirs',
            'transport' => 'Transport',
            'immobilier' => 'Immobilier',
            'service' => 'Service',
            'autre' => 'Autre',
        ];

        return $labels[$category] ?? ucfirst($category);
    }

    /**
     * Helper: Get category color
     */
    private function getCategoryColor($category)
    {
        $colors = [
            'restaurant' => '#FF6B6B',
            'hotel' => '#4ECDC4',
            'commerce' => '#45B7D1',
            'sante' => '#96CEB4',
            'education' => '#FFE194',
            'culture' => '#DDA0DD',
            'sport' => '#FFA07A',
            'loisirs' => '#90EE90',
            'transport' => '#A9A9A9',
            'immobilier' => '#1b4f6b',
            'service' => '#B0C4DE',
        ];

        return $colors[$category] ?? '#6c757d';
    }

    /**
     * Helper: Get category icon
     */
    private function getCategoryIcon($category)
    {
        $icons = [
            'restaurant' => 'fa-utensils',
            'hotel' => 'fa-hotel',
            'commerce' => 'fa-shop',
            'sante' => 'fa-hospital',
            'education' => 'fa-school',
            'culture' => 'fa-museum',
            'sport' => 'fa-dumbbell',
            'loisirs' => 'fa-tree',
            'transport' => 'fa-bus',
            'immobilier' => 'fa-building',
            'service' => 'fa-gear',
        ];

        return $icons[$category] ?? 'fa-map-pin';
    }

    /**
     * Helper: Get categories list for forms
     */
    private function getCategoriesList()
    {
        return [
            'restaurant' => 'Restaurant',
            'hotel' => 'Hôtel',
            'commerce' => 'Commerce',
            'sante' => 'Santé',
            'education' => 'Éducation',
            'culture' => 'Culture',
            'sport' => 'Sport',
            'loisirs' => 'Loisirs',
            'transport' => 'Transport',
            'immobilier' => 'Immobilier',
            'service' => 'Service',
            'autre' => 'Autre',
        ];
    }
}