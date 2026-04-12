<?php

namespace Vendor\Chatbot\Controllers;

use App\Http\Controllers\Controller;
use Vendor\Chatbot\Models\InternalChatRoom;
use Vendor\Chatbot\Services\InternalLongPollingService;
use Vendor\Chatbot\Services\InternalChatCacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InternalPollController extends Controller
{
    public function __construct(
        protected InternalLongPollingService $pollingService,
        protected InternalChatCacheService   $cache,
    ) {}

    /**
     * Long-Polling endpoint.
     *
     * Le client envoie :
     *   GET /api/internal-chat/rooms/{roomId}/poll?last_id=42
     *
     * La requête reste ouverte (max 25s) jusqu'à :
     *   - Nouveau(x) message(s) → { status: "new_messages", messages: [...], last_id: 45, typing_user_ids: [] }
     *   - Timeout               → { status: "timeout",      messages: [],   last_id: 42, typing_user_ids: [] }
     *
     * Le client relance immédiatement après chaque réponse.
     */
    public function poll(Request $request, int $roomId): JsonResponse
    {
        $room = InternalChatRoom::forUser(auth()->id())->findOrFail($roomId);

        $lastId         = (int) $request->query('last_id', 0);
        $viewerId       = auth()->id();
        $participantIds = $room->participants()->pluck('user_id')->all();

        // Libérer le buffer de sortie pour ne pas retarder la réponse
        if (ob_get_level()) ob_end_flush();

        // Mettre à jour la présence de l'utilisateur
        $this->cache->setUserOnline($viewerId);

        $result = $this->pollingService->poll($room, $lastId, $viewerId, $participantIds);

        return response()->json($result);
    }

    /**
     * Typing indicator endpoint (non-bloquant, réponse immédiate).
     *
     * POST /api/internal-chat/rooms/{roomId}/typing
     * Body: { is_typing: bool }
     *
     * Réponse : { typing_user_ids: [userId, ...] }
     */
    public function typing(Request $request, int $roomId): JsonResponse
    {
        $room     = InternalChatRoom::forUser(auth()->id())->findOrFail($roomId);
        $userId   = auth()->id();
        $isTyping = (bool) $request->input('is_typing', false);

        // Mettre à jour l'état de frappe de l'utilisateur
        $this->cache->setTyping($roomId, $userId, $isTyping);

        // Récupérer qui est en train d'écrire (hors soi-même)
        $participantIds = $room->participants()->pluck('user_id')->all();
        $typingIds      = $this->cache->getTypingUsers($roomId, $participantIds, $userId);

        // Enrichir avec les noms (optionnel, utile si plusieurs personnes écrivent)
        $typingNames = [];
        if (!empty($typingIds)) {
            $typingNames = \App\Models\User::whereIn('id', $typingIds)
                ->pluck('name', 'id')
                ->toArray();
        }

        return response()->json([
            'typing_user_ids'  => $typingIds,
            'typing_user_names'=> $typingNames,
        ]);
    }
}