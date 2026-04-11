<?php

namespace Vendor\Chatbot\Controllers\Api;

use App\Http\Controllers\Controller;
use Vendor\Chatbot\Models\ChatAgent;
use Vendor\Chatbot\Models\ChatRoom;
use Vendor\Chatbot\Services\ChatService;
use Vendor\Chatbot\Requests\UpdateAgentRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AgentController extends Controller
{
    public function __construct(protected ChatService $chatService) {}

    /**
     * Met à jour le statut de présence de l'agent.
     */
    public function updateStatus(UpdateAgentRequest $request, int $etablissementId): JsonResponse
    {
        $agent = ChatAgent::where('user_id', auth()->id())
            ->where('etablissement_id', $etablissementId)
            ->firstOrFail();

        $agent->updatePresence($request->status);

        if (isset($request->max_rooms)) {
            $agent->update(['max_rooms' => $request->max_rooms]);
        }

        return response()->json([
            'agent'  => [
                'id'           => $agent->id,
                'status'       => $agent->status,
                'max_rooms'    => $agent->max_rooms,
                'active_rooms' => $agent->activeRooms()->count(),
            ],
        ]);
    }

    /**
     * Assigne une room à l'agent authentifié.
     */
    public function assignRoom(Request $request, int $etablissementId, int $roomId): JsonResponse
    {
        $agent = ChatAgent::where('user_id', auth()->id())
            ->where('etablissement_id', $etablissementId)
            ->firstOrFail();

        $room = ChatRoom::where('id', $roomId)
            ->where('etablissement_id', $etablissementId)
            ->firstOrFail();

        if (!$agent->canAcceptRoom()) {
            return response()->json(['error' => 'Nombre maximum de rooms atteint.'], 422);
        }

        $this->chatService->assignAgent($room, $agent);

        return response()->json(['success' => true, 'room_id' => $room->id]);
    }

    /**
     * Liste des rooms actives de l'agent.
     */
    public function myRooms(Request $request, int $etablissementId): JsonResponse
    {
        $agent = ChatAgent::where('user_id', auth()->id())
            ->where('etablissement_id', $etablissementId)
            ->firstOrFail();

        $rooms = ChatRoom::where('agent_id', $agent->id)
            ->whereIn('status', ['open', 'pending'])
            ->with(['lastMessage', 'session'])
            ->orderByDesc('updated_at')
            ->get()
            ->map(fn($room) => [
                'id'           => $room->id,
                'status'       => $room->status,
                'visitor_name' => $room->visitor_name,
                'unread_count' => $room->unreadCount('agent'),
                'last_message' => $room->lastMessage?->toApiArray(),
                'opened_at'    => $room->opened_at?->toIso8601String(),
            ]);

        return response()->json(['rooms' => $rooms]);
    }

    /**
     * Heartbeat agent — maintient le statut "online" via polling régulier.
     */
    public function heartbeat(Request $request, int $etablissementId): JsonResponse
    {
        $agent = ChatAgent::where('user_id', auth()->id())
            ->where('etablissement_id', $etablissementId)
            ->firstOrFail();

        $agent->last_seen_at = now();
        $agent->save();

        \Cache::put("chatbot_agent_online_{$agent->id}", true, 30);

        // Retourner les rooms en attente non assignées
        $pendingCount = ChatRoom::where('etablissement_id', $etablissementId)
            ->where('status', 'pending')
            ->whereNull('agent_id')
            ->count();

        return response()->json([
            'agent_id'      => $agent->id,
            'status'        => $agent->status,
            'pending_rooms' => $pendingCount,
            'ts'            => now()->toIso8601String(),
        ]);
    }
}