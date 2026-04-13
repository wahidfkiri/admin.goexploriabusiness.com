<?php

namespace Vendor\Chatbot\Services;

use Vendor\Chatbot\Models\InternalChatRoom;
use Vendor\Chatbot\Models\InternalChatMessage;
use Vendor\Chatbot\Models\InternalChatRead;
use App\Models\User;

/**
 * Long-polling dedie au chat interne.
 */
class InternalLongPollingService
{
    protected int $timeout;
    protected int $sleepInterval;
    protected float $dbCheckInterval;

    public function __construct(protected InternalChatCacheService $cache)
    {
        $this->timeout = (int) config('internal_chat.poll_timeout', 25);
        $this->sleepInterval = (int) config('internal_chat.poll_sleep', 500_000);
        $this->dbCheckInterval = (float) config('internal_chat.poll_db_check_interval', 2.5);
    }

    /**
     * @param array<int> $allParticipantIds
     */
    public function poll(InternalChatRoom $room, int $lastId, int $viewerId, array $allParticipantIds = []): array
    {
        set_time_limit($this->timeout + 10);
        ignore_user_abort(true);

        $deadline = microtime(true) + $this->timeout;
        $lastDbCheck = 0.0;

        while (microtime(true) < $deadline) {
            $now = microtime(true);

            // Fast-path cache: aucun acces DB si le cache n'indique pas de nouveau message.
            $cachedLastId = $this->cache->getLastNotifiedMessageId($room->id);
            if ($cachedLastId > $lastId) {
                $messages = $this->fetchMessages($room->id, $lastId);
                if ($messages->isNotEmpty()) {
                    return $this->buildResponse(
                        $messages,
                        $messages->last()->id,
                        'new_messages',
                        $room->id,
                        $viewerId,
                        $allParticipantIds
                    );
                }
            }

            // Fallback DB periodique pour tolerer toute perte de signal cache.
            if (($now - $lastDbCheck) >= $this->dbCheckInterval) {
                $lastDbCheck = $now;
                $messages = $this->fetchMessages($room->id, $lastId);
                if ($messages->isNotEmpty()) {
                    return $this->buildResponse(
                        $messages,
                        $messages->last()->id,
                        'new_messages',
                        $room->id,
                        $viewerId,
                        $allParticipantIds
                    );
                }
            }

            usleep($this->sleepInterval);
        }

        return $this->buildResponse([], $lastId, 'timeout', $room->id, $viewerId, $allParticipantIds);
    }

    protected function fetchMessages(int $roomId, int $lastId): \Illuminate\Database\Eloquent\Collection
    {
        return InternalChatMessage::where('room_id', $roomId)
            ->where('id', '>', $lastId)
            ->with(['user:id,name,avatar', 'files'])
            ->orderBy('id')
            ->limit((int) config('internal_chat.poll_max_messages', 50))
            ->get();
    }

    /**
     * @param \Illuminate\Support\Collection<int, \Vendor\Chatbot\Models\InternalChatMessage>|array<int, mixed> $messages
     * @param array<int> $participantIds
     */
    protected function buildResponse(
        $messages,
        int $lastId,
        string $status,
        int $roomId,
        int $viewerId,
        array $participantIds
    ): array {
        $messagesArray = $messages instanceof \Illuminate\Support\Collection
            ? $messages->map(fn($m) => $m->toApiArray())->values()->all()
            : $messages;

        $typingUserIds = $this->cache->getTypingUsers($roomId, $participantIds, $viewerId);
        $typingUserNames = empty($typingUserIds)
            ? []
            : User::whereIn('id', $typingUserIds)->pluck('name', 'id')->toArray();
        $readByOthersMaxId = (int) InternalChatRead::where('room_id', $roomId)
            ->where('user_id', '!=', $viewerId)
            ->max('last_read_message_id');

        return [
            'status' => $status,
            'messages' => $messagesArray,
            'last_id' => $lastId,
            'typing_user_ids' => $typingUserIds,
            'typing_user_names' => $typingUserNames,
            'read_by_others_max_id' => $readByOthersMaxId,
            'ts' => now()->toIso8601String(),
        ];
    }
}
