<?php

namespace Vendor\Chatbot\Services;

use Illuminate\Support\Facades\Cache;

/**
 * Gère le cache Redis/File utilisé par le long-polling et les indicateurs temps réel.
 * Séparé du ChatCacheService du package chatbot pour éviter les collisions de clés.
 */
class InternalChatCacheService
{
    protected string $prefix;
    protected int    $ttl;
    private array $localCache = [];

    public function __construct()
    {
        $this->prefix = config('internal_chat.cache_prefix', 'ichat_');
        $this->ttl    = config('internal_chat.cache_ttl', 300);
    }

    // ──────────────────────────────────────────────────────────────
    // LONG-POLLING — notification de nouveau message
    // ──────────────────────────────────────────────────────────────

    /**
     * Notifie tous les polling en attente qu'un nouveau message est arrivé.
     * On stocke le dernier message_id dans le cache avec une TTL courte.
     */
    public function notifyNewMessage(int $roomId, int $messageId): void
    {
        Cache::put($this->roomKey($roomId), $messageId, $this->ttl);
    }

    /**
     * Récupère le dernier message_id connu pour une room.
     * Retourne 0 si aucune notification récente en cache.
     */

public function getLastNotifiedMessageId(int $roomId): int
{
    // Cache local pour éviter les appels Redis multiples
    $now = time();
    if (isset($this->localCache[$roomId]) && ($now - $this->localCache[$roomId]['time']) < 1) {
        return $this->localCache[$roomId]['id'];
    }
    
    $id = (int) Cache::get($this->roomKey($roomId), 0);
    
    $this->localCache[$roomId] = ['id' => $id, 'time' => $now];
    
    return $id;
}

    // ──────────────────────────────────────────────────────────────
    // TYPING INDICATOR
    // ──────────────────────────────────────────────────────────────

    /**
     * Marque un utilisateur comme "en train d'écrire" dans une room.
     * Expire automatiquement après 5 secondes sans mise à jour.
     */
    public function setTyping(int $roomId, int $userId, bool $isTyping): void
    {
        $key = $this->typingKey($roomId, $userId);

        if ($isTyping) {
            Cache::put($key, true, 5);
        } else {
            Cache::forget($key);
        }
    }

    /**
     * Vérifie si un utilisateur est en train d'écrire dans une room.
     */
    public function isTyping(int $roomId, int $userId): bool
    {
        return (bool) Cache::get($this->typingKey($roomId, $userId), false);
    }

    /**
     * Retourne la liste des user_ids en train d'écrire dans une room,
     * parmi les participants fournis, en excluant l'utilisateur courant.
     */
    public function getTypingUsers(int $roomId, array $participantIds, int $excludeUserId): array
    {
        return collect($participantIds)
            ->reject(fn($id) => $id === $excludeUserId)
            ->filter(fn($id) => $this->isTyping($roomId, $id))
            ->values()
            ->all();
    }

    // ──────────────────────────────────────────────────────────────
    // USER PRESENCE (optionnel — online/offline)
    // ──────────────────────────────────────────────────────────────

    /**
     * Marque un utilisateur comme "en ligne".
     * Expire après 60 secondes (le client envoie un heartbeat toutes les 30s).
     */
    public function setUserOnline(int $userId): void
    {
        Cache::put($this->onlineKey($userId), now()->toIso8601String(), 60);
    }

    /**
     * Vérifie si un utilisateur est considéré comme en ligne.
     */
    public function isUserOnline(int $userId): bool
    {
        return Cache::has($this->onlineKey($userId));
    }

    /**
     * Retourne la date de dernière activité d'un utilisateur, ou null.
     */
    public function lastSeenAt(int $userId): ?string
    {
        return Cache::get($this->onlineKey($userId));
    }

    /**
     * Parmi une liste d'IDs, retourne ceux qui sont en ligne.
     */
    public function getOnlineUsers(array $userIds): array
    {
        return collect($userIds)
            ->filter(fn($id) => $this->isUserOnline($id))
            ->values()
            ->all();
    }

    // ──────────────────────────────────────────────────────────────
    // KEYS
    // ──────────────────────────────────────────────────────────────

    protected function roomKey(int $roomId): string
    {
        return $this->prefix . "room_last_msg_{$roomId}";
    }

    protected function typingKey(int $roomId, int $userId): string
    {
        return $this->prefix . "typing_{$roomId}_{$userId}";
    }

    protected function onlineKey(int $userId): string
    {
        return $this->prefix . "online_{$userId}";
    }
}

