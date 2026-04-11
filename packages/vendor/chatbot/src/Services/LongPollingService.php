<?php

namespace Vendor\Chatbot\Services;

use Vendor\Chatbot\Models\ChatRoom;
use Vendor\Chatbot\Models\ChatMessage;
use Illuminate\Support\Facades\Log;

class LongPollingService
{
    /**
     * Timeout maximum en secondes (laisser 5s de marge avant le timeout PHP/nginx).
     */
    protected int $timeout;

    /**
     * Intervalle de vérification en microsecondes (0.5s = 500 000µs).
     */
    protected int $sleepInterval;

    public function __construct()
    {
        $this->timeout       = config('chatbot.poll_timeout', 25);
        $this->sleepInterval = config('chatbot.poll_sleep', 500000);
    }

    /**
     * Point d'entrée principal du Long-Polling.
     *
     * Bloque la requête jusqu'à ce qu'un nouveau message arrive OU que le
     * timeout soit atteint. Répond toujours du JSON valide.
     *
     * @param  ChatRoom  $room     La room à surveiller.
     * @param  int       $lastId   Le dernier ID de message connu du client.
     * @param  string    $viewer   'visitor' ou 'agent' (pour marquer comme lu).
     * @return array     Tableau de messages nouveaux (peut être vide si timeout).
     */
    public function poll(ChatRoom $room, int $lastId, string $viewer = 'visitor'): array
    {
        // Sécurité: éviter que PHP ne ferme la connexion prématurément
        set_time_limit($this->timeout + 10);
        ignore_user_abort(true);

        $startTime = microtime(true);
        $deadline  = $startTime + $this->timeout;

        Log::debug('Long-Polling started', [
            'room_id' => $room->id,
            'last_id' => $lastId,
            'timeout' => $this->timeout,
        ]);

        while (microtime(true) < $deadline) {
            // La room est-elle encore ouverte ?
            $room->refresh();
            if ($room->isClosed()) {
                return $this->buildResponse([], $lastId, 'room_closed');
            }

            // Y a-t-il de nouveaux messages ?
            $messages = $this->fetchNewMessages($room, $lastId, $viewer);

            if ($messages->isNotEmpty()) {
                Log::debug('Long-Polling: new messages found', [
                    'room_id' => $room->id,
                    'count'   => $messages->count(),
                ]);

                return $this->buildResponse(
                    $messages->map(fn($m) => $m->toApiArray())->values()->all(),
                    $messages->last()->id,
                    'new_messages'
                );
            }

            // Pas encore de messages → dormir et réessayer
            usleep($this->sleepInterval);
        }

        // Timeout atteint → réponse vide (le client va relancer immédiatement)
        Log::debug('Long-Polling: timeout', ['room_id' => $room->id]);

        return $this->buildResponse([], $lastId, 'timeout');
    }

    /**
     * Récupère les messages non encore vus (id > lastId).
     */
    protected function fetchNewMessages(ChatRoom $room, int $lastId, string $viewer): \Illuminate\Database\Eloquent\Collection
    {
        $messages = ChatMessage::where('room_id', $room->id)
            ->where('id', '>', $lastId)
            ->with(['files', 'agent'])
            ->orderBy('id')
            ->limit(config('chatbot.poll_max_messages', 50))
            ->get();

        // Marquer comme lus les messages de l'autre partie
        if ($messages->isNotEmpty()) {
            $senderToMark = $viewer === 'visitor' ? 'agent' : 'visitor';
            ChatMessage::where('room_id', $room->id)
                ->whereIn('id', $messages->where('sender_type', $senderToMark)->pluck('id'))
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }

        return $messages;
    }

    protected function buildResponse(array $messages, int $lastId, string $status): array
    {
        return [
            'status'   => $status,      // 'new_messages' | 'timeout' | 'room_closed'
            'messages' => $messages,
            'last_id'  => $lastId,
            'ts'       => now()->toIso8601String(),
        ];
    }
}