<?php

namespace Vendor\AdsManager\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Vendor\AdsManager\Services\AdReportService;
use Throwable;

class AdController extends Controller
{
    public function __construct(protected AdReportService $reportService) {}

    /* ------------------------------------------------------------------ */
    /*  INDEX                                                               */
    /* ------------------------------------------------------------------ */
    public function index(Request $request)
    {
        try {
            $query = DB::table('ads')
                ->leftJoin('users as u', 'ads.created_by', '=', 'u.id')
                ->select('ads.*', 'u.name as creator_name');

            if ($request->filled('search')) {
                $s = $request->search;
                $query->where(function ($q) use ($s) {
                    $q->where('ads.titre', 'like', "%$s%")
                      ->orWhere('ads.advertiser_name', 'like', "%$s%");
                });
            }
            if ($request->filled('status'))  $query->where('ads.status', $request->status);
            if ($request->filled('format'))  $query->where('ads.format', $request->format);
            if ($request->filled('pricing')) $query->where('ads.pricing_model', $request->pricing);

            $ads   = $query->latest('ads.created_at')->paginate(15);
            $stats = $this->reportService->globalStats();

            return view('ads-manager::ads.index', compact('ads', 'stats'));

        } catch (Throwable $e) {
            Log::error('AdController::index', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur de chargement : ' . $e->getMessage());
        }
    }

    /* ------------------------------------------------------------------ */
    /*  CREATE                                                              */
    /* ------------------------------------------------------------------ */
    public function create()
    {
        $etablissements = \App\Models\Etablissement::all();
        $placements     = DB::table('ad_placements')->where('is_active', true)->get();
        $formats        = config('ads-manager.ad_formats');
        $pricingModels  = config('ads-manager.pricing_models');

        return view('ads-manager::ads.create', compact('etablissements', 'placements', 'formats', 'pricingModels'));
    }

    /* ------------------------------------------------------------------ */
    /*  STORE                                                               */
    /* ------------------------------------------------------------------ */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'titre'                => 'required|string|max:255',
                'description'          => 'nullable|string',
                'type'                 => 'required|in:image,html,video,text',
                'image'                => 'nullable|image|max:2048',
                'video_url'            => 'nullable|url',
                'html_content'         => 'nullable|string',
                'text_content'         => 'nullable|string',
                'destination_url'      => 'nullable|url',
                'open_new_tab'         => 'nullable|boolean',
                'format'               => 'required|string',
                'start_date'           => 'nullable|date',
                'end_date'             => 'nullable|date|after_or_equal:start_date',
                'target_etablissements'=> 'nullable|array',
                'target_categories'    => 'nullable|array',
                'target_pages'         => 'nullable|array',
                'target_audience'      => 'nullable|string',
                'pricing_model'        => 'required|in:cpm,cpc,cpa,flat',
                'rate'                 => 'required|numeric|min:0',
                'budget_total'         => 'nullable|numeric|min:0',
                'budget_daily'         => 'nullable|numeric|min:0',
                'impression_limit'     => 'nullable|integer|min:0',
                'click_limit'          => 'nullable|integer|min:0',
                'frequency_cap'        => 'nullable|integer|min:0',
                'priority'             => 'nullable|integer|min:1|max:10',
                'advertiser_name'      => 'nullable|string|max:255',
                'advertiser_email'     => 'nullable|email',
                'placements'           => 'nullable|array',
            ]);

            // Upload image
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store(
                    config('ads-manager.image_path', 'ads/images'), 'public'
                );
            }

            $dims = config('ads-manager.ad_formats.' . $validated['format'], []);

            $adId = DB::table('ads')->insertGetId([
                'titre'                 => $validated['titre'],
                'description'           => $validated['description'] ?? null,
                'advertiser_name'       => $validated['advertiser_name'] ?? null,
                'advertiser_email'      => $validated['advertiser_email'] ?? null,
                'advertiser_id'         => auth()->id(),
                'type'                  => $validated['type'],
                'image_path'            => $imagePath,
                'video_url'             => $validated['video_url'] ?? null,
                'html_content'          => $validated['html_content'] ?? null,
                'text_content'          => $validated['text_content'] ?? null,
                'destination_url'       => $validated['destination_url'] ?? null,
                'open_new_tab'          => $request->boolean('open_new_tab', true),
                'format'                => $validated['format'],
                'width'                 => $dims['width'] ?? null,
                'height'                => $dims['height'] ?? null,
                'start_date'            => $validated['start_date'] ?? null,
                'end_date'              => $validated['end_date'] ?? null,
                'target_etablissements' => json_encode($validated['target_etablissements'] ?? []),
                'target_categories'     => json_encode($validated['target_categories'] ?? []),
                'target_pages'          => json_encode($validated['target_pages'] ?? []),
                'target_audience'       => $validated['target_audience'] ?? null,
                'pricing_model'         => $validated['pricing_model'],
                'rate'                  => $validated['rate'],
                'budget_total'          => $validated['budget_total'] ?? null,
                'budget_daily'          => $validated['budget_daily'] ?? null,
                'budget_spent'          => 0,
                'impression_limit'      => $validated['impression_limit'] ?? null,
                'click_limit'           => $validated['click_limit'] ?? null,
                'frequency_cap'         => $validated['frequency_cap'] ?? null,
                'priority'              => $validated['priority'] ?? 5,
                'status'                => config('ads-manager.auto_approve') ? 'active' : 'pending',
                'created_by'            => auth()->id(),
                'created_at'            => now(),
                'updated_at'            => now(),
            ]);

            // Attacher aux emplacements
            if (!empty($validated['placements'])) {
                foreach ($validated['placements'] as $placementId) {
                    DB::table('ad_placement')->insert([
                        'ad_id'        => $adId,
                        'placement_id' => $placementId,
                        'is_active'    => true,
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ]);
                }
            }

            Log::info('Annonce créée', ['ad_id' => $adId, 'user_id' => auth()->id()]);

            return redirect()->route('ads-manager.ads.show', $adId)
                ->with('success', 'Annonce créée. En attente de validation.');

        } catch (Throwable $e) {
            Log::error('AdController::store', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur : ' . $e->getMessage())->withInput();
        }
    }

    /* ------------------------------------------------------------------ */
    /*  SHOW                                                                */
    /* ------------------------------------------------------------------ */
    public function show(int $id)
    {
        try {
            $ad = DB::table('ads')->find($id);
            if (!$ad) abort(404);

            $stats      = $this->reportService->adStats($id);
            $placements = DB::table('ad_placements')
                ->join('ad_placement', 'ad_placements.id', '=', 'ad_placement.placement_id')
                ->where('ad_placement.ad_id', $id)
                ->select('ad_placements.*', 'ad_placement.is_active as linked_active')
                ->get();

            return view('ads-manager::ads.show', compact('ad', 'stats', 'placements'));

        } catch (Throwable $e) {
            Log::error('AdController::show', ['id' => $id, 'error' => $e->getMessage()]);
            return redirect()->route('ads-manager.ads.index')->with('error', 'Annonce introuvable.');
        }
    }

    /* ------------------------------------------------------------------ */
    /*  EDIT                                                                */
    /* ------------------------------------------------------------------ */
    public function edit(int $id)
    {
        $ad             = DB::table('ads')->find($id);
        if (!$ad) abort(404);

        if (!in_array($ad->status, ['draft', 'paused', 'pending'])) {
            return redirect()->route('ads-manager.ads.show', $id)
                ->with('error', 'Seules les annonces en brouillon / pause peuvent être modifiées.');
        }

        $etablissements = \App\Models\Etablissement::all();
        $placements     = DB::table('ad_placements')->where('is_active', true)->get();
        $linkedIds      = DB::table('ad_placement')->where('ad_id', $id)->pluck('placement_id')->toArray();
        $formats        = config('ads-manager.ad_formats');
        $pricingModels  = config('ads-manager.pricing_models');

        return view('ads-manager::ads.edit', compact('ad', 'etablissements', 'placements', 'linkedIds', 'formats', 'pricingModels'));
    }

    /* ------------------------------------------------------------------ */
    /*  UPDATE                                                              */
    /* ------------------------------------------------------------------ */
    public function update(Request $request, int $id)
    {
        try {
            $ad = DB::table('ads')->find($id);
            if (!$ad) abort(404);

            $validated = $request->validate([
                'titre'           => 'required|string|max:255',
                'description'     => 'nullable|string',
                'destination_url' => 'nullable|url',
                'open_new_tab'    => 'nullable|boolean',
                'start_date'      => 'nullable|date',
                'end_date'        => 'nullable|date|after_or_equal:start_date',
                'pricing_model'   => 'required|in:cpm,cpc,cpa,flat',
                'rate'            => 'required|numeric|min:0',
                'budget_total'    => 'nullable|numeric|min:0',
                'budget_daily'    => 'nullable|numeric|min:0',
                'priority'        => 'nullable|integer|min:1|max:10',
                'placements'      => 'nullable|array',
                'image'           => 'nullable|image|max:2048',
            ]);

            $data = [
                'titre'           => $validated['titre'],
                'description'     => $validated['description'] ?? $ad->description,
                'destination_url' => $validated['destination_url'] ?? $ad->destination_url,
                'open_new_tab'    => $request->boolean('open_new_tab', true),
                'start_date'      => $validated['start_date'] ?? $ad->start_date,
                'end_date'        => $validated['end_date'] ?? $ad->end_date,
                'pricing_model'   => $validated['pricing_model'],
                'rate'            => $validated['rate'],
                'budget_total'    => $validated['budget_total'] ?? $ad->budget_total,
                'budget_daily'    => $validated['budget_daily'] ?? $ad->budget_daily,
                'priority'        => $validated['priority'] ?? $ad->priority,
                'status'          => 'pending', // re-validation requise
                'updated_at'      => now(),
            ];

            if ($request->hasFile('image')) {
                if ($ad->image_path) Storage::disk('public')->delete($ad->image_path);
                $data['image_path'] = $request->file('image')->store(config('ads-manager.image_path'), 'public');
            }

            DB::table('ads')->where('id', $id)->update($data);

            // Sync emplacements
            DB::table('ad_placement')->where('ad_id', $id)->delete();
            foreach (($validated['placements'] ?? []) as $placementId) {
                DB::table('ad_placement')->insert([
                    'ad_id' => $id, 'placement_id' => $placementId,
                    'is_active' => true, 'created_at' => now(), 'updated_at' => now(),
                ]);
            }

            Log::info('Annonce mise à jour', ['ad_id' => $id]);
            return redirect()->route('ads-manager.ads.show', $id)->with('success', 'Annonce mise à jour.');

        } catch (Throwable $e) {
            Log::error('AdController::update', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Erreur : ' . $e->getMessage())->withInput();
        }
    }

    /* ------------------------------------------------------------------ */
    /*  DESTROY                                                             */
    /* ------------------------------------------------------------------ */
    public function destroy(int $id)
    {
        try {
            $ad = DB::table('ads')->find($id);
            if (!$ad) abort(404);

            if ($ad->status === 'active') {
                return back()->with('error', 'Désactivez l\'annonce avant de la supprimer.');
            }

            if ($ad->image_path) Storage::disk('public')->delete($ad->image_path);
            DB::table('ad_placement')->where('ad_id', $id)->delete();
            DB::table('ads')->where('id', $id)->delete();

            Log::info('Annonce supprimée', ['ad_id' => $id]);
            return redirect()->route('ads-manager.ads.index')->with('success', 'Annonce supprimée.');

        } catch (Throwable $e) {
            Log::error('AdController::destroy', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    /* ------------------------------------------------------------------ */
    /*  APPROVE / REJECT / PAUSE / ACTIVATE                                 */
    /* ------------------------------------------------------------------ */
    public function approve(int $id)
    {
        DB::table('ads')->where('id', $id)->update([
            'status'      => 'active',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'updated_at'  => now(),
        ]);
        return back()->with('success', 'Annonce approuvée et activée.');
    }

    public function reject(Request $request, int $id)
    {
        $request->validate(['reason' => 'required|string|max:500']);
        DB::table('ads')->where('id', $id)->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->reason,
            'updated_at'       => now(),
        ]);
        return back()->with('success', 'Annonce rejetée.');
    }

    public function pause(int $id)
    {
        DB::table('ads')->where('id', $id)->update(['status' => 'paused', 'updated_at' => now()]);
        return back()->with('success', 'Annonce mise en pause.');
    }

    public function activate(int $id)
    {
        DB::table('ads')->where('id', $id)->update(['status' => 'active', 'updated_at' => now()]);
        return back()->with('success', 'Annonce activée.');
    }

    public function duplicate(int $id)
    {
        try {
            $ad = DB::table('ads')->find($id);
            if (!$ad) abort(404);

            $adData = (array)$ad;
            unset($adData['id'], $adData['deleted_at']);
            $adData['titre']        = $ad->titre . ' (copie)';
            $adData['status']       = 'draft';
            $adData['budget_spent'] = 0;
            $adData['approved_at']  = null;
            $adData['created_by']   = auth()->id();
            $adData['created_at']   = now();
            $adData['updated_at']   = now();

            $newId = DB::table('ads')->insertGetId($adData);

            return redirect()->route('ads-manager.ads.edit', $newId)->with('success', 'Annonce dupliquée.');

        } catch (Throwable $e) {
            Log::error('AdController::duplicate', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Erreur lors de la duplication.');
        }
    }

    /* ------------------------------------------------------------------ */
    /*  STATS JSON                                                          */
    /* ------------------------------------------------------------------ */
    public function stats(int $id)
    {
        return response()->json($this->reportService->adStats($id));
    }

    /* ------------------------------------------------------------------ */
    /*  PREVIEW                                                             */
    /* ------------------------------------------------------------------ */
    public function preview(int $id)
    {
        $ad = DB::table('ads')->find($id);
        if (!$ad) abort(404);

        return view('ads-manager::ads.preview', compact('ad'));
    }
}