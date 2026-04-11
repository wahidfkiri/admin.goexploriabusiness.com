<?php

namespace Vendor\Chatbot\Services;

use Illuminate\Support\Facades\Cache;

class ChatCacheService
{
    protected string $prefix;
    protected int    $ttl;

    public function __construct()
    {
        $this->prefix = config('chatbot.cache_prefix', 'chatbot_');
        $this->ttl    = config('chatbot.cache_ttl', 300);
    }

    /**
     * Notifie le Long-Polling qu'un nouveau message est arrivé.
     * On stocke le dernier message_id dans le cache pour que le poll le voie.
     */
    public function notifyNewMessage(int $roomId, int $messageId): void
    {
        Cache::put($this->roomKey($roomId), $messageId, $this->ttl);
    }

    /**
     * Récupère le dernier message_id connu pour une room.
     */
    public function getLastMessageId(int $roomId): int
    {
        return (int) Cache::get($this->roomKey($roomId), 0);
    }

    // ── Typing indicator ──

    public function setTyping(int $roomId, string $role, bool $isTyping): void
    {
        $key = $this->prefix . "typing_{$roomId}_{$role}";

        if ($isTyping) {
            Cache::put($key, true, 5); // expire en 5s si plus de heartbeat
        } else {
            Cache::forget($key);
        }
    }

    public function isTyping(int $roomId, string $role): bool
    {
        return (bool) Cache::get($this->prefix . "typing_{$roomId}_{$role}", false);
    }

    // ── Agent presence ──

    public function setAgentOnline(int $agentId): void
    {
        Cache::put($this->prefix . "agent_online_{$agentId}", true, 30);
    }

    public function isAgentOnline(int $agentId): bool
    {
        return (bool) Cache::get($this->prefix . "agent_online_{$agentId}", false);
    }

    // ── Helpers ──

    protected function roomKey(int $roomId): string
    {
        return $this->prefix . "room_last_msg_{$roomId}";
    }
}