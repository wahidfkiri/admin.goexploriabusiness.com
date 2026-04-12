<?php

namespace Vendor\Chatbot\Services;

use Vendor\Chatbot\Models\InternalChatRoom;
use Vendor\Chatbot\Models\InternalChatMessage;
use Illuminate\Support\Facades\Log;

/**
 * Long-Polling dédié au chat interne.
 *
 * Même pattern que Vendor\Chatbot\Services\LongPollingService mais opère
 * sur internal_chat_messages (pas chat_messages). Complètement indépendant.
 */
class InternalLongPollingService
{
    protected int $timeout;        // secondes avant de répondre "timeout"
    protected int $sleepInterval;  // microsecondes entre chaque vérification DB

    public function __construct(protected InternalChatCacheService $cache)
    {
        $this->timeout       = (int) config('internal_chat.poll_timeout', 25);
        $this->sleepInterval = (int) config('internal_chat.poll_sleep', 500_000);  // 0.5s
    }

    /**
     * Point d'entrée principal.
     *
     * Bloque la requête jusqu'à :
     *   - Nouveau(x) message(s) détecté(s) → status: new_messages
     *   - Timeout atteint               → status: timeout
     *
     * @param  InternalChatRoom  $room
     * @param  int               $lastId    Dernier ID connu du client
     * @param  int               $viewerId  User ID du demandeur (pour exclure ses propres messages du compteur "autres qui écrivent")
     * @param  array             $allParticipantIds  Tous les participants de la room (pour typing indicator)
     *
     * @return array  Réponse JSON-sérialisable
     */
    public function poll(
        InternalChatRoom $room,
        int              $lastId,
        int              $viewerId,
        array            $allParticipantIds = []
    ): array {
        // Prévenir PHP de fermer la connexion prématurément
        set_time_limit($this->timeout + 10);
        ignore_user_abort(true);

        $deadline = microtime(true) + $this->timeout;

        Log::debug('InternalChat poll started', [
            'room_id' => $room->id,
            'last_id' => $lastId,
            'viewer'  => $viewerId,
        ]);

        while (microtime(true) < $deadline) {

            // ── Vérification rapide via cache avant de taper la DB ──
            $cachedLastId = $this->cache->getLastNotifiedMessageId($room->id);
            if ($cachedLastId > $lastId) {
                $messages = $this->fetchMessages($room->id, $lastId);
                if ($messages->isNotEmpty()) {
                    return $this->buildResponse(
                        $messages->map(fn($m) => $m->toApiArray())->values()->all(),
                        $messages->last()->id,
                        'new_messages',
                        $room->id,
                        $viewerId,
                        $allParticipantIds
                    );
                }
            }

            // ── Fallback : lecture directe DB (cache manqué ou cold start) ──
            $messages = $this->fetchMessages($room->id, $lastId);
            if ($messages->isNotEmpty()) {
                Log::debug('InternalChat poll: messages found (DB fallback)', [
                    'room_id' => $room->id,
                    'count'   => $messages->count(),
                ]);

                return $this->buildResponse(
                    $messages->map(fn($m) => $m->toApiArray())->values()->all(),
                    $messages->last()->id,
                    'new_messages',
                    $room->id,
                    $viewerId,
                    $allParticipantIds
                );
            }

            usleep($this->sleepInterval);
        }

        // Timeout — le client relance immédiatement
        Log::debug('InternalChat poll: timeout', ['room_id' => $room->id]);

        return $this->buildResponse([], $lastId, 'timeout', $room->id, $viewerId, $allParticipantIds);
    }

    // ──────────────────────────────────────────────────────────────
    // PRIVÉ
    // ──────────────────────────────────────────────────────────────

    protected function fetchMessages(int $roomId, int $lastId): \Illuminate\Database\Eloquent\Collection
    {
        return InternalChatMessage::where('room_id', $roomId)
            ->where('id', '>', $lastId)
            ->with(['user:id,name,avatar', 'files'])
            ->orderBy('id')
            ->limit((int) config('internal_chat.poll_max_messages', 50))
            ->get();
    }

    protected function buildResponse(
        array  $messages,
        int    $lastId,
        string $status,
        int    $roomId,
        int    $viewerId,
        array  $participantIds
    ): array {
        // Indicateur "qui est en train d'écrire" (hors le viewer)
        $typingUserIds = $this->cache->getTypingUsers($roomId, $participantIds, $viewerId);

        return [
            'status'          => $status,            // 'new_messages' | 'timeout'
            'messages'        => $messages,
            'last_id'         => $lastId,
            'typing_user_ids' => $typingUserIds,     // [] ou [userId, ...]
            'ts'              => now()->toIso8601String(),
        ];
    }
}