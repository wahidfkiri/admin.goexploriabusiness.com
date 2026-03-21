<?php

namespace Vendor\MailMarketing\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MailCampaign;
use App\Models\MailSubscriber;
use App\Models\MailTrackingEvent;
use Vendor\MailMarketing\Services\MailThemeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Throwable;

class MailCampaignController extends Controller
{
    protected $mailThemeService;

    public function __construct(MailThemeService $mailThemeService)
    {
        $this->mailThemeService = $mailThemeService;
    }

    /**
     * Affiche la liste des campagnes
     */
    public function index()
    {
        try {
            $campaigns = MailCampaign::with('createdBy')->latest()->paginate(10);
            
            $stats = [
                'total' => MailCampaign::count(),
                'sent' => MailCampaign::where('status', 'sent')->count(),
                'scheduled' => MailCampaign::where('status', 'scheduled')->count(),
                'draft' => MailCampaign::where('status', 'draft')->count(),
                'total_subscribers' => MailSubscriber::count(),
            ];
            
            return view('mail-marketing::campaigns.index', compact('campaigns', 'stats'));
            
        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage des campagnes', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Erreur lors du chargement des campagnes: ' . $e->getMessage());
        }
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        try {
            $etablissements = \App\Models\Etablissement::all();
            $totalSubscribers = MailSubscriber::count();
            $activeSubscribers = MailSubscriber::where('is_subscribed', true)->count();
            
            return view('mail-marketing::campaigns.create', compact(
                'etablissements', 
                'totalSubscribers', 
                'activeSubscribers'
            ));
            
        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage du formulaire de création', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Erreur lors du chargement du formulaire: ' . $e->getMessage());
        }
    }

   /**
 * Enregistre une nouvelle campagne
 */
public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'sujet' => 'required|string|max:255',
            'contenu' => 'required|string',
            'theme' => 'nullable|string|in:modern,elegant,dynamic',
            'segment' => 'nullable|string',
            'etablissement_id' => 'nullable|exists:etablissements,id',
            'schedule' => 'nullable|boolean',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        // Correction : convertir la valeur de schedule en booléen
        $schedule = $request->has('schedule') && filter_var($request->input('schedule'), FILTER_VALIDATE_BOOLEAN);
        
        // Si schedule est true, vérifier que scheduled_at est présent
        if ($schedule && empty($validated['scheduled_at'])) {
            throw new \Exception('La date de planification est requise.');
        }

        $campaign = MailCampaign::create([
            'nom' => $validated['nom'],
            'sujet' => $validated['sujet'],
            'contenu' => $validated['contenu'],
            'options' => [
                'theme' => $validated['theme'] ?? 'modern',
                'segment' => $validated['segment'] ?? 'all',
                'etablissement_id' => $validated['etablissement_id'] ?? null,
            ],
            'status' => $schedule ? 'scheduled' : 'draft',
            'scheduled_at' => $schedule ? $validated['scheduled_at'] : null,
            'created_by' => auth()->id(),
        ]);

        Log::info('Campagne créée avec succès', [
            'campaign_id' => $campaign->id,
            'campaign_name' => $campaign->nom,
            'status' => $campaign->status,
            'user_id' => auth()->id()
        ]);

        $message = $schedule 
            ? 'Campagne planifiée avec succès.' 
            : 'Campagne enregistrée comme brouillon.';

        return redirect()->route('mail-campaigns.show', $campaign)
            ->with('success', $message);
            
    } catch (Throwable $e) {
        Log::error('Erreur lors de la création de la campagne', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'user_id' => auth()->id(),
            'request_data' => $request->except('_token', 'contenu')
        ]);
        
        return back()->with('error', 'Erreur lors de la création: ' . $e->getMessage())
            ->withInput();
    }
}

    /**
     * Affiche une campagne
     */
    public function show(MailCampaign $campaign)
    {
        try {
            $campaign->load(['subscribers', 'createdBy']);
            
            $stats = [
                'total' => $campaign->subscribers()->count(),
                'sent' => $campaign->subscribers()->wherePivotNotNull('sent_at')->count(),
                'opened' => $campaign->subscribers()->wherePivotNotNull('opened_at')->count(),
                'clicked' => $campaign->subscribers()->wherePivotNotNull('clicked_at')->count(),
                'failed' => $campaign->subscribers()->wherePivotNotNull('failed_at')->count(),
            ];
            
            return view('mail-marketing::campaigns.show', compact('campaign', 'stats'));
            
        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage de la campagne', [
                'campaign_id' => $campaign->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('mail-campaigns.index')
                ->with('error', 'Erreur lors du chargement de la campagne: ' . $e->getMessage());
        }
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit(MailCampaign $campaign)
    {
        try {
            if ($campaign->status !== 'draft') {
                return redirect()->route('mail-campaigns.show', $campaign)
                    ->with('error', 'Seules les campagnes en brouillon peuvent être modifiées.');
            }
            
            $etablissements = \App\Models\Etablissement::all();
            
            return view('mail-marketing::campaigns.edit', compact('campaign', 'etablissements'));
            
        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'affichage du formulaire d\'édition', [
                'campaign_id' => $campaign->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('mail-campaigns.index')
                ->with('error', 'Erreur lors du chargement du formulaire: ' . $e->getMessage());
        }
    }

    /**
     * Met à jour une campagne
     */
    public function update(Request $request, MailCampaign $campaign)
    {
        try {
            if ($campaign->status !== 'draft') {
                return back()->with('error', 'Impossible de modifier une campagne non brouillon.');
            }

            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'sujet' => 'required|string|max:255',
                'contenu' => 'required|string',
                'theme' => 'nullable|string|in:modern,elegant,dynamic',
            ]);

            $campaign->update([
                'nom' => $validated['nom'],
                'sujet' => $validated['sujet'],
                'contenu' => $validated['contenu'],
                'options' => array_merge($campaign->options ?? [], [
                    'theme' => $validated['theme'] ?? 'modern',
                ]),
            ]);

            Log::info('Campagne mise à jour', [
                'campaign_id' => $campaign->id,
                'campaign_name' => $campaign->nom,
                'user_id' => auth()->id()
            ]);

            return redirect()->route('mail-campaigns.show', $campaign)
                ->with('success', 'Campagne mise à jour.');
                
        } catch (Throwable $e) {
            Log::error('Erreur lors de la mise à jour de la campagne', [
                'campaign_id' => $campaign->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Supprime une campagne
     */
    public function destroy(MailCampaign $campaign)
    {
        try {
            if ($campaign->status === 'sent') {
                return back()->with('error', 'Impossible de supprimer une campagne envoyée.');
            }

            $campaignName = $campaign->nom;
            $campaign->delete();

            Log::info('Campagne supprimée', [
                'campaign_id' => $campaign->id,
                'campaign_name' => $campaignName,
                'user_id' => auth()->id()
            ]);

            return redirect()->route('mail-campaigns.index')
                ->with('success', 'Campagne supprimée.');
                
        } catch (Throwable $e) {
            Log::error('Erreur lors de la suppression de la campagne', [
                'campaign_id' => $campaign->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Envoie une campagne
     */
    public function send(MailCampaign $campaign)
    {
        try {
            if ($campaign->status === 'sent') {
                return back()->with('error', 'Cette campagne a déjà été envoyée.');
            }

            if ($campaign->status === 'sending') {
                return back()->with('error', 'Cette campagne est déjà en cours d\'envoi.');
            }

            // Vérifier qu'il y a des abonnés
            $subscriberCount = MailSubscriber::where('is_subscribed', true)->count();
            if ($subscriberCount === 0) {
                return back()->with('error', 'Aucun abonné actif pour recevoir cette campagne.');
            }

            Log::info('Démarrage de l\'envoi de la campagne', [
                'campaign_id' => $campaign->id,
                'campaign_name' => $campaign->nom,
                'subscriber_count' => $subscriberCount,
                'user_id' => auth()->id()
            ]);

            // Dispatch le job d'envoi
            dispatch(function () use ($campaign) {
                $this->processSendCampaign($campaign);
            })->onQueue('emails');

            $campaign->update(['status' => 'sending']);

            return back()->with('success', 'Envoi de la campagne lancé en arrière-plan.');

        } catch (Throwable $e) {
            Log::error('Erreur lors du lancement de l\'envoi de la campagne', [
                'campaign_id' => $campaign->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Erreur lors du lancement de l\'envoi: ' . $e->getMessage());
        }
    }

    /**
     * Traite l'envoi d'une campagne
     */
    protected function processSendCampaign(MailCampaign $campaign)
    {
        $startTime = microtime(true);
        $successCount = 0;
        $failCount = 0;
        $errors = [];

        Log::info('Début du traitement de la campagne', [
            'campaign_id' => $campaign->id,
            'campaign_name' => $campaign->nom
        ]);

        try {
            // Récupérer les abonnés actifs
            $subscribers = MailSubscriber::where('is_subscribed', true)->get();
            $total = $subscribers->count();

            Log::info('Nombre d\'abonnés à traiter', [
                'campaign_id' => $campaign->id,
                'count' => $total
            ]);

            foreach ($subscribers as $subscriber) {
                try {
                    $this->sendToSubscriber($campaign, $subscriber);
                    $successCount++;

                    // Log de progression toutes les 100 emails
                    if ($successCount % 100 === 0) {
                        Log::info('Progression de l\'envoi', [
                            'campaign_id' => $campaign->id,
                            'sent' => $successCount,
                            'total' => $total,
                            'progress' => round(($successCount / $total) * 100, 2) . '%'
                        ]);
                    }

                } catch (Throwable $e) {
                    $failCount++;
                    $errorMsg = "Erreur pour {$subscriber->email}: " . $e->getMessage();
                    $errors[] = $errorMsg;

                    Log::error('Erreur lors de l\'envoi à un abonné', [
                        'campaign_id' => $campaign->id,
                        'subscriber_id' => $subscriber->id,
                        'subscriber_email' => $subscriber->email,
                        'error' => $e->getMessage()
                    ]);

                    // Enregistrer l'échec dans la table pivot
                    try {
                        $campaign->subscribers()->attach($subscriber->id, [
                            'failed_at' => now()
                        ]);
                    } catch (Throwable $pivotError) {
                        Log::warning('Erreur lors de l\'enregistrement de l\'échec', [
                            'campaign_id' => $campaign->id,
                            'subscriber_id' => $subscriber->id,
                            'error' => $pivotError->getMessage()
                        ]);
                    }
                }
            }

            $endTime = microtime(true);
            $duration = round($endTime - $startTime, 2);

            // Mettre à jour le statut de la campagne
            $campaign->update([
                'status' => 'sent',
                'sent_at' => now()
            ]);

            Log::info('Campagne envoyée avec succès', [
                'campaign_id' => $campaign->id,
                'campaign_name' => $campaign->nom,
                'success_count' => $successCount,
                'fail_count' => $failCount,
                'total' => $total,
                'duration_seconds' => $duration,
                'errors' => count($errors) > 10 ? array_slice($errors, 0, 10) : $errors
            ]);

        } catch (Throwable $e) {
            Log::critical('Erreur critique lors du traitement de la campagne', [
                'campaign_id' => $campaign->id,
                'campaign_name' => $campaign->nom,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'success_count' => $successCount,
                'fail_count' => $failCount
            ]);

            // Mettre à jour le statut en cas d'erreur critique
            try {
                $campaign->update([
                    'status' => 'failed'
                ]);
            } catch (Throwable $updateError) {
                Log::error('Erreur lors de la mise à jour du statut', [
                    'campaign_id' => $campaign->id,
                    'error' => $updateError->getMessage()
                ]);
            }
        }
    }

    /**
     * Envoie un email à un seul abonné
     */
    protected function sendToSubscriber(MailCampaign $campaign, MailSubscriber $subscriber)
    {
        // Vérifier que l'email est valide
        if (!filter_var($subscriber->email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Email invalide: {$subscriber->email}");
        }

        $trackingData = [
            'campaign_id' => $campaign->id,
            'subscriber_id' => $subscriber->id,
            'unsubscribe_url' => route('mail-marketing.unsubscribe', ['email' => $subscriber->email]),
            'open_tracker' => route('mail-marketing.track.open', [$campaign->id, $subscriber->id]),
            'click_tracker' => route('mail-marketing.track.click', [$campaign->id, $subscriber->id]) . '?url=',
        ];

        $mailData = [
            'subject' => $campaign->sujet,
            'content' => $this->parseContent($campaign->contenu, $subscriber),
            'tracking' => $trackingData,
            'prenom' => $subscriber->prenom,
            'nom' => $subscriber->nom,
        ];

        $theme = $campaign->options['theme'] ?? 'modern';
        
        $html = $this->mailThemeService->render($theme, $mailData);
        
        // Envoi de l'email
        Mail::send([], [], function ($message) use ($subscriber, $campaign, $html) {
            $message->to($subscriber->email)
                ->subject($campaign->sujet)
                ->html($html);
        });

        // Enregistrer dans la table pivot
        $campaign->subscribers()->attach($subscriber->id, ['sent_at' => now()]);

        // Enregistrer l'événement de tracking
        try {
            if (class_exists(\App\Models\MailTrackingEvent::class)) {
                \App\Models\MailTrackingEvent::create([
                    'campaign_id' => $campaign->id,
                    'subscriber_id' => $subscriber->id,
                    'event_type' => 'sent',
                    'payload' => ['status' => 'success']
                ]);
            }
        } catch (Throwable $e) {
            Log::warning('Erreur lors de l\'enregistrement de l\'événement', [
                'campaign_id' => $campaign->id,
                'subscriber_id' => $subscriber->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Parse le contenu avec les variables personnalisées
     */
    protected function parseContent(string $content, MailSubscriber $subscriber): string
    {
        $replacements = [
            '{{nom}}' => $subscriber->nom ?? '',
            '{{prenom}}' => $subscriber->prenom ?? '',
            '{{email}}' => $subscriber->email,
            '{{etablissement}}' => $subscriber->etablissement->name ?? '',
            '{{date}}' => now()->format('d/m/Y'),
            '{{year}}' => now()->format('Y'),
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }

    /**
     * Duplique une campagne
     */
    public function duplicate(MailCampaign $campaign)
    {
        try {
            $newCampaign = $campaign->replicate();
            $newCampaign->nom = $campaign->nom . ' (copie)';
            $newCampaign->status = 'draft';
            $newCampaign->scheduled_at = null;
            $newCampaign->sent_at = null;
            $newCampaign->save();

            Log::info('Campagne dupliquée', [
                'original_campaign_id' => $campaign->id,
                'new_campaign_id' => $newCampaign->id,
                'user_id' => auth()->id()
            ]);

            return redirect()->route('mail-campaigns.edit', $newCampaign)
                ->with('success', 'Campagne dupliquée avec succès.');

        } catch (Throwable $e) {
            Log::error('Erreur lors de la duplication de la campagne', [
                'campaign_id' => $campaign->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Erreur lors de la duplication: ' . $e->getMessage());
        }
    }

    /**
     * Affiche les statistiques d'une campagne
     */
    public function stats(MailCampaign $campaign)
    {
        try {
            $stats = [
                'overview' => [
                    'total' => $campaign->subscribers()->count(),
                    'sent' => $campaign->subscribers()->wherePivotNotNull('sent_at')->count(),
                    'opened' => $campaign->subscribers()->wherePivotNotNull('opened_at')->count(),
                    'clicked' => $campaign->subscribers()->wherePivotNotNull('clicked_at')->count(),
                ],
                'open_rate' => $campaign->subscribers()->wherePivotNotNull('sent_at')->count() > 0 
                    ? round(($campaign->subscribers()->wherePivotNotNull('opened_at')->count() / $campaign->subscribers()->wherePivotNotNull('sent_at')->count()) * 100) 
                    : 0,
                'click_rate' => $campaign->subscribers()->wherePivotNotNull('sent_at')->count() > 0 
                    ? round(($campaign->subscribers()->wherePivotNotNull('clicked_at')->count() / $campaign->subscribers()->wherePivotNotNull('sent_at')->count()) * 100) 
                    : 0,
            ];

            return response()->json($stats);

        } catch (Throwable $e) {
            Log::error('Erreur lors de la récupération des statistiques', [
                'campaign_id' => $campaign->id,
                'error' => $e->getMessage()
            ]);

            return response()->json(['error' => 'Erreur lors du chargement des statistiques'], 500);
        }
    }

    /**
     * Envoie un email de test - VERSION CORRIGÉE (sans Mail::failures())
     */
    public function sendTest(Request $request, MailCampaign $campaign)
    {
        try {
            $request->validate([
                'test_email' => 'required|email',
            ]);

            $trackingData = [
                'campaign_id' => $campaign->id,
                'subscriber_id' => 0,
                'unsubscribe_url' => '#',
                'open_tracker' => '#',
                'click_tracker' => '#',
            ];

            $mailData = [
                'subject' => '[TEST] ' . $campaign->sujet,
                'content' => $campaign->contenu,
                'tracking' => $trackingData,
                'prenom' => 'Test',
                'nom' => 'Utilisateur',
            ];

            $theme = $campaign->options['theme'] ?? 'modern';
            $html = $this->mailThemeService->render($theme, $mailData);

            // Envoi de l'email - l'exception sera capturée automatiquement en cas d'erreur
            Mail::send([], [], function ($message) use ($request, $campaign, $html) {
                $message->to($request->test_email)
                    ->subject('[TEST] ' . $campaign->sujet)
                    ->html($html);
            });

            Log::info('Email de test envoyé', [
                'campaign_id' => $campaign->id,
                'test_email' => $request->test_email,
                'user_id' => auth()->id()
            ]);

            return back()->with('success', 'Email de test envoyé à ' . $request->test_email);

        } catch (Throwable $e) {
            Log::error('Erreur lors de l\'envoi de l\'email de test', [
                'campaign_id' => $campaign->id,
                'test_email' => $request->test_email ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Erreur lors de l\'envoi du test: ' . $e->getMessage());
        }
    }

    /**
     * Prévisualisation d'une campagne existante
     */
    public function preview(MailCampaign $campaign)
    {
        try {
            $trackingData = [
                'campaign_id' => $campaign->id,
                'subscriber_id' => 0,
                'unsubscribe_url' => '#',
                'open_tracker' => '#',
                'click_tracker' => '#',
            ];

            $mailData = [
                'subject' => $campaign->sujet,
                'content' => $campaign->contenu,
                'tracking' => $trackingData,
                'prenom' => 'Prénom',
                'nom' => 'Nom',
            ];

            $theme = $campaign->options['theme'] ?? 'modern';
            
            return $this->mailThemeService->render($theme, $mailData);

        } catch (Throwable $e) {
            Log::error('Erreur lors de la prévisualisation de la campagne', [
                'campaign_id' => $campaign->id,
                'error' => $e->getMessage()
            ]);

            return '<div style="padding: 20px; color: red;">Erreur de prévisualisation: ' . $e->getMessage() . '</div>';
        }
    }

    /**
     * Prévisualisation des thèmes en temps réel (SANS paramètre campaign)
     */
    public function previewTheme(Request $request)
    {
        try {
            $theme = $request->input('theme', 'modern');
            $data = $request->input('data', []);
            
            // Données par défaut pour la prévisualisation
            $defaultData = [
                'subject' => 'Prévisualisation du thème',
                'content' => '<p>Ceci est un exemple de contenu. Modifiez le texte dans l\'éditeur pour voir les changements en temps réel.</p>',
                'prenom' => 'Jean',
                'nom' => 'Dupont',
                'tracking' => [
                    'open_tracker' => '#',
                    'unsubscribe_url' => '#',
                    'click_tracker' => '#',
                ],
                'ctaUrl' => '#',
                'ctaText' => 'Découvrir',
                'features' => [
                    ['icon' => '✨', 'title' => 'Fonctionnalité 1', 'description' => 'Description de la fonctionnalité'],
                    ['icon' => '🚀', 'title' => 'Fonctionnalité 2', 'description' => 'Description de la fonctionnalité'],
                    ['icon' => '💡', 'title' => 'Fonctionnalité 3', 'description' => 'Description de la fonctionnalité'],
                ],
                'stats' => [
                    ['icon' => '📧', 'value' => '10k+', 'label' => 'Emails envoyés'],
                    ['icon' => '👁️', 'value' => '45%', 'label' => "Taux d'ouverture"],
                    ['icon' => '🖱️', 'value' => '12%', 'label' => 'Taux de clic'],
                ],
                'highlights' => [
                    ['icon' => '✓', 'title' => 'Avantage 1', 'description' => 'Description de l\'avantage'],
                    ['icon' => '✓', 'title' => 'Avantage 2', 'description' => 'Description de l\'avantage'],
                ],
                'socialLinks' => [
                    ['platform' => 'facebook', 'url' => '#'],
                    ['platform' => 'twitter', 'url' => '#'],
                    ['platform' => 'linkedin', 'url' => '#'],
                ],
            ];
            
            // Fusionner les données reçues avec les données par défaut
            $mergedData = array_merge($defaultData, $data);
            
            return $this->mailThemeService->render($theme, $mergedData);

        } catch (Throwable $e) {
            Log::error('Erreur lors de la prévisualisation du thème', [
                'theme' => $request->input('theme'),
                'error' => $e->getMessage()
            ]);

            return '<div style="padding: 20px; color: red;">Erreur de prévisualisation: ' . $e->getMessage() . '</div>';
        }
    }
}