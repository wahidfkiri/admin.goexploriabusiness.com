<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class GrapesJSServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Configuration GrapesJS
        $this->app->singleton('grapesjs.config', function () {
            return [
                'cdn' => [
                    'css' => 'https://unpkg.com/grapesjs/dist/css/grapes.min.css',
                    'js' => 'https://unpkg.com/grapesjs',
                    'plugins' => [
                        'webpage' => 'https://unpkg.com/grapesjs-preset-webpage',
                        'blocks' => 'https://unpkg.com/grapesjs-blocks-basic',
                        'forms' => 'https://unpkg.com/grapesjs-plugin-forms'
                    ]
                ]
            ];
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publier les configurations si nécessaire
    }
}