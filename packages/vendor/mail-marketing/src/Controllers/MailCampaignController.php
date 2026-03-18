<?php

namespace Vendor\MailMarketing\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MailCampaign;
use App\Models\MailSubscriber;
use Vendor\MailMarketing\Services\MailMarketingService;
use Illuminate\Http\Request;

class MailCampaignController extends Controller
{
    protected $mailService;

    public function __construct(MailMarketingService $mailService)
    {
        $this->mailService = $mailService;
       // $this->authorizeResource(MailCampaign::class, 'campaign');
    }

    public function index()
    {
        $campaigns = MailCampaign::with('createdBy')
            ->latest()
            ->paginate(10);
            
        return view('mail-marketing::campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        return view('mail-marketing::campaigns.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'sujet' => 'required|string|max:255',
            'contenu' => 'required|string',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        $campaign = MailCampaign::create([
            ...$validated,
            'created_by' => auth()->id(),
            'status' => $request->has('schedule') ? 'scheduled' : 'draft',
        ]);

        if ($request->has('schedule') && $request->scheduled_at) {
            $this->mailService->scheduleCampaign($campaign, $request->scheduled_at);
        }

        return redirect()
            ->route('mail-campaigns.show', $campaign)
            ->with('success', 'Campagne créée avec succès.');
    }

    public function show(MailCampaign $campaign)
    {
        $campaign->load(['subscribers', 'createdBy']);
        
        return view('mail-marketing.campaigns.show', [
            'campaign' => $campaign,
            'stats' => $campaign->stats,
        ]);
    }

    public function edit(MailCampaign $campaign)
    {
        if ($campaign->status !== 'draft') {
            return redirect()
                ->route('mail-campaigns.show', $campaign)
                ->with('error', 'Seules les campagnes en brouillon peuvent être modifiées.');
        }

        return view('mail-marketing.campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, MailCampaign $campaign)
    {
        if ($campaign->status !== 'draft') {
            return back()->with('error', 'Impossible de modifier une campagne non brouillon.');
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'sujet' => 'required|string|max:255',
            'contenu' => 'required|string',
        ]);

        $campaign->update($validated);

        return redirect()
            ->route('mail-campaigns.show', $campaign)
            ->with('success', 'Campagne mise à jour.');
    }

    public function destroy(MailCampaign $campaign)
    {
        if ($campaign->status === 'sent') {
            return back()->with('error', 'Impossible de supprimer une campagne envoyée.');
        }

        $campaign->delete();

        return redirect()
            ->route('mail-campaigns.index')
            ->with('success', 'Campagne supprimée.');
    }

    public function send(MailCampaign $campaign)
    {
        $this->authorize('send', $campaign);

        if ($campaign->status === 'sent') {
            return back()->with('error', 'Cette campagne a déjà été envoyée.');
        }

        dispatch(function () use ($campaign) {
            $this->mailService->sendCampaign($campaign);
        })->onQueue('emails');

        return back()->with('success', 'Envoi de la campagne lancé en arrière-plan.');
    }

    public function duplicate(MailCampaign $campaign)
    {
        $newCampaign = $this->mailService->duplicateCampaign($campaign);

        return redirect()
            ->route('mail-campaigns.edit', $newCampaign)
            ->with('success', 'Campagne dupliquée avec succès.');
    }

    public function stats(MailCampaign $campaign)
    {
        $stats = [
            'overview' => $campaign->stats,
            'timeline' => $campaign->trackingEvents()
                ->selectRaw('DATE(created_at) as date, event_type, COUNT(*) as count')
                ->groupBy('date', 'event_type')
                ->get(),
        ];

        return response()->json($stats);
    }
}