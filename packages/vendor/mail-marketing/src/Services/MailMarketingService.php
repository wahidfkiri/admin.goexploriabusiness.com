<?php

namespace Vendor\MailMarketing\Services;

use App\Models\MailCampaign;
use App\Models\MailSubscriber;
use App\Models\MailTrackingEvent;
use App\Mail\MarketingMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

class MailMarketingService
{
    /**
     * Envoie un email à un abonné spécifique
     */
    public function sendToSubscriber(MailCampaign $campaign, MailSubscriber $subscriber): void
    {
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
    }

    /**
     * Traque une ouverture
     */
    public function trackOpen(int $campaignId, int $subscriberId): void
    {
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
        }
    }

    /**
     * Traque un clic
     */
    public function trackClick(int $campaignId, int $subscriberId, string $url): void
    {
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
        }
    }

    /**
     * Désabonne un utilisateur
     */
    public function unsubscribe(string $email, ?string $token = null): bool
    {
        $subscriber = MailSubscriber::where('email', $email)->first();

        if (!$subscriber) {
            return false;
        }

        if ($token && !$this->validateUnsubscribeToken($subscriber, $token)) {
            return false;
        }

        $subscriber->update([
            'is_subscribed' => false,
            'unsubscribed_at' => now(),
        ]);

        return true;
    }

    /**
     * Planifie une campagne
     */
    public function scheduleCampaign(MailCampaign $campaign, \DateTime $scheduledAt): void
    {
        $campaign->update([
            'status' => 'scheduled',
            'scheduled_at' => $scheduledAt,
        ]);
    }

    /**
     * Duplique une campagne
     */
    public function duplicateCampaign(MailCampaign $campaign): MailCampaign
    {
        $newCampaign = $campaign->replicate();
        $newCampaign->nom = $campaign->nom . ' (copie)';
        $newCampaign->status = 'draft';
        $newCampaign->scheduled_at = null;
        $newCampaign->sent_at = null;
        $newCampaign->save();

        return $newCampaign;
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