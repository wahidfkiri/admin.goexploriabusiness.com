<?php

namespace Vendor\MailMarketing\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MailCampaign;
use App\Models\MailSubscriber;
use App\Models\MailTrackingEvent;
use Vendor\MailMarketing\Services\MailMarketingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Throwable;

class MailTrackingController extends Controller
{
    protected $mailService;

    public function __construct(MailMarketingService $mailService)
    {
        $this->mailService = $mailService;
    }

    /**
     * Tracking des ouvertures d'email (pixel invisible)
     */
    public function trackOpen(Request $request, int $campaign, int $subscriber, ?string $token = null)
    {
        try {
            // Vérifier que la campagne et l'abonné existent
            $campaignModel = MailCampaign::find($campaign);
            $subscriberModel = MailSubscriber::find($subscriber);

            if (!$campaignModel || !$subscriberModel) {
                Log::warning('Tracking d\'ouverture - Campagne ou abonné non trouvé', [
                    'campaign_id' => $campaign,
                    'subscriber_id' => $subscriber
                ]);
                return $this->pixelResponse();
            }

            // Vérifier que l'email a bien été envoyé
            $pivot = DB::table('mail_campaign_subscriber')
                ->where('campaign_id', $campaign)
                ->where('subscriber_id', $subscriber)
                ->first();

            if (!$pivot || !$pivot->sent_at) {
                Log::warning('Tracking d\'ouverture - Email non encore envoyé', [
                    'campaign_id' => $campaign,
                    'subscriber_id' => $subscriber,
                    'email' => $subscriberModel->email
                ]);
                return $this->pixelResponse();
            }

            // Vérifier si déjà ouvert pour éviter les doublons
            if ($pivot->opened_at) {
                Log::info('Tracking d\'ouverture - Déjà enregistré', [
                    'campaign_id' => $campaign,
                    'subscriber_id' => $subscriber,
                    'email' => $subscriberModel->email
                ]);
                return $this->pixelResponse();
            }

            // Mettre à jour la table pivot
            DB::table('mail_campaign_subscriber')
                ->where('campaign_id', $campaign)
                ->where('subscriber_id', $subscriber)
                ->update(['opened_at' => now()]);

            // Enregistrer l'événement de tracking
            try {
                MailTrackingEvent::create([
                    'campaign_id' => $campaign,
                    'subscriber_id' => $subscriber,
                    'event_type' => 'open',
                    'payload' => [
                        'ip' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'timestamp' => now()->toIso8601String()
                    ]
                ]);
            } catch (Throwable $e) {
                Log::warning('Erreur lors de l\'enregistrement de l\'événement d\'ouverture', [
                    'campaign_id' => $campaign,
                    'subscriber_id' => $subscriber,
                    'error' => $e->getMessage()
                ]);
            }

            Log::info('Ouverture d\'email trackée', [
                'campaign_id' => $campaign,
                'campaign_name' => $campaignModel->nom,
                'subscriber_id' => $subscriber,
                'subscriber_email' => $subscriberModel->email,
                'ip' => $request->ip()
            ]);

        } catch (Throwable $e) {
            Log::error('Erreur lors du tracking d\'ouverture', [
                'campaign_id' => $campaign,
                'subscriber_id' => $subscriber,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return $this->pixelResponse();
    }

    /**
     * Tracking des clics sur les liens
     */
    public function trackClick(Request $request, int $campaign, int $subscriber)
    {
        try {
            $url = $request->query('url');
            
            if (!$url) {
                Log::warning('Tracking de clic - URL manquante', [
                    'campaign_id' => $campaign,
                    'subscriber_id' => $subscriber
                ]);
                abort(400, 'URL manquante');
            }

            $decodedUrl = urldecode($url);

            // Vérifier que la campagne et l'abonné existent
            $campaignModel = MailCampaign::find($campaign);
            $subscriberModel = MailSubscriber::find($subscriber);

            if (!$campaignModel || !$subscriberModel) {
                Log::warning('Tracking de clic - Campagne ou abonné non trouvé', [
                    'campaign_id' => $campaign,
                    'subscriber_id' => $subscriber,
                    'url' => $decodedUrl
                ]);
                return redirect()->away($decodedUrl);
            }

            // Vérifier que l'email a bien été envoyé
            $pivot = DB::table('mail_campaign_subscriber')
                ->where('campaign_id', $campaign)
                ->where('subscriber_id', $subscriber)
                ->first();

            if (!$pivot || !$pivot->sent_at) {
                Log::warning('Tracking de clic - Email non encore envoyé', [
                    'campaign_id' => $campaign,
                    'subscriber_id' => $subscriber,
                    'email' => $subscriberModel->email,
                    'url' => $decodedUrl
                ]);
                return redirect()->away($decodedUrl);
            }

            // Mettre à jour la table pivot (ne mettre à jour que si pas déjà cliqué)
            if (!$pivot->clicked_at) {
                DB::table('mail_campaign_subscriber')
                    ->where('campaign_id', $campaign)
                    ->where('subscriber_id', $subscriber)
                    ->update(['clicked_at' => now()]);
            }

            // Enregistrer l'événement de tracking
            try {
                MailTrackingEvent::create([
                    'campaign_id' => $campaign,
                    'subscriber_id' => $subscriber,
                    'event_type' => 'click',
                    'payload' => [
                        'url' => $decodedUrl,
                        'ip' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'timestamp' => now()->toIso8601String()
                    ]
                ]);
            } catch (Throwable $e) {
                Log::warning('Erreur lors de l\'enregistrement de l\'événement de clic', [
                    'campaign_id' => $campaign,
                    'subscriber_id' => $subscriber,
                    'error' => $e->getMessage()
                ]);
            }

            Log::info('Clic sur lien tracké', [
                'campaign_id' => $campaign,
                'campaign_name' => $campaignModel->nom,
                'subscriber_id' => $subscriber,
                'subscriber_email' => $subscriberModel->email,
                'url' => $decodedUrl,
                'ip' => $request->ip()
            ]);

            // Rediriger vers l'URL d'origine
            return redirect()->away($decodedUrl);

        } catch (Throwable $e) {
            Log::error('Erreur lors du tracking de clic', [
                'campaign_id' => $campaign,
                'subscriber_id' => $subscriber,
                'url' => $request->query('url'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Rediriger vers l'URL même en cas d'erreur
            $url = $request->query('url');
            if ($url) {
                return redirect()->away(urldecode($url));
            }
            return redirect()->route('home');
        }
    }

    /**
     * Retourne un pixel transparent
     */
    protected function pixelResponse()
    {
        $pixel = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
        
        return response($pixel, 200)
            ->header('Content-Type', 'image/gif')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Page de désabonnement
     */
    public function unsubscribe($email)
    {
        try {
            $subscriber = MailSubscriber::where('email', $email)->first();
            
            if (!$subscriber) {
                Log::warning('Désabonnement - Email non trouvé', ['email' => $email]);
                
                return view('mail-marketing::unsubscribe', [
                    'success' => false,
                    'email' => $email,
                    'error' => 'Cette adresse email n\'est pas présente dans notre base de données.'
                ]);
            }
            
            if (!$subscriber->is_subscribed) {
                Log::info('Désabonnement - Déjà désabonné', [
                    'email' => $email,
                    'subscriber_id' => $subscriber->id
                ]);
                
                return view('mail-marketing::unsubscribe', [
                    'success' => false,
                    'email' => $email,
                    'error' => 'Cette adresse email est déjà désabonnée de notre liste.'
                ]);
            }
            
            // Désabonner l'utilisateur
            $subscriber->update([
                'is_subscribed' => false,
                'unsubscribed_at' => now(),
            ]);
            
            Log::info('Abonné désabonné avec succès', [
                'email' => $email,
                'subscriber_id' => $subscriber->id,
                'unsubscribed_at' => now()
            ]);
            
            return view('mail-marketing::unsubscribe', [
                'success' => true,
                'email' => $email
            ]);
            
        } catch (Throwable $e) {
            Log::error('Erreur lors du désabonnement', [
                'email' => $email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return view('mail-marketing::unsubscribe', [
                'success' => false,
                'email' => $email,
                'error' => 'Une erreur technique est survenue. Veuillez réessayer plus tard.'
            ]);
        }
    }
    
    /**
     * Gère le réabonnement (API)
     */
    public function resubscribe(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email'
            ]);
            
            $subscriber = MailSubscriber::where('email', $request->email)->first();
            
            if (!$subscriber) {
                Log::warning('Réabonnement - Email non trouvé', ['email' => $request->email]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Adresse email non trouvée.'
                ], 404);
            }
            
            if ($subscriber->is_subscribed) {
                Log::info('Réabonnement - Déjà abonné', [
                    'email' => $request->email,
                    'subscriber_id' => $subscriber->id
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Cette adresse est déjà abonnée.'
                ], 400);
            }
            
            // Réabonner l'utilisateur
            $subscriber->update([
                'is_subscribed' => true,
                'unsubscribed_at' => null,
            ]);
            
            Log::info('Abonné réabonné avec succès', [
                'email' => $request->email,
                'subscriber_id' => $subscriber->id,
                'resubscribed_at' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Vous avez été réabonné avec succès.'
            ]);
            
        } catch (Throwable $e) {
            Log::error('Erreur lors du réabonnement', [
                'email' => $request->email ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur technique: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Webhook pour les rebonds (bounces)
     */
    public function webhookBounce(Request $request)
    {
        try {
            $email = $request->input('email');
            $reason = $request->input('reason', 'unknown');
            
            if (!$email) {
                Log::warning('Webhook bounce - Email manquant', ['payload' => $request->all()]);
                return response()->json(['status' => 'error', 'message' => 'Email required'], 400);
            }
            
            $subscriber = MailSubscriber::where('email', $email)->first();
            
            if (!$subscriber) {
                Log::warning('Webhook bounce - Abonné non trouvé', ['email' => $email]);
                return response()->json(['status' => 'ok']);
            }
            
            // Marquer l'abonné comme inactif si bounce permanent
            if (str_contains(strtolower($reason), 'permanent') || str_contains(strtolower($reason), 'invalid')) {
                $subscriber->update([
                    'is_subscribed' => false,
                    'unsubscribed_at' => now(),
                ]);
                
                Log::warning('Abonné désactivé suite à bounce permanent', [
                    'email' => $email,
                    'subscriber_id' => $subscriber->id,
                    'reason' => $reason
                ]);
            }
            
            // Enregistrer l'événement
            MailTrackingEvent::create([
                'campaign_id' => $request->input('campaign_id'),
                'subscriber_id' => $subscriber->id,
                'event_type' => 'bounce',
                'payload' => [
                    'reason' => $reason,
                    'raw' => $request->all()
                ]
            ]);
            
            Log::info('Bounce enregistré', [
                'email' => $email,
                'subscriber_id' => $subscriber->id,
                'reason' => $reason
            ]);
            
            return response()->json(['status' => 'ok']);
            
        } catch (Throwable $e) {
            Log::error('Erreur webhook bounce', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Webhook pour les plaintes (spam complaints)
     */
    public function webhookComplaint(Request $request)
    {
        try {
            $email = $request->input('email');
            
            if (!$email) {
                Log::warning('Webhook complaint - Email manquant', ['payload' => $request->all()]);
                return response()->json(['status' => 'error', 'message' => 'Email required'], 400);
            }
            
            $subscriber = MailSubscriber::where('email', $email)->first();
            
            if ($subscriber) {
                // Désabonner automatiquement en cas de plainte spam
                $subscriber->update([
                    'is_subscribed' => false,
                    'unsubscribed_at' => now(),
                ]);
                
                Log::warning('Abonné désactivé suite à plainte spam', [
                    'email' => $email,
                    'subscriber_id' => $subscriber->id
                ]);
                
                // Enregistrer l'événement
                MailTrackingEvent::create([
                    'campaign_id' => $request->input('campaign_id'),
                    'subscriber_id' => $subscriber->id,
                    'event_type' => 'complaint',
                    'payload' => ['raw' => $request->all()]
                ]);
            }
            
            return response()->json(['status' => 'ok']);
            
        } catch (Throwable $e) {
            Log::error('Erreur webhook complaint', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Statistiques de tracking pour une campagne
     */
    public function getStats(Request $request, int $campaign)
    {
        try {
            $campaignModel = MailCampaign::findOrFail($campaign);
            
            $stats = [
                'campaign' => [
                    'id' => $campaignModel->id,
                    'name' => $campaignModel->nom,
                    'subject' => $campaignModel->sujet,
                    'status' => $campaignModel->status,
                    'sent_at' => $campaignModel->sent_at?->toIso8601String(),
                ],
                'totals' => [
                    'subscribers' => DB::table('mail_campaign_subscriber')
                        ->where('campaign_id', $campaign)
                        ->count(),
                    'sent' => DB::table('mail_campaign_subscriber')
                        ->where('campaign_id', $campaign)
                        ->whereNotNull('sent_at')
                        ->count(),
                    'opened' => DB::table('mail_campaign_subscriber')
                        ->where('campaign_id', $campaign)
                        ->whereNotNull('opened_at')
                        ->count(),
                    'clicked' => DB::table('mail_campaign_subscriber')
                        ->where('campaign_id', $campaign)
                        ->whereNotNull('clicked_at')
                        ->count(),
                    'failed' => DB::table('mail_campaign_subscriber')
                        ->where('campaign_id', $campaign)
                        ->whereNotNull('failed_at')
                        ->count(),
                ],
                'rates' => [
                    'open_rate' => $this->calculateRate(
                        DB::table('mail_campaign_subscriber')->where('campaign_id', $campaign)->whereNotNull('sent_at')->count(),
                        DB::table('mail_campaign_subscriber')->where('campaign_id', $campaign)->whereNotNull('opened_at')->count()
                    ),
                    'click_rate' => $this->calculateRate(
                        DB::table('mail_campaign_subscriber')->where('campaign_id', $campaign)->whereNotNull('sent_at')->count(),
                        DB::table('mail_campaign_subscriber')->where('campaign_id', $campaign)->whereNotNull('clicked_at')->count()
                    ),
                    'click_to_open_rate' => $this->calculateRate(
                        DB::table('mail_campaign_subscriber')->where('campaign_id', $campaign)->whereNotNull('opened_at')->count(),
                        DB::table('mail_campaign_subscriber')->where('campaign_id', $campaign)->whereNotNull('clicked_at')->count()
                    ),
                ],
                'timeline' => [
                    'opens_by_hour' => DB::table('mail_tracking_events')
                        ->where('campaign_id', $campaign)
                        ->where('event_type', 'open')
                        ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
                        ->groupBy('hour')
                        ->orderBy('hour')
                        ->get(),
                    'clicks_by_hour' => DB::table('mail_tracking_events')
                        ->where('campaign_id', $campaign)
                        ->where('event_type', 'click')
                        ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
                        ->groupBy('hour')
                        ->orderBy('hour')
                        ->get(),
                ]
            ];
            
            return response()->json($stats);
            
        } catch (Throwable $e) {
            Log::error('Erreur lors de la récupération des statistiques', [
                'campaign_id' => $campaign,
                'error' => $e->getMessage()
            ]);
            
            return response()->json(['error' => 'Erreur lors du chargement des statistiques'], 500);
        }
    }

    /**
     * Calcule un taux en pourcentage
     */
    protected function calculateRate($total, $count)
    {
        if ($total <= 0) {
            return 0;
        }
        return round(($count / $total) * 100, 2);
    }
}