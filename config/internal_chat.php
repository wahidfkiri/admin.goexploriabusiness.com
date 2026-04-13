<?php
// ============================================================
// config/internal_chat.php
// ============================================================

return [

    /*
    |--------------------------------------------------------------------------
    | Long-Polling
    |--------------------------------------------------------------------------
    | poll_timeout  : durée max en secondes d'une requête poll (laisser 5s de
    |                 marge avant le timeout PHP/nginx, configuré à 30s).
    | poll_sleep    : intervalle de vérification en microsecondes (120000 = 0.12s).
    | poll_max_messages : nombre maximum de messages retournés par réponse poll.
    */
    'poll_timeout'      => env('ICHAT_POLL_TIMEOUT', 25),
    'poll_sleep'        => env('ICHAT_POLL_SLEEP', 120_000),
    'poll_max_messages' => env('ICHAT_POLL_MAX_MESSAGES', 50),
    'poll_db_check_interval' => env('ICHAT_POLL_DB_CHECK_INTERVAL', 0.6),

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    | Préfixe ajouté à toutes les clés Redis/File pour éviter les collisions
    | avec le package chatbot visiteur.
    | ttl : durée de vie des clés de notification (en secondes).
    */
    'cache_prefix' => env('ICHAT_CACHE_PREFIX', 'ichat_'),
    'cache_ttl'    => env('ICHAT_CACHE_TTL', 300),

    /*
    |--------------------------------------------------------------------------
    | Fichiers uploadés
    |--------------------------------------------------------------------------
    */
    'storage_disk'       => env('ICHAT_STORAGE_DISK', 'public'),
    'storage_path'       => env('ICHAT_STORAGE_PATH', 'internal-chat/files'),
    'max_file_size'      => env('ICHAT_MAX_FILE_SIZE', 100240),   // Ko (10 Mo)
    'allowed_file_types' => [
        'jpg', 'jpeg', 'png', 'gif', 'webp',
        'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
        'txt', 'csv', 'zip', 'mp4', 'mp3', 'mov',
    ],

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    | notify_offline_users : envoyer une notification (email/push) aux utilisateurs
    |                        hors ligne quand un message leur est adressé.
    */
    'notify_offline_users' => env('ICHAT_NOTIFY_OFFLINE', true),

    /*
    |--------------------------------------------------------------------------
    | Présence
    |--------------------------------------------------------------------------
    | La présence "en ligne" est maintenue via un heartbeat toutes les 30s
    | côté client. La clé cache expire après presence_ttl secondes.
    */
    'presence_ttl' => env('ICHAT_PRESENCE_TTL', 60),
];

