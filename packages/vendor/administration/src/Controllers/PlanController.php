<?php

namespace Vendor\Administration\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\PlanMedia;
use App\Models\Plugin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PlanController extends Controller
{
    /**
     * Base URL for public storage (from .env APP_URL).
     * Files are stored under storage/app/public/plans/...
     * and served via /storage/plans/...
     */
    protected string $storageUrl;

    public function __construct()
    {
        $this->storageUrl = rtrim(env('APP_URL', 'http://localhost'), '/') . '/storage/';
    }

    // ========================================================
    // INDEX
    // ========================================================

    public function index(Request $request)
    {
        $query = Plan::withCount(['abonnements as subscribers_count'])
            ->withCount(['abonnements as active_abonnements_count' => function ($q) {
                $q->where('status', 'active')->where('end_date', '>=', now());
            }])
            ->with(['primaryMedia', 'plugins']);

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('billing_cycle')) {
            $query->where('billing_cycle', $request->billing_cycle);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $plans = $query->orderBy('sort_order')->orderBy('price')->get();

        $totalPlans        = Plan::count();
        $activePlans       = Plan::where('is_active', true)->count();
        $popularPlans      = Plan::where('is_popular', true)->count();
        $activeSubscribers = \App\Models\Abonnement::where('status', 'active')
                              ->where('end_date', '>=', now())->count();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'plans'   => $plans,
                'stats'   => compact('totalPlans', 'activePlans', 'popularPlans', 'activeSubscribers'),
            ]);
        }

        return view('administration::plans.index', compact(
            'plans', 'totalPlans', 'activePlans', 'popularPlans', 'activeSubscribers'
        ));
    }

    // ========================================================
    // CREATE
    // ========================================================

    public function create()
    {
        $plugins = Plugin::where('status', 'active')->orderBy('name')->get();
        return view('administration::plans.create', compact('plugins'));
    }

    // ========================================================
    // STORE
    // ========================================================

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255|unique:plans',
            'description'   => 'nullable|string',
            'services'      => 'nullable|string',   // HTML from WYSIWYG
            'price'         => 'required|numeric|min:0',
            'currency'      => 'required|string|size:3',
            'duration_days' => 'required|integer|min:1',
            'billing_cycle' => 'required|in:monthly,yearly,custom',
            'sort_order'    => 'integer',
            'is_active'     => 'boolean',
            'is_popular'    => 'boolean',
            'plugin_ids'    => 'nullable|array',
            'plugin_ids.*'  => 'exists:plugins,id',

            // Media validation
            'media'                    => 'nullable|array',
            'media.*.type'             => 'required_with:media|in:image,video',
            'media.*.file'             => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,avi,mov,wmv|max:102400',
            'media.*.video_url'        => 'nullable|url',
            'media.*.video_platform'   => 'nullable|in:youtube,vimeo,upload,other',
            'media.*.thumbnail'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'media.*.is_primary'       => 'nullable|boolean',
            'media.*.sort_order'       => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // 1. Create the plan
            $plan = Plan::create([
                'name'          => $request->name,
                'description'   => $request->description,
                'services'      => $request->services,
                'price'         => $request->price,
                'currency'      => $request->currency,
                'duration_days' => $request->duration_days,
                'billing_cycle' => $request->billing_cycle,
                'features'      => $request->features ?? [],
                'limits'        => $request->limits ?? [],
                'sort_order'    => $request->sort_order ?? 0,
                'is_active'     => $request->boolean('is_active'),
                'is_popular'    => $request->boolean('is_popular'),
            ]);

            // 2. Attach plugins (many-to-many)
            if ($request->filled('plugin_ids')) {
                $plan->plugins()->sync($request->plugin_ids);
            }

            // 3. Handle media uploads
            if ($request->has('media')) {
                $this->handleMediaUploads($plan, $request->media, $request->allFiles());
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Plan créé avec succès',
                'plan'     => $plan->load(['plugins', 'media']),
                'redirect' => route('plans.index'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Plan store error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()], 500);
        }
    }

    // ========================================================
    // EDIT
    // ========================================================

    public function edit($id)
    {
        $plan = Plan::withCount(['abonnements as abonnements_count'])
            ->withCount(['abonnements as active_abonnements_count' => function ($q) {
                $q->where('status', 'active')->where('end_date', '>=', now());
            }])
            ->withSum(['abonnements as total_revenue' => function ($q) {
                $q->where('payment_status', 'paid');
            }], 'amount_paid')
            ->with(['plugins', 'media'])
            ->findOrFail($id);

        $plugins = Plugin::where('status', 'active')->orderBy('name')->get();

        return view('administration::plans.edit', compact('plan', 'plugins'));
    }

    // ========================================================
    // UPDATE
    // ========================================================

    public function update(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255|unique:plans,name,' . $id,
            'description'   => 'nullable|string',
            'services'      => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'currency'      => 'required|string|size:3',
            'duration_days' => 'required|integer|min:1',
            'billing_cycle' => 'required|in:monthly,yearly,custom',
            'sort_order'    => 'integer',
            'is_active'     => 'boolean',
            'is_popular'    => 'boolean',
            'plugin_ids'    => 'nullable|array',
            'plugin_ids.*'  => 'exists:plugins,id',

            // New media to add
            'media'                    => 'nullable|array',
            'media.*.type'             => 'required_with:media|in:image,video',
            'media.*.file'             => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,avi,mov,wmv|max:102400',
            'media.*.video_url'        => 'nullable|url',
            'media.*.video_platform'   => 'nullable|in:youtube,vimeo,upload,other',
            'media.*.thumbnail'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'media.*.is_primary'       => 'nullable|boolean',
            'media.*.sort_order'       => 'nullable|integer',

            // Media IDs to delete
            'delete_media_ids'   => 'nullable|array',
            'delete_media_ids.*' => 'exists:plan_media,id',

            // Primary media designation
            'primary_media_id'   => 'nullable|exists:plan_media,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // 1. Update plan fields
            $plan->update([
                'name'          => $request->name,
                'description'   => $request->description,
                'services'      => $request->services,
                'price'         => $request->price,
                'currency'      => $request->currency,
                'duration_days' => $request->duration_days,
                'billing_cycle' => $request->billing_cycle,
                'features'      => $request->features ?? [],
                'limits'        => $request->limits ?? [],
                'sort_order'    => $request->sort_order ?? 0,
                'is_active'     => $request->boolean('is_active'),
                'is_popular'    => $request->boolean('is_popular'),
            ]);

            // 2. Sync plugins
            $plan->plugins()->sync($request->plugin_ids ?? []);

            // 3. Delete selected media
            if ($request->filled('delete_media_ids')) {
                $toDelete = PlanMedia::whereIn('id', $request->delete_media_ids)
                                     ->where('plan_id', $plan->id)
                                     ->get();
                foreach ($toDelete as $media) {
                    $this->deleteMediaFiles($media);
                    $media->delete();
                }
            }

            // 4. Set primary media
            if ($request->filled('primary_media_id')) {
                PlanMedia::where('plan_id', $plan->id)->update(['is_primary' => false]);
                PlanMedia::where('id', $request->primary_media_id)
                         ->where('plan_id', $plan->id)
                         ->update(['is_primary' => true]);
            }

            // 5. Add new media
            if ($request->has('media')) {
                $this->handleMediaUploads($plan, $request->media, $request->allFiles());
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Plan mis à jour avec succès',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Plan update error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()], 500);
        }
    }

    // ========================================================
    // DESTROY
    // ========================================================

    public function destroy($id)
    {
        try {
            $plan = Plan::findOrFail($id);

            if ($plan->abonnements()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer ce plan car il est utilisé par des abonnements',
                ], 400);
            }

            // Delete all media files from storage
            foreach ($plan->media as $media) {
                $this->deleteMediaFiles($media);
            }

            $plan->delete();

            return response()->json(['success' => true, 'message' => 'Plan supprimé avec succès']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()], 500);
        }
    }

    // ========================================================
    // DELETE SINGLE MEDIA (AJAX endpoint)
    // ========================================================

    public function deleteMedia($planId, $mediaId)
    {
        try {
            $media = PlanMedia::where('id', $mediaId)->where('plan_id', $planId)->firstOrFail();
            $this->deleteMediaFiles($media);
            $media->delete();

            return response()->json(['success' => true, 'message' => 'Média supprimé avec succès']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()], 500);
        }
    }

    // ========================================================
    // SET PRIMARY MEDIA (AJAX endpoint)
    // ========================================================

    public function setPrimaryMedia($planId, $mediaId)
    {
        try {
            PlanMedia::where('plan_id', $planId)->update(['is_primary' => false]);
            PlanMedia::where('id', $mediaId)->where('plan_id', $planId)->update(['is_primary' => true]);
            return response()->json(['success' => true, 'message' => 'Média principal défini']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()], 500);
        }
    }

    // ========================================================
    // TOGGLE STATUS
    // ========================================================

    public function toggleStatus($id)
    {
        try {
            $plan = Plan::findOrFail($id);
            $plan->is_active = !$plan->is_active;
            $plan->save();

            return response()->json([
                'success'   => true,
                'message'   => 'Statut modifié avec succès',
                'is_active' => $plan->is_active,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la modification du statut'], 500);
        }
    }

    // ========================================================
    // REORDER
    // ========================================================

    public function reorder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'orders'         => 'required|array',
            'orders.*.id'    => 'required|exists:plans,id',
            'orders.*.order' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();
            foreach ($request->orders as $item) {
                Plan::where('id', $item['id'])->update(['sort_order' => $item['order']]);
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Ordre mis à jour avec succès']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Erreur lors de la mise à jour'], 500);
        }
    }

    // ========================================================
    // PRIVATE HELPERS
    // ========================================================

    /**
     * Handle media[] array from multipart form.
     *
     * The form sends:
     *   media[0][type]           = 'image'|'video'
     *   media[0][file]           = UploadedFile (image or local video)
     *   media[0][video_url]      = 'https://...'   (for youtube/vimeo/other)
     *   media[0][video_platform] = 'youtube'|'vimeo'|'upload'|'other'
     *   media[0][thumbnail]      = UploadedFile (video poster image)
     *   media[0][is_primary]     = '1'|'0'
     *   media[0][sort_order]     = integer
     */
    private function handleMediaUploads(Plan $plan, array $mediaItems, array $allFiles): void
    {
        // If we're adding a new primary, clear existing primaries first
        $hasPrimary = collect($mediaItems)->contains(fn($m) => !empty($m['is_primary']));
        if ($hasPrimary) {
            PlanMedia::where('plan_id', $plan->id)->update(['is_primary' => false]);
        }

        foreach ($mediaItems as $index => $item) {
            $type      = $item['type'] ?? 'image';
            $isPrimary = !empty($item['is_primary']);
            $sortOrder = (int) ($item['sort_order'] ?? $index);

            // Get uploaded files by index from allFiles structure
            $uploadedFile    = $allFiles['media'][$index]['file']      ?? null;
            $thumbnailFile   = $allFiles['media'][$index]['thumbnail'] ?? null;

            if ($type === 'image') {
                if (!$uploadedFile) continue; // No file = skip this entry

                $imagePath = $this->uploadFile($uploadedFile, "plans/{$plan->id}/images");
                $imageUrl  = $this->storageUrl . $imagePath;

                PlanMedia::create([
                    'plan_id'       => $plan->id,
                    'type'          => 'image',
                    'file_url'      => $imageUrl,
                    'file_path'     => $imagePath,
                    'original_name' => $uploadedFile->getClientOriginalName(),
                    'mime_type'     => $uploadedFile->getMimeType(),
                    'file_size'     => $uploadedFile->getSize(),
                    'is_primary'    => $isPrimary,
                    'sort_order'    => $sortOrder,
                ]);

            } elseif ($type === 'video') {
                $platform    = $item['video_platform'] ?? 'youtube';
                $fileUrl     = null;
                $filePath    = null;
                $originalName = null;
                $mimeType    = null;
                $fileSize    = null;
                $thumbnailUrl = null;
                $thumbnailPath = null;

                if ($platform === 'upload' && $uploadedFile) {
                    // Local video upload
                    $filePath    = $this->uploadFile($uploadedFile, "plans/{$plan->id}/videos");
                    $fileUrl     = $this->storageUrl . $filePath;
                    $originalName = $uploadedFile->getClientOriginalName();
                    $mimeType    = $uploadedFile->getMimeType();
                    $fileSize    = $uploadedFile->getSize();
                } else {
                    // External URL (YouTube / Vimeo / other)
                    $fileUrl  = $item['video_url'] ?? null;
                    if (!$fileUrl) continue; // No URL = skip
                }

                // Optional poster/thumbnail image
                if ($thumbnailFile) {
                    $thumbnailPath = $this->uploadFile($thumbnailFile, "plans/{$plan->id}/thumbnails");
                    $thumbnailUrl  = $this->storageUrl . $thumbnailPath;
                }

                PlanMedia::create([
                    'plan_id'        => $plan->id,
                    'type'           => 'video',
                    'file_url'       => $fileUrl,
                    'file_path'      => $filePath,
                    'original_name'  => $originalName,
                    'mime_type'      => $mimeType,
                    'file_size'      => $fileSize,
                    'video_platform' => $platform,
                    'thumbnail_url'  => $thumbnailUrl,
                    'thumbnail_path' => $thumbnailPath,
                    'is_primary'     => $isPrimary,
                    'sort_order'     => $sortOrder,
                ]);
            }
        }
    }

    /**
     * Store file to local public disk, return relative path.
     * Full URL = APP_URL/storage/{relative_path}
     */
    private function uploadFile($file, string $directory): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename  = Str::random(40) . '.' . $extension;
        $stored    = $file->storeAs($directory, $filename, 'public');

        if (!$stored) {
            throw new \Exception('Failed to store file: ' . $directory . '/' . $filename);
        }

        return $stored; // e.g. "plans/1/images/abc123.jpg"
    }

    /**
     * Remove physical files from local storage for a PlanMedia record.
     */
    private function deleteMediaFiles(PlanMedia $media): void
    {
        $paths = array_filter([$media->file_path, $media->thumbnail_path]);

        foreach ($paths as $path) {
            try {
                $relative = $this->extractRelativePath($path);
                if ($relative && Storage::disk('public')->exists($relative)) {
                    Storage::disk('public')->delete($relative);
                }
            } catch (\Exception $e) {
                Log::warning('Could not delete plan media file: ' . $path . ' — ' . $e->getMessage());
            }
        }
    }

    /**
     * Extract the relative storage path from a full URL or relative path.
     */
    private function extractRelativePath(?string $url): ?string
    {
        if (!$url) return null;

        // Already a relative path (no scheme)
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }

        // Strip /storage/ prefix
        if (preg_match('#/storage/(.+)$#', $url, $m)) {
            return $m[1];
        }

        $base = rtrim(env('APP_URL', ''), '/');
        if (str_starts_with($url, $base)) {
            $relative = substr($url, strlen($base));
            return ltrim(str_replace('/storage/', '', $relative), '/');
        }

        return null;
    }
}