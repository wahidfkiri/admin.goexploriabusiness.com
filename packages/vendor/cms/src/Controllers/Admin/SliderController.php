<?php

namespace Vendor\Cms\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Etablissement;
use Vendor\Cms\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SliderController extends Controller
{
    protected $cdnService;
    protected $cdnEnabled;

    public function __construct()
    {
        $this->cdnEnabled = env('CDN_ENABLED', false);
        if ($this->cdnEnabled) {
            $this->cdnService = app(\App\Services\CDNService::class);
        }
    }

    /**
     * Get all slider items
     */
    public function index($etablissementId): JsonResponse
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            $sliderItems = Setting::where('etablissement_id', $etablissement->id)
                ->where('group', 'slider')
                ->orderBy('order', 'asc')
                ->get()
                ->map(function ($setting) {
                    $value = $this->parseSettingValue($setting->value);
                    return [
                        'id' => $setting->id,
                        'type' => $value['type'] ?? 'image',
                        'url' => $value['url'] ?? '',
                        'video_path' => $value['video_path'] ?? '',
                        'title' => $value['title'] ?? '',
                        'subtitle' => $value['subtitle'] ?? '',
                        'button_text' => $value['button_text'] ?? '',
                        'button_link' => $value['button_link'] ?? '',
                        'is_active' => $value['is_active'] ?? true,
                        'order' => $setting->order ?? 0,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $sliderItems
            ]);

        } catch (\Exception $e) {
            Log::error('Slider index error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du slider'
            ], 500);
        }
    }

    /**
     * Store a new slider item
     */
    public function store(Request $request, $etablissementId): JsonResponse
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            $validated = $request->validate([
                'type' => 'required|in:image,video',
                'media_id' => 'nullable|exists:cms_media,id',
                'video_file' => 'nullable|file|mimes:mp4,mov,ogg,webm|max:51200', // 50MB max
                'title' => 'nullable|string|max:255',
                'subtitle' => 'nullable|string|max:500',
                'button_text' => 'nullable|string|max:100',
                'button_link' => 'nullable|string|max:500',
            ]);

            // Get the max order
            $maxOrder = Setting::where('etablissement_id', $etablissement->id)
                ->where('group', 'slider')
                ->max('order') ?? 0;
            
            $order = $maxOrder + 1;
            
            $value = [
                'type' => $request->type,
                'title' => $request->title ?? '',
                'subtitle' => $request->subtitle ?? '',
                'button_text' => $request->button_text ?? '',
                'button_link' => $request->button_link ?? '',
                'is_active' => true,
            ];

            // Handle media based on type
            if ($request->type === 'image') {
                $mediaUrl = $this->handleImageUpload($request, $etablissement);
                $value['url'] = $mediaUrl;
            } else {
                // Video handling
                $videoUrl = $this->handleVideoUpload($request, $etablissement);
                $value['url'] = $videoUrl;
                $value['video_path'] = $request->video_path ?? '';
            }

            $setting = Setting::create([
                'etablissement_id' => $etablissement->id,
                'group' => 'slider',
                'key' => 'slider_item_' . Str::random(8),
                'value' => json_encode($value),
                'type' => 'json',
                'order' => $order,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Slide ajouté avec succès',
                'data' => [
                    'id' => $setting->id,
                    'type' => $value['type'],
                    'url' => $value['url'],
                    'title' => $value['title'],
                    'subtitle' => $value['subtitle'],
                    'button_text' => $value['button_text'],
                    'button_link' => $value['button_link'],
                    'is_active' => $value['is_active'],
                    'order' => $order,
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Slider store error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a slider item
     */
    public function update(Request $request, $etablissementId, $id): JsonResponse
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            $setting = Setting::where('etablissement_id', $etablissement->id)
                ->where('group', 'slider')
                ->where('id', $id)
                ->firstOrFail();
            
            $currentValue = $this->parseSettingValue($setting->value);
            
            $validated = $request->validate([
                'type' => 'sometimes|in:image,video',
                'media_id' => 'nullable|exists:cms_media,id',
                'video_file' => 'nullable|file|mimes:mp4,mov,ogg,webm|max:51200',
                'title' => 'nullable|string|max:255',
                'subtitle' => 'nullable|string|max:500',
                'button_text' => 'nullable|string|max:100',
                'button_link' => 'nullable|string|max:500',
                'is_active' => 'boolean',
            ]);

            $value = [
                'type' => $request->type ?? $currentValue['type'],
                'title' => $request->title ?? $currentValue['title'] ?? '',
                'subtitle' => $request->subtitle ?? $currentValue['subtitle'] ?? '',
                'button_text' => $request->button_text ?? $currentValue['button_text'] ?? '',
                'button_link' => $request->button_link ?? $currentValue['button_link'] ?? '',
                'is_active' => $request->is_active ?? $currentValue['is_active'] ?? true,
            ];

            // Handle media update if new file provided
            if ($request->hasFile('video_file') || ($request->type === 'image' && $request->media_id)) {
                // Delete old file if exists
                if (!empty($currentValue['url'])) {
                    $this->deleteMediaFile($currentValue['url'], $etablissement);
                }
                
                if ($request->type === 'image') {
                    $mediaUrl = $this->handleImageUpload($request, $etablissement);
                    $value['url'] = $mediaUrl;
                } else {
                    $videoUrl = $this->handleVideoUpload($request, $etablissement);
                    $value['url'] = $videoUrl;
                }
            } else {
                // Keep existing URL
                $value['url'] = $currentValue['url'] ?? '';
                if (isset($currentValue['video_path'])) {
                    $value['video_path'] = $currentValue['video_path'];
                }
            }

            $setting->update([
                'value' => json_encode($value),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Slide mis à jour avec succès',
                'data' => [
                    'id' => $setting->id,
                    'type' => $value['type'],
                    'url' => $value['url'],
                    'title' => $value['title'],
                    'subtitle' => $value['subtitle'],
                    'button_text' => $value['button_text'],
                    'button_link' => $value['button_link'],
                    'is_active' => $value['is_active'],
                    'order' => $setting->order,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Slider update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a slider item
     */
    public function destroy($etablissementId, $id): JsonResponse
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            $setting = Setting::where('etablissement_id', $etablissement->id)
                ->where('group', 'slider')
                ->where('id', $id)
                ->firstOrFail();
            
            $value = $this->parseSettingValue($setting->value);
            
            // Delete the physical file
            if (!empty($value['url'])) {
                $this->deleteMediaFile($value['url'], $etablissement);
            }
            
            $setting->delete();
            
            // Reorder remaining items
            $this->reorderSliders($etablissement->id);

            return response()->json([
                'success' => true,
                'message' => 'Slide supprimé avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error('Slider delete error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression'
            ], 500);
        }
    }

    /**
     * Reorder slider items
     */
    public function reorder(Request $request, $etablissementId): JsonResponse
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            $request->validate([
                'orders' => 'required|array',
                'orders.*.id' => 'required|integer',
                'orders.*.order' => 'required|integer',
            ]);

            foreach ($request->orders as $item) {
                Setting::where('etablissement_id', $etablissement->id)
                    ->where('group', 'slider')
                    ->where('id', $item['id'])
                    ->update(['order' => $item['order']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Ordre mis à jour'
            ]);

        } catch (\Exception $e) {
            Log::error('Slider reorder error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la réorganisation'
            ], 500);
        }
    }

    /**
     * Toggle slider item active status
     */
    public function toggleActive($etablissementId, $id): JsonResponse
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            $setting = Setting::where('etablissement_id', $etablissement->id)
                ->where('group', 'slider')
                ->where('id', $id)
                ->firstOrFail();
            
            $value = $this->parseSettingValue($setting->value);
            $value['is_active'] = !($value['is_active'] ?? true);
            
            $setting->update([
                'value' => json_encode($value)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Statut modifié',
                'is_active' => $value['is_active']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du changement de statut'
            ], 500);
        }
    }

    // ============================================
    // PRIVATE METHODS
    // ============================================

    /**
     * Handle image upload from media library or direct upload
     */
    private function handleImageUpload(Request $request, $etablissement): string
    {
        // If media_id is provided, get from media library
        if ($request->media_id) {
            $media = \Vendor\Cms\Models\Media::find($request->media_id);
            if ($media) {
                return $media->url;
            }
        }
        
        // If file is uploaded directly
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $path = "sliders/{$etablissement->id}/images";
            $filename = 'slide_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            
            if ($this->cdnEnabled) {
                $result = $this->cdnService->upload($file, $path, 'public');
                if ($result['success'] ?? false) {
                    return $result['url'];
                }
            }
            
            $storedPath = $file->storeAs($path, $filename, 'public');
            return Storage::disk('public')->url($storedPath);
        }
        
        return '';
    }

    /**
     * Handle video upload
     */
    private function handleVideoUpload(Request $request, $etablissement): string
    {
        // If media_id is provided, get from media library
        if ($request->media_id) {
            $media = \Vendor\Cms\Models\Media::find($request->media_id);
            if ($media && $media->isVideo()) {
                return $media->url;
            }
        }
        
        // If video file is uploaded directly
        if ($request->hasFile('video_file')) {
            $file = $request->file('video_file');
            $path = "sliders/{$etablissement->id}/videos";
            $filename = 'video_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            
            if ($this->cdnEnabled) {
                $result = $this->cdnService->upload($file, $path, 'public');
                if ($result['success'] ?? false) {
                    return $result['url'];
                }
            }
            
            $storedPath = $file->storeAs($path, $filename, 'public');
            return Storage::disk('public')->url($storedPath);
        }
        
        // If video path is provided (from existing)
        if ($request->video_path) {
            return $request->video_path;
        }
        
        return '';
    }

    /**
     * Delete media file from storage
     */
    private function deleteMediaFile($url, $etablissement): void
    {
        if (empty($url)) {
            return;
        }
        
        try {
            // Check if it's a local storage path
            if (strpos($url, '/storage/') !== false) {
                $relativePath = str_replace('/storage/', '', parse_url($url, PHP_URL_PATH));
                if (Storage::disk('public')->exists($relativePath)) {
                    Storage::disk('public')->delete($relativePath);
                }
            } 
            // CDN deletion
            else if ($this->cdnEnabled && strpos($url, env('CDN_URL', '')) !== false) {
                $path = str_replace(env('CDN_URL', '') . '/storage/', '', $url);
                $this->cdnService->delete($path);
            }
        } catch (\Exception $e) {
            Log::warning('Failed to delete media file: ' . $e->getMessage());
        }
    }

    /**
     * Reorder all sliders after deletion
     */
    private function reorderSliders($etablissementId): void
    {
        $sliders = Setting::where('etablissement_id', $etablissementId)
            ->where('group', 'slider')
            ->orderBy('order', 'asc')
            ->get();
        
        $order = 1;
        foreach ($sliders as $slider) {
            $slider->update(['order' => $order]);
            $order++;
        }
    }

    /**
     * Parse setting value (handle both JSON and string)
     */
    private function parseSettingValue($value): array
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return $decoded ?: [];
        }
        return is_array($value) ? $value : [];
    }
}