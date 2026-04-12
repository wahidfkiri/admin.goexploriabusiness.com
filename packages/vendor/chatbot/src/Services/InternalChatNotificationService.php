<?php

namespace Vendor\Chatbot\Services;

use Vendor\Chatbot\Models\InternalChatRoom;
use Vendor\Chatbot\Models\InternalChatMessage;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

/**
 * Gère les notifications liées au chat interne :
 * - Notifications in-app (Laravel Notifications)
 * - Email si l'utilisateur est hors ligne (extensible)
 */
class InternalChatNotificationService
{
    public function __construct(
        protected InternalChatCacheService $cache,
    ) {}

    /**
     * Notifie les participants d'un nouveau message.
     * Ne notifie pas l'expéditeur, ni les utilisateurs online (ils reçoivent via polling).
     */
    public function notifyNewMessage(
        InternalChatRoom    $room,
        InternalChatMessage $message,
        User                $sender
    ): void {
        $participantIds = $room->participants()
            ->where('user_id', '!=', $sender->id)
            ->pluck('user_id');

        foreach ($participantIds as $userId) {
            try {
                // Si l'utilisateur est offline → notification différée (email, push…)
                if (!$this->cache->isUserOnline($userId)) {
                    $this->sendOfflineNotification($userId, $room, $message, $sender);
                }
                // Si online → le long-polling gère la réception, pas besoin de notifier
            } catch (\Throwable $e) {
                Log::error('InternalChat notification error', [
                    'user_id' => $userId,
                    'error'   => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Notification pour un utilisateur hors ligne.
     * À compléter avec un vrai Mailable/Notification Laravel selon votre setup.
     */
    protected function sendOfflineNotification(
        int                 $userId,
        InternalChatRoom    $room,
        InternalChatMessage $message,
        User                $sender
    ): void {
        $user = User::find($userId);
        if (!$user) return;

        // Exemple : notification Laravel in-app
        // $user->notify(new \App\Notifications\NewInternalChatMessageNotification($room, $message, $sender));

        // Exemple : email
        // Mail::to($user->email)->queue(new \App\Mail\NewInternalChatMessageMail($room, $message, $sender));

        Log::info('InternalChat: offline notification queued', [
            'to_user' => $userId,
            'room_id' => $room->id,
            'sender'  => $sender->id,
        ]);
    }
}