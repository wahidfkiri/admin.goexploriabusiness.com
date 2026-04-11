<?php

namespace Vendor\Chatbot\Services;

use Vendor\Chatbot\Models\ChatRoom;
use Vendor\Chatbot\Models\ChatMessage;
use Vendor\Chatbot\Models\ChatBotFlow;
use Illuminate\Support\Facades\Log;

class BotService
{
    public function __construct(protected ChatCacheService $cacheService) {}

    /**
     * Analyse le message visiteur et répond si un flow correspond.
     */
    public function handleVisitorMessage(ChatRoom $room, ChatMessage $message): void
    {
        if (!config('chatbot.bot_enabled', true)) return;
        if (empty($message->body)) return;

        $flow = $this->findMatchingFlow($room->etablissement_id, $message->body);

        if (!$flow) {
            Log::debug('BotService: no flow matched', ['body' => $message->body]);
            return;
        }

        // Simuler un délai de frappe humain
        $delay = (int) config('chatbot.bot_response_delay', 800);
        if ($delay > 0) {
            usleep($delay * 1000);
        }

        $botMessage = ChatMessage::create([
            'room_id'     => $room->id,
            'sender_type' => 'bot',
            'body'        => $flow->response_text,
            'type'        => 'text',
        ]);

        $this->cacheService->notifyNewMessage($room->id, $botMessage->id);

        Log::info('BotService: responded', [
            'flow_id'    => $flow->id,
            'room_id'    => $room->id,
            'message_id' => $botMessage->id,
        ]);

        // Chaîner le flow suivant si défini
        if ($flow->next_flow_id && $flow->nextFlow) {
            $this->sendFlowResponse($room, $flow->nextFlow);
        }
    }

    protected function findMatchingFlow(int $etablissementId, string $message): ?ChatBotFlow
    {
        $flows = ChatBotFlow::where('etablissement_id', $etablissementId)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        foreach ($flows as $flow) {
            if ($flow->matches($message)) {
                return $flow;
            }
        }

        return null;
    }

    protected function sendFlowResponse(ChatRoom $room, ChatBotFlow $flow): void
    {
        usleep(1200 * 1000); // 1.2s entre les réponses enchaînées

        $botMessage = ChatMessage::create([
            'room_id'     => $room->id,
            'sender_type' => 'bot',
            'body'        => $flow->response_text,
            'type'        => 'text',
        ]);

        $this->cacheService->notifyNewMessage($room->id, $botMessage->id);
    }
}