<?php

namespace Vendor\MailMarketing\Services;

use App\Models\MailCampaign;
use App\Models\MailSubscriber;
use App\Models\MailTrackingEvent;
use App\Mail\MarketingMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

class MailMarketingService
{
    /**
     * Envoie un email à un abonné spécifique
     */
    public function sendToSubscriber(MailCampaign $campaign, MailSubscriber $subscriber): void
    {
        Log::info('Début de l\'envoi d\'email', [
            'campaign_id' => $campaign->id,
            'campaign_nom' => $campaign->nom,
            'subscriber_id' => $subscriber->id,
            'subscriber_email' => $subscriber->email
        ]);

        try {
            $trackingData = [
                'campaign_id' => $campaign->id,
                'subscriber_id' => $subscriber->id,
                'unsubscribe_url' => route('mail-marketing.unsubscribe', [
                    'email' => $subscriber->email,
                    'token' => $this->generateUnsubscribeToken($subscriber)
                ]),
                'open_tracker' => route('mail-marketing.track.open', [
                    'campaign' => $campaign->id,
                    'subscriber' => $subscriber->id,
                    'token' => $this->generateTrackingToken($subscriber, $campaign)
                ]),
            ];

            $mailData = [
                'subject' => $campaign->sujet,
                'content' => $this->parseContent($campaign->contenu, $subscriber),
                'tracking' => $trackingData,
            ];

            Mail::to($subscriber->email)
                ->queue(new MarketingMail($mailData));

            $campaign->subscribers()->syncWithoutDetaching([
                $subscriber->id => ['sent_at' => now()]
            ]);

            Log::info('Email mis en file d\'attente avec succès', [
                'campaign_id' => $campaign->id,
                'subscriber_id' => $subscriber->id,
                'subscriber_email' => $subscriber->email
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi d\'email', [
                'campaign_id' => $campaign->id,
                'subscriber_id' => $subscriber->id,
                'subscriber_email' => $subscriber->email,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Traque une ouverture
     */
    public function trackOpen(int $campaignId, int $subscriberId): void
    {
        Log::info('Tentative de tracking d\'ouverture', [
            'campaign_id' => $campaignId,
            'subscriber_id' => $subscriberId
        ]);

        try {
            $campaign = MailCampaign::find($campaignId);
            $subscriber = MailSubscriber::find($subscriberId);

            if ($campaign && $subscriber) {
                $campaign->subscribers()->updateExistingPivot($subscriberId, [
                    'opened_at' => now()
                ]);

                MailTrackingEvent::create([
                    'campaign_id' => $campaignId,
                    'subscriber_id' => $subscriberId,
                    'event_type' => 'open',
                ]);

                Log::info('Ouverture traquée avec succès', [
                    'campaign_id' => $campaignId,
                    'subscriber_id' => $subscriberId,
                    'subscriber_email' => $subscriber->email
                ]);
            } else {
                Log::warning('Campagne ou abonné non trouvé pour le tracking d\'ouverture', [
                    'campaign_id' => $campaignId,
                    'subscriber_id' => $subscriberId,
                    'campaign_exists' => (bool)$campaign,
                    'subscriber_exists' => (bool)$subscriber
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors du tracking d\'ouverture', [
                'campaign_id' => $campaignId,
                'subscriber_id' => $subscriberId,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Traque un clic
     */
    public function trackClick(int $campaignId, int $subscriberId, string $url): void
    {
        Log::info('Tentative de tracking de clic', [
            'campaign_id' => $campaignId,
            'subscriber_id' => $subscriberId,
            'clicked_url' => $url
        ]);

        try {
            $campaign = MailCampaign::find($campaignId);
            $subscriber = MailSubscriber::find($subscriberId);

            if ($campaign && $subscriber) {
                $campaign->subscribers()->updateExistingPivot($subscriberId, [
                    'clicked_at' => now()
                ]);

                MailTrackingEvent::create([
                    'campaign_id' => $campaignId,
                    'subscriber_id' => $subscriberId,
                    'event_type' => 'click',
                    'payload' => ['url' => $url],
                ]);

                Log::info('Clic traqué avec succès', [
                    'campaign_id' => $campaignId,
                    'subscriber_id' => $subscriberId,
                    'subscriber_email' => $subscriber->email,
                    'clicked_url' => $url
                ]);
            } else {
                Log::warning('Campagne ou abonné non trouvé pour le tracking de clic', [
                    'campaign_id' => $campaignId,
                    'subscriber_id' => $subscriberId,
                    'clicked_url' => $url,
                    'campaign_exists' => (bool)$campaign,
                    'subscriber_exists' => (bool)$subscriber
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors du tracking de clic', [
                'campaign_id' => $campaignId,
                'subscriber_id' => $subscriberId,
                'clicked_url' => $url,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Désabonne un utilisateur
     */
    public function unsubscribe(string $email, ?string $token = null): bool
    {
        Log::info('Tentative de désabonnement', [
            'email' => $email,
            'token_provided' => !empty($token)
        ]);

        try {
            $subscriber = MailSubscriber::where('email', $email)->first();

            if (!$subscriber) {
                Log::warning('Désabonnement échoué - Abonné non trouvé', ['email' => $email]);
                return false;
            }

            if ($token && !$this->validateUnsubscribeToken($subscriber, $token)) {
                Log::warning('Désabonnement échoué - Token invalide', [
                    'email' => $email,
                    'subscriber_id' => $subscriber->id
                ]);
                return false;
            }

            $subscriber->update([
                'is_subscribed' => false,
                'unsubscribed_at' => now(),
            ]);

            Log::info('Désabonnement réussi', [
                'email' => $email,
                'subscriber_id' => $subscriber->id,
                'previous_status' => $subscriber->is_subscribed
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Erreur lors du désabonnement', [
                'email' => $email,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Planifie une campagne
     */
    public function scheduleCampaign(MailCampaign $campaign, \DateTime $scheduledAt): void
    {
        Log::info('Planification d\'une campagne', [
            'campaign_id' => $campaign->id,
            'campaign_nom' => $campaign->nom,
            'scheduled_at' => $scheduledAt->format('Y-m-d H:i:s')
        ]);

        try {
            $campaign->update([
                'status' => 'scheduled',
                'scheduled_at' => $scheduledAt,
            ]);

            Log::info('Campagne planifiée avec succès', [
                'campaign_id' => $campaign->id,
                'campaign_nom' => $campaign->nom,
                'scheduled_at' => $scheduledAt->format('Y-m-d H:i:s')
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la planification de la campagne', [
                'campaign_id' => $campaign->id,
                'campaign_nom' => $campaign->nom,
                'scheduled_at' => $scheduledAt->format('Y-m-d H:i:s'),
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Duplique une campagne
     */
    public function duplicateCampaign(MailCampaign $campaign): MailCampaign
    {
        Log::info('Duplication d\'une campagne', [
            'original_campaign_id' => $campaign->id,
            'original_campaign_nom' => $campaign->nom
        ]);

        try {
            $newCampaign = $campaign->replicate();
            $newCampaign->nom = $campaign->nom . ' (copie)';
            $newCampaign->status = 'draft';
            $newCampaign->scheduled_at = null;
            $newCampaign->sent_at = null;
            $newCampaign->save();

            Log::info('Campagne dupliquée avec succès', [
                'original_campaign_id' => $campaign->id,
                'original_campaign_nom' => $campaign->nom,
                'new_campaign_id' => $newCampaign->id,
                'new_campaign_nom' => $newCampaign->nom
            ]);

            return $newCampaign;

        } catch (\Exception $e) {
            Log::error('Erreur lors de la duplication de la campagne', [
                'original_campaign_id' => $campaign->id,
                'original_campaign_nom' => $campaign->nom,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Génère un token de désabonnement
     */
    protected function generateUnsubscribeToken(MailSubscriber $subscriber): string
    {
        return hash_hmac('sha256', $subscriber->email . $subscriber->id, config('app.key'));
    }

    /**
     * Génère un token de tracking
     */
    protected function generateTrackingToken(MailSubscriber $subscriber, MailCampaign $campaign): string
    {
        return hash_hmac('sha256', $subscriber->id . $campaign->id . now()->timestamp, config('app.key'));
    }

    /**
     * Valide le token de désabonnement
     */
    protected function validateUnsubscribeToken(MailSubscriber $subscriber, string $token): bool
    {
        return $token === $this->generateUnsubscribeToken($subscriber);
    }

    /**
     * Parse le contenu avec les variables de l'abonné
     */
    protected function parseContent(string $content, MailSubscriber $subscriber): string
    {
        $replacements = [
            '{{nom}}' => $subscriber->nom ?? '',
            '{{prenom}}' => $subscriber->prenom ?? '',
            '{{email}}' => $subscriber->email,
            '{{etablissement}}' => $subscriber->etablissement->name ?? '',
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }
}