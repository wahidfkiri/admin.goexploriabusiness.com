<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Database Connection
    |--------------------------------------------------------------------------
    |
    | Specify the database connection to use for CMS tables.
    | If null, will use the default database connection.
    |
    */
    'database_connection' => env('CMS_DB_CONNECTION', 'cms'),

    /*
    |--------------------------------------------------------------------------
    | Themes Storage Path
    |--------------------------------------------------------------------------
    |
    | Path where themes will be stored relative to storage path.
    |
    */
    'themes_path' => env('CMS_THEMES_PATH', 'app/cms/themes'),

    /*
    |--------------------------------------------------------------------------
    | Default Theme
    |--------------------------------------------------------------------------
    |
    | Default theme name to use if no theme is activated.
    |
    */
    'default_theme' => env('CMS_DEFAULT_THEME', 'default'),

    /*
    |--------------------------------------------------------------------------
    | Cache Pages
    |--------------------------------------------------------------------------
    |
    | Enable page caching for better performance.
    |
    */
    'cache_pages' => env('CMS_CACHE_PAGES', false),

    /*
    |--------------------------------------------------------------------------
    | Page Cache Lifetime
    |--------------------------------------------------------------------------
    |
    | Number of minutes to cache pages.
    |
    */
    'page_cache_lifetime' => env('CMS_PAGE_CACHE_LIFETIME', 60),

    /*
    |--------------------------------------------------------------------------
    | Allowed File Extensions for Themes
    |--------------------------------------------------------------------------
    |
    | File extensions allowed in theme uploads.
    |
    */
    'allowed_theme_extensions' => ['blade.php', 'css', 'js', 'json', 'png', 'jpg', 'jpeg', 'svg', 'woff', 'woff2', 'ttf'],
];