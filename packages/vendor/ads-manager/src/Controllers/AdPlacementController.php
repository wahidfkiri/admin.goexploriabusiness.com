<?php

namespace Vendor\AdsManager\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class AdPlacementController extends Controller
{
    public function index()
    {
        $placements = DB::table('ad_placements')
            ->leftJoin('etablissements', 'ad_placements.etablissement_id', '=', 'etablissements.id')
            ->select('ad_placements.*', 'etablissements.name as etab_name')
            ->orderBy('ad_placements.nom')
            ->paginate(20);

        // Compte d'annonces par emplacement
        $adCounts = DB::table('ad_placement')
            ->join('ads', 'ad_placement.ad_id', '=', 'ads.id')
            ->where('ads.status', 'active')
            ->select('ad_placement.placement_id', DB::raw('COUNT(*) as cnt'))
            ->groupBy('ad_placement.placement_id')
            ->pluck('cnt', 'placement_id');

        $etablissements = \App\Models\Etablissement::all();

        return view('ads-manager::placements.index', compact('placements', 'adCounts', 'etablissements'));
    }

    public function create()
    {
        $etablissements = \App\Models\Etablissement::all();
        $positions      = config('ads-manager.placement_positions');
        $formats        = config('ads-manager.ad_formats');

        return view('ads-manager::placements.create', compact('etablissements', 'positions', 'formats'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nom'              => 'required|string|max:255',
                'code'             => 'required|string|max:100|unique:ad_placements,code|regex:/^[a-z0-9_]+$/',
                'description'      => 'nullable|string',
                'position'         => 'required|string',
                'format'           => 'required|string',
                'etablissement_id' => 'nullable|exists:etablissements,id',
                'page_context'     => 'nullable|string',
                'max_ads'          => 'nullable|integer|min:1|max:10',
                'is_active'        => 'nullable|boolean',
            ]);

            $dims = config('ads-manager.ad_formats.' . $validated['format'], []);

            DB::table('ad_placements')->insert([
                'nom'              => $validated['nom'],
                'code'             => $validated['code'],
                'description'      => $validated['description'] ?? null,
                'position'         => $validated['position'],
                'format'           => $validated['format'],
                'width'            => $dims['width'] ?? null,
                'height'           => $dims['height'] ?? null,
                'etablissement_id' => $validated['etablissement_id'] ?? null,
                'page_context'     => $validated['page_context'] ?? null,
                'max_ads'          => $validated['max_ads'] ?? 1,
                'is_active'        => $request->boolean('is_active', true),
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);

            return redirect()->route('ads-manager.placements.index')->with('success', 'Emplacement créé.');

        } catch (Throwable $e) {
            Log::error('AdPlacementController::store', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur : ' . $e->getMessage())->withInput();
        }
    }

    public function edit(int $id)
    {
        $placement      = DB::table('ad_placements')->find($id);
        if (!$placement) abort(404);

        $etablissements = \App\Models\Etablissement::all();
        $positions      = config('ads-manager.placement_positions');
        $formats        = config('ads-manager.ad_formats');

        return view('ads-manager::placements.edit', compact('placement', 'etablissements', 'positions', 'formats'));
    }

    public function update(Request $request, int $id)
    {
        try {
            $validated = $request->validate([
                'nom'              => 'required|string|max:255',
                'description'      => 'nullable|string',
                'position'         => 'required|string',
                'etablissement_id' => 'nullable|exists:etablissements,id',
                'page_context'     => 'nullable|string',
                'max_ads'          => 'nullable|integer|min:1|max:10',
                'is_active'        => 'nullable|boolean',
            ]);

            DB::table('ad_placements')->where('id', $id)->update([
                'nom'              => $validated['nom'],
                'description'      => $validated['description'] ?? null,
                'position'         => $validated['position'],
                'etablissement_id' => $validated['etablissement_id'] ?? null,
                'page_context'     => $validated['page_context'] ?? null,
                'max_ads'          => $validated['max_ads'] ?? 1,
                'is_active'        => $request->boolean('is_active', true),
                'updated_at'       => now(),
            ]);

            return redirect()->route('ads-manager.placements.index')->with('success', 'Emplacement mis à jour.');

        } catch (Throwable $e) {
            Log::error('AdPlacementController::update', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Erreur : ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(int $id)
    {
        $linked = DB::table('ad_placement')->where('placement_id', $id)->count();
        if ($linked > 0) {
            return back()->with('error', "Cet emplacement est lié à $linked annonce(s). Détachez-les d'abord.");
        }
        DB::table('ad_placements')->where('id', $id)->delete();
        return redirect()->route('ads-manager.placements.index')->with('success', 'Emplacement supprimé.');
    }

    /**
     * Génère le snippet HTML/Blade à coller dans les vues
     */
    public function getSnippet(int $id)
    {
        $placement = DB::table('ad_placements')->find($id);
        if (!$placement) return response()->json(['error' => 'Introuvable'], 404);

        return response()->json([
            'blade'  => "@adZone('{$placement->code}')",
            'html'   => '<div id="ad-zone-' . $placement->code . '"></div>',
            'script' => "AdsManager.loadZone('{$placement->code}');",
        ]);
    }

    public function toggleActive(int $id)
    {
        $placement = DB::table('ad_placements')->find($id);
        DB::table('ad_placements')->where('id', $id)->update([
            'is_active'  => !$placement->is_active,
            'updated_at' => now(),
        ]);
        return back()->with('success', 'Statut mis à jour.');
    }
}