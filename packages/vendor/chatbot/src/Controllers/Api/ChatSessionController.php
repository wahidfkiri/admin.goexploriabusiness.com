<?php

namespace Vendor\Chatbot\Controllers\Api;

use App\Http\Controllers\Controller;
use Vendor\Chatbot\Models\ChatRoom;
use Vendor\Chatbot\Models\ChatSession;
use Vendor\Chatbot\Services\ChatService;
use Vendor\Chatbot\Requests\StartSessionRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChatSessionController extends Controller
{
    public function __construct(protected ChatService $chatService) {}

    /**
     * Crée ou retrouve une session visiteur et ouvre une room.
     */
    public function start(StartSessionRequest $request, int $etablissementId): JsonResponse
    {
        // Le token est soit fourni (cookie/localStorage), soit généré
        $token = $request->header('X-Chat-Token') ?? $request->input('token');

        if ($token) {
            $session = ChatSession::where('token', $token)
                ->where('etablissement_id', $etablissementId)
                ->first();
        }

        if (empty($session)) {
            $session = $this->chatService->findOrCreateSession($etablissementId, \Str::random(64), [
                'visitor_name'  => $request->visitor_name,
                'visitor_email' => $request->visitor_email,
                'last_page'     => $request->page_url,
                'extra'         => $request->extra,
            ]);
        }

        $session->touch();

        // Ouvrir (ou récupérer) la room active
        $room = $this->chatService->openRoom($session, $request->validated());

        return response()->json([
            'session_token' => $session->token,
            'room' => [
                'id'     => $room->id,
                'status' => $room->status,
            ],
            'messages' => $room->messages()
                ->with('files', 'agent')
                ->orderBy('id')
                ->get()
                ->map(fn($m) => $m->toApiArray())
                ->values(),
        ]);
    }

    /**
     * Récupère une room par token de session.
     */
    public function getRoom(Request $request, int $etablissementId): JsonResponse
    {
        $token   = $request->header('X-Chat-Token');
        $session = ChatSession::where('token', $token)
            ->where('etablissement_id', $etablissementId)
            ->firstOrFail();

        $room = $session->activeRoom;

        if (!$room) {
            return response()->json(['room' => null], 404);
        }

        return response()->json([
            'room' => [
                'id'     => $room->id,
                'status' => $room->status,
            ],
        ]);
    }

    /**
     * Ferme une room (côté visiteur).
     */
    public function close(Request $request, int $etablissementId, int $roomId): JsonResponse
    {
        $token = $request->header('X-Chat-Token');
        $session = ChatSession::where('token', $token)->firstOrFail();

        $room = ChatRoom::where('id', $roomId)
            ->where('session_id', $session->id)
            ->firstOrFail();

        $this->chatService->closeRoom($room, 'visitor');

        return response()->json(['success' => true]);
    }
}