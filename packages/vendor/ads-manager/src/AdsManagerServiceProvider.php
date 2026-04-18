<?php

namespace Vendor\AdsManager;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Console\Scheduling\Schedule;
use Vendor\AdsManager\Services\AdDisplayService;
use Vendor\AdsManager\Services\AdReportService;
use Vendor\AdsManager\Services\AdExpiryService;
use Vendor\AdsManager\Services\AdBudgetService;
use Vendor\AdsManager\Services\AdTargetingService;

class AdsManagerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Routes
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        // Views
        $this->loadViewsFrom(__DIR__ . '/Views', 'ads-manager');

        // Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Publish config
        $this->publishes([
            __DIR__ . '/../config/ads-manager.php' => config_path('ads-manager.php'),
        ], 'ads-manager-config');

        // Publish assets
        $this->publishes([
            __DIR__ . '/../resources/assets' => public_path('vendor/ads-manager'),
        ], 'ads-manager-assets');

        // Publish views (so the host app can override them)
        $this->publishes([
            __DIR__ . '/Views' => resource_path('views/vendor/ads-manager'),
        ], 'ads-manager-views');

        // Register artisan commands
        if ($this->app->runningInConsole()) {
            // Commande chargée dynamiquement pour éviter l'erreur "class does not exist"
            // si illuminate/console n'est pas encore résolu lors du boot.
            $this->commands([
                \Vendor\AdsManager\Console\AggregateAdReports::class,
            ]);

            $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
                $schedule->command('ads:aggregate')
                         ->dailyAt('02:00')
                         ->withoutOverlapping()
                         ->runInBackground();
            });
        }

        // ----------------------------------------------------------------
        // Blade directives — CORRECTION : $expression est déjà compilé par
        // Blade sous forme de valeur PHP (ex: "'sidebar_right'").
        // On passe donc $expression directement, sans re-quoter.
        // ----------------------------------------------------------------

        // @adZone('code')
        // @adZone('code', ['key' => 'val'])
        Blade::directive('adZone', function (string $expression) {
            // $expression peut être : "'sidebar_right'"  OU  "'sidebar_right', ['k'=>'v']"
            // On enveloppe dans un appel PHP direct.
            return "<?php echo app(\\Vendor\\AdsManager\\Services\\AdDisplayService::class)->renderZone({$expression}); ?>";
        });

        // @adBanner(42)
        Blade::directive('adBanner', function (string $expression) {
            return "<?php echo app(\\Vendor\\AdsManager\\Services\\AdDisplayService::class)->renderBanner({$expression}); ?>";
        });

        // @adZoneTargeted('code', ['etablissement_id' => 1, 'page' => 'detail'])
        Blade::directive('adZoneTargeted', function (string $expression) {
            return "<?php echo app(\\Vendor\\AdsManager\\Services\\AdDisplayService::class)->renderZoneTargeted({$expression}); ?>";
        });
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/ads-manager.php', 'ads-manager');

        $this->app->singleton(AdDisplayService::class, fn ($app) =>
            new AdDisplayService($app->make(AdTargetingService::class))
        );
        $this->app->singleton(AdReportService::class,    fn () => new AdReportService());
        $this->app->singleton(AdExpiryService::class,    fn () => new AdExpiryService());
        $this->app->singleton(AdBudgetService::class,    fn () => new AdBudgetService());
        $this->app->singleton(AdTargetingService::class, fn () => new AdTargetingService());
    }
}