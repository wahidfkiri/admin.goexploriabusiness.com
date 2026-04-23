<?php

namespace Vendor\Administration\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\PlanMedia;
use App\Models\PlanDestination;
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
            // Basic fields
            'name'          => 'required|string|max:255|unique:plans',
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
            
            // Presentation fields
            'vision_text'           => 'nullable|string',
            'vision_quote'          => 'nullable|string',
            'vision_quote_author'   => 'nullable|string',
            'marketing_budget'      => 'nullable|numeric',
            'marketing_features'    => 'nullable|string',
            'markets'               => 'nullable|string',
            'market_languages'      => 'nullable|string',
            'marketing_tools'       => 'nullable|string',
            'space_type'            => 'nullable|string|in:entreprise,destination,partenaire,perso',
            'space_features'        => 'nullable|string',
            'concrete_results'      => 'nullable|string',

            // Media validation
            'media'                    => 'nullable|array',
            'media.*.type'             => 'required_with:media|in:image,video',
            'media.*.file'             => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,avi,mov,wmv|max:102400',
            'media.*.video_url'        => 'nullable|url',
            'media.*.video_platform'   => 'nullable|in:youtube,vimeo,upload,other',
            'media.*.thumbnail'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'media.*.is_primary'       => 'nullable|boolean',
            'media.*.sort_order'       => 'nullable|integer',
            
            // Destinations
            'destinations'              => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // Process JSON fields
            $markets = $request->markets ? json_decode($request->markets, true) : null;
            $marketLanguages = $request->market_languages ? json_decode($request->market_languages, true) : null;
            $marketingTools = $request->marketing_tools ? json_decode($request->marketing_tools, true) : null;
            $concreteResults = $request->concrete_results ? json_decode($request->concrete_results, true) : null;
            $spaceFeatures = $request->space_features ? array_map('trim', explode(',', $request->space_features)) : null;
            $marketingFeatures = $request->marketing_features ? array_map('trim', explode(',', $request->marketing_features)) : null;

            // Create the plan
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
                
                // Presentation fields
                'vision_text'           => $request->vision_text,
                'vision_quote'          => $request->vision_quote,
                'vision_quote_author'   => $request->vision_quote_author,
                'marketing_budget'      => $request->marketing_budget,
                'marketing_features'    => $marketingFeatures,
                'markets'               => $markets,
                'market_languages'      => $marketLanguages,
                'marketing_tools'       => $marketingTools,
                'space_type'            => $request->space_type,
                'space_features'        => $spaceFeatures,
                'concrete_results'      => $concreteResults,
            ]);

            // Attach plugins
            if ($request->filled('plugin_ids')) {
                $plan->plugins()->sync($request->plugin_ids);
            }

            // Handle media uploads
            if ($request->has('media')) {
                $this->handleMediaUploads($plan, $request->media, $request->allFiles());
            }

            // Handle destinations
            if ($request->filled('destinations')) {
                $destinations = json_decode($request->destinations, true);
                $this->handleDestinations($plan, $destinations, $request->allFiles());
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message'  => 'Plan créé avec succès',
                'plan'     => $plan->load(['plugins', 'media', 'destinations']),
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
            ->with(['plugins', 'media', 'destinations'])
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
            
            // Presentation fields
            'vision_text'           => 'nullable|string',
            'vision_quote'          => 'nullable|string',
            'vision_quote_author'   => 'nullable|string',
            'marketing_budget'      => 'nullable|numeric',
            'marketing_features'    => 'nullable|string',
            'markets'               => 'nullable|string',
            'market_languages'      => 'nullable|string',
            'marketing_tools'       => 'nullable|string',
            'space_type'            => 'nullable|string|in:entreprise,destination,partenaire,perso',
            'space_features'        => 'nullable|string',
            'concrete_results'      => 'nullable|string',

            // Media
            'media'                    => 'nullable|array',
            'media.*.type'             => 'required_with:media|in:image,video',
            'media.*.file'             => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,avi,mov,wmv|max:102400',
            'media.*.video_url'        => 'nullable|url',
            'media.*.video_platform'   => 'nullable|in:youtube,vimeo,upload,other',
            'media.*.thumbnail'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'media.*.is_primary'       => 'nullable|boolean',
            'media.*.sort_order'       => 'nullable|integer',

            'delete_media_ids'   => 'nullable|array',
            'delete_media_ids.*' => 'exists:plan_media,id',
            'primary_media_id'   => 'nullable|exists:plan_media,id',
            
            // Destinations
            'destinations'       => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // Process JSON fields
            $markets = $request->markets ? json_decode($request->markets, true) : null;
            $marketLanguages = $request->market_languages ? json_decode($request->market_languages, true) : null;
            $marketingTools = $request->marketing_tools ? json_decode($request->marketing_tools, true) : null;
            $concreteResults = $request->concrete_results ? json_decode($request->concrete_results, true) : null;
            $spaceFeatures = $request->space_features ? array_map('trim', explode(',', $request->space_features)) : null;
            $marketingFeatures = $request->marketing_features ? array_map('trim', explode(',', $request->marketing_features)) : null;

            // Update plan fields
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
                
                'vision_text'           => $request->vision_text,
                'vision_quote'          => $request->vision_quote,
                'vision_quote_author'   => $request->vision_quote_author,
                'marketing_budget'      => $request->marketing_budget,
                'marketing_features'    => $marketingFeatures,
                'markets'               => $markets,
                'market_languages'      => $marketLanguages,
                'marketing_tools'       => $marketingTools,
                'space_type'            => $request->space_type,
                'space_features'        => $spaceFeatures,
                'concrete_results'      => $concreteResults,
            ]);

            // Sync plugins
            $plan->plugins()->sync($request->plugin_ids ?? []);

            // Delete selected media
            if ($request->filled('delete_media_ids')) {
                $toDelete = PlanMedia::whereIn('id', $request->delete_media_ids)
                                     ->where('plan_id', $plan->id)
                                     ->get();
                foreach ($toDelete as $media) {
                    $this->deleteMediaFiles($media);
                    $media->delete();
                }
            }

            // Set primary media
            if ($request->filled('primary_media_id')) {
                PlanMedia::where('plan_id', $plan->id)->update(['is_primary' => false]);
                PlanMedia::where('id', $request->primary_media_id)
                         ->where('plan_id', $plan->id)
                         ->update(['is_primary' => true]);
            }

            // Add new media
            if ($request->has('media')) {
                $this->handleMediaUploads($plan, $request->media, $request->allFiles());
            }

            // Handle destinations
            if ($request->filled('destinations')) {
                $destinations = json_decode($request->destinations, true);
                $this->handleDestinations($plan, $destinations, $request->allFiles());
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

            // Delete all media files
            foreach ($plan->media as $media) {
                $this->deleteMediaFiles($media);
            }
            
            // Delete destinations images
            foreach ($plan->destinations as $destination) {
                if ($destination->destination_image && file_exists(public_path($destination->destination_image))) {
                    unlink(public_path($destination->destination_image));
                }
            }

            $plan->delete();

            return response()->json(['success' => true, 'message' => 'Plan supprimé avec succès']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()], 500);
        }
    }

    // ========================================================
    // DESTINATIONS CRUD (AJAX endpoints)
    // ========================================================

    public function getDestinations($planId)
    {
        $plan = Plan::findOrFail($planId);
        return response()->json([
            'success' => true,
            'destinations' => $plan->destinations
        ]);
    }

    public function storeDestination(Request $request, $planId)
    {
        $validator = Validator::make($request->all(), [
            'destination_name' => 'required|string|max:255',
            'destination_slug' => 'nullable|string|max:255',
            'destination_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'destination_description' => 'nullable|string',
            'destination_country' => 'nullable|string|max:100',
            'destination_city' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $plan = Plan::findOrFail($planId);
            
            $data = $request->except('destination_image');
            $data['plan_id'] = $planId;
            $data['sort_order'] = $plan->destinations()->count();
            $data['is_active'] = $request->boolean('is_active');
            
            if ($request->hasFile('destination_image')) {
                $path = $request->file('destination_image')->store("plans/{$planId}/destinations", 'public');
                $data['destination_image'] = '/storage/' . $path;
            }
            
            if (empty($data['destination_slug'])) {
                $data['destination_slug'] = Str::slug($data['destination_name']);
            }
            
            $destination = $plan->destinations()->create($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Destination ajoutée avec succès',
                'destination' => $destination
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateDestination(Request $request, $planId, $destinationId)
    {
        $destination = PlanDestination::where('plan_id', $planId)->findOrFail($destinationId);
        
        $validator = Validator::make($request->all(), [
            'destination_name' => 'required|string|max:255',
            'destination_description' => 'nullable|string',
            'destination_country' => 'nullable|string|max:100',
            'destination_city' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $data = $request->except('destination_image');
            $data['is_active'] = $request->boolean('is_active');
            
            if ($request->hasFile('destination_image')) {
                // Delete old image
                if ($destination->destination_image && file_exists(public_path($destination->destination_image))) {
                    unlink(public_path($destination->destination_image));
                }
                $path = $request->file('destination_image')->store("plans/{$planId}/destinations", 'public');
                $data['destination_image'] = '/storage/' . $path;
            }
            
            if (empty($data['destination_slug']) && !empty($data['destination_name'])) {
                $data['destination_slug'] = Str::slug($data['destination_name']);
            }
            
            $destination->update($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Destination mise à jour avec succès',
                'destination' => $destination
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteDestination($planId, $destinationId)
    {
        try {
            $destination = PlanDestination::where('plan_id', $planId)->findOrFail($destinationId);
            
            // Delete image
            if ($destination->destination_image && file_exists(public_path($destination->destination_image))) {
                unlink(public_path($destination->destination_image));
            }
            
            $destination->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Destination supprimée avec succès'
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function reorderDestinations(Request $request, $planId)
    {
        $validator = Validator::make($request->all(), [
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:plan_destination,id',
            'orders.*.order' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            foreach ($request->orders as $item) {
                PlanDestination::where('plan_id', $planId)
                    ->where('id', $item['id'])
                    ->update(['sort_order' => $item['order']]);
            }
            
            return response()->json(['success' => true, 'message' => 'Ordre mis à jour avec succès']);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ========================================================
    // MEDIA MANAGEMENT
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
     * Handle destinations from the form submission
     */
    private function handleDestinations(Plan $plan, ?array $destinations, array $allFiles = []): void
    {
        if (!$destinations) return;
        
        // Get existing destination IDs to keep
        $keepIds = [];
        
        foreach ($destinations as $index => $destData) {
            if (isset($destData['id']) && !empty($destData['id'])) {
                // Update existing
                $destination = PlanDestination::where('plan_id', $plan->id)
                    ->where('id', $destData['id'])
                    ->first();
                if ($destination) {
                    $destination->update([
                        'destination_name' => $destData['destination_name'],
                        'destination_slug' => $destData['destination_slug'] ?? Str::slug($destData['destination_name']),
                        'destination_description' => $destData['destination_description'] ?? null,
                        'destination_country' => $destData['destination_country'] ?? null,
                        'destination_city' => $destData['destination_city'] ?? null,
                        'sort_order' => $destData['sort_order'] ?? $index,
                        'is_active' => $destData['is_active'] ?? true,
                    ]);
                    $keepIds[] = $destination->id;
                }
            } else {
                // Create new
                $data = [
                    'destination_name' => $destData['destination_name'],
                    'destination_slug' => $destData['destination_slug'] ?? Str::slug($destData['destination_name']),
                    'destination_description' => $destData['destination_description'] ?? null,
                    'destination_country' => $destData['destination_country'] ?? null,
                    'destination_city' => $destData['destination_city'] ?? null,
                    'sort_order' => $destData['sort_order'] ?? $index,
                    'is_active' => $destData['is_active'] ?? true,
                ];
                
                // Handle image file if present
                if (isset($destData['_image_file']) && $destData['_image_file']) {
                    // This would need to be handled differently since files come from request
                    // For now, skip file upload in this helper
                }
                
                $destination = $plan->destinations()->create($data);
                $keepIds[] = $destination->id;
            }
        }
        
        // Delete destinations not in keep list
        PlanDestination::where('plan_id', $plan->id)
            ->whereNotIn('id', $keepIds)
            ->delete();
    }

    /**
     * Handle media[] array from multipart form.
     */
    private function handleMediaUploads(Plan $plan, array $mediaItems, array $allFiles): void
    {
        $hasPrimary = collect($mediaItems)->contains(fn($m) => !empty($m['is_primary']));
        if ($hasPrimary) {
            PlanMedia::where('plan_id', $plan->id)->update(['is_primary' => false]);
        }

        foreach ($mediaItems as $index => $item) {
            $type      = $item['type'] ?? 'image';
            $isPrimary = !empty($item['is_primary']);
            $sortOrder = (int) ($item['sort_order'] ?? $index);

            $uploadedFile    = $allFiles['media'][$index]['file']      ?? null;
            $thumbnailFile   = $allFiles['media'][$index]['thumbnail'] ?? null;

            if ($type === 'image') {
                if (!$uploadedFile) continue;

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
                    $filePath    = $this->uploadFile($uploadedFile, "plans/{$plan->id}/videos");
                    $fileUrl     = $this->storageUrl . $filePath;
                    $originalName = $uploadedFile->getClientOriginalName();
                    $mimeType    = $uploadedFile->getMimeType();
                    $fileSize    = $uploadedFile->getSize();
                } else {
                    $fileUrl  = $item['video_url'] ?? null;
                    if (!$fileUrl) continue;
                }

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
     */
    private function uploadFile($file, string $directory): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename  = Str::random(40) . '.' . $extension;
        $stored    = $file->storeAs($directory, $filename, 'public');

        if (!$stored) {
            throw new \Exception('Failed to store file: ' . $directory . '/' . $filename);
        }

        return $stored;
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

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }

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