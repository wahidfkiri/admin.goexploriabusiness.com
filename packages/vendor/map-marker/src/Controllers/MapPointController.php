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
use Illuminate\Support\Facades\DB;

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

            if ($request->filled('featured')) {
                $query->where('is_featured', $request->featured === 'yes');
            }

            if ($request->filled('rating_min')) {
                $query->whereHas('details', function($q) use ($request) {
                    $q->where('rating', '>=', $request->rating_min);
                });
            }

            // Tri
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            
            if ($sortBy === 'rating' || $sortBy === 'reviews_count') {
                $query->leftJoin('map_point_details', 'map_points.id', '=', 'map_point_details.map_point_id')
                      ->orderBy('map_point_details.' . $sortBy, $sortOrder)
                      ->select('map_points.*');
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }

            // Pagination
            $perPage = $request->get('per_page', 10);
            $points = $query->paginate($perPage);

            // Formatage des données pour le tableau
            $points->getCollection()->transform(function($point) {
                $data = [
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

                // Ajouter les infos des détails si disponibles
                if ($point->details) {
                    $data['details'] = [
                        'phone' => $point->details->phone,
                        'email' => $point->details->email,
                        'website' => $point->details->website,
                        'rating' => $point->details->rating,
                        'reviews_count' => $point->details->reviews_count,
                        'social_networks' => $point->details->social_networks,
                    ];
                }

                return $data;
            });

            // Statistiques avancées
            $stats = [
                'total' => MapPoint::count(),
                'with_video' => MapPoint::whereNotNull('youtube_id')->count(),
                'with_details' => MapPoint::where('has_details_page', true)->count(),
                'featured' => MapPoint::where('is_featured', true)->count(),
                'by_category' => MapPoint::selectRaw('category, count(*) as total')
                    ->groupBy('category')
                    ->get()
                    ->mapWithKeys(function($item) {
                        return [$item->category => [
                            'total' => $item->total,
                            'label' => $this->getCategoryLabel($item->category),
                            'color' => $this->getCategoryColor($item->category),
                            'icon' => $this->getCategoryIcon($item->category)
                        ]];
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
                'avg_rating' => MapPointDetail::avg('rating'),
                'total_reviews' => MapPointDetail::sum('reviews_count'),
                'social_stats' => $this->getSocialMediaStats(),
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
        $categories = $this->getCategoriesList();
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
        // Validation avec tous les réseaux sociaux
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
            
            // Détails de base
            'details.phone' => 'nullable|string|max:50',
            'details.email' => 'nullable|email|max:191',
            'details.website' => 'nullable|url|max:191',
            'details.long_description' => 'nullable|string',
            'details.contact_person' => 'nullable|string|max:191',
            'details.horaires' => 'nullable|json',
            'details.services' => 'nullable|json',
            'details.tarifs' => 'nullable|json',
            
            // Tous les réseaux sociaux
            'details.facebook' => 'nullable|url|max:191',
            'details.instagram' => 'nullable|url|max:191',
            'details.twitter' => 'nullable|url|max:191',
            'details.linkedin' => 'nullable|url|max:191',
            'details.youtube' => 'nullable|url|max:191',
            'details.tiktok' => 'nullable|url|max:191',
            'details.pinterest' => 'nullable|url|max:191',
            'details.snapchat' => 'nullable|string|max:191',
            'details.whatsapp' => 'nullable|string|max:50',
            'details.telegram' => 'nullable|string|max:191',
            'details.discord' => 'nullable|string|max:191',
            'details.twitch' => 'nullable|url|max:191',
            'details.reddit' => 'nullable|url|max:191',
            'details.github' => 'nullable|url|max:191',
            'details.medium' => 'nullable|url|max:191',
            'details.tumblr' => 'nullable|url|max:191',
            'details.vimeo' => 'nullable|url|max:191',
            'details.dribbble' => 'nullable|url|max:191',
            'details.behance' => 'nullable|url|max:191',
            'details.soundcloud' => 'nullable|url|max:191',
            'details.spotify' => 'nullable|url|max:191',
            'details.tripadvisor' => 'nullable|url|max:191',
            'details.foursquare' => 'nullable|url|max:191',
            'details.yelp' => 'nullable|url|max:191',
            'details.google_maps' => 'nullable|url|max:191',
        ]);

        DB::beginTransaction();

        try {
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

            // Traitement des images supplémentaires
            if ($request->hasFile('additional_images')) {
                $images = $request->file('additional_images');
                
                foreach ($images as $index => $image) {
                    $path = $image->store('map-points/gallery', 'public');
                    
                    MapPointImage::create([
                        'map_point_id' => $mapPoint->id,
                        'image' => $path,
                        'thumbnail' => $path,
                        'caption' => $request->input('image_captions.' . $index, ''),
                        'sort_order' => $index,
                    ]);
                }
            }

            // Traitement des vidéos supplémentaires
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

            // Traitement des détails avec tous les réseaux sociaux
            if ($request->boolean('has_details_page') && $request->has('details')) {
                $details = $request->input('details');
                $details['map_point_id'] = $mapPoint->id;
                $details['slug'] = $request->input('details_url') ?: Str::slug($mapPoint->title) . '-' . $mapPoint->id;
                
                // Convertir les champs JSON
                if (isset($details['horaires']) && is_string($details['horaires'])) {
                    $details['horaires'] = json_decode($details['horaires'], true);
                }
                if (isset($details['services']) && is_string($details['services'])) {
                    $details['services'] = json_decode($details['services'], true);
                }
                if (isset($details['tarifs']) && is_string($details['tarifs'])) {
                    $details['tarifs'] = json_decode($details['tarifs'], true);
                }

                MapPointDetail::create($details);
            }

            DB::commit();

            return redirect()->route('map-points.index')
                ->with('success', 'Point sur la carte créé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Supprimer les images en cas d'erreur
            if (isset($path)) {
                Storage::disk('public')->delete($path);
            }
            
            return back()->withInput()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    /**
     * Display the specified map point.
     */
    public function show(MapPoint $mapPoint)
    {
        $mapPoint->load(['images', 'videos', 'details', 'etablissement', 'user']);
        $mapPoint->incrementViews();

        // Récupérer les réseaux sociaux via l'accesseur du modèle
        if ($mapPoint->details) {
            $socialNetworks = $mapPoint->details->social_networks;
        }

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
            'is_active' => 'boolean',
        ]);

        DB::beginTransaction();

        try {
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

            DB::commit();

            return redirect()->route('map-points.index')
                ->with('success', 'Point sur la carte mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified map point from storage.
     */
    public function destroy(MapPoint $mapPoint)
    {
        DB::beginTransaction();

        try {
            // Supprimer les images associées
            foreach ($mapPoint->images as $image) {
                Storage::disk('public')->delete($image->image);
                if ($image->thumbnail) {
                    Storage::disk('public')->delete($image->thumbnail);
                }
                $image->delete();
            }

            // Supprimer les vidéos associées
            foreach ($mapPoint->videos as $video) {
                $video->delete();
            }

            // Supprimer les détails
            if ($mapPoint->details) {
                $mapPoint->details->delete();
            }

            // Supprimer l'image principale
            if ($mapPoint->main_image) {
                Storage::disk('public')->delete($mapPoint->main_image);
            }

            $mapPoint->delete();

            DB::commit();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Point supprimé avec succès.'
                ]);
            }

            return redirect()->route('map-points.index')
                ->with('success', 'Point supprimé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression : ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * Get map points for the map view (API endpoint).
     */
    public function getMapPoints(Request $request)
    {
        $bounds = $request->input('bounds');
        
        $query = MapPoint::with(['images', 'videos', 'details'])
            ->where('is_active', true)
            ->select('id', 'title', 'description', 'category', 'latitude', 'longitude', 
                    'main_image', 'youtube_id', 'has_details_page', 'details_url', 'views');

        // Filtrer par les limites de la carte
        if ($bounds && isset($bounds['_southWest']) && isset($bounds['_northEast'])) {
            $query->whereBetween('latitude', [$bounds['_southWest']['lat'], $bounds['_northEast']['lat']])
                  ->whereBetween('longitude', [$bounds['_southWest']['lng'], $bounds['_northEast']['lng']]);
        }

        // Filtre par catégorie
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filtre par évaluation minimum
        if ($request->filled('rating_min')) {
            $query->whereHas('details', function($q) use ($request) {
                $q->where('rating', '>=', $request->rating_min);
            });
        }

        // Points en vedette uniquement
        if ($request->boolean('featured_only')) {
            $query->where('is_featured', true);
        }

        $points = $query->get()->map(function($point) {
            $data = [
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
                'views' => $point->views,
            ];

            // Ajouter les évaluations si disponibles
            if ($point->details && $point->details->rating) {
                $data['rating'] = $point->details->rating;
                $data['reviews_count'] = $point->details->reviews_count;
                $data['star_rating'] = $point->details->star_rating;
            }

            return $data;
        });

        return response()->json([
            'success' => true,
            'points' => $points,
            'total' => $points->count()
        ]);
    }

    /**
     * Display the full map view.
     */
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
            })->toArray();
        
        $villes = MapPoint::select('ville')
            ->selectRaw('count(*) as total')
            ->whereNotNull('ville')
            ->groupBy('ville')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'ville' => $item->ville,
                    'total' => $item->total
                ];
            })->toArray();
        
        // Statistiques des évaluations
        $ratingStats = MapPointDetail::selectRaw('
            AVG(rating) as avg_rating,
            SUM(reviews_count) as total_reviews,
            COUNT(*) as rated_points
        ')->first();

        // Statistiques des réseaux sociaux
        $socialStats = $this->getSocialMediaStats();
        
        $categoriesCount = count($categories);
        $villesCount = count($villes);
        
        return view('map-marker::map', compact(
            'totalPoints', 
            'categories', 
            'villes', 
            'categoriesCount', 
            'villesCount',
            'ratingStats',
            'socialStats'
        ));
    }

    /**
     * API endpoint to get point details including all social media.
     */
    public function getPointDetails($id)
    {
        $point = MapPoint::with(['images', 'videos', 'details', 'etablissement', 'user'])
            ->findOrFail($id);
        
        $point->incrementViews();

        $data = [
            'id' => $point->id,
            'title' => $point->title,
            'description' => $point->description,
            'category' => [
                'value' => $point->category,
                'label' => $this->getCategoryLabel($point->category),
                'color' => $this->getCategoryColor($point->category),
                'icon' => $this->getCategoryIcon($point->category),
            ],
            'location' => [
                'latitude' => $point->latitude,
                'longitude' => $point->longitude,
                'adresse' => $point->adresse,
                'ville' => $point->ville,
                'code_postal' => $point->code_postal,
                'full_address' => $point->details ? $point->details->full_address : null,
            ],
            'media' => [
                'main_image' => $point->main_image ? asset('storage/' . $point->main_image) : null,
                'youtube_id' => $point->youtube_id,
                'images' => $point->images->map(function($image) {
                    return [
                        'id' => $image->id,
                        'url' => asset('storage/' . $image->image),
                        'thumbnail' => asset('storage/' . ($image->thumbnail ?: $image->image)),
                        'caption' => $image->caption,
                    ];
                }),
                'videos' => $point->videos->map(function($video) {
                    return [
                        'id' => $video->id,
                        'title' => $video->title,
                        'youtube_id' => $video->youtube_id,
                        'youtube_url' => $video->youtube_url,
                        'thumbnail' => "https://img.youtube.com/vi/{$video->youtube_id}/hqdefault.jpg",
                    ];
                }),
            ],
            'stats' => [
                'views' => $point->views,
                'is_featured' => $point->is_featured,
                'created_at' => $point->created_at->format('d/m/Y'),
                'updated_at' => $point->updated_at->format('d/m/Y'),
            ],
        ];

        // Ajouter les détails avec tous les réseaux sociaux
        if ($point->details) {
            $data['details'] = [
                'phone' => $point->details->phone,
                'email' => $point->details->email,
                'website' => $point->details->website,
                'long_description' => $point->details->long_description,
                'contact_person' => $point->details->contact_person,
                'horaires' => $point->details->horaires,
                'services' => $point->details->services,
                'tarifs' => $point->details->tarifs,
                'rating' => $point->details->rating,
                'reviews_count' => $point->details->reviews_count,
                'star_rating' => $point->details->star_rating,
                'social_networks' => $point->details->social_networks,
                'slug' => $point->details->slug,
                'meta_title' => $point->details->meta_title,
                'meta_description' => $point->details->meta_description,
            ];
        }

        // Ajouter l'établissement lié
        if ($point->etablissement) {
            $data['etablissement'] = [
                'id' => $point->etablissement->id,
                'name' => $point->etablissement->name,
                'logo' => $point->etablissement->logo ? asset('storage/' . $point->etablissement->logo) : null,
            ];
        }

        // Ajouter l'utilisateur créateur
        if ($point->user) {
            $data['user'] = [
                'id' => $point->user->id,
                'name' => $point->user->name,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Get statistics about social media usage.
     */
    private function getSocialMediaStats()
    {
        $socialFields = [
            'facebook', 'instagram', 'twitter', 'linkedin', 'youtube', 'tiktok',
            'pinterest', 'snapchat', 'whatsapp', 'telegram', 'discord', 'twitch',
            'reddit', 'github', 'medium', 'tumblr', 'vimeo', 'dribbble', 'behance',
            'soundcloud', 'spotify', 'tripadvisor', 'foursquare', 'yelp', 'google_maps'
        ];

        $stats = [];
        $totalDetails = MapPointDetail::count();

        foreach ($socialFields as $field) {
            $count = MapPointDetail::whereNotNull($field)->where($field, '!=', '')->count();
            $stats[$field] = [
                'count' => $count,
                'percentage' => $totalDetails > 0 ? round(($count / $totalDetails) * 100, 2) : 0,
                'label' => $this->getSocialLabel($field),
                'icon' => $this->getSocialIcon($field),
                'color' => $this->getSocialColor($field),
            ];
        }

        return $stats;
    }

    /**
     * Get social media label.
     */
    private function getSocialLabel($field)
    {
        $labels = [
            'facebook' => 'Facebook',
            'instagram' => 'Instagram',
            'twitter' => 'X (Twitter)',
            'linkedin' => 'LinkedIn',
            'youtube' => 'YouTube',
            'tiktok' => 'TikTok',
            'pinterest' => 'Pinterest',
            'snapchat' => 'Snapchat',
            'whatsapp' => 'WhatsApp',
            'telegram' => 'Telegram',
            'discord' => 'Discord',
            'twitch' => 'Twitch',
            'reddit' => 'Reddit',
            'github' => 'GitHub',
            'medium' => 'Medium',
            'tumblr' => 'Tumblr',
            'vimeo' => 'Vimeo',
            'dribbble' => 'Dribbble',
            'behance' => 'Behance',
            'soundcloud' => 'SoundCloud',
            'spotify' => 'Spotify',
            'tripadvisor' => 'TripAdvisor',
            'foursquare' => 'Foursquare',
            'yelp' => 'Yelp',
            'google_maps' => 'Google Maps'
        ];

        return $labels[$field] ?? ucfirst($field);
    }

    /**
     * Get social media icon.
     */
    private function getSocialIcon($field)
    {
        $icons = [
            'facebook' => 'fab fa-facebook',
            'instagram' => 'fab fa-instagram',
            'twitter' => 'fab fa-x-twitter',
            'linkedin' => 'fab fa-linkedin',
            'youtube' => 'fab fa-youtube',
            'tiktok' => 'fab fa-tiktok',
            'pinterest' => 'fab fa-pinterest',
            'snapchat' => 'fab fa-snapchat',
            'whatsapp' => 'fab fa-whatsapp',
            'telegram' => 'fab fa-telegram',
            'discord' => 'fab fa-discord',
            'twitch' => 'fab fa-twitch',
            'reddit' => 'fab fa-reddit',
            'github' => 'fab fa-github',
            'medium' => 'fab fa-medium',
            'tumblr' => 'fab fa-tumblr',
            'vimeo' => 'fab fa-vimeo',
            'dribbble' => 'fab fa-dribbble',
            'behance' => 'fab fa-behance',
            'soundcloud' => 'fab fa-soundcloud',
            'spotify' => 'fab fa-spotify',
            'tripadvisor' => 'fab fa-tripadvisor',
            'foursquare' => 'fab fa-foursquare',
            'yelp' => 'fab fa-yelp',
            'google_maps' => 'fab fa-google'
        ];

        return $icons[$field] ?? 'fas fa-link';
    }

    /**
     * Get social media color.
     */
    private function getSocialColor($field)
    {
        $colors = [
            'facebook' => '#1877f2',
            'instagram' => '#e4405f',
            'twitter' => '#000000',
            'linkedin' => '#0a66c2',
            'youtube' => '#ff0000',
            'tiktok' => '#000000',
            'pinterest' => '#bd081c',
            'snapchat' => '#fffc00',
            'whatsapp' => '#25d366',
            'telegram' => '#0088cc',
            'discord' => '#5865f2',
            'twitch' => '#9146ff',
            'reddit' => '#ff4500',
            'github' => '#333333',
            'medium' => '#00ab6c',
            'tumblr' => '#34526f',
            'vimeo' => '#1ab7ea',
            'dribbble' => '#ea4c89',
            'behance' => '#1769ff',
            'soundcloud' => '#ff5500',
            'spotify' => '#1db954',
            'tripadvisor' => '#00af87',
            'foursquare' => '#f94877',
            'yelp' => '#d32323',
            'google_maps' => '#4285f4'
        ];

        return $colors[$field] ?? '#6c757d';
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