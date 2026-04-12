<?php

namespace Vendor\Chatbot;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class InternalChatServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/Config/internal_chat.php', 'chatbot'
        );

        // Register models
        $this->app->bind('chatbot.internal_chat', function ($app) {
            return new \Vendor\Chatbot\Models\InternalChat();
        });
        
        // Register view namespace
        $this->loadViewsFrom(__DIR__ . '/Views', 'chatbot');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
        
        $this->registerRoutes();
        
        $this->publishes([
            __DIR__ . '/Config/internal_chat.php' => config_path('internal_chat.php'),
        ], 'internal-chat-config');
        
        $this->publishes([
            __DIR__ . '/Database/Migrations/' => database_path('migrations'),
        ], 'internal-chat-migrations');
        
        $this->publishes([
            __DIR__ . '/Views' => resource_path('views/vendor/chatbot'),
        ], 'internal-chat-views');
        
        $this->publishes([
            __DIR__ . '/Assets' => public_path('vendor/chatbot'),
        ], 'internal-chat-assets');
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
            'prefix' => 'api/chatbot',
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