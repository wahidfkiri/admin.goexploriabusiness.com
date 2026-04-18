<?php

namespace Vendor\AdsManager\Console;

use Illuminate\Console\Command;
use Vendor\AdsManager\Services\AdReportService;
use Vendor\AdsManager\Services\AdExpiryService;
use Throwable;

class AggregateAdReports extends Command
{
    protected $signature   = 'ads:aggregate {--date= : Date à agréger (Y-m-d), défaut=hier}';
    protected $description = 'Agrège les données quotidiennes d\'impressions/clics/revenus dans ad_daily_reports';

    public function __construct(
        protected AdReportService $reportService,
        protected AdExpiryService $expiryService,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $date = $this->option('date') ?? now()->subDay()->toDateString();

        $this->info("📊 Agrégation des rapports pour : {$date}");

        try {
            $this->reportService->aggregateDaily($date);
            $this->info('✅ Rapport journalier agrégé avec succès.');
        } catch (Throwable $e) {
            $this->error('❌ Erreur lors de l\'agrégation : ' . $e->getMessage());
            return self::FAILURE;
        }

        // Expire les annonces dont la date est dépassée ou budget épuisé
        try {
            $expired = $this->expiryService->expireOutdatedAds();
            if ($expired > 0) {
                $this->info("⏱️  {$expired} annonce(s) expirée(s) automatiquement.");
            }
        } catch (Throwable $e) {
            $this->warn('⚠️  Erreur lors de l\'expiration : ' . $e->getMessage());
        }

        return self::SUCCESS;
    }
}