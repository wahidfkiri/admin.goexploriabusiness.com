<?php

namespace Vendor\Administration\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Services\CDNService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SliderController extends Controller
{
    protected $cdnService;
    
    public function __construct(CDNService $cdnService)
    {
        $this->cdnService = $cdnService;
        
        Log::channel('slider')->info('SliderController initialized', [
            'cdn_enabled' => env('CDN_ENABLED', false),
            'cdn_url' => env('CDN_URL')
        ]);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $startTime = microtime(true);
        $requestId = (string) Str::uuid();
        
        Log::channel('slider')->info('Slider index request', [
            'request_id' => $requestId,
            'params' => $request->all(),
            'user_id' => auth()->id(),
            'ip' => $request->ip()
        ]);
        
        // Récupérer les paramètres
        $search = $request->input('search', '');
        $status = $request->input('status', '');
        $type = $request->input('type', '');
        $perPage = $request->input('per_page', 10);

        // Construire la requête
        $query = Slider::withTrashed()->ordered();

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

        $duration = round((microtime(true) - $startTime) * 1000, 2);
        
        // Si c'est une requête AJAX
        if ($request->ajax()) {
            $sliders = $query->paginate($perPage);
            
            Log::channel('slider')->info('Slider index completed (AJAX)', [
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
        
        Log::channel('slider')->info('Slider index completed (View)', [
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
        
        Log::channel('slider')->info('Slider store started', [
            'request_id' => $requestId,
            'user_id' => auth()->id(),
            'request_data' => $request->except(['image', 'video_file']),
            'has_image' => $request->hasFile('image'),
            'has_video' => $request->hasFile('video_file'),
            'type' => $request->type,
            'cdn_enabled' => env('CDN_ENABLED', false)
        ]);
        
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:image,video',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'video_file' => 'nullable|mimes:mp4,avi,mov,wmv|max:102400',
            'video_type' => 'nullable',
            'video_url' => 'nullable',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'button_text' => 'nullable|string|max:50',
            'button_url' => 'nullable',
        ]);

        if ($validator->fails()) {
            Log::channel('slider')->warning('Slider store validation failed', [
                'request_id' => $requestId,
                'errors' => $validator->errors()->toArray()
            ]);
            
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Traitement des fichiers
        $imagePath = null;
        $videoPath = null;
        $thumbnailPath = null;

        // Traitement de l'image
        if ($request->hasFile('image')) {
            try {
                $imagePath = $this->uploadFile($request->file('image'), 'sliders', $requestId);
                Log::channel('slider')->debug('Image uploaded', [
                    'request_id' => $requestId,
                    'path' => $imagePath
                ]);
            } catch (\Exception $e) {
                Log::channel('slider')->error('Image upload failed', [
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

        // Traitement de la vidéo uploadée
        if ($request->hasFile('video_file')) {
            try {
                $videoPath = $this->uploadFile($request->file('video_file'), 'sliders/videos', $requestId);
                Log::channel('slider')->debug('Video uploaded', [
                    'request_id' => $requestId,
                    'path' => $videoPath
                ]);
            } catch (\Exception $e) {
                Log::channel('slider')->error('Video upload failed', [
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
            'video_type' => $request->video_type,
            'video_url' => $request->video_url,
            'thumbnail_path' => $thumbnailPath,
            'order' => $request->order ?? (Slider::max('order') + 1),
            'is_active' => $request->boolean('is_active', true),
            'button_text' => $request->button_text,
            'button_url' => $request->button_url,
        ];

        $slider = Slider::create($sliderData);
        
        $duration = round((microtime(true) - $startTime) * 1000, 2);
        
        Log::channel('slider')->info('Slider created successfully', [
            'request_id' => $requestId,
            'slider_id' => $slider->id,
            'slider_name' => $slider->name,
            'image_path' => $imagePath,
            'video_path' => $videoPath,
            'duration_ms' => $duration
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Slider créé avec succès !',
            'data' => $slider
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $startTime = microtime(true);
        $requestId = (string) Str::uuid();
        
        Log::channel('slider')->info('Slider show request', [
            'request_id' => $requestId,
            'slider_id' => $id,
            'user_id' => auth()->id()
        ]);
        
        $slider = Slider::withTrashed()->findOrFail($id);
        
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
        
        Log::channel('slider')->info('Slider update started', [
            'request_id' => $requestId,
            'slider_id' => $id,
            'user_id' => auth()->id(),
            'request_data' => $request->except(['image', 'video_file']),
            'has_image' => $request->hasFile('image'),
            'has_video' => $request->hasFile('video_file')
        ]);
        
        $slider = Slider::withTrashed()->findOrFail($id);

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:image,video',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'video_file' => 'nullable|mimes:mp4,avi,mov,wmv|max:102400',
            'video_type' => 'in:youtube,vimeo,upload',
            'video_url' => 'nullable|url',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'button_text' => 'nullable|string|max:50',
            'button_url' => 'nullable',
        ]);

        if ($validator->fails()) {
            Log::channel('slider')->warning('Slider update validation failed', [
                'request_id' => $requestId,
                'slider_id' => $id,
                'errors' => $validator->errors()->toArray()
            ]);
            
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
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
                
                Log::channel('slider')->debug('Image updated', [
                    'request_id' => $requestId,
                    'slider_id' => $id,
                    'new_path' => $imagePath
                ]);
            } catch (\Exception $e) {
                Log::channel('slider')->error('Image update failed', [
                    'request_id' => $requestId,
                    'slider_id' => $id,
                    'error' => $e->getMessage()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'upload de l\'image',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        // Traitement de la vidéo uploadée
        if ($request->hasFile('video_file')) {
            try {
                // Supprimer l'ancienne vidéo
                if ($slider->video_path) {
                    $this->deleteFile($slider->video_path, $requestId);
                }
                
                $videoPath = $this->uploadFile($request->file('video_file'), 'sliders/videos', $requestId);
                $slider->video_path = $videoPath;
                $slider->video_type = 'upload';
                $slider->video_url = null;
                
                Log::channel('slider')->debug('Video updated', [
                    'request_id' => $requestId,
                    'slider_id' => $id,
                    'new_path' => $videoPath
                ]);
            } catch (\Exception $e) {
                Log::channel('slider')->error('Video update failed', [
                    'request_id' => $requestId,
                    'slider_id' => $id,
                    'error' => $e->getMessage()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'upload de la vidéo',
                    'error' => $e->getMessage()
                ], 500);
            }
        } elseif ($request->type === 'video' && $request->video_type !== 'upload') {
            // Si c'est une vidéo externe, supprimer la vidéo uploadée
            if ($slider->video_path) {
                $this->deleteFile($slider->video_path, $requestId);
                $slider->video_path = null;
            }
        }

        // Mise à jour des données
        $slider->name = $request->name;
        $slider->description = $request->description;
        $slider->type = $request->type;
        $slider->video_type = $request->video_type;
        $slider->video_url = $request->video_url;
        $slider->is_active = $request->boolean('is_active', true);
        $slider->button_text = $request->button_text;
        $slider->button_url = $request->button_url;

        // Gestion du thumbnail pour les vidéos
        if ($request->type === 'video' && empty($slider->thumbnail_path) && $slider->image_path) {
            $slider->thumbnail_path = $slider->image_path;
        }

        // Sauvegarder
        $slider->save();
        
        $duration = round((microtime(true) - $startTime) * 1000, 2);
        
        Log::channel('slider')->info('Slider updated successfully', [
            'request_id' => $requestId,
            'slider_id' => $id,
            'slider_name' => $slider->name,
            'image_path' => $slider->image_path,
            'video_path' => $slider->video_path,
            'duration_ms' => $duration
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Slider mis à jour avec succès !',
            'data' => $slider
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $startTime = microtime(true);
        $requestId = (string) Str::uuid();
        
        Log::channel('slider')->info('Slider delete started', [
            'request_id' => $requestId,
            'slider_id' => $id,
            'user_id' => auth()->id()
        ]);
        
        $slider = Slider::findOrFail($id);
        
        // Supprimer les fichiers
        $this->deleteFile($slider->image_path, $requestId);
        $this->deleteFile($slider->video_path, $requestId);
        $this->deleteFile($slider->thumbnail_path, $requestId);
        
        $slider->delete();
        
        $duration = round((microtime(true) - $startTime) * 1000, 2);
        
        Log::channel('slider')->info('Slider deleted successfully', [
            'request_id' => $requestId,
            'slider_id' => $id,
            'slider_name' => $slider->name,
            'duration_ms' => $duration
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Slider supprimé avec succès !'
        ]);
    }

    /**
     * Restore soft deleted slider
     */
    public function restore($id)
    {
        $startTime = microtime(true);
        $requestId = (string) Str::uuid();
        
        Log::channel('slider')->info('Slider restore started', [
            'request_id' => $requestId,
            'slider_id' => $id,
            'user_id' => auth()->id()
        ]);
        
        $slider = Slider::onlyTrashed()->findOrFail($id);
        $slider->restore();
        
        $duration = round((microtime(true) - $startTime) * 1000, 2);
        
        Log::channel('slider')->info('Slider restored successfully', [
            'request_id' => $requestId,
            'slider_id' => $id,
            'slider_name' => $slider->name,
            'duration_ms' => $duration
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Slider restauré avec succès !'
        ]);
    }

    /**
     * Force delete slider
     */
    public function forceDelete($id)
    {
        $startTime = microtime(true);
        $requestId = (string) Str::uuid();
        
        Log::channel('slider')->info('Slider force delete started', [
            'request_id' => $requestId,
            'slider_id' => $id,
            'user_id' => auth()->id()
        ]);
        
        $slider = Slider::onlyTrashed()->findOrFail($id);
        
        // Supprimer les fichiers
        $this->deleteFile($slider->image_path, $requestId);
        $this->deleteFile($slider->video_path, $requestId);
        $this->deleteFile($slider->thumbnail_path, $requestId);
        
        $slider->forceDelete();
        
        $duration = round((microtime(true) - $startTime) * 1000, 2);
        
        Log::channel('slider')->info('Slider force deleted successfully', [
            'request_id' => $requestId,
            'slider_id' => $id,
            'slider_name' => $slider->name,
            'duration_ms' => $duration
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Slider définitivement supprimé !'
        ]);
    }

    /**
     * Toggle active status
     */
    public function toggleStatus($id)
    {
        $startTime = microtime(true);
        $requestId = (string) Str::uuid();
        
        Log::channel('slider')->info('Slider toggle status', [
            'request_id' => $requestId,
            'slider_id' => $id,
            'user_id' => auth()->id()
        ]);
        
        $slider = Slider::findOrFail($id);
        $slider->is_active = !$slider->is_active;
        $slider->save();
        
        $duration = round((microtime(true) - $startTime) * 1000, 2);
        
        Log::channel('slider')->info('Slider status toggled', [
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
        
        Log::channel('slider')->info('Update order started', [
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
            Log::channel('slider')->warning('Update order validation failed', [
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
            
            Log::channel('slider')->info('Order updated successfully', [
                'request_id' => $requestId,
                'duration_ms' => $duration
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ordre des sliders mis à jour avec succès !'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::channel('slider')->error('Order update failed', [
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
        
        Log::channel('slider')->info('Statistics requested', [
            'request_id' => $requestId,
            'user_id' => auth()->id()
        ]);
        
        $total = Slider::count();
        $active = Slider::where('is_active', true)->count();
        $inactive = Slider::where('is_active', false)->count();
        $images = Slider::where('type', 'image')->count();
        $videos = Slider::where('type', 'video')->count();
        $deleted = Slider::onlyTrashed()->count();
        
        $thisMonth = Slider::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        $duration = round((microtime(true) - $startTime) * 1000, 2);
        
        Log::channel('slider')->info('Statistics retrieved', [
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
        
        Log::channel('slider')->info('Preview requested', [
            'request_id' => $requestId,
            'slider_id' => $id,
            'user_id' => auth()->id()
        ]);
        
        $slider = Slider::findOrFail($id);
        
        $duration = round((microtime(true) - $startTime) * 1000, 2);
        
        return response()->json([
            'success' => true,
            'data' => [
                'name' => $slider->name,
                'type' => $slider->type,
                'image_url' => $slider->image_url,
                'video_url' => $slider->video_url,
                'thumbnail_url' => $slider->thumbnail_url,
                'description' => $slider->description,
                'button_text' => $slider->button_text,
                'button_url' => $slider->button_url,
                'is_youtube' => $slider->is_youtube,
                'is_vimeo' => $slider->is_vimeo,
                'youtube_id' => $slider->youtube_id,
            ]
        ]);
    }
    
    /**
     * Upload a file to CDN (always use CDN)
     */
    private function uploadFile($file, $path, $requestId)
    {
        // Générer un nom de fichier unique
        $extension = $file->getClientOriginalExtension();
        $filename = Str::random(40) . '.' . $extension;
        $fullPath = trim($path . '/' . $filename, '/');
        
        Log::channel('slider')->debug('Uploading to CDN', [
            'request_id' => $requestId,
            'file_name' => $file->getClientOriginalName(),
            'original_extension' => $extension,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'target_path' => $fullPath
        ]);
        
        // Upload vers le CDN via le service
        $result = $this->cdnService->upload($file, $path, 'public');
        
        if (isset($result['success']) && $result['success']) {
            $cdnUrl = $result['url'];
            Log::channel('slider')->debug('CDN upload successful', [
                'request_id' => $requestId,
                'cdn_url' => $cdnUrl,
                'cdn_path' => $result['path'],
                'full_url' => $cdnUrl
            ]);
            
            // Retourne l'URL complète du CDN
            return $cdnUrl;
        }
        
        // Si CDN échoue, on utilise le stockage local en fallback
        Log::channel('slider')->warning('CDN upload failed, falling back to local storage', [
            'request_id' => $requestId,
            'error' => $result
        ]);
        
        $storedPath = $file->store($path, 'public');
        $localUrl = Storage::disk('public')->url($storedPath);
        
        Log::channel('slider')->debug('Local upload successful (fallback)', [
            'request_id' => $requestId,
            'path' => $storedPath,
            'url' => $localUrl
        ]);
        
        return $localUrl;
    }
    
    /**
     * Delete a file from storage (CDN or local)
     */
    private function deleteFile($filePath, $requestId)
    {
        if (!$filePath) {
            return false;
        }
        
        try {
            // Vérifier si c'est une URL CDN
            if ($this->isCdnUrl($filePath)) {
                $path = $this->extractPathFromCdnUrl($filePath);
                Log::channel('slider')->debug('Deleting from CDN', [
                    'request_id' => $requestId,
                    'url' => $filePath,
                    'path' => $path
                ]);
                
                $result = $this->cdnService->delete($path);
                
                if (isset($result['success']) && $result['success']) {
                    Log::channel('slider')->debug('CDN deletion successful', [
                        'request_id' => $requestId,
                        'path' => $path
                    ]);
                    return true;
                } else {
                    Log::channel('slider')->warning('CDN deletion failed', [
                        'request_id' => $requestId,
                        'path' => $path,
                        'result' => $result
                    ]);
                }
            } else {
                // Supprimer du stockage local
                Log::channel('slider')->debug('Deleting from local storage', [
                    'request_id' => $requestId,
                    'path' => $filePath
                ]);
                
                if (Storage::disk('public')->exists($filePath)) {
                    $deleted = Storage::disk('public')->delete($filePath);
                    Log::channel('slider')->debug('Local deletion ' . ($deleted ? 'successful' : 'failed'), [
                        'request_id' => $requestId,
                        'path' => $filePath
                    ]);
                    return $deleted;
                }
            }
        } catch (\Exception $e) {
            Log::channel('slider')->error('File deletion failed', [
                'request_id' => $requestId,
                'file_path' => $filePath,
                'error' => $e->getMessage()
            ]);
        }
        
        return false;
    }
    
    /**
     * Check if a URL is from our CDN
     */
    private function isCdnUrl($url)
    {
        if (!$url) {
            return false;
        }
        
        $cdnUrl = env('CDN_URL', 'https://upload.goexploriabusiness.com');
        return Str::startsWith($url, $cdnUrl);
    }
    
    /**
     * Extract the storage path from a CDN URL
     */
    private function extractPathFromCdnUrl($url)
    {
        $cdnUrl = env('CDN_URL', 'https://upload.goexploriabusiness.com');
        // L'URL est comme: https://upload.goexploriabusiness.com/storage/cdn/sliders/xxx.jpg
        // On extrait la partie après /storage/
        $path = str_replace($cdnUrl . '/storage/', '', $url);
        return $path;
    }
}