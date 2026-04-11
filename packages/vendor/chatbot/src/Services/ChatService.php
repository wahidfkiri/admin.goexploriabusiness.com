<?php

namespace Vendor\Chatbot\Services;

use Vendor\Chatbot\Models\ChatRoom;
use Vendor\Chatbot\Models\ChatMessage;
use Vendor\Chatbot\Models\ChatSession;
use Vendor\Chatbot\Models\ChatAgent;
use Illuminate\Support\Facades\Log;

class ChatService
{
    public function __construct(
        protected BotService $botService,
        protected NotificationService $notificationService,
        protected ChatCacheService $cacheService,
    ) {}

    /**
     * Crée ou retrouve une session visiteur.
     */
    public function findOrCreateSession(
        int $etablissementId,
        string $token,
        array $data = []
    ): ChatSession {
        return ChatSession::firstOrCreate(
            ['token' => $token],
            array_merge([
                'etablissement_id' => $etablissementId,
                'ip_address'       => request()->ip(),
                'user_agent'       => request()->userAgent(),
                'last_page'        => request()->header('Referer'),
                'language'         => request()->header('Accept-Language'),
            ], $data)
        );
    }

    /**
     * Ouvre une nouvelle room pour un visiteur.
     */
    public function openRoom(ChatSession $session, array $data = []): ChatRoom
    {
        // Vérifier s'il y a déjà une room ouverte pour cette session
        $existing = ChatRoom::where('session_id', $session->id)
            ->whereIn('status', ['pending', 'open'])
            ->first();

        if ($existing) {
            return $existing;
        }

        $room = ChatRoom::create([
            'etablissement_id' => $session->etablissement_id,
            'session_id'       => $session->id,
            'status'           => 'pending',
            'subject'          => $data['subject'] ?? null,
            'visitor_name'     => $data['visitor_name'] ?? $session->visitor_name,
            'visitor_email'    => $data['visitor_email'] ?? $session->visitor_email,
            'metadata'         => [
                'ip'        => $session->ip_address,
                'page'      => $session->last_page,
                'referrer'  => $session->referrer,
                'language'  => $session->language,
            ],
        ]);

        // Essayer d'assigner automatiquement un agent disponible
        $agent = $this->findAvailableAgent($session->etablissement_id);
        if ($agent) {
            $this->assignAgent($room, $agent);
        }

        // Message de bienvenue du bot si activé
        if (config('chatbot.bot_enabled')) {
            $this->sendBotWelcome($room);
        }

        // Notifier les agents
        $this->notificationService->notifyNewRoom($room);

        Log::info('ChatRoom created', ['room_id' => $room->id, 'etablissement' => $session->etablissement_id]);

        return $room;
    }

    /**
     * Envoie un message dans une room.
     */
    public function sendMessage(
        ChatRoom $room,
        string $senderType,
        ?int $senderId,
        string $body,
        string $type = 'text',
        array $attachments = []
    ): ChatMessage {
        $message = ChatMessage::create([
            'room_id'     => $room->id,
            'sender_type' => $senderType,
            'sender_id'   => $senderId,
            'body'        => $body,
            'type'        => $type,
            'attachments' => $attachments ?: null,
        ]);

        // Mettre à jour le cache pour débloquer le Long-Polling
        $this->cacheService->notifyNewMessage($room->id, $message->id);

        // Si message visiteur → tenter une réponse bot
        if ($senderType === 'visitor' && config('chatbot.bot_enabled') && !$room->agent_id) {
            $this->botService->handleVisitorMessage($room, $message);
        }

        // Marquer les messages de l'autre partie comme lus
        if ($senderType === 'agent') {
            ChatMessage::where('room_id', $room->id)
                ->where('sender_type', 'visitor')
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }

        Log::info('Message sent', [
            'room_id'     => $room->id,
            'sender_type' => $senderType,
            'message_id'  => $message->id,
        ]);

        return $message;
    }

    /**
     * Ferme une room.
     */
    public function closeRoom(ChatRoom $room, string $closedBy = 'agent'): ChatRoom
    {
        $room->close();

        // Message système de fermeture
        ChatMessage::create([
            'room_id'     => $room->id,
            'sender_type' => 'bot',
            'body'        => $closedBy === 'visitor'
                ? 'Le visiteur a mis fin à la conversation.'
                : 'La conversation a été fermée par un agent.',
            'type'        => 'event',
        ]);

        $this->cacheService->notifyNewMessage($room->id, PHP_INT_MAX);

        Log::info('ChatRoom closed', ['room_id' => $room->id, 'closed_by' => $closedBy]);

        return $room;
    }

    /**
     * Assigne un agent à une room.
     */
    public function assignAgent(ChatRoom $room, ChatAgent $agent): void
    {
        $room->assignTo($agent);

        // Message système d'assignation
        ChatMessage::create([
            'room_id'     => $room->id,
            'sender_type' => 'bot',
            'body'        => "Vous êtes maintenant connecté avec {$agent->display_name}.",
            'type'        => 'event',
        ]);

        $this->cacheService->notifyNewMessage($room->id, $room->messages()->max('id') ?? 0);
    }

    /**
     * Trouve l'agent disponible ayant le moins de rooms actives.
     */
    public function findAvailableAgent(int $etablissementId): ?ChatAgent
    {
        return ChatAgent::where('etablissement_id', $etablissementId)
            ->where('status', 'online')
            ->withCount(['rooms as active_count' => fn($q) => $q->whereIn('status', ['pending', 'open'])])
            ->having('active_count', '<', \DB::raw('max_rooms'))
            ->orderBy('active_count')
            ->first();
    }

    /**
     * Récupère les messages d'une room après un ID donné (pour Long-Polling).
     */
    public function getMessagesAfter(ChatRoom $room, int $lastId, int $limit = 50): \Illuminate\Support\Collection
    {
        return ChatMessage::where('room_id', $room->id)
            ->where('id', '>', $lastId)
            ->with('files', 'agent')
            ->orderBy('id')
            ->limit($limit)
            ->get();
    }

    /**
     * Marque les messages comme lus.
     */
    public function markRoomAsRead(ChatRoom $room, string $readerType): int
    {
        $senderType = $readerType === 'agent' ? 'visitor' : 'agent';

        return ChatMessage::where('room_id', $room->id)
            ->where('sender_type', $senderType)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    protected function sendBotWelcome(ChatRoom $room): void
    {
        $welcomeMsg = config('chatbot.widget.welcome_message', 'Bonjour ! Comment puis-je vous aider ?');

        ChatMessage::create([
            'room_id'     => $room->id,
            'sender_type' => 'bot',
            'body'        => $welcomeMsg,
            'type'        => 'text',
        ]);
    }
}