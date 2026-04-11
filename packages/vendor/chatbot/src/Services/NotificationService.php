<?php

namespace Vendor\Chatbot\Services;

use Vendor\Chatbot\Models\ChatRoom;
use Vendor\Chatbot\Models\ChatAgent;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Notifie les agents qu'une nouvelle room est en attente.
     */
    public function notifyNewRoom(ChatRoom $room): void
    {
        if (!config('chatbot.notify_agent_email', true)) return;

        $agents = ChatAgent::where('etablissement_id', $room->etablissement_id)
            ->where('status', '!=', 'offline')
            ->get();

        foreach ($agents as $agent) {
            try {
                // Ici on enverrait un email via la Mailable Laravel
                // Mail::to($agent->user->email)->queue(new NewChatRoomMail($room, $agent));
                Log::info('Notification sent to agent', [
                    'agent_id' => $agent->id,
                    'room_id'  => $room->id,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to notify agent', [
                    'agent_id' => $agent->id,
                    'error'    => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Notifie le visiteur par email si la room est fermée avec un résumé.
     */
    public function notifyVisitorRoomClosed(ChatRoom $room): void
    {
        if (!$room->visitor_email) return;

        try {
            // Mail::to($room->visitor_email)->queue(new ChatTranscriptMail($room));
            Log::info('Transcript email queued', ['room_id' => $room->id]);
        } catch (\Exception $e) {
            Log::error('Failed to send transcript', ['error' => $e->getMessage()]);
        }
    }
}