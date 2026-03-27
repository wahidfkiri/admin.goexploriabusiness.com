<?php

namespace Vendor\Cms\Controllers\Admin;

use App\Http\Controllers\Controller;
use Vendor\Cms\Models\Media;
use App\Models\Etablissement;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    /**
     * Affiche la médiathèque
     */
    public function index(Request $request, $etablissementId)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        
        if (!$this->userHasAccess($request->user(), $etablissement)) {
            abort(403, 'Accès non autorisé');
        }
        
        $query = Media::where('etablissement_id', $etablissement->id);
        
        // Filtre par type
        if ($request->has('type') && $request->type != 'all') {
            $query->where('type', $request->type);
        }
        
        // Filtre par dossier
        if ($request->has('folder')) {
            $query->folder($request->folder);
        }
        
        // Recherche
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('original_name', 'like', "%{$search}%");
            });
        }
        
        $media = $query->orderBy('created_at', 'desc')->paginate(24);
        
        $stats = [
            'total' => Media::where('etablissement_id', $etablissement->id)->count(),
            'images' => Media::where('etablissement_id', $etablissement->id)->images()->count(),
            'documents' => Media::where('etablissement_id', $etablissement->id)->documents()->count(),
            'videos' => Media::where('etablissement_id', $etablissement->id)->videos()->count(),
            'size' => $this->getTotalSize($etablissement->id),
            'folders' => $this->getFolders($etablissement->id),
        ];
        
        return view('cms::admin.media.index', compact('media', 'stats', 'etablissement'));
    }

    /**
     * Upload un fichier
     */
    public function upload(Request $request, $etablissementId): JsonResponse
    {
        try {
            $request->validate([
                'file' => 'required|file|max:10240', // max 10MB
                'name' => 'nullable|string|max:255',
                'folder' => 'nullable|string',
            ]);
            
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            // if (!$this->userHasAccess($request->user(), $etablissement)) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Accès non autorisé'
            //     ], 403);
            // }
            
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $mimeType = $file->getMimeType();
            $size = $file->getSize();
            
            // Déterminer le type
            $type = $this->getFileType($mimeType);
            
            // Générer un nom unique
            $filename = Str::uuid() . '.' . $extension;
            $folder = $request->input('folder', '/');
            $folder = trim($folder, '/');
            $path = "cms/media/{$etablissement->id}/{$folder}/{$filename}";
            
            // Upload du fichier
            $storedPath = $file->storeAs("cms/media/{$etablissement->id}/{$folder}", $filename, 'public');
            
            // Obtenir les dimensions pour les images
            $width = null;
            $height = null;
            if (str_starts_with($mimeType, 'image/')) {
                $imageInfo = getimagesize($file->getPathname());
                if ($imageInfo) {
                    $width = $imageInfo[0];
                    $height = $imageInfo[1];
                }
            }
            
            // Créer l'enregistrement en base
            $media = Media::create([
                'etablissement_id' => $etablissement->id,
                'user_id' => $request->user()->id,
                'name' => $request->input('name', pathinfo($originalName, PATHINFO_FILENAME)),
                'original_name' => $originalName,
                'filename' => $filename,
                'path' => $storedPath,
                'size' => $size,
                'mime_type' => $mimeType,
                'extension' => $extension,
                'type' => $type,
                'folder' => $folder ?: '/',
                'width' => $width,
                'height' => $height,
                'is_public' => true,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Fichier uploadé avec succès',
                'media' => $media,
                'url' => $media->url
            ]);
            
        } catch (\Exception $e) {
            Log::error('Media upload error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'upload: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer un média
     */
    public function destroy(Request $request, $etablissementId, $id): JsonResponse
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            if (!$this->userHasAccess($request->user(), $etablissement)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }
            
            $media = Media::where('id', $id)
                ->where('etablissement_id', $etablissement->id)
                ->firstOrFail();
            
            // Supprimer le fichier physique
            if (Storage::disk('public')->exists($media->path)) {
                Storage::disk('public')->delete($media->path);
            }
            
            $media->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Fichier supprimé avec succès'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Media delete error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Suppression multiple
     */
    public function bulkDelete(Request $request, $etablissementId): JsonResponse
    {
        try {
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            if (!$this->userHasAccess($request->user(), $etablissement)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }
            
            $ids = $request->input('ids', []);
            
            if (empty($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun fichier sélectionné'
                ], 400);
            }
            
            $media = Media::where('etablissement_id', $etablissement->id)
                ->whereIn('id', $ids)
                ->get();
            
            foreach ($media as $item) {
                if (Storage::disk('public')->exists($item->path)) {
                    Storage::disk('public')->delete($item->path);
                }
                $item->delete();
            }
            
            return response()->json([
                'success' => true,
                'message' => count($ids) . ' fichier(s) supprimé(s) avec succès'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Bulk delete error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mettre à jour les informations d'un média
     */
    public function update(Request $request, $etablissementId, $id): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'nullable|string|max:255',
                'alt' => 'nullable|string|max:255',
                'title' => 'nullable|string|max:255',
                'description' => 'nullable|string',
            ]);
            
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            if (!$this->userHasAccess($request->user(), $etablissement)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }
            
            $media = Media::where('id', $id)
                ->where('etablissement_id', $etablissement->id)
                ->firstOrFail();
            
            $media->update($request->only(['name', 'alt', 'title', 'description']));
            
            return response()->json([
                'success' => true,
                'message' => 'Média mis à jour avec succès',
                'media' => $media
            ]);
            
        } catch (\Exception $e) {
            Log::error('Media update error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer les médias par dossier
     */
    public function folder(Request $request, $etablissementId, $folder)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        
        if (!$this->userHasAccess($request->user(), $etablissement)) {
            abort(403, 'Accès non autorisé');
        }
        
        $media = Media::where('etablissement_id', $etablissement->id)
            ->folder($folder)
            ->orderBy('created_at', 'desc')
            ->paginate(24);
        
        return view('cms::admin.media.index', compact('media', 'folder', 'etablissement'));
    }

    /**
     * Créer un dossier
     */
    public function createFolder(Request $request, $etablissementId): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|regex:/^[a-zA-Z0-9-_]+$/',
                'parent' => 'nullable|string',
            ]);
            
            $etablissement = Etablissement::findOrFail($etablissementId);
            
            if (!$this->userHasAccess($request->user(), $etablissement)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }
            
            $parent = $request->input('parent', '');
            $folderPath = $parent ? trim($parent, '/') . '/' . $request->name : $request->name;
            
            // Créer le dossier dans storage
            $storagePath = "cms/media/{$etablissement->id}/{$folderPath}";
            Storage::disk('public')->makeDirectory($storagePath);
            
            return response()->json([
                'success' => true,
                'message' => 'Dossier créé avec succès',
                'folder' => $folderPath
            ]);
            
        } catch (\Exception $e) {
            Log::error('Create folder error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du dossier: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exporter les médias
     */
    public function export(Request $request, $etablissementId)
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        
        if (!$this->userHasAccess($request->user(), $etablissement)) {
            abort(403, 'Accès non autorisé');
        }
        
        $media = Media::where('etablissement_id', $etablissement->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $filename = 'media_' . $etablissement->slug . '_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];
        
        $callback = function() use ($media) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, ['ID', 'Nom', 'Nom original', 'Type', 'Taille', 'Extension', 'Créé le']);
            
            // Data
            foreach ($media as $item) {
                fputcsv($file, [
                    $item->id,
                    $item->name,
                    $item->original_name,
                    $item->type,
                    $item->formatted_size,
                    $item->extension,
                    $item->created_at->format('d/m/Y H:i')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Récupérer un média spécifique (API)
     */
    public function getMedia(Request $request, $etablissementId, $id): JsonResponse
    {
        $etablissement = Etablissement::findOrFail($etablissementId);
        
        if (!$this->userHasAccess($request->user(), $etablissement)) {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé'
            ], 403);
        }
        
        $media = Media::where('id', $id)
            ->where('etablissement_id', $etablissement->id)
            ->firstOrFail();
        
        return response()->json([
            'success' => true,
            'media' => $media,
            'url' => $media->url
        ]);
    }

    // ============================================
    // MÉTHODES PRIVÉES
    // ============================================

    /**
     * Déterminer le type de fichier
     */
    protected function getFileType($mimeType): string
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        }
        
        if (str_starts_with($mimeType, 'video/')) {
            return 'video';
        }
        
        $documentTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/plain',
        ];
        
        if (in_array($mimeType, $documentTypes)) {
            return 'document';
        }
        
        return 'other';
    }

    /**
     * Calculer la taille totale des médias
     */
    protected function getTotalSize($etablissementId): string
    {
        $total = Media::where('etablissement_id', $etablissementId)->sum('size');
        
        $bytes = $total;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * Récupérer la liste des dossiers
     */
    protected function getFolders($etablissementId): array
    {
        $folders = Media::where('etablissement_id', $etablissementId)
            ->select('folder')
            ->distinct()
            ->pluck('folder')
            ->toArray();
        
        $result = [];
        foreach ($folders as $folder) {
            $parts = explode('/', trim($folder, '/'));
            $current = '';
            foreach ($parts as $part) {
                $current = $current ? $current . '/' . $part : $part;
                $result[] = $current;
            }
        }
        
        return array_unique($result);
    }

    /**
     * Vérifier si l'utilisateur a accès à l'établissement
     */
    protected function userHasAccess($user, $etablissement): bool
    {
         // Vérifier si l'utilisateur est admin (utilise la méthode isAdmin)
    if ($user && method_exists($user, 'isAdmin') && $user->isAdmin()) {
        return true;
    }
        
        if (method_exists($user, 'etablissement') && $user->etablissement && $user->etablissement->id === $etablissement->id) {
            return true;
        }
        
        if (method_exists($user, 'etablissements') && $user->etablissements->contains($etablissement)) {
            return true;
        }
        
        return false;
    }
}