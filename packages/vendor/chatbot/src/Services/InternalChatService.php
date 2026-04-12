<?php

namespace Vendor\Chatbot\Services;

use Vendor\Chatbot\Models\InternalChatRoom;
use Vendor\Chatbot\Models\InternalChatMessage;
use Vendor\Chatbot\Models\InternalChatRead;
use Vendor\Chatbot\Models\InternalChatFile;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InternalChatService
{
    public function __construct(
        protected InternalChatCacheService $cache,
        protected InternalChatNotificationService $notifier,
    ) {}

    // ──────────────────────────────────────────────────────────────
    // ROOMS
    // ──────────────────────────────────────────────────────────────

    /**
     * Crée une room directe (1:1) ou récupère l'existante.
     */
    public function findOrCreateDirect(User $initiator, User $target): InternalChatRoom
    {
        $room = InternalChatRoom::findOrCreateDirect($initiator->id, $target->id);

        Log::info('InternalChat: direct room', [
            'room_id' => $room->id,
            'users'   => [$initiator->id, $target->id],
        ]);

        return $room;
    }

    /**
     * Crée une room de groupe avec les membres fournis.
     */
    public function createGroup(User $creator, string $name, array $memberIds): InternalChatRoom
    {
        return DB::transaction(function () use ($creator, $name, $memberIds) {
            $room = InternalChatRoom::create([
                'name'       => $name,
                'type'       => 'group',
                'created_by' => $creator->id,
            ]);

            // Ajouter le créateur (admin) + membres
            $participants = collect($memberIds)
                ->unique()
                ->reject(fn($id) => (int) $id === $creator->id)
                ->map(fn($id) => ['user_id' => (int) $id, 'role' => 'member', 'joined_at' => now()])
                ->prepend(['user_id' => $creator->id, 'role' => 'admin', 'joined_at' => now()])
                ->all();

            $room->participants()->createMany($participants);

            // Message système de création
            $this->createSystemMessage($room, "{$creator->name} a créé le groupe « {$name} ».");

            Log::info('InternalChat: group created', [
                'room_id'  => $room->id,
                'name'     => $name,
                'creator'  => $creator->id,
                'members'  => count($participants),
            ]);

            return $room;
        });
    }

    /**
     * Ajoute des membres à un groupe (réservé aux admins du groupe).
     */
    public function addMembers(InternalChatRoom $room, array $userIds, User $addedBy): void
    {
        $existing = $room->participants()->pluck('user_id')->toArray();
        $toAdd    = collect($userIds)->unique()->diff($existing)->values();

        foreach ($toAdd as $userId) {
            $room->participants()->create(['user_id' => $userId, 'role' => 'member', 'joined_at' => now()]);
            $user = User::find($userId);
            if ($user) {
                $this->createSystemMessage($room, "{$addedBy->name} a ajouté {$user->name}.");
            }
        }
    }

    /**
     * Supprime un participant d'un groupe.
     */
    public function removeMember(InternalChatRoom $room, int $userId, User $removedBy): void
    {
        $room->participants()->where('user_id', $userId)->delete();
        $user = User::find($userId);
        if ($user) {
            $this->createSystemMessage($room, "{$removedBy->name} a retiré {$user->name} du groupe.");
        }
    }

    /**
     * Quitter un groupe.
     */
    public function leaveGroup(InternalChatRoom $room, User $user): void
    {
        $room->participants()->where('user_id', $user->id)->delete();
        $this->createSystemMessage($room, "{$user->name} a quitté le groupe.");
    }

    // ──────────────────────────────────────────────────────────────
    // MESSAGES
    // ──────────────────────────────────────────────────────────────

    /**
     * Envoie un message texte dans une room.
     */
    public function sendMessage(InternalChatRoom $room, User $sender, string $body): InternalChatMessage
    {
        $message = InternalChatMessage::create([
            'room_id' => $room->id,
            'user_id' => $sender->id,
            'body'    => $body,
            'type'    => 'text',
        ]);

        $room->touchLastMessage();

        // Débloquer le long-polling pour tous les participants
        $this->cache->notifyNewMessage($room->id, $message->id);

        // Notifications push/email pour les autres participants
        $this->notifier->notifyNewMessage($room, $message, $sender);

        Log::info('InternalChat: message sent', [
            'room_id'    => $room->id,
            'message_id' => $message->id,
            'sender'     => $sender->id,
        ]);

        return $message;
    }

    /**
     * Envoie un fichier attaché et crée le message associé.
     */
    public function sendFile(InternalChatRoom $room, User $sender, UploadedFile $file): InternalChatMessage
    {
        $disk     = config('internal_chat.storage_disk', 'public');
        $basePath = config('internal_chat.storage_path', 'internal-chat/files');
        $ext      = strtolower($file->getClientOriginalExtension());
        $filename = Str::uuid() . '.' . $ext;
        $subPath  = $basePath . '/' . now()->format('Y/m');
        $isImage  = str_starts_with($file->getMimeType(), 'image/');

        $path = $file->storeAs($subPath, $filename, $disk);

        $width = $height = null;
        if ($isImage) {
            try { [$width, $height] = getimagesize($file->getRealPath()); } catch (\Throwable) {}
        }

        return DB::transaction(function () use ($room, $sender, $file, $path, $filename, $ext, $isImage, $width, $height, $disk) {
            $message = InternalChatMessage::create([
                'room_id'  => $room->id,
                'user_id'  => $sender->id,
                'body'     => null,
                'type'     => 'file',
                'metadata' => ['original_name' => $file->getClientOriginalName()],
            ]);

            InternalChatFile::create([
                'message_id'    => $message->id,
                'room_id'       => $room->id,
                'uploaded_by'   => $sender->id,
                'filename'      => $filename,
                'original_name' => $file->getClientOriginalName(),
                'path'          => $path,
                'mime_type'     => $file->getMimeType(),
                'extension'     => $ext,
                'size'          => $file->getSize(),
                'width'         => $width,
                'height'        => $height,
                'is_image'      => $isImage,
            ]);

            $room->touchLastMessage();
            $this->cache->notifyNewMessage($room->id, $message->id);

            return $message->load('files');
        });
    }

    /**
     * Supprime un message (soft delete — l'auteur ou un admin uniquement).
     */
    public function deleteMessage(InternalChatMessage $message, User $by): bool
    {
        // Seul l'auteur peut supprimer son message
        if ($message->user_id !== $by->id) {
            return false;
        }

        $message->softDelete();

        // Notifier le polling que quelque chose a changé
        $this->cache->notifyNewMessage($message->room_id, $message->id);

        return true;
    }

    /**
     * Message système (type 'event') — non affiché comme un message utilisateur.
     */
    public function createSystemMessage(InternalChatRoom $room, string $text): InternalChatMessage
    {
        $msg = InternalChatMessage::create([
            'room_id' => $room->id,
            'user_id' => $room->created_by,   // attribué au créateur de la room
            'body'    => $text,
            'type'    => 'event',
        ]);

        $this->cache->notifyNewMessage($room->id, $msg->id);

        return $msg;
    }

    // ──────────────────────────────────────────────────────────────
    // LECTURE
    // ──────────────────────────────────────────────────────────────

    /**
     * Marque tous les messages visibles d'une room comme lus pour un utilisateur.
     */
    public function markRead(InternalChatRoom $room, User $user): void
    {
        $lastId = InternalChatMessage::where('room_id', $room->id)
            ->whereNull('deleted_at')
            ->max('id') ?? 0;

        if ($lastId === 0) return;

        InternalChatRead::updateOrCreate(
            ['room_id' => $room->id, 'user_id' => $user->id],
            ['last_read_message_id' => $lastId, 'read_at' => now()]
        );
    }

    // ──────────────────────────────────────────────────────────────
    // POLLING HELPER
    // ──────────────────────────────────────────────────────────────

    /**
     * Récupère les messages après un ID donné (utilisé par InternalPollController).
     */
    public function getMessagesAfter(InternalChatRoom $room, int $lastId, int $limit = 50): Collection
    {
        return InternalChatMessage::where('room_id', $room->id)
            ->where('id', '>', $lastId)
            ->with(['user', 'files'])
            ->orderBy('id')
            ->limit($limit)
            ->get();
    }

    // ──────────────────────────────────────────────────────────────
    // ROOMS LIST
    // ──────────────────────────────────────────────────────────────

    /**
     * Retourne les rooms d'un utilisateur, triées par dernier message.
     */
    public function getRoomsForUser(User $user): Collection
    {
        return InternalChatRoom::forUser($user->id)
            ->with([
                'users:id,name,email,avatar',
                'lastMessage.user:id,name',
                'participants' => fn($q) => $q->where('user_id', $user->id),
            ])
            ->orderByDesc('last_message_at')
            ->get();
    }

    /**
     * Total de messages non lus toutes rooms confondues pour un utilisateur.
     */
    public function totalUnreadCount(User $user): int
    {
        return $this->getRoomsForUser($user)->sum(fn($room) => $room->unreadCount($user->id));
    }
}