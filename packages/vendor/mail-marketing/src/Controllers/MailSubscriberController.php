<?php

namespace Vendor\MailMarketing\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MailSubscriber;
use App\Models\Etablissement;
use Illuminate\Http\Request;

class MailSubscriberController extends Controller
{
    public function index(Request $request)
    {
        $query = MailSubscriber::with('etablissement');

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('email', 'like', "%{$request->search}%")
                  ->orWhere('nom', 'like', "%{$request->search}%")
                  ->orWhere('prenom', 'like', "%{$request->search}%");
            });
        }

        if ($request->has('etablissement_id')) {
            $query->where('etablissement_id', $request->etablissement_id);
        }

        if ($request->has('status')) {
            $query->where('is_subscribed', $request->status === 'subscribed');
        }

        $subscribers = $query->latest()->paginate(20);
        $etablissements = Etablissement::all();

        return view('mail-marketing::subscribers.index', compact('subscribers', 'etablissements'));
    }

    public function create()
    {
        $etablissements = Etablissement::all();
        return view('mail-marketing::subscribers.create', compact('etablissements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:mail_subscribers,email',
            'nom' => 'nullable|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'etablissement_id' => 'nullable|exists:etablissements,id',
        ]);

        MailSubscriber::create($validated);

        return redirect()
            ->route('mail-subscribers.index')
            ->with('success', 'Abonné ajouté avec succès.');
    }

    public function edit(MailSubscriber $subscriber)
    {
        $etablissements = Etablissement::all();
        return view('mail-marketing::subscribers.edit', [
            'subscriber' => $subscriber,
            'etablissements' => $etablissements,
        ]);
    }

    public function update(Request $request, MailSubscriber $subscriber)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:mail_subscribers,email,' . $subscriber->id,
            'nom' => 'nullable|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'etablissement_id' => 'nullable|exists:etablissements,id',
        ]);

        $subscriber->update($validated);

        return redirect()
            ->route('mail-subscribers.index')
            ->with('success', 'Abonné mis à jour.');
    }

      /**
     * Affiche les détails d'un abonné
     */
    public function show($id)
    {
        $subscriber = MailSubscriber::with(['etablissement', 'campaigns'])->findOrFail($id);
        
        // Statistiques d'engagement
        $totalCampaigns = $subscriber->campaigns()->count();
        $openedCount = $subscriber->campaigns()->wherePivotNotNull('opened_at')->count();
        $clickedCount = $subscriber->campaigns()->wherePivotNotNull('clicked_at')->count();
        
        $stats = [
            'total_campaigns' => $totalCampaigns,
            'opened' => $openedCount,
            'clicked' => $clickedCount,
            'open_rate' => $totalCampaigns > 0 ? round(($openedCount / $totalCampaigns) * 100) : 0,
            'click_rate' => $totalCampaigns > 0 ? round(($clickedCount / $totalCampaigns) * 100) : 0,
        ];
        
        // Dernières activités
        $recentActivities = $subscriber->trackingEvents()
            ->with('campaign')
            ->latest()
            ->limit(10)
            ->get();
        
        // Campagnes reçues
        $campaigns = $subscriber->campaigns()
            ->orderByPivot('sent_at', 'desc')
            ->paginate(10);
        
        return view('mail-marketing::subscribers.show', compact('subscriber', 'stats', 'recentActivities', 'campaigns'));
    }

    public function destroy(MailSubscriber $mailSubscriber)
    {
        $mailSubscriber->delete();

        return redirect()
            ->route('mail-subscribers.index')
            ->with('success', 'Abonné supprimé.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        // Logique d'import CSV
        $path = $request->file('file')->getRealPath();
        $file = fopen($path, 'r');
        
        $imported = 0;
        $skipped = 0;

        while (($data = fgetcsv($file, 1000, ',')) !== false) {
            if (filter_var($data[0], FILTER_VALIDATE_EMAIL)) {
                MailSubscriber::firstOrCreate(
                    ['email' => $data[0]],
                    [
                        'nom' => $data[1] ?? null,
                        'prenom' => $data[2] ?? null,
                    ]
                );
                $imported++;
            } else {
                $skipped++;
            }
        }

        fclose($file);

        return back()->with('success', "Import terminé : {$imported} importés, {$skipped} ignorés.");
    }

    public function export()
    {
        $subscribers = MailSubscriber::where('is_subscribed', true)->get();
        
        $filename = "abonnes_" . now()->format('Y-m-d') . ".csv";
        $handle = fopen('php://output', 'w');
        
        fputcsv($handle, ['Email', 'Nom', 'Prénom', 'Établissement', 'Date inscription']);
        
        foreach ($subscribers as $subscriber) {
            fputcsv($handle, [
                $subscriber->email,
                $subscriber->nom,
                $subscriber->prenom,
                $subscriber->etablissement->name ?? '',
                $subscriber->created_at->format('d/m/Y'),
            ]);
        }
        
        fclose($handle);
        
        return response()->stream(
            function () use ($handle) {
                fclose($handle);
            },
            200,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]
        );
    }
}