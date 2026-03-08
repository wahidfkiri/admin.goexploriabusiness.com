<?php

namespace Vendor\Destination\Controllers\Countries;

use App\Http\Controllers\Controller;
use App\Models\CountryMedia;
use App\Models\Country;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;


class CountryMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = CountryMedia::query();
            
            // Filtres
            if ($request->has('country_id')) {
                $query->where('country_id', $request->country_id);
            }
            
            if ($request->has('activity_id')) {
                $query->where('activity_id', $request->activity_id);
            }
            
            if ($request->has('type')) {
                $query->where('type', $request->type);
            }
            
            if ($request->has('is_active')) {
                $query->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN));
            }
            
            if ($request->has('is_featured')) {
                $query->where('is_featured', filter_var($request->is_featured, FILTER_VALIDATE_BOOLEAN));
            }
            
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('alt_text', 'like', "%{$search}%");
                });
            }
            
            // Tri
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);
            
            // Pagination
            $perPage = $request->get('per_page', 20);
            $medias = $query->with(['country', 'activity', 'creator'])->paginate($perPage);
            
            return response()->json([
                'success' => true,
                'data' => $medias,
                'message' => 'Médias récupérés avec succès'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des médias', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des médias'
            ], 500);
        }
    }


public function store(Request $request)
{
        Log::info('Début création de média', ['request' => $request->all()]);
    try {
        
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:image,video_local,video_youtube,video_vimeo,video_dailymotion,video_other',
            'image_file' => 'nullable|image|max:51200', // 50MB max pour les images
            'video_image' => 'nullable|image|max:51200', // 50MB max pour les images
            'video_file' => 'nullable|mimes:mp4,avi,mov,wmv,flv,mkv,webm|max:512000', // 500MB max pour les vidéos
            'video_url' => 'nullable|url',
            'alt_text' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'country_id' => 'required|exists:countries,id',
            'activity_id' => 'nullable|exists:activities,id',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Erreur de validation'
            ], 422);
        }
        
        $data = $request->only([
            'title', 'description', 'type', 'video_url', 
            'alt_text', 'is_featured', 'is_active', 
            'country_id', 'activity_id'
        ]);
        
        $data['is_featured'] = $request->boolean('is_featured', false);
        $data['is_active'] = $request->boolean('is_active', true);
        
        // Gérer les tags
        if ($request->has('tags')) {
            $tags = array_map('trim', explode(',', $request->tags));
            $data['tags'] = json_encode($tags);
        }
        
        // Gérer les images
        if ($request->hasFile('image_file') && $request->type === 'image') {
            $imageFile = $request->file('image_file');
            $imagePath = $imageFile->store('country_medias/images', 'public');
            $data['image_path'] = $imagePath;
            
            // Récupérer les infos de l'image
            $data['mime_type'] = $imageFile->getMimeType();
            $data['file_size'] = $imageFile->getSize();
            
            // Obtenir les dimensions de l'image avec Intervention Image
            try {
                $manager = ImageManager::gd(); // ou ImageManager::imagick()
                $image = $manager->read($imageFile);
                $data['width'] = $image->width();
                $data['height'] = $image->height();
                
                // Optionnel: Créer une version réduite (thumbnail)
                $thumbnailPath = 'country_medias/thumbnails/' . pathinfo($imagePath, PATHINFO_FILENAME) . '_thumb.jpg';
                $image->resize(300, 200, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save(storage_path('app/public/' . $thumbnailPath));
                
                $data['thumbnail_path'] = $thumbnailPath;
                
            } catch (\Exception $imageError) {
                Log::warning('Erreur lors du traitement de l\'image', [
                    'error' => $imageError->getMessage()
                ]);
                // Continuer sans les dimensions
            }
        }
        
        // Gérer les vidéos locales
        if ($request->hasFile('video_file') && $request->type === 'video_local') {
            $videoFile = $request->file('video_file');
            $videoPath = $videoFile->store('country_medias/videos', 'public');
            $data['video_path'] = $videoPath;
            
            $data['mime_type'] = $videoFile->getMimeType();
            $data['file_size'] = $videoFile->getSize();
            
            // Générer une thumbnail pour la vidéo
            $thumbnailPath = $this->generateVideoThumbnail($videoFile, $videoPath);
            
            if ($thumbnailPath) {
                $data['image_path'] = $thumbnailPath;
                
                // Obtenir les dimensions de la thumbnail
                try {
                    $manager = ImageManager::gd();
                    $image = $manager->read(storage_path('app/public/' . $thumbnailPath));
                    $data['width'] = $image->width();
                    $data['height'] = $image->height();
                } catch (\Exception $thumbError) {
                    Log::warning('Erreur lors de la lecture des dimensions de la thumbnail', [
                        'error' => $thumbError->getMessage()
                    ]);
                }
            } else {
                 if ($request->hasFile('video_image')) {
            $imageVideo = $request->file('video_image');
            $imagePath = $imageVideo->store('country_medias/images', 'public');
            $data['image_path'] = $imagePath;
            
            // Récupérer les infos de l'image
            $data['mime_type'] = $imageVideo->getMimeType();
            $data['file_size'] = $imageVideo->getSize();
            
            // Obtenir les dimensions de l'image avec Intervention Image
            try {
                $manager = ImageManager::gd(); // ou ImageManager::imagick()
                $image = $manager->read($imageVideo);
                $data['width'] = $image->width();
                $data['height'] = $image->height();
                
                // Optionnel: Créer une version réduite (thumbnail)
                $thumbnailPath = 'country_medias/thumbnails/' . pathinfo($imagePath, PATHINFO_FILENAME) . '_thumb.jpg';
                $image->resize(300, 200, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save(storage_path('app/public/' . $thumbnailPath));
                
                $data['thumbnail_path'] = $thumbnailPath;
                
            } catch (\Exception $imageError) {
                Log::warning('Erreur lors du traitement de l\'image', [
                    'error' => $imageError->getMessage()
                ]);
                // Continuer sans les dimensions
            }
        }else{
                // Utiliser une image par défaut si la génération échoue
                $data['image_path'] = 'defaults/video-thumbnail.jpg';
        }
            }
            
            // Obtenir la durée de la vidéo (si FFmpeg est disponible)
            $duration = $this->getVideoDuration($videoFile);
            if ($duration) {
                $data['duration'] = $duration;
            }
        }
        
        // Gérer les vidéos YouTube/Vimeo
        if (in_array($request->type, ['video_youtube', 'video_vimeo', 'video_dailymotion', 'video_other']) && $request->video_url) {
            $data['video_url'] = $request->video_url;
            
            // Extraire l'ID de la vidéo
            $videoId = $this->extractVideoId($request->video_url, $request->type);
            if ($videoId) {
                $data['video_id'] = $videoId;
                $data['video_provider'] = str_replace('video_', '', $request->type);
            }
            
            // Télécharger la thumbnail depuis YouTube/Vimeo
            if ($request->type === 'video_youtube' && $videoId) {
                $thumbnailUrl = "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg";
                $thumbnailPath = $this->downloadThumbnailFromUrl($thumbnailUrl, 'youtube_' . $videoId);
                if ($thumbnailPath) {
                    $data['image_path'] = $thumbnailPath;
                }
            } elseif ($request->type === 'video_vimeo' && $videoId) {
                // Pour Vimeo, on peut utiliser l'API ou une URL par défaut
                $thumbnailPath = $this->downloadVimeoThumbnail($videoId);
                if ($thumbnailPath) {
                    $data['image_path'] = $thumbnailPath;
                }
            }
            
            if (!isset($data['image_path'])) {
                $data['image_path'] = 'defaults/video-thumbnail.jpg';
            }
        }
        
        // Créer le média
        $media = CountryMedia::create($data);
        
        Log::info('Média créé avec succès', ['media_id' => $media->id]);
        
        return response()->json([
            'success' => true,
            'message' => 'Média créé avec succès',
            'data' => $media->load(['country', 'activity', 'creator'])
        ], 201);
        
    } catch (\Exception $e) {
        Log::error('Erreur lors de la création du média', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'request' => $request->except(['image_file', 'video_file'])
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la création du média'
        ], 500);
    }
}

private function generateVideoThumbnail($videoFile, $videoPath)
{
    try {
        // Solution alternative sans FFmpeg
        // 1. Utiliser la première frame (nécessite FFmpeg)
        // 2. Ou créer une image par défaut avec des infos
        
        $thumbnailFilename = pathinfo($videoPath, PATHINFO_FILENAME) . '_thumb.jpg';
        $thumbnailPath = 'country_medias/thumbnails/' . $thumbnailFilename;
        $fullThumbnailPath = storage_path('app/public/' . $thumbnailPath);
        
        // Créer le dossier
        $thumbnailDir = dirname($fullThumbnailPath);
        if (!is_dir($thumbnailDir)) {
            mkdir($thumbnailDir, 0755, true);
        }
        
        // Créer une image par défaut stylisée
        $manager = ImageManager::gd();
        
        // Créer un canvas
        $image = $manager->canvas(640, 360, '#1a237e');
        
        // Ajouter un dégradé
        for ($i = 0; $i < 360; $i++) {
            $color = imagecolorallocate($image->getCore(), 
                max(0, 26 - $i/20), 
                max(0, 35 - $i/15), 
                min(255, 126 + $i/5));
            imageline($image->getCore(), 0, $i, 640, $i, $color);
        }
        
        // Ajouter une icône de play
        $image->text('▶', 320, 130, function($font) {
            $font->size(80);
            $font->color('#ffffff');
            $font->align('center');
            $font->valign('middle');
        });
        
        // Ajouter le nom du fichier
        $filename = pathinfo($videoFile->getClientOriginalName(), PATHINFO_FILENAME);
        $image->text($filename, 320, 220, function($font) {
            $font->size(16);
            $font->color('#ffffff');
            $font->align('center');
            $font->valign('middle');
        });
        
        // Ajouter le type de fichier
        $fileSize = $this->formatBytes($videoFile->getSize());
        $image->text(strtoupper($videoFile->getClientOriginalExtension()) . ' • ' . $fileSize, 
                    320, 250, function($font) {
            $font->size(12);
            $font->color('#bbbbbb');
            $font->align('center');
            $font->valign('middle');
        });
        
        // Sauvegarder
        $image->save($fullThumbnailPath, 90);
        
        return $thumbnailPath;
        
    } catch (\Exception $e) {
        Log::error('Erreur lors de la génération de la thumbnail', [
            'error' => $e->getMessage()
        ]);
        
        return 'defaults/video-thumbnail.jpg';
    }
}

private function formatBytes($bytes, $precision = 2)
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    
    return round($bytes, $precision) . ' ' . $units[$pow];
}

/**
 * Créer une thumbnail par défaut pour les vidéos
 */
private function createDefaultVideoThumbnail()
{
    try {
        $defaultThumbnailPath = 'country_medias/thumbnails/default_video_thumb.jpg';
        $fullPath = storage_path('app/public/' . $defaultThumbnailPath);
        
        // Créer le dossier si nécessaire
        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        // Créer une image par défaut si elle n'existe pas
        if (!file_exists($fullPath)) {
            $manager = ImageManager::gd();
            $image = $manager->canvas(640, 360, '#2c3e50');
            
            // Ajouter une icône de play
            $image->text('▶', 320, 180, function($font) {
                $font->file(storage_path('fonts/arial.ttf')); // Assurez-vous d'avoir une police
                $font->size(100);
                $font->color('#ffffff');
                $font->align('center');
                $font->valign('middle');
            });
            
            $image->save($fullPath);
        }
        
        return $defaultThumbnailPath;
        
    } catch (\Exception $e) {
        Log::error('Erreur lors de la création de la thumbnail par défaut', [
            'error' => $e->getMessage()
        ]);
        
        return 'defaults/video-thumbnail.jpg';
    }
}

/**
 * Obtenir la durée d'une vidéo
 */
private function getVideoDuration($videoFile)
{
    try {
        $ffmpegPath = config('app.ffmpeg_path', 'ffmpeg');
        
        if (!shell_exec("which {$ffmpegPath}")) {
            return null;
        }
        
        $tempPath = $videoFile->getRealPath();
        $command = "{$ffmpegPath} -i \"{$tempPath}\" 2>&1 | grep 'Duration' | cut -d ' ' -f 4 | sed s/,//";
        $duration = shell_exec($command);
        
        if ($duration) {
            list($hours, $minutes, $seconds) = explode(':', $duration);
            $totalSeconds = $hours * 3600 + $minutes * 60 + $seconds;
            return (int) $totalSeconds;
        }
        
        return null;
        
    } catch (\Exception $e) {
        Log::warning('Erreur lors de la récupération de la durée de la vidéo', [
            'error' => $e->getMessage()
        ]);
        
        return null;
    }
}

/**
 * Télécharger une thumbnail depuis une URL
 */
private function downloadThumbnailFromUrl($url, $filename)
{
    try {
        $thumbnailPath = 'country_medias/thumbnails/external_' . Str::slug($filename) . '.jpg';
        $fullPath = storage_path('app/public/' . $thumbnailPath);
        
        // Créer le dossier si nécessaire
        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        // Télécharger l'image
        $client = new \GuzzleHttp\Client();
        $response = $client->get($url, ['verify' => false]);
        
        if ($response->getStatusCode() === 200) {
            file_put_contents($fullPath, $response->getBody());
            
            // Vérifier que c'est une image valide
            if (getimagesize($fullPath)) {
                // Optimiser l'image
                $manager = ImageManager::gd();
                $image = $manager->read($fullPath);
                
                // Redimensionner si trop grand
                if ($image->width() > 640) {
                    $image->resize(640, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }
                
                $image->save($fullPath, 80);
                
                return $thumbnailPath;
            }
        }
        
        return null;
        
    } catch (\Exception $e) {
        Log::warning('Erreur lors du téléchargement de la thumbnail', [
            'url' => $url,
            'error' => $e->getMessage()
        ]);
        
        return null;
    }
}

/**
 * Télécharger une thumbnail Vimeo via l'API
 */
private function downloadVimeoThumbnail($videoId)
{
    try {
        // Vous avez besoin d'un token d'accès Vimeo
        $accessToken = config('services.vimeo.access_token');
        
        if (!$accessToken) {
            return null;
        }
        
        $client = new \GuzzleHttp\Client();
        $response = $client->get("https://api.vimeo.com/videos/{$videoId}", [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json',
            ]
        ]);
        
        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody(), true);
            
            if (isset($data['pictures']['sizes']) && count($data['pictures']['sizes']) > 0) {
                // Prendre la plus grande image disponible
                $largestPicture = end($data['pictures']['sizes']);
                $thumbnailUrl = $largestPicture['link'];
                
                return $this->downloadThumbnailFromUrl($thumbnailUrl, 'vimeo_' . $videoId);
            }
        }
        
        return null;
        
    } catch (\Exception $e) {
        Log::warning('Erreur lors de la récupération de la thumbnail Vimeo', [
            'video_id' => $videoId,
            'error' => $e->getMessage()
        ]);
        
        return null;
    }
}

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $media = CountryMedia::with(['country', 'activity', 'creator'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $media,
                'message' => 'Média récupéré avec succès'
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Média non trouvé'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération du média', [
                'media_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du média'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $media = CountryMedia::findOrFail($id);
            
            Log::info('Début mise à jour de média', [
                'media_id' => $id,
                'request' => $request->all()
            ]);
            
            $validator = Validator::make($request->all(), [
                'title' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'type' => 'sometimes|in:image,video_local,video_youtube,video_vimeo,video_dailymotion,video_other',
                'image_file' => 'nullable|image|max:51200',
                'video_file' => 'nullable|mimes:mp4,avi,mov,wmv,flv,mkv|max:512000',
                'video_url' => 'nullable|url',
                'alt_text' => 'nullable|string|max:255',
                'tags' => 'nullable|string',
                'is_featured' => 'boolean',
                'is_active' => 'boolean',
                'activity_id' => 'nullable|exists:activities,id',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Erreur de validation'
                ], 422);
            }
            
            $data = $request->only([
                'title', 'description', 'alt_text', 'is_featured', 
                'is_active', 'activity_id'
            ]);
            
            if ($request->has('is_featured')) {
                $data['is_featured'] = $request->boolean('is_featured');
            }
            
            if ($request->has('is_active')) {
                $data['is_active'] = $request->boolean('is_active');
            }
            
            // Gérer les tags
            if ($request->has('tags')) {
                $tags = array_map('trim', explode(',', $request->tags));
                $data['tags'] = json_encode($tags);
            }
            
            // Gérer le changement d'image
            if ($request->hasFile('image_file')) {
                // Supprimer l'ancienne image
                if ($media->image_path && !Str::startsWith($media->image_path, 'defaults/')) {
                    Storage::disk('public')->delete($media->image_path);
                }
                
                $imagePath = $request->file('image_file')->store('country_medias/images', 'public');
                $data['image_path'] = $imagePath;
                $data['mime_type'] = $request->file('image_file')->getMimeType();
                $data['file_size'] = $request->file('image_file')->getSize();
            }
            
            // Gérer le changement de vidéo locale
            if ($request->hasFile('video_file')) {
                // Supprimer l'ancienne vidéo
                if ($media->video_path) {
                    Storage::disk('public')->delete($media->video_path);
                }
                
                $videoPath = $request->file('video_file')->store('country_medias/videos', 'public');
                $data['video_path'] = $videoPath;
                $data['mime_type'] = $request->file('video_file')->getMimeType();
                $data['file_size'] = $request->file('video_file')->getSize();
                $data['type'] = 'video_local';
            }
            
            // Gérer le changement de vidéo URL
            if ($request->has('video_url') && in_array($request->type, ['video_youtube', 'video_vimeo', 'video_dailymotion'])) {
                $data['video_url'] = $request->video_url;
                
                // Extraire l'ID de la vidéo
                $videoId = $this->extractVideoId($request->video_url, $request->type);
                if ($videoId) {
                    $data['video_id'] = $videoId;
                    $data['video_provider'] = str_replace('video_', '', $request->type);
                }
                
                $data['type'] = $request->type;
            }
            
            // Mettre à jour le média
            $media->update($data);
            
            Log::info('Média mis à jour avec succès', ['media_id' => $media->id]);
            
            return response()->json([
                'success' => true,
                'message' => 'Média mis à jour avec succès',
                'data' => $media->load(['country', 'activity', 'creator'])
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Média non trouvé'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du média', [
                'media_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du média'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $media = CountryMedia::findOrFail($id);
            
            // Supprimer les fichiers physiques
            if ($media->image_path && !Str::startsWith($media->image_path, 'defaults/')) {
                Storage::disk('public')->delete($media->image_path);
            }
            
            if ($media->video_path) {
                Storage::disk('public')->delete($media->video_path);
            }
            
            $media->delete();
            
            Log::info('Média supprimé avec succès', ['media_id' => $id]);
            
            return response()->json([
                'success' => true,
                'message' => 'Média supprimé avec succès'
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Média non trouvé'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du média', [
                'media_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du média'
            ], 500);
        }
    }

    /**
     * Toggle active status
     */
    public function toggleStatus($id)
    {
        try {
            $media = CountryMedia::findOrFail($id);
            $media->is_active = !$media->is_active;
            $media->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour avec succès',
                'data' => [
                    'is_active' => $media->is_active
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors du changement de statut', [
                'media_id' => $id,
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
            $media = CountryMedia::findOrFail($id);
            $media->is_featured = !$media->is_featured;
            $media->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Statut "À la une" mis à jour avec succès',
                'data' => [
                    'is_featured' => $media->is_featured
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors du changement de statut "À la une"', [
                'media_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du changement de statut "À la une"'
            ], 500);
        }
    }

    /**
     * Extract video ID from URL
     */
    private function extractVideoId($url, $type)
    {
        switch ($type) {
            case 'video_youtube':
                // Patterns pour YouTube
                $patterns = [
                    '/youtube\.com\/watch\?v=([^&]+)/',
                    '/youtu\.be\/([^?]+)/',
                    '/youtube\.com\/embed\/([^?]+)/'
                ];
                break;
                
            case 'video_vimeo':
                // Patterns pour Vimeo
                $patterns = [
                    '/vimeo\.com\/(\d+)/',
                    '/vimeo\.com\/video\/(\d+)/'
                ];
                break;
                
            case 'video_dailymotion':
                // Patterns pour Dailymotion
                $patterns = [
                    '/dailymotion\.com\/video\/([^_]+)/',
                    '/dai\.ly\/([^?]+)/'
                ];
                break;
                
            default:
                return null;
        }
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }
        
        return null;
    }

    /**
     * Get statistics for media
     */
    public function statistics(Request $request)
    {
        try {
            $countryId = $request->get('country_id');
            
            $query = CountryMedia::query();
            
            if ($countryId) {
                $query->where('country_id', $countryId);
            }
            
            $stats = [
                'total' => $query->count(),
                'images' => $query->clone()->where('type', 'image')->count(),
                'videos' => $query->clone()->where('type', 'like', 'video_%')->count(),
                'video_local' => $query->clone()->where('type', 'video_local')->count(),
                'video_youtube' => $query->clone()->where('type', 'video_youtube')->count(),
                'video_vimeo' => $query->clone()->where('type', 'video_vimeo')->count(),
                'active' => $query->clone()->where('is_active', true)->count(),
                'featured' => $query->clone()->where('is_featured', true)->count(),
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
}