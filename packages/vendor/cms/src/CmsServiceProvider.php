<?php

namespace Vendor\Cms;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class CmsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/Config/cms.php', 'cms'
        );

        // Register models
        $this->app->bind('cms.theme', function ($app) {
            return new \Vendor\Cms\Models\Theme();
        });
        
        // Register view namespace
        $this->loadViewsFrom(__DIR__ . '/Views', 'cms');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
        
        $this->registerRoutes();
        
        $this->publishes([
            __DIR__ . '/Config/cms.php' => config_path('cms.php'),
        ], 'cms-config');
        
        $this->publishes([
            __DIR__ . '/Database/Migrations/' => database_path('migrations'),
        ], 'cms-migrations');
        
        $this->publishes([
            __DIR__ . '/Views' => resource_path('views/vendor/cms'),
        ], 'cms-views');
        
        $this->publishes([
            __DIR__ . '/Assets' => public_path('vendor/cms'),
        ], 'cms-assets');
    }

    /**
     * Register routes.
     */
    protected function registerRoutes(): void
    {
        // Routes API
        Route::group($this->apiRouteConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
        });
        
        // Routes Web
        Route::group($this->webRouteConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        });
    }

    /**
     * API route configuration.
     */
    protected function apiRouteConfiguration(): array
    {
        return [
            'prefix' => 'api/cms',
            'middleware' => ['api', 'auth:sanctum'],
        ];
    }
    
    /**
     * Web route configuration.
     */
    protected function webRouteConfiguration(): array
    {
        return [
            'middleware' => ['web'],
        ];
    }
}