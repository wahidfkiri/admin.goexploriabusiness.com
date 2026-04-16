<?php

namespace Vendor\AdsManager\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Vendor\AdsManager\Services\AdReportService;
use Throwable;

class AdReportController extends Controller
{
    public function __construct(protected AdReportService $reportService) {}

    public function index()
    {
        $stats          = $this->reportService->globalStats();
        $topAds         = $this->reportService->topAds(10, 'impressions');
        $topByRevenue   = $this->reportService->topAds(5, 'revenue');
        $topByClicks    = $this->reportService->topAds(5, 'clicks');
        $placementStats = $this->reportService->placementStats();

        return view('ads-manager::reports.index', compact(
            'stats', 'topAds', 'topByRevenue', 'topByClicks', 'placementStats'
        ));
    }

    public function adReport(int $id)
    {
        $ad      = \Illuminate\Support\Facades\DB::table('ads')->find($id);
        if (!$ad) abort(404);

        $stats   = $this->reportService->adStats($id);
        $timeline= $this->reportService->adTimeline($id, 30);

        return view('ads-manager::reports.ad', compact('ad', 'stats', 'timeline'));
    }

    public function apiOverview()
    {
        return response()->json($this->reportService->globalStats());
    }

    public function apiAdStats(int $id)
    {
        return response()->json($this->reportService->adStats($id));
    }

    public function aggregate(Request $request)
    {
        try {
            $date = $request->input('date', now()->toDateString());
            $this->reportService->aggregateDaily($date);
            return back()->with('success', "Rapport du $date agrégé avec succès.");
        } catch (Throwable $e) {
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }
}