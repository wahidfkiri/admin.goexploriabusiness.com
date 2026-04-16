<?php

namespace Vendor\AdsManager;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Vendor\AdsManager\Services\AdDisplayService;

class AdsManagerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/Views', 'ads-manager');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Publish config
        $this->publishes([
            __DIR__.'/../config/ads-manager.php' => config_path('ads-manager.php'),
        ], 'ads-manager-config');

        // Publish assets
        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/ads-manager'),
        ], 'ads-manager-assets');

        // Blade directives
        Blade::directive('adZone', function ($expression) {
            return "<?php echo app(\Vendor\AdsManager\Services\AdDisplayService::class)->renderZone($expression); ?>";
        });

        Blade::directive('adBanner', function ($expression) {
            return "<?php echo app(\Vendor\AdsManager\Services\AdDisplayService::class)->renderBanner($expression); ?>";
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/ads-manager.php', 'ads-manager');

        $this->app->singleton(AdDisplayService::class, function ($app) {
            return new AdDisplayService();
        });
    }
}