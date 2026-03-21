<?php

namespace App\Console\Commands;

use App\Models\MailCampaign;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckCampaignLogs extends Command
{
    protected $signature = 'mail:check-logs {campaign?} {--errors-only}';
    protected $description = 'Vérifie les logs d\'envoi des campagnes';

    public function handle()
    {
        $campaignId = $this->argument('campaign');
        $errorsOnly = $this->option('errors-only');

        if ($campaignId) {
            $campaign = MailCampaign::find($campaignId);
            if (!$campaign) {
                $this->error('Campagne non trouvée');
                return 1;
            }

            $this->displayCampaignStats($campaign);
        } else {
            $this->displayAllStats($errorsOnly);
        }

        return 0;
    }

    protected function displayCampaignStats(MailCampaign $campaign)
    {
        $this->info('=== Statistiques de la campagne ===');
        $this->line("ID: {$campaign->id}");
        $this->line("Nom: {$campaign->nom}");
        $this->line("Statut: {$campaign->status}");
        
        $sent = $campaign->subscribers()->wherePivotNotNull('sent_at')->count();
        $failed = $campaign->subscribers()->wherePivotNotNull('failed_at')->count();
        $opened = $campaign->subscribers()->wherePivotNotNull('opened_at')->count();
        $clicked = $campaign->subscribers()->wherePivotNotNull('clicked_at')->count();
        
        $this->line("Envoyés: $sent");
        $this->line("Échecs: $failed");
        $this->line("Ouvertures: $opened");
        $this->line("Clics: $clicked");
        
        if ($sent > 0) {
            $this->line("Taux d'ouverture: " . round(($opened / $sent) * 100, 2) . "%");
            $this->line("Taux de clic: " . round(($clicked / $sent) * 100, 2) . "%");
        }
    }

    protected function displayAllStats($errorsOnly = false)
    {
        $campaigns = MailCampaign::orderBy('created_at', 'desc')->get();
        
        $this->info('=== Récapitulatif des campagnes ===');
        
        foreach ($campaigns as $campaign) {
            $sent = $campaign->subscribers()->wherePivotNotNull('sent_at')->count();
            $failed = $campaign->subscribers()->wherePivotNotNull('failed_at')->count();
            
            if ($errorsOnly && $failed === 0) {
                continue;
            }
            
            $this->line("\n📧 {$campaign->nom}");
            $this->line("   Statut: {$campaign->status}");
            $this->line("   Envoyés: $sent | Échecs: $failed");
            $this->line("   Date: " . $campaign->created_at->format('d/m/Y H:i'));
        }
    }
}