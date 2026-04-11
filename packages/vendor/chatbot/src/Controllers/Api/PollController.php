<?php

namespace Vendor\Chatbot\Controllers\Api;

use App\Http\Controllers\Controller;
use Vendor\Chatbot\Models\ChatRoom;
use Vendor\Chatbot\Models\ChatSession;
use Vendor\Chatbot\Services\LongPollingService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PollController extends Controller
{
    public function __construct(protected LongPollingService $pollingService) {}

    /**
     * Long-Polling endpoint.
     *
     * Le client envoie GET /api/chatbot/{etablissementId}/rooms/{roomId}/poll?last_id=X
     * Cette requête reste ouverte jusqu'à ce qu'un nouveau message arrive ou timeout.
     *
     * Headers attendus:
     *   X-Chat-Token: <session_token>  (visiteur)
     *   Authorization: Bearer <token>  (agent)
     *
     * Réponse:
     * {
     *   "status": "new_messages" | "timeout" | "room_closed",
     *   "messages": [...],
     *   "last_id": 42,
     *   "ts": "2024-01-01T00:00:00Z"
     * }
     */
    public function poll(Request $request, int $etablissementId, int $roomId): JsonResponse
    {
        $lastId  = (int) $request->query('last_id', 0);
        $viewer  = $request->header('X-Chat-Token') ? 'visitor' : 'agent';

        // Résolution de la room selon le rôle
        $room = $this->resolveRoom($request, $etablissementId, $roomId, $viewer);

        if (!$room) {
            return response()->json(['error' => 'Room introuvable ou accès refusé.'], 404);
        }

        if ($room->isClosed()) {
            return response()->json([
                'status'   => 'room_closed',
                'messages' => [],
                'last_id'  => $lastId,
                'ts'       => now()->toIso8601String(),
            ]);
        }

        // Désactiver le buffer de sortie pour libérer la connexion immédiatement
        if (ob_get_level()) ob_end_flush();

        // === LONG POLLING ===
        $result = $this->pollingService->poll($room, $lastId, $viewer);

        return response()->json($result);
    }

    /**
     * Endpoint de typing indicator (courte durée, non bloquant).
     */
    public function typing(Request $request, int $etablissementId, int $roomId): JsonResponse
    {
        $viewer   = $request->header('X-Chat-Token') ? 'visitor' : 'agent';
        $isTyping = (bool) $request->input('is_typing', false);

        // Stocker en cache pour 5s
        $cacheKey = "chatbot_typing_{$roomId}_{$viewer}";
        if ($isTyping) {
            \Cache::put($cacheKey, true, 5);
        } else {
            \Cache::forget($cacheKey);
        }

        // Renvoyer aussi l'état de frappe de l'autre partie
        $otherRole    = $viewer === 'visitor' ? 'agent' : 'visitor';
        $otherTyping  = (bool) \Cache::get("chatbot_typing_{$roomId}_{$otherRole}", false);

        return response()->json(['other_typing' => $otherTyping]);
    }

    protected function resolveRoom(Request $request, int $etablissementId, int $roomId, string $viewer): ?ChatRoom
    {
        if ($viewer === 'visitor') {
            $token   = $request->header('X-Chat-Token');
            $session = ChatSession::where('token', $token)
                ->where('etablissement_id', $etablissementId)
                ->first();

            if (!$session) return null;

            return ChatRoom::where('id', $roomId)
                ->where('session_id', $session->id)
                ->first();
        }

        // Agent: la room doit appartenir à l'établissement
        return ChatRoom::where('id', $roomId)
            ->where('etablissement_id', $etablissementId)
            ->first();
    }
}