<?php

namespace Vendor\Website;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Vendor\Website\Services\WebsiteCreationService;
// use Vendor\Storage\Components\GoogleDrive\MainApp;

class WebsiteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/Views', 'website');


        // Blade::component('drive-google-drive-main-app', MainApp::class);

        // Publish assets if needed
        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/website'),
        ], 'website-assets');
    }

    public function register()
    {
        $this->app->singleton(WebsiteCreationService::class, function ($app) {
            return new WebsiteCreationService();
        });
    }
}
