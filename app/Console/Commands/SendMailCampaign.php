<?php

namespace App\Console\Commands;

use App\Models\MailCampaign;
use App\Models\MailSubscriber;
use App\Mail\MarketingMail;
use Vendor\MailMarketing\Services\MailMarketingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendMailCampaign extends Command
{
    protected $signature = 'mail:send-campaign 
                            {campaign : ID de la campagne}
                            {--test : Mode test (n\'envoie qu\'un email de test)}
                            {--limit= : Limite le nombre d\'envois}';
    
    protected $description = 'Envoyer une campagne email à tous les abonnés';

    protected $mailService;

    public function __construct(MailMarketingService $mailService)
    {
        parent::__construct();
        $this->mailService = $mailService;
    }

    public function handle()
    {
        $campaignId = $this->argument('campaign');
        $campaign = MailCampaign::findOrFail($campaignId);

        if ($campaign->status === 'sent' && !$this->option('test')) {
            $this->error('Cette campagne a déjà été envoyée.');
            return 1;
        }

        $this->info("Préparation de l'envoi : {$campaign->nom}");

        if ($this->option('test')) {
            $this->sendTestEmail($campaign);
            return 0;
        }

        $query = MailSubscriber::where('is_subscribed', true);
        
        if ($this->option('limit')) {
            $query->limit($this->option('limit'));
        }

        $subscribers = $query->get();

        if ($subscribers->isEmpty()) {
            $this->warn('Aucun abonné actif trouvé.');
            return 0;
        }

        $this->info("Envoi à {$subscribers->count()} abonnés...");
        
        $campaign->update(['status' => 'sending']);
        
        $bar = $this->output->createProgressBar($subscribers->count());
        $bar->start();

        $successCount = 0;
        $failCount = 0;

        foreach ($subscribers as $subscriber) {
            try {
                $this->mailService->sendToSubscriber($campaign, $subscriber);
                $successCount++;
            } catch (\Exception $e) {
                $failCount++;
                $campaign->subscribers()->updateExistingPivot($subscriber->id, [
                    'failed_at' => now()
                ]);
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        $campaign->update([
            'status' => 'sent',
            'sent_at' => now()
        ]);

        $this->info("Envoi terminé : {$successCount} succès, {$failCount} échecs.");

        return 0;
    }

    protected function sendTestEmail(MailCampaign $campaign)
    {
        $testEmail = $this->ask('Adresse email de test', auth()->user()->email ?? null);
        
        if (!$testEmail) {
            $this->error('Adresse email requise.');
            return;
        }

        $trackingData = [
            'campaign_id' => $campaign->id,
            'subscriber_id' => 0,
            'unsubscribe_url' => '#',
            'open_tracker' => '#',
        ];

        $mailData = [
            'subject' => "[TEST] " . $campaign->sujet,
            'content' => $campaign->contenu,
            'tracking' => $trackingData,
        ];

        Mail::to($testEmail)->send(new MarketingMail($mailData));
        
        $this->info("Email de test envoyé à {$testEmail}");
    }
}