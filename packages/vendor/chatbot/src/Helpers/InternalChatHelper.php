<?php
// ============================================================
// app/Helpers/InternalChatHelper.php
// ============================================================
// Enregistrer dans composer.json > autoload > files:
//   "app/Helpers/InternalChatHelper.php"
// ============================================================

if (!function_exists('ichat_unread_count')) {
    /**
     * Retourne le nombre total de messages non lus dans le chat interne
     * pour l'utilisateur authentifié.
     *
     * Usage dans une vue Blade : {{ ichat_unread_count() }}
     * Usage dans la navbar     : @if(ichat_unread_count() > 0) ... @endif
     */
    function ichat_unread_count(): int
    {
        if (!auth()->check()) return 0;

        /** @var \App\Services\InternalChatService $service */
        $service = app(\App\Services\InternalChatService::class);
        return $service->totalUnreadCount(auth()->user());
    }
}

if (!function_exists('ichat_route')) {
    /**
     * Génère l'URL vers une room du chat interne.
     *
     * Usage : <a href="{{ ichat_route($room->id) }}">...</a>
     */
    function ichat_route(int $roomId): string
    {
        return route('internal.chat.room', $roomId);
    }
}

if (!function_exists('ichat_is_online')) {
    /**
     * Vérifie si un utilisateur est en ligne dans le chat interne.
     *
     * Usage : @if(ichat_is_online($user->id)) ... @endif
     */
    function ichat_is_online(int $userId): bool
    {
        /** @var \App\Services\InternalChatCacheService $cache */
        $cache = app(\App\Services\InternalChatCacheService::class);
        return $cache->isUserOnline($userId);
    }
}


