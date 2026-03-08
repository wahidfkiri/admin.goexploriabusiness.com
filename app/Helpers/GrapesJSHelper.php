<?php

if (!function_exists('grapesjs_cdn_assets')) {
    /**
     * Générer les balises CDN pour GrapesJS
     */
    function grapesjs_cdn_assets(array $plugins = []): string
    {
        $defaultPlugins = [
            'grapesjs-preset-webpage',
            'grapesjs-blocks-basic',
            'grapesjs-plugin-forms'
        ];
        
        $plugins = array_merge($defaultPlugins, $plugins);
        
        $assets = [
            '<link rel="stylesheet" href="https://unpkg.com/grapesjs/dist/css/grapes.min.css">',
            '<script src="https://unpkg.com/grapesjs"></script>'
        ];
        
        foreach ($plugins as $plugin) {
            $assets[] = "<script src=\"https://unpkg.com/{$plugin}\"></script>";
        }
        
        return implode("\n", $assets);
    }
}

if (!function_exists('grapesjs_init_script')) {
    /**
     * Générer le script d'initialisation GrapesJS
     */
    function grapesjs_init_script(array $config = []): string
    {
        $defaultConfig = [
            'container' => '#gjs',
            'height' => '100%',
            'fromElement' => true,
            'storageManager' => false,
            'plugins' => ['grapesjs-preset-webpage', 'grapesjs-blocks-basic', 'grapesjs-plugin-forms']
        ];
        
        $config = array_merge($defaultConfig, $config);
        
        $jsonConfig = json_encode($config, JSON_PRETTY_PRINT);
        
        return <<<SCRIPT
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const editor = grapesjs.init({$jsonConfig});
                window.grapesjsEditor = editor;
            });
        </script>
        SCRIPT;
    }
}