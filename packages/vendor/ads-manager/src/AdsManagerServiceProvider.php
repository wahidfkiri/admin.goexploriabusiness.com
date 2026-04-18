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
use Vendor\AdsManager\Console\AggregateAdReports;

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
            $this->commands([
                AggregateAdReports::class,
            ]);

            // Schedule daily aggregation
            $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
                $schedule->command('ads:aggregate')
                         ->dailyAt('02:00')
                         ->withoutOverlapping()
                         ->runInBackground()
                         ->appendOutputTo(storage_path('logs/ads-aggregate.log'));
            });
        }

        // Blade directives
        Blade::directive('adZone', function ($expression) {
            return "<?php echo app(\Vendor\AdsManager\Services\AdDisplayService::class)->renderZone($expression); ?>";
        });

        Blade::directive('adBanner', function ($expression) {
            return "<?php echo app(\Vendor\AdsManager\Services\AdDisplayService::class)->renderBanner($expression); ?>";
        });

        // @adZoneTargeted('placement_code', ['etablissement_id' => 1, 'page' => 'detail'])
        Blade::directive('adZoneTargeted', function ($expression) {
            return "<?php echo app(\Vendor\AdsManager\Services\AdDisplayService::class)->renderZoneTargeted($expression); ?>";
        });
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/ads-manager.php', 'ads-manager');

        $this->app->singleton(AdDisplayService::class, fn() => new AdDisplayService());
        $this->app->singleton(AdReportService::class,  fn() => new AdReportService());
        $this->app->singleton(AdExpiryService::class,  fn() => new AdExpiryService());
        $this->app->singleton(AdBudgetService::class,  fn() => new AdBudgetService());
        $this->app->singleton(AdTargetingService::class, fn() => new AdTargetingService());
    }
}