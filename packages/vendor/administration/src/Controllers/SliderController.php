<?php

namespace Vendor\Administration\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Models\Country;
use App\Models\Province;
use App\Models\Region;
use App\Models\Ville;
use Illuminate\Http\Request;
use Illuminate\Http\File as HttpFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SliderController extends Controller
{
    protected $storageUrl;
    
    public function __construct()
    {
        // Récupérer l'URL de base depuis le fichier .env
        $this->storageUrl = rtrim(env('APP_URL', 'https://admin.goexploriabusiness.com'), '/') . '/storage/';
        
        Log::channel('slider_debug')->info('SliderController initialized', [
            'storage_url' => $this->storageUrl,
            'app_url' => env('APP_URL')
        ]);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $startTime = microtime(true);
        $requestId = (string) Str::uuid();
        
        Log::channel('slider_debug')->info('Slider index request', [
            'request_id' => $requestId,
            'params' => $request->all(),
            'user_id' => auth()->id(),
            'ip' => $request->ip()
        ]);
        
        // Récupérer les paramètres
        $search = $request->input('search', '');
        $status = $request->input('status', '');
        $type = $request->input('type', '');
        $countryId = $request->input('country_id', '');
        $provinceId = $request->input('province_id', '');
        $regionId = $request->input('region_id', '');
        $villeId = $request->input('ville_id', '');
        $perPage = $request->input('per_page', 10);

        // Construire la requête
        $query = Slider::query()
            ->with(['country', 'province', 'region', 'ville'])
            ->ordered();

        // Appliquer les filtres
        if (!empty($search)) {
            $query->search($search);
        }

        if (!empty($status)) {
            $query->status($status);
        }

        if (!empty($type)) {
            $query->ofType($type);
        }

        // Filtres hiérarchiques de localisation
        if (!empty($villeId)) {
            $query->where('ville_id', $villeId);
        } elseif (!empty($regionId)) {
            $query->where('region_id', $regionId);
        } elseif (!empty($provinceId)) {
            $query->where('province_id', $provinceId);
        } elseif (!empty($countryId)) {
            $query->where('country_id', $countryId);
        }

        $duration = round((microtime(true) - $startTime) * 1000, 2);
        
        // Si c'est une requête AJAX
        if ($request->ajax()) {
            $sliders = $query->paginate($perPage);
            
            // Ajouter la localisation complète à chaque slider
            $sliders->getCollection()->transform(function($slider) {
                $slider->full_location = $slider->getFullLocationAttribute();
                return $slider;
            });
            
            Log::channel('slider_debug')->info('Slider index completed (AJAX)', [
                'request_id' => $requestId,
                'total' => $sliders->total(),
                'per_page' => $perPage,
                'duration_ms' => $duration
            ]);
            
            return response()->json([
                'success' => true,
                'data' => $sliders->items(),
                'current_page' => $sliders->currentPage(),
                'last_page' => $sliders->lastPage(),
                'per_page' => $sliders->perPage(),
                'total' => $sliders->total(),
                'next_page_url' => $sliders->nextPageUrl(),
                'prev_page_url' => $sliders->previousPageUrl(),
            ]);
        }

        // Pour vue non-AJAX
        $sliders = $query->paginate($perPage);
        
        Log::channel('slider_debug')->info('Slider index completed (View)', [
            'request_id' => $requestId,
            'total' => $sliders->total(),
            'duration_ms' => $duration
        ]);
        
        return view('administration::sliders.index', compact('sliders', 'search', 'status', 'type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $startTime = microtime(true);
        $requestId = (string) Str::uuid();
        
        Log::info('Slider store started', [
            'request_id' => $requestId,
            'user_id' => auth()->id(),
            'request_data' => $request->except(['image', 'video_file']),
            'has_image' => $request->hasFile('image'),
            'has_video' => $request->hasFile('video_file'),
            'type' => $request->type,
            'video_source' => $request->video_source,
            'storage_url' => $this->storageUrl
        ]);
        
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:image,video',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'video_file' => 'nullable|mimes:mp4,avi,mov,wmv|max:102400',
            'video_source' => 'nullable|in:url,upload',
            'video_platform' => 'nullable|in:youtube,vimeo,other',
            'video_url' => 'nullable|url',
            'clip_start' => 'nullable|integer|min:0',
            'clip_duration' => 'nullable|integer|min:1|max:30',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'button_text' => 'nullable|string|max:50',
            'button_url' => 'nullable|url',
            'country_id' => 'nullable|exists:countries,id',
            'province_id' => 'nullable|exists:provinces,id',
            'region_id' => 'nullable|exists:regions,id',
            'ville_id' => 'nullable|exists:villes,id',
        ]);

        if ($validator->fails()) {
            Log::warning('Slider store validation failed', [
                'request_id' => $requestId,
                'errors' => $validator->errors()->toArray()
            ]);
            
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Validation hiérarchique de la localisation
        if ($request->filled('ville_id')) {
            $ville = Ville::with('region.province.country')->find($request->ville_id);
            if ($ville) {
                $request->merge([
                    'region_id' => $ville->region_id,
                    'province_id' => $ville->region->province_id,
                    'country_id' => $ville->region->province->country_id,
                ]);
            }
        } elseif ($request->filled('region_id')) {
            $region = Region::with('province.country')->find($request->region_id);
            if ($region) {
                $request->merge([
                    'province_id' => $region->province_id,
                    'country_id' => $region->province->country_id,
                ]);
            }
        } elseif ($request->filled('province_id')) {
            $province = Province::with('country')->find($request->province_id);
            if ($province) {
                $request->merge([
                    'country_id' => $province->country_id,
                ]);
            }
        }

        // Traitement des fichiers
        $imagePath = null;
        $videoPath = null;
        $thumbnailPath = null;
        $videoType = null;
        $videoUrl = null;

        // Traitement de l'image
        if ($request->hasFile('image')) {
            try {
                $imagePath = $this->uploadFile($request->file('image'), 'sliders', $requestId);
                Log::channel('slider_debug')->debug('Image uploaded', [
                    'request_id' => $requestId,
                    'path' => $imagePath
                ]);
            } catch (\Exception $e) {
                Log::channel('slider_debug')->error('Image upload failed', [
                    'request_id' => $requestId,
                    'error' => $e->getMessage()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'upload de l\'image',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        // Traitement de la vidéo selon la source
        if ($request->type === 'video') {
            $videoSource = $request->video_source;
            
            if ($videoSource === 'url') {
                $videoPlatform = $request->video_platform;
                $externalUrl = $request->video_url;

                if (empty($externalUrl)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'URL vidéo requise pour la source URL.',
                    ], 422);
                }

                if (in_array($videoPlatform, ['youtube', 'vimeo'], true)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Méthode 3 active: pour YouTube/Vimeo, téléchargez d\'abord la vidéo puis utilisez "Upload vidéo local".',
                    ], 422);
                } else {
                    // Mode URL "other" conservé tel quel
                    $videoUrl = $externalUrl;
                    $videoPath = $externalUrl;
                    $videoType = 'other';
                }
                
            } elseif ($videoSource === 'upload' && $request->hasFile('video_file')) {
                // Mode Upload local
                try {
                    $videoPath = $this->uploadFile($request->file('video_file'), 'sliders/videos', $requestId);
                    $videoType = 'upload';
                    
                    Log::channel('slider_debug')->debug('Video file uploaded', [
                        'request_id' => $requestId,
                        'path' => $videoPath
                    ]);
                } catch (\Exception $e) {
                    Log::channel('slider_debug')->error('Video upload failed', [
                        'request_id' => $requestId,
                        'error' => $e->getMessage()
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Erreur lors de l\'upload de la vidéo',
                        'error' => $e->getMessage()
                    ], 500);
                }
            }
        }

        // Si c'est une vidéo et qu'on a une image, utiliser l'image comme thumbnail
        if ($request->type === 'video' && $imagePath) {
            $thumbnailPath = $imagePath;
        }

        // Création du slider
        $sliderData = [
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'image_path' => $imagePath,
            'video_path' => $videoPath,
            'video_type' => $videoType,
            'video_url' => $videoUrl,
            'thumbnail_path' => $thumbnailPath,
            'order' => $request->order ?? (Slider::max('order') + 1),
            'is_active' => $request->boolean('is_active', true),
            'button_text' => $request->button_text,
            'button_url' => $request->button_url,
            'country_id' => $request->country_id,
            'province_id' => $request->province_id,
            'region_id' => $request->region_id,
            'ville_id' => $request->ville_id,
        ];

        $slider = Slider::create($sliderData);
        
        $duration = round((microtime(true) - $startTime) * 1000, 2);
        
        Log::channel('slider_debug')->info('Slider created successfully', [
            'request_id' => $requestId,
            'slider_id' => $slider->id,
            'slider_name' => $slider->name,
            'image_path' => $imagePath,
            'video_path' => $videoPath,
            'video_url' => $videoUrl,
            'video_type' => $videoType,
            'video_source' => $videoSource ?? null,
            'location' => [
                'country_id' => $request->country_id,
                'province_id' => $request->province_id,
                'region_id' => $request->region_id,
                'ville_id' => $request->ville_id,
            ],
            'duration_ms' => $duration
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Slider créé avec succès !',
            'data' => $slider->load(['country', 'province', 'region', 'ville'])
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $startTime = microtime(true);
        $requestId = (string) Str::uuid();
        
        Log::channel('slider_debug')->info('Slider show request', [
            'request_id' => $requestId,
            'slider_id' => $id,
            'user_id' => auth()->id()
        ]);
        
        $slider = Slider::query()
            ->with(['country', 'province', 'region', 'ville'])
            ->findOrFail($id);
        
        $duration = round((microtime(true) - $startTime) * 1000, 2);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $slider
            ]);
        }

        return view('sliders.show', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $startTime = microtime(true);
        $requestId = (string) Str::uuid();
        
        Log::info('UPDATE REQUEST DETAILS', [
            'request_id' => $requestId,
            'slider_id' => $id,
            'all_input_keys' => array_keys($request->all()),
            'all_files_keys' => array_keys($request->allFiles()),
            'has_edit_video_file' => $request->hasFile('edit_video_file'),
            'edit_video_source' => $request->input('edit_video_source'),
            'type' => $request->input('type'),
        ]);
        
        $slider = Slider::findOrFail($id);

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:image,video',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'edit_video_file' => 'nullable|mimes:mp4,avi,mov,wmv|max:102400',
            'edit_video_source' => 'nullable|in:url,upload',
            'edit_video_platform' => 'nullable|in:youtube,vimeo,other',
            'video_url' => 'nullable|url',
            'edit_clip_start' => 'nullable|integer|min:0',
            'edit_clip_duration' => 'nullable|integer|min:1|max:30',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'button_text' => 'nullable|string|max:50',
            'button_url' => 'nullable|url',
            'country_id' => 'nullable|exists:countries,id',
            'province_id' => 'nullable|exists:provinces,id',
            'region_id' => 'nullable|exists:regions,id',
            'ville_id' => 'nullable|exists:villes,id',
        ]);

        if ($validator->fails()) {
            Log::channel('slider_debug')->warning('Slider update validation failed', [
                'request_id' => $requestId,
                'slider_id' => $id,
                'errors' => $validator->errors()->toArray()
            ]);
            
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Validation hiérarchique de la localisation
        if ($request->filled('ville_id')) {
            $ville = Ville::with('region.province.country')->find($request->ville_id);
            if ($ville) {
                $request->merge([
                    'region_id' => $ville->region_id,
                    'province_id' => $ville->region->province_id,
                    'country_id' => $ville->region->province->country_id,
                ]);
            }
        } elseif ($request->filled('region_id')) {
            $region = Region::with('province.country')->find($request->region_id);
            if ($region) {
                $request->merge([
                    'province_id' => $region->province_id,
                    'country_id' => $region->province->country_id,
                ]);
            }
        } elseif ($request->filled('province_id')) {
            $province = Province::with('country')->find($request->province_id);
            if ($province) {
                $request->merge([
                    'country_id' => $province->country_id,
                ]);
            }
        }

        // Traitement de l'image
        if ($request->hasFile('image')) {
            try {
                // Supprimer l'ancienne image
                if ($slider->image_path) {
                    $this->deleteFile($slider->image_path, $requestId);
                }
                
                $imagePath = $this->uploadFile($request->file('image'), 'sliders', $requestId);
                $slider->image_path = $imagePath;
                
                // Si c'est une vidéo, mettre à jour le thumbnail
                if ($request->type === 'video' && empty($slider->thumbnail_path)) {
                    $slider->thumbnail_path = $imagePath;
                }
                
                Log::channel('slider_debug')->info('Image updated', [
                    'request_id' => $requestId,
                    'slider_id' => $id,
                    'new_path' => $imagePath
                ]);
            } catch (\Exception $e) {
                Log::channel('slider_debug')->error('Image update failed', [
                    'request_id' => $requestId,
                    'error' => $e->getMessage()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'upload de l\'image',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        // Traitement de la vidéo selon la source
        if ($request->type === 'video') {
            $videoSource = $request->input('edit_video_source');
            
            Log::channel('slider_debug')->info('Video processing', [
                'request_id' => $requestId,
                'video_source' => $videoSource,
                'has_file' => $request->hasFile('edit_video_file'),
                'has_url' => $request->filled('video_url'),
            ]);
            
            if ($videoSource === 'url') {
                // Mode URL - remplacer par nouvelle URL
                if ($request->filled('video_url')) {
                    // Supprimer l'ancienne vidéo si c'était un upload
                    if ($slider->video_path && $slider->video_type === 'upload') {
                        $this->deleteFile($slider->video_path, $requestId);
                    }

                    $videoPlatform = $request->input('edit_video_platform', 'other');

                    if (in_array($videoPlatform, ['youtube', 'vimeo'], true)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Méthode 3 active: pour YouTube/Vimeo, téléchargez d\'abord la vidéo puis utilisez "Upload vidéo local".',
                        ], 422);
                    } else {
                        $slider->video_url = $request->video_url;
                        $slider->video_path = $request->video_url;
                        $slider->video_type = 'other';
                    }
                    
                    Log::channel('slider_debug')->info('Video URL updated', [
                        'request_id' => $requestId,
                        'url' => $request->video_url,
                        'platform' => $videoPlatform,
                    ]);
                }
            } 
            elseif ($videoSource === 'upload' && $request->hasFile('edit_video_file')) {
                // Mode Upload avec nouveau fichier
                try {
                    // Supprimer l'ancienne vidéo
                    if ($slider->video_path) {
                        $this->deleteFile($slider->video_path, $requestId);
                    }
                    
                    $videoFile = $request->file('edit_video_file');
                    $videoPath = $this->uploadFile($videoFile, 'sliders/videos', $requestId);
                    
                    $slider->video_path = $videoPath;
                    $slider->video_type = 'upload';
                    $slider->video_url = null;
                    
                    Log::channel('slider_debug')->info('Video file uploaded', [
                        'request_id' => $requestId,
                        'new_path' => $videoPath,
                        'file_name' => $videoFile->getClientOriginalName(),
                        'file_size' => $videoFile->getSize(),
                    ]);
                } catch (\Exception $e) {
                    Log::channel('slider_debug')->error('Video upload failed', [
                        'request_id' => $requestId,
                        'error' => $e->getMessage()
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Erreur lors de l\'upload de la vidéo',
                        'error' => $e->getMessage()
                    ], 500);
                }
            }
            // Si aucun video_source n'est envoyé, on garde la vidéo existante
            else {
                Log::channel('slider_debug')->info('Keeping existing video', [
                    'request_id' => $requestId,
                    'current_video_path' => $slider->video_path,
                    'current_video_type' => $slider->video_type,
                ]);
            }
        } else {
            // Si le type n'est pas vidéo, supprimer les données vidéo
            if ($slider->video_path) {
                $this->deleteFile($slider->video_path, $requestId);
                $slider->video_path = null;
                $slider->video_type = null;
                $slider->video_url = null;
            }
        }

        // Mise à jour des données de base
        $slider->name = $request->name;
        $slider->description = $request->description;
        $slider->type = $request->type;
        $slider->is_active = $request->boolean('is_active', true);
        $slider->button_text = $request->button_text;
        $slider->button_url = $request->button_url;
        
        // Mise à jour de l'ordre si fourni
        if ($request->has('order')) {
            $slider->order = $request->order;
        }
        
        // Mise à jour de la localisation
        $slider->country_id = $request->country_id;
        $slider->province_id = $request->province_id;
        $slider->region_id = $request->region_id;
        $slider->ville_id = $request->ville_id;

        // Gestion du thumbnail pour les vidéos
        if ($request->type === 'video' && empty($slider->thumbnail_path) && $slider->image_path) {
            $slider->thumbnail_path = $slider->image_path;
        }

        // Sauvegarder
        $slider->save();
        
        $duration = round((microtime(true) - $startTime) * 1000, 2);
        
        Log::channel('slider_debug')->info('Slider updated successfully', [
            'request_id' => $requestId,
            'slider_id' => $id,
            'slider_name' => $slider->name,
            'image_path' => $slider->image_path,
            'video_path' => $slider->video_path,
            'video_type' => $slider->video_type,
            'duration_ms' => $duration
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Slider mis à jour avec succès !',
            'data' => $slider->load(['country', 'province', 'region', 'ville'])
        ]);
    }

/**
 * Remove the specified resource from storage (immediate permanent delete).
 */
public function destroy($id)
{
    $startTime = microtime(true);
    $requestId = (string) Str::uuid();
    
    Log::channel('slider_debug')->info('Slider delete started', [
        'request_id' => $requestId,
        'slider_id' => $id,
        'user_id' => auth()->id()
    ]);
    
    $slider = Slider::findOrFail($id);
    
    // Supprimer les fichiers immédiatement
    $this->deleteFile($slider->image_path, $requestId);
    $this->deleteFile($slider->video_path, $requestId);
    $this->deleteFile($slider->thumbnail_path, $requestId);
    
    // Suppression definitive de l'enregistrement
    $slider->forceDelete();
    
    $duration = round((microtime(true) - $startTime) * 1000, 2);
    
    Log::channel('slider_debug')->info('Slider permanently deleted with files removed', [
        'request_id' => $requestId,
        'slider_id' => $id,
        'slider_name' => $slider->name,
        'duration_ms' => $duration
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Slider supprime definitivement avec succes !'
    ]);
}

/**
 * Force delete alias kept for backward compatibility.
 */
public function forceDelete($id)
{
    return $this->destroy($id);
}

/**
 * Delete a file from local storage immediately
 */
private function deleteFile($filePath, $requestId)
{
    if (!$filePath) {
        return false;
    }
    
    try {
        // Si c'est une URL complète, extraire le chemin relatif
        $relativePath = $this->extractRelativePath($filePath);
        
        Log::channel('slider_debug')->info('Deleting file immediately from local storage', [
            'request_id' => $requestId,
            'full_url' => $filePath,
            'relative_path' => $relativePath
        ]);
        
        if ($relativePath && Storage::disk('public')->exists($relativePath)) {
            $deleted = Storage::disk('public')->delete($relativePath);
            Log::channel('slider_debug')->info('File deleted ' . ($deleted ? 'successfully' : 'failed'), [
                'request_id' => $requestId,
                'relative_path' => $relativePath,
                'deleted' => $deleted
            ]);
            return $deleted;
        }
        
        Log::channel('slider_debug')->warning('File not found for immediate deletion', [
            'request_id' => $requestId,
            'relative_path' => $relativePath,
            'full_url' => $filePath
        ]);
        
        return false;
    } catch (\Exception $e) {
        Log::channel('slider_debug')->error('Immediate file deletion failed', [
            'request_id' => $requestId,
            'file_path' => $filePath,
            'error' => $e->getMessage()
        ]);
        return false;
    }
}

    /**
     * Toggle active status
     */
    public function toggleStatus($id)
    {
        $startTime = microtime(true);
        $requestId = (string) Str::uuid();
        
        Log::channel('slider_debug')->info('Slider toggle status', [
            'request_id' => $requestId,
            'slider_id' => $id,
            'user_id' => auth()->id()
        ]);
        
        $slider = Slider::findOrFail($id);
        $slider->is_active = !$slider->is_active;
        $slider->save();
        
        $duration = round((microtime(true) - $startTime) * 1000, 2);
        
        Log::channel('slider_debug')->info('Slider status toggled', [
            'request_id' => $requestId,
            'slider_id' => $id,
            'new_status' => $slider->is_active,
            'duration_ms' => $duration
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Statut modifié avec succès !',
            'is_active' => $slider->is_active
        ]);
    }

    /**
     * Update order of sliders
     */
    public function updateOrder(Request $request)
    {
        $startTime = microtime(true);
        $requestId = (string) Str::uuid();
        
        Log::channel('slider_debug')->info('Update order started', [
            'request_id' => $requestId,
            'sliders_count' => count($request->sliders ?? []),
            'user_id' => auth()->id()
        ]);
        
        $validator = Validator::make($request->all(), [
            'sliders' => 'required|array',
            'sliders.*.id' => 'required|exists:sliders,id',
            'sliders.*.order' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            Log::channel('slider_debug')->warning('Update order validation failed', [
                'request_id' => $requestId,
                'errors' => $validator->errors()->toArray()
            ]);
            
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            foreach ($request->sliders as $sliderData) {
                Slider::where('id', $sliderData['id'])
                    ->update(['order' => $sliderData['order']]);
            }

            DB::commit();
            
            $duration = round((microtime(true) - $startTime) * 1000, 2);
            
            Log::channel('slider_debug')->info('Order updated successfully', [
                'request_id' => $requestId,
                'duration_ms' => $duration
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ordre des sliders mis à jour avec succès !'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::channel('slider_debug')->error('Order update failed', [
                'request_id' => $requestId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de l\'ordre'
            ], 500);
        }
    }

    /**
     * Get statistics
     */
    public function statistics()
    {
        $startTime = microtime(true);
        $requestId = (string) Str::uuid();
        
        Log::channel('slider_debug')->info('Statistics requested', [
            'request_id' => $requestId,
            'user_id' => auth()->id()
        ]);
        
        $total = Slider::count();
        $active = Slider::where('is_active', true)->count();
        $inactive = Slider::where('is_active', false)->count();
        $images = Slider::where('type', 'image')->count();
        $videos = Slider::where('type', 'video')->count();
        $deleted = 0;
        
        $thisMonth = Slider::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        $duration = round((microtime(true) - $startTime) * 1000, 2);
        
        Log::channel('slider_debug')->info('Statistics retrieved', [
            'request_id' => $requestId,
            'total' => $total,
            'duration_ms' => $duration
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $total,
                'active' => $active,
                'inactive' => $inactive,
                'images' => $images,
                'videos' => $videos,
                'deleted' => $deleted,
                'this_month' => $thisMonth,
            ]
        ]);
    }

    /**
     * Preview slider
     */
    public function preview($id)
    {
        $startTime = microtime(true);
        $requestId = (string) Str::uuid();
        
        Log::channel('slider_debug')->info('Preview requested', [
            'request_id' => $requestId,
            'slider_id' => $id,
            'user_id' => auth()->id()
        ]);
        
        $slider = Slider::with(['country', 'province', 'region', 'ville'])->findOrFail($id);
        
        $duration = round((microtime(true) - $startTime) * 1000, 2);
        
        return response()->json([
            'success' => true,
            'data' => [
                'name' => $slider->name,
                'type' => $slider->type,
                'image_url' => $slider->image_url,
                'video_url' => $slider->video_url,
                'video_embed_url' => $slider->video_embed_url,
                'thumbnail_url' => $slider->thumbnail_url,
                'description' => $slider->description,
                'button_text' => $slider->button_text,
                'button_url' => $slider->button_url,
                'is_youtube' => $slider->is_youtube,
                'is_vimeo' => $slider->is_vimeo,
                'is_uploaded_video' => $slider->is_uploaded_video,
                'youtube_id' => $slider->youtube_id,
                'location' => $slider->full_location,
                'location_hierarchy' => $slider->location_hierarchy,
                'video_source' => $slider->video_path ? 'upload' : ($slider->video_url ? 'url' : null),
                'video_platform' => $this->getVideoPlatform($slider->video_type),
            ]
        ]);
    }
    
    /**
     * Get video platform label
     */
    private function getVideoPlatform($type)
    {
        switch ($type) {
            case 'youtube':
                return 'YouTube';
            case 'vimeo':
                return 'Vimeo';
            case 'upload':
                return 'Upload local';
            case 'other':
                return 'Autre URL';
            default:
                return null;
        }
    }
    
    /**
     * Upload a file to local storage and return full URL
     */
    private function uploadFile($file, $path, $requestId)
    {
        // Générer un nom de fichier unique
        $extension = $file->getClientOriginalExtension();
        $filename = Str::random(40) . '.' . $extension;
        
        // Chemin relatif pour le stockage
        $relativePath = $path . '/' . $filename;
        
        Log::channel('slider_debug')->info('Uploading file to local storage', [
            'request_id' => $requestId,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'relative_path' => $relativePath,
        ]);
        
        // Stocker le fichier dans le disque public
        $stored = $file->storeAs($path, $filename, 'public');
        
        if (!$stored) {
            throw new \Exception('Failed to store file locally');
        }
        
        // Construire l'URL complète
        $fullUrl = $this->storageUrl . $relativePath;
        
        Log::channel('slider_debug')->info('File stored successfully', [
            'request_id' => $requestId,
            'relative_path' => $stored,
            'full_url' => $fullUrl,
        ]);
        
        // Retourner l'URL complète (pas le chemin relatif)
        return $fullUrl;
    }

    /**
     * Download YouTube/Vimeo video, clip 1-30 seconds, then store as local upload.
     */
    private function downloadAndClipExternalVideo(string $externalUrl, int $clipStart, int $clipDuration, string $requestId): string
    {
        $clipStart = max(0, $clipStart);
        $clipDuration = max(1, min(30, $clipDuration));

        if (!$this->isCommandAvailable('ffmpeg')) {
            throw new \Exception('ffmpeg est requis sur le serveur pour couper la vidéo.');
        }

        $ytDlpBin = $this->resolveYtDlpCommand();
        if ($ytDlpBin === null) {
            throw new \Exception('yt-dlp est requis sur le serveur pour télécharger la vidéo externe.');
        }

        $tempDir = storage_path('app/temp/slider-clips');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0775, true);
        }

        $sourcePath = $tempDir . DIRECTORY_SEPARATOR . 'source_' . Str::random(12) . '.mp4';
        $clipPath = $tempDir . DIRECTORY_SEPARATOR . 'clip_' . Str::random(12) . '.mp4';

        try {
            $cookieOption = $this->buildYtDlpCookieOption();
            $downloadCommand = sprintf(
                '%s %s -f %s -o %s %s',
                $ytDlpBin,
                $cookieOption,
                escapeshellarg('best[ext=mp4]/best'),
                escapeshellarg($sourcePath),
                escapeshellarg($externalUrl)
            );

            $this->runShellCommand($downloadCommand, $requestId, 'yt-dlp download');

            if (!file_exists($sourcePath) || filesize($sourcePath) === 0) {
                throw new \Exception('Téléchargement vidéo échoué: fichier source introuvable.');
            }

            $clipCommand = sprintf(
                'ffmpeg -y -ss %d -i %s -t %d -c:v libx264 -c:a aac -movflags +faststart %s',
                $clipStart,
                escapeshellarg($sourcePath),
                $clipDuration,
                escapeshellarg($clipPath)
            );

            $this->runShellCommand($clipCommand, $requestId, 'ffmpeg clip');

            if (!file_exists($clipPath) || filesize($clipPath) === 0) {
                throw new \Exception('Découpe vidéo échouée: fichier de sortie introuvable.');
            }

            $fileName = 'clip_' . now()->format('Ymd_His') . '_' . Str::random(8) . '.mp4';
            $relativePath = 'sliders/videos/' . $fileName;

            Storage::disk('public')->putFileAs(
                'sliders/videos',
                new HttpFile($clipPath),
                $fileName
            );

            return $this->storageUrl . $relativePath;
        } finally {
            if (file_exists($sourcePath)) {
                @unlink($sourcePath);
            }
            if (file_exists($clipPath)) {
                @unlink($clipPath);
            }
        }
    }

    private function resolveYtDlpCommand(): ?string
    {
        if ($this->isCommandAvailable('yt-dlp')) {
            return 'yt-dlp';
        }

        if ($this->isCommandAvailable('yt_dlp')) {
            return 'yt_dlp';
        }

        return null;
    }

    private function buildYtDlpCookieOption(): string
    {
        $cookiePath = env('YT_DLP_COOKIES_FILE');
        if (empty($cookiePath)) {
            return '';
        }

        $resolvedPath = $cookiePath;
        if (!str_starts_with($cookiePath, DIRECTORY_SEPARATOR)) {
            $resolvedPath = base_path($cookiePath);
        }

        if (!file_exists($resolvedPath)) {
            Log::channel('slider_debug')->warning('YT_DLP_COOKIES_FILE does not exist', [
                'configured_path' => $cookiePath,
                'resolved_path' => $resolvedPath,
            ]);

            return '';
        }

        return '--cookies ' . escapeshellarg($resolvedPath);
    }

    private function isCommandAvailable(string $command): bool
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            @exec('where ' . $command . ' 2>NUL', $output, $exitCode);
            return $exitCode === 0;
        }

        @exec('command -v ' . escapeshellarg($command) . ' >/dev/null 2>&1', $output, $exitCode);
        return $exitCode === 0;
    }

    private function runShellCommand(string $command, string $requestId, string $context): void
    {
        $output = [];
        $exitCode = 0;
        @exec($command . ' 2>&1', $output, $exitCode);

        Log::channel('slider_debug')->info('Shell command executed', [
            'request_id' => $requestId,
            'context' => $context,
            'exit_code' => $exitCode,
            'command' => $command,
            'output' => implode(PHP_EOL, $output),
        ]);

        if ($exitCode !== 0) {
            $errorText = trim(implode(PHP_EOL, $output));

            if (
                str_contains($errorText, 'Sign in to confirm you') ||
                str_contains($errorText, '--cookies-from-browser') ||
                str_contains($errorText, '--cookies for the authentication')
            ) {
                throw new \Exception(
                    'YouTube bloque ce téléchargement anonyme. Configurez YT_DLP_COOKIES_FILE dans .env avec un fichier cookies YouTube valide.'
                );
            }

            throw new \Exception($errorText !== '' ? $errorText : 'Commande système échouée (' . $context . ').');
        }
    }
    
    /**
     * Extract relative path from full URL
     */
    private function extractRelativePath($url)
    {
        // Si c'est déjà un chemin relatif (pas d'URL complète)
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }
        
        // Extraire le chemin après /storage/
        $pattern = '#/storage/(.*)$#';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
        
        // Si on ne trouve pas le pattern, essayer de supprimer l'URL de base
        $baseUrl = rtrim(env('APP_URL', 'http://localhost:8000'), '/');
        if (strpos($url, $baseUrl) === 0) {
            $relative = substr($url, strlen($baseUrl));
            // Enlever le /storage/ au début
            $relative = preg_replace('#^/storage/#', '', $relative);
            return $relative;
        }
        
        return null;
    }
}
