<?php

namespace Vendor\Administration\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
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

        // Si c'est une requête AJAX
        if ($request->ajax()) {
            $sliders = $query->paginate($perPage);
            
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
        return view('administration::sliders.index', compact('sliders', 'search', 'status', 'type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:image,video',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'video_file' => 'nullable|mimes:mp4,avi,mov,wmv|max:10240',
            'video_type' => 'nullable',
            'video_url' => 'nullable',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'button_text' => 'nullable|string|max:50',
            'button_url' => 'nullable',
        ]);

        if ($validator->fails()) {
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
            $imagePath = $request->file('image')->store('sliders', 'public');
        }

        // Traitement de la vidéo uploadée
        if ($request->hasFile('video_file')) {
            $videoPath = $request->file('video_file')->store('sliders/videos', 'public');
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
        $slider = Slider::withTrashed()->findOrFail($id);
        
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
        $slider = Slider::withTrashed()->findOrFail($id);

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:image,video',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'video_file' => 'nullable|mimes:mp4,avi,mov,wmv|max:10240',
            'video_type' => 'in:youtube,vimeo,upload',
            'video_url' => 'nullable|url',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'button_text' => 'nullable|string|max:50',
            'button_url' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Traitement de l'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($slider->image_path && Storage::disk('public')->exists($slider->image_path)) {
                Storage::disk('public')->delete($slider->image_path);
            }
            
            $imagePath = $request->file('image')->store('sliders', 'public');
            $slider->image_path = $imagePath;
            
            // Si c'est une vidéo, mettre à jour le thumbnail
            if ($request->type === 'video' && empty($slider->thumbnail_path)) {
                $slider->thumbnail_path = $imagePath;
            }
        }

        // Traitement de la vidéo uploadée
        if ($request->hasFile('video_file')) {
            // Supprimer l'ancienne vidéo
            if ($slider->video_path && Storage::disk('public')->exists($slider->video_path)) {
                Storage::disk('public')->delete($slider->video_path);
            }
            
            $videoPath = $request->file('video_file')->store('sliders/videos', 'public');
            $slider->video_path = $videoPath;
            $slider->video_type = 'upload';
            $slider->video_url = null;
        } elseif ($request->type === 'video' && $request->video_type !== 'upload') {
            // Si c'est une vidéo externe, supprimer la vidéo uploadée
            if ($slider->video_path && Storage::disk('public')->exists($slider->video_path)) {
                Storage::disk('public')->delete($slider->video_path);
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
        $slider = Slider::findOrFail($id);
        
        // Supprimer les fichiers
        if ($slider->image_path && Storage::disk('public')->exists($slider->image_path)) {
            Storage::disk('public')->delete($slider->image_path);
        }
        
        if ($slider->video_path && Storage::disk('public')->exists($slider->video_path)) {
            Storage::disk('public')->delete($slider->video_path);
        }
        
        if ($slider->thumbnail_path && Storage::disk('public')->exists($slider->thumbnail_path)) {
            Storage::disk('public')->delete($slider->thumbnail_path);
        }
        
        $slider->delete();

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
        $slider = Slider::onlyTrashed()->findOrFail($id);
        $slider->restore();

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
        $slider = Slider::onlyTrashed()->findOrFail($id);
        
        // Supprimer les fichiers
        if ($slider->image_path && Storage::disk('public')->exists($slider->image_path)) {
            Storage::disk('public')->delete($slider->image_path);
        }
        
        if ($slider->video_path && Storage::disk('public')->exists($slider->video_path)) {
            Storage::disk('public')->delete($slider->video_path);
        }
        
        if ($slider->thumbnail_path && Storage::disk('public')->exists($slider->thumbnail_path)) {
            Storage::disk('public')->delete($slider->thumbnail_path);
        }
        
        $slider->forceDelete();

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
        $slider = Slider::findOrFail($id);
        $slider->is_active = !$slider->is_active;
        $slider->save();

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
        $validator = Validator::make($request->all(), [
            'sliders' => 'required|array',
            'sliders.*.id' => 'required|exists:sliders,id',
            'sliders.*.order' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
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

            return response()->json([
                'success' => true,
                'message' => 'Ordre des sliders mis à jour avec succès !'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
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
        $total = Slider::count();
        $active = Slider::where('is_active', true)->count();
        $inactive = Slider::where('is_active', false)->count();
        $images = Slider::where('type', 'image')->count();
        $videos = Slider::where('type', 'video')->count();
        $deleted = Slider::onlyTrashed()->count();
        
        $thisMonth = Slider::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

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
        $slider = Slider::findOrFail($id);
        
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
}