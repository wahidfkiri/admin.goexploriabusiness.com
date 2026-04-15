<?php

namespace Vendor\Chatbot\Controllers;

use App\Http\Controllers\Controller;
use Vendor\Chatbot\Requests\StartDirectRequest;
use Vendor\Chatbot\Requests\StartGroupRequest;
use Vendor\Chatbot\Requests\SendMessageRequest;
use Vendor\Chatbot\Requests\SendFileRequest;
use Vendor\Chatbot\Requests\UpdateGroupRequest;
use Vendor\Chatbot\Requests\UserSearchRequest;
use Vendor\Chatbot\Models\InternalChatRoom;
use Vendor\Chatbot\Models\InternalChatMessage;
use Vendor\Chatbot\Models\InternalChatParticipant;
use Vendor\Chatbot\Models\InternalChatRead;
use App\Models\User;
use Vendor\Chatbot\Services\InternalChatService;
use Vendor\Chatbot\Services\InternalChatCacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;

class InternalChatController extends Controller
{
    public function __construct(
        protected InternalChatService      $chatService,
        protected InternalChatCacheService $cache,
    ) {}

    // ──────────────────────────────────────────────────────────────
    // VUES WEB
    // ──────────────────────────────────────────────────────────────

    /**
     * Page principale — liste des conversations.
     */
    public function index()
    {
        try {
            Log::info('Accès à la page principale du chat', ['user_id' => auth()->id()]);
            
            $rooms = $this->chatService->getRoomsForUser(auth()->user());
            
            Log::debug('Conversations chargées', ['user_id' => auth()->id(), 'rooms_count' => $rooms->count()]);
            
            return view('chatbot::internal-chat.index', compact('rooms'));
        } catch (Exception $e) {
            Log::error('Erreur lors du chargement de la page principale du chat', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Impossible de charger les conversations.');
        }
    }

    /**
     * Vue d'une conversation.
     */
    public function room(int $roomId)
    {
        try {
            Log::info('Accès à une conversation', ['user_id' => auth()->id(), 'room_id' => $roomId]);
            
            // Vérifier que l'utilisateur est bien participant
            $room = InternalChatRoom::forUser(auth()->id())
                ->with([
                    'users:id,name,email,avatar',
                    'participants' => fn($q) => $q->with('user:id,name,avatar'),
                ])
                ->findOrFail($roomId);
            
            // Toutes les rooms pour la sidebar
            $rooms = $this->chatService->getRoomsForUser(auth()->user());
            $viewerId = auth()->id();
            $unreadByRoom = $this->chatService->getUnreadCountsForRooms($viewerId, $rooms->pluck('id')->all());
            
            // 60 derniers messages (puis remis dans l'ordre chronologique)
            $messages = InternalChatMessage::where('room_id', $roomId)
                ->with(['user:id,name,avatar', 'files'])
                ->orderByDesc('id')
                ->limit(60)
                ->get()
                ->reverse()
                ->values();
            
            $lastMessageId = $messages->last()?->id ?? 0;
            
            // Marquer comme lu à l'ouverture
            $this->chatService->markRead($room, auth()->user());
            
            // IDs participants pour polling typing indicator
            $participantIds = $room->participants->pluck('user_id')->all();
            $readByOthersMaxId = (int) InternalChatRead::where('room_id', $roomId)
                ->where('user_id', '!=', auth()->id())
                ->max('last_read_message_id');
            
            Log::debug('Conversation chargée avec succès', [
                'user_id' => auth()->id(), 
                'room_id' => $roomId,
                'messages_count' => $messages->count()
            ]);
            
            return view('chatbot::internal-chat.room', compact(
                'room', 'rooms', 'messages', 'lastMessageId', 'participantIds', 'readByOthersMaxId', 'unreadByRoom'
            ));
        } catch (ModelNotFoundException $e) {
            Log::warning('Conversation non trouvée', ['user_id' => auth()->id(), 'room_id' => $roomId]);
            return redirect()->route('internal.chat.index')->with('error', 'Conversation non trouvée.');
        } catch (Exception $e) {
            Log::error('Erreur lors du chargement d\'une conversation', [
                'user_id' => auth()->id(),
                'room_id' => $roomId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('internal.chat.index')->with('error', 'Impossible de charger la conversation.');
        }
    }

    /**
     * Page de création d'une nouvelle conversation.
     */
    public function new()
    {
        try {
            Log::info('Accès à la page de création de conversation', ['user_id' => auth()->id()]);
            return view('chatbot::internal-chat.new');
        } catch (Exception $e) {
            Log::error('Erreur lors du chargement de la page de création', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('internal-chat.index')->with('error', 'Impossible de charger la page de création.');
        }
    }

    // ──────────────────────────────────────────────────────────────
    // API — ROOMS
    // ──────────────────────────────────────────────────────────────

    /**
     * API: Crée ou récupère une room directe (1:1).
     *
     * POST /api/internal-chat/direct
     * Body: { user_id: int }
     */
    public function startDirect(StartDirectRequest $request): JsonResponse
    {
        try {
            Log::info('Démarrage d\'une conversation directe', [
                'user_id' => auth()->id(),
                'target_user_id' => $request->user_id
            ]);
            
            $target = User::findOrFail($request->user_id);
            $room   = $this->chatService->findOrCreateDirect(auth()->user(), $target);
            
            Log::info('Conversation directe créée/récupérée avec succès', [
                'user_id' => auth()->id(),
                'target_user_id' => $request->user_id,
                'room_id' => $room->id,
                'created' => $room->wasRecentlyCreated
            ]);
            
            return response()->json([
                'room_id' => $room->id,
                'created' => $room->wasRecentlyCreated,
            ], $room->wasRecentlyCreated ? 201 : 200);
        } catch (ModelNotFoundException $e) {
            Log::warning('Utilisateur cible non trouvé pour conversation directe', [
                'user_id' => auth()->id(),
                'target_user_id' => $request->user_id
            ]);
            
            return response()->json(['error' => 'Utilisateur cible non trouvé.'], 404);
        } catch (Exception $e) {
            Log::error('Erreur lors du démarrage d\'une conversation directe', [
                'user_id' => auth()->id(),
                'target_user_id' => $request->user_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Erreur lors de la création de la conversation.'], 500);
        }
    }

    /**
     * API: Crée un groupe.
     *
     * POST /api/internal-chat/group
     * Body: { name: string, user_ids: int[] }
     */
    public function startGroup(StartGroupRequest $request): JsonResponse
    {
        try {
            Log::info('Création d\'un groupe', [
                'user_id' => auth()->id(),
                'group_name' => $request->name,
                'members_count' => count($request->user_ids)
            ]);
            
            $room = $this->chatService->createGroup(
                auth()->user(),
                $request->name,
                $request->user_ids
            );
            
            Log::info('Groupe créé avec succès', [
                'user_id' => auth()->id(),
                'room_id' => $room->id,
                'group_name' => $request->name
            ]);
            
            return response()->json(['room_id' => $room->id], 201);
        } catch (ValidationException $e) {
            Log::warning('Erreur de validation lors de la création du groupe', [
                'user_id' => auth()->id(),
                'errors' => $e->errors()
            ]);
            
            return response()->json(['error' => 'Données invalides.', 'details' => $e->errors()], 422);
        } catch (Exception $e) {
            Log::error('Erreur lors de la création du groupe', [
                'user_id' => auth()->id(),
                'group_name' => $request->name,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Erreur lors de la création du groupe.'], 500);
        }
    }

    /**
     * API: Mise à jour d'un groupe (nom, ajout/suppression membres).
     *
     * PUT /api/internal-chat/rooms/{roomId}/group
     */
    public function updateGroup(UpdateGroupRequest $request, int $roomId): JsonResponse
    {
        try {
            Log::info('Mise à jour d\'un groupe', [
                'user_id' => auth()->id(),
                'room_id' => $roomId,
                'updates' => $request->only(['name', 'user_ids'])
            ]);
            
            $room = $this->resolveRoomForUser($roomId);
            
            // Seul un admin du groupe peut modifier
            $participant = $room->participants()
                ->where('user_id', auth()->id())
                ->where('role', 'admin')
                ->firstOrFail();
            
            if ($request->has('name')) {
                $room->update(['name' => $request->name]);
                Log::debug('Nom du groupe mis à jour', ['room_id' => $roomId, 'new_name' => $request->name]);
            }
            
            if ($request->has('user_ids')) {
                $this->chatService->addMembers($room, $request->user_ids, auth()->user());
                Log::debug('Membres ajoutés au groupe', ['room_id' => $roomId, 'new_members' => $request->user_ids]);
            }
            
            Log::info('Groupe mis à jour avec succès', ['user_id' => auth()->id(), 'room_id' => $roomId]);
            
            return response()->json(['success' => true]);
        } catch (ModelNotFoundException $e) {
            Log::warning('Groupe non trouvé ou accès non autorisé pour mise à jour', [
                'user_id' => auth()->id(),
                'room_id' => $roomId
            ]);
            
            return response()->json(['error' => 'Groupe non trouvé ou accès non autorisé.'], 404);
        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour du groupe', [
                'user_id' => auth()->id(),
                'room_id' => $roomId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Erreur lors de la mise à jour du groupe.'], 500);
        }
    }

    /**
     * API: Quitter un groupe.
     *
     * DELETE /api/internal-chat/rooms/{roomId}/leave
     */
    public function leaveGroup(int $roomId): JsonResponse
    {
        try {
            Log::info('Tentative de départ d\'un groupe', [
                'user_id' => auth()->id(),
                'room_id' => $roomId
            ]);
            
            $room = $this->resolveRoomForUser($roomId);
            
            if ($room->type !== 'group') {
                Log::warning('Tentative de quitter un chat direct', [
                    'user_id' => auth()->id(),
                    'room_id' => $roomId
                ]);
                
                return response()->json(['error' => 'Impossible de quitter un chat direct.'], 422);
            }
            
            $this->chatService->leaveGroup($room, auth()->user());
            
            Log::info('Départ du groupe effectué avec succès', [
                'user_id' => auth()->id(),
                'room_id' => $roomId
            ]);
            
            return response()->json(['success' => true]);
        } catch (ModelNotFoundException $e) {
            Log::warning('Groupe non trouvé pour départ', [
                'user_id' => auth()->id(),
                'room_id' => $roomId
            ]);
            
            return response()->json(['error' => 'Groupe non trouvé.'], 404);
        } catch (Exception $e) {
            Log::error('Erreur lors du départ du groupe', [
                'user_id' => auth()->id(),
                'room_id' => $roomId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Erreur lors du départ du groupe.'], 500);
        }
    }

    /**
     * API: Retourne la liste des rooms de l'utilisateur (pour rafraîchir la sidebar).
     *
     * GET /api/internal-chat/rooms
     */
    /**
     * API: Supprime une discussion.
     *
     * DELETE /api/internal-chat/rooms/{roomId}
     */
    public function deleteRoom(int $roomId): JsonResponse
    {
        try {
            Log::info('Tentative de suppression de discussion', [
                'user_id' => auth()->id(),
                'room_id' => $roomId,
            ]);

            $room = $this->resolveRoomForUser($roomId);
            $deleted = $this->chatService->deleteRoom($room, auth()->user());

            if (!$deleted) {
                return response()->json(['error' => 'Suppression non autorisee.'], 403);
            }

            Log::info('Discussion supprimee avec succes', [
                'user_id' => auth()->id(),
                'room_id' => $roomId,
            ]);

            return response()->json(['success' => true]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Discussion non trouvee.'], 404);
        } catch (Exception $e) {
            Log::error('Erreur lors de la suppression de discussion', [
                'user_id' => auth()->id(),
                'room_id' => $roomId,
                'error' => $e->getMessage(),
            ]);

            return response()->json(['error' => 'Erreur lors de la suppression.'], 500);
        }
    }
    public function roomsList(): JsonResponse
    {
        try {
            Log::debug('Récupération de la liste des conversations', ['user_id' => auth()->id()]);
            
            $viewerId = auth()->id();
            $rooms = $this->chatService->getRoomsForUser(auth()->user());
            $unreadByRoom = $this->chatService->getUnreadCountsForRooms($viewerId, $rooms->pluck('id')->all());
            $totalUnread = array_sum($unreadByRoom);
            
            Log::debug('Liste des conversations récupérée', [
                'user_id' => auth()->id(),
                'rooms_count' => $rooms->count(),
                'total_unread' => $totalUnread
            ]);
            
            return response()->json([
                'rooms'        => $rooms->map(
                    fn($r) => $r->toApiArray($viewerId, $unreadByRoom[$r->id] ?? 0)
                )->values(),
                'total_unread' => $totalUnread,
            ]);
        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération de la liste des conversations', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Erreur lors de la récupération des conversations.'], 500);
        }
    }

    // ──────────────────────────────────────────────────────────────
    // API — MESSAGES
    // ──────────────────────────────────────────────────────────────

    /**
     * API: Envoie un message texte.
     *
     * POST /api/internal-chat/rooms/{roomId}/messages
     * Body: { body: string }
     */
    public function sendMessage(SendMessageRequest $request, int $roomId): JsonResponse
    {
        try {
            Log::info('Envoi d\'un message', [
                'user_id' => auth()->id(),
                'room_id' => $roomId,
                'message_length' => strlen($request->body)
            ]);
            
            $room    = $this->resolveRoomForUser($roomId);
            $message = $this->chatService->sendMessage($room, auth()->user(), $request->body);
            $message->load(['user:id,name,avatar', 'files']);
            
            Log::info('Message envoyé avec succès', [
                'user_id' => auth()->id(),
                'room_id' => $roomId,
                'message_id' => $message->id
            ]);
            
            return response()->json(['message' => $message->toApiArray()], 201);
        } catch (ModelNotFoundException $e) {
            Log::warning('Conversation non trouvée pour l\'envoi de message', [
                'user_id' => auth()->id(),
                'room_id' => $roomId
            ]);
            
            return response()->json(['error' => 'Conversation non trouvée.'], 404);
        } catch (Exception $e) {
            Log::error('Erreur lors de l\'envoi du message', [
                'user_id' => auth()->id(),
                'room_id' => $roomId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Erreur lors de l\'envoi du message.'], 500);
        }
    }

    /**
     * API: Envoie un fichier.
     *
     * POST /api/internal-chat/rooms/{roomId}/files
     * Multipart: file
     */
    public function sendFile(SendFileRequest $request, int $roomId): JsonResponse
{
    try {
        $file = $request->file('file');
        
        // Add validation before processing
        if (!$file || !$file->isValid()) {
            $errorCode = $file ? $file->getError() : 'no_file';
            $errorMessages = [
                UPLOAD_ERR_INI_SIZE => 'Le fichier dépasse la taille maximale autorisée par le serveur.',
                UPLOAD_ERR_FORM_SIZE => 'Le fichier dépasse la taille maximale autorisée par le formulaire.',
                UPLOAD_ERR_PARTIAL => 'Le fichier n\'a été que partiellement téléchargé.',
                UPLOAD_ERR_NO_FILE => 'Aucun fichier n\'a été téléchargé.',
                UPLOAD_ERR_NO_TMP_DIR => 'Dossier temporaire manquant sur le serveur.',
                UPLOAD_ERR_CANT_WRITE => 'Échec de l\'écriture du fichier sur le disque.',
                UPLOAD_ERR_EXTENSION => 'Une extension PHP a arrêté l\'envoi du fichier.',
            ];
            
            $errorMsg = $errorMessages[$errorCode] ?? 'Erreur inconnue lors du téléchargement.';
            Log::error('File upload error', ['user_id' => auth()->id(), 'error_code' => $errorCode]);
            return response()->json(['error' => $errorMsg], 422);
        }
        
        Log::info('Envoi d\'un fichier', [
            'user_id' => auth()->id(),
            'room_id' => $roomId,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'tmp_path' => $file->getRealPath(),
            'mime_type' => $file->getMimeType()
        ]);
        
        $room = $this->resolveRoomForUser($roomId);
        $message = $this->chatService->sendFile($room, auth()->user(), $file);
        
        Log::info('Fichier envoyé avec succès', [
            'user_id' => auth()->id(),
            'room_id' => $roomId,
            'message_id' => $message->id
        ]);
        
        return response()->json(['message' => $message->toApiArray()], 201);
        
    } catch (ModelNotFoundException $e) {
        Log::warning('Conversation non trouvée pour l\'envoi de fichier', [
            'user_id' => auth()->id(),
            'room_id' => $roomId
        ]);
        return response()->json(['error' => 'Conversation non trouvée.'], 404);
    } catch (Exception $e) {
        Log::error('Erreur lors de l\'envoi du fichier', [
            'user_id' => auth()->id(),
            'room_id' => $roomId,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json(['error' => 'Erreur lors de l\'envoi du fichier: ' . $e->getMessage()], 500);
    }
}

    /**
     * API: Supprime un message (soft delete, auteur uniquement).
     *
     * DELETE /api/internal-chat/rooms/{roomId}/messages/{messageId}
     */
    public function deleteMessage(int $roomId, int $messageId): JsonResponse
    {
        try {
            Log::info('Tentative de suppression de message', [
                'user_id' => auth()->id(),
                'room_id' => $roomId,
                'message_id' => $messageId
            ]);
            
            $room    = $this->resolveRoomForUser($roomId);
            $message = InternalChatMessage::where('room_id', $room->id)->findOrFail($messageId);
            
            $deleted = $this->chatService->deleteMessage($message, auth()->user());
            
            if (!$deleted) {
                Log::warning('Tentative de suppression non autorisée', [
                    'user_id' => auth()->id(),
                    'room_id' => $roomId,
                    'message_id' => $messageId,
                    'message_author_id' => $message->user_id
                ]);
                
                return response()->json(['error' => 'Vous ne pouvez pas supprimer ce message.'], 403);
            }
            
            Log::info('Message supprimé avec succès', [
                'user_id' => auth()->id(),
                'room_id' => $roomId,
                'message_id' => $messageId
            ]);
            
            return response()->json(['success' => true]);
        } catch (ModelNotFoundException $e) {
            Log::warning('Message ou conversation non trouvé pour suppression', [
                'user_id' => auth()->id(),
                'room_id' => $roomId,
                'message_id' => $messageId
            ]);
            
            return response()->json(['error' => 'Message ou conversation non trouvé.'], 404);
        } catch (Exception $e) {
            Log::error('Erreur lors de la suppression du message', [
                'user_id' => auth()->id(),
                'room_id' => $roomId,
                'message_id' => $messageId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Erreur lors de la suppression du message.'], 500);
        }
    }

    /**
     * API: Récupère les messages plus anciens (infinite scroll vers le haut).
     *
     * GET /api/internal-chat/rooms/{roomId}/messages?before_id={id}&limit={n}
     */
    public function loadMessages(Request $request, int $roomId): JsonResponse
    {
        try {
            $beforeId = (int) $request->query('before_id', PHP_INT_MAX);
            $limit    = min((int) $request->query('limit', 30), 100);
            
            Log::debug('Chargement de messages plus anciens', [
                'user_id' => auth()->id(),
                'room_id' => $roomId,
                'before_id' => $beforeId,
                'limit' => $limit
            ]);
            
            $room     = $this->resolveRoomForUser($roomId);
            
            $messages = InternalChatMessage::where('room_id', $room->id)
                ->where('id', '<', $beforeId)
                ->with(['user:id,name,avatar', 'files'])
                ->orderByDesc('id')
                ->limit($limit)
                ->get()
                ->reverse()
                ->values();
            
            Log::debug('Messages plus anciens chargés', [
                'user_id' => auth()->id(),
                'room_id' => $roomId,
                'messages_count' => $messages->count(),
                'has_more' => $messages->count() === $limit
            ]);
            
            return response()->json([
                'messages' => $messages->map(fn($m) => $m->toApiArray())->values(),
                'has_more' => $messages->count() === $limit,
            ]);
        } catch (ModelNotFoundException $e) {
            Log::warning('Conversation non trouvée pour chargement des messages', [
                'user_id' => auth()->id(),
                'room_id' => $roomId
            ]);
            
            return response()->json(['error' => 'Conversation non trouvée.'], 404);
        } catch (Exception $e) {
            Log::error('Erreur lors du chargement des messages plus anciens', [
                'user_id' => auth()->id(),
                'room_id' => $roomId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Erreur lors du chargement des messages.'], 500);
        }
    }

    // ──────────────────────────────────────────────────────────────
    // API — LECTURE & PRÉSENCE
    // ──────────────────────────────────────────────────────────────

    /**
     * API: Marque les messages d'une room comme lus.
     *
     * POST /api/internal-chat/rooms/{roomId}/read
     */
    public function markRead(int $roomId): JsonResponse
    {
        try {
            Log::debug('Marquage des messages comme lus', [
                'user_id' => auth()->id(),
                'room_id' => $roomId
            ]);
            
            $room = $this->resolveRoomForUser($roomId);
            $this->chatService->markRead($room, auth()->user());
            
            Log::debug('Messages marqués comme lus avec succès', [
                'user_id' => auth()->id(),
                'room_id' => $roomId
            ]);
            
            return response()->json(['success' => true]);
        } catch (ModelNotFoundException $e) {
            Log::warning('Conversation non trouvée pour marquage comme lu', [
                'user_id' => auth()->id(),
                'room_id' => $roomId
            ]);
            
            return response()->json(['error' => 'Conversation non trouvée.'], 404);
        } catch (Exception $e) {
            Log::error('Erreur lors du marquage des messages comme lus', [
                'user_id' => auth()->id(),
                'room_id' => $roomId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Erreur lors du marquage des messages.'], 500);
        }
    }

    /**
     * API: Heartbeat de présence — maintient l'utilisateur "en ligne".
     *
     * POST /api/internal-chat/heartbeat
     */
    public function heartbeat(): JsonResponse
    {
        try {
            $user = auth()->user();
            
            Log::debug('Heartbeat reçu', ['user_id' => $user->id]);
            
            $this->cache->setUserOnline($user->id);
            $totalUnread = $this->chatService->totalUnreadCount($user);
            
            Log::debug('Heartbeat traité avec succès', [
                'user_id' => $user->id,
                'total_unread' => $totalUnread
            ]);
            
            return response()->json([
                'user_id'      => $user->id,
                'total_unread' => $totalUnread,
                'ts'           => now()->toIso8601String(),
            ]);
        } catch (Exception $e) {
            Log::error('Erreur lors du traitement du heartbeat', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Erreur lors du heartbeat.'], 500);
        }
    }

    // ──────────────────────────────────────────────────────────────
    // API — RECHERCHE UTILISATEURS
    // ──────────────────────────────────────────────────────────────

    /**
     * API: Recherche d'utilisateurs pour démarrer une conversation.
     *
     * GET /api/internal-chat/users/search?q={query}&exclude[]=id1
     */
    public function searchUsers(UserSearchRequest $request): JsonResponse
{
    try {
        $q       = $request->query('q');
        $exclude = array_merge(
            (array) $request->query('exclude', []),
            [auth()->id()]    // toujours exclure soi-même
        );
        
        Log::debug('Recherche d\'utilisateurs', [
            'user_id' => auth()->id(),
            'query' => $q,
            'exclude' => $exclude
        ]);
        
        $users = User::where('is_active', true)
            ->whereNotIn('id', $exclude)
            ->whereDoesntHave('etablissement') // 🔥 Exclut les utilisateurs qui ont déjà un établissement
            ->where(fn($query) =>
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}")
            )
            ->select('id', 'name', 'email', 'avatar', 'position', 'department')
            ->orderBy('name')
            ->limit(15)
            ->get()
            ->map(fn($u) => [
                'id'         => $u->id,
                'name'       => $u->name,
                'email'      => $u->email,
                'avatar_url' => $u->avatar_url,
                'position'   => $u->position,
                'department' => $u->department,
                'online'     => $this->cache->isUserOnline($u->id),
            ]);
        
        Log::debug('Recherche d\'utilisateurs terminée', [
            'user_id' => auth()->id(),
            'query' => $q,
            'results_count' => $users->count()
        ]);
        
        return response()->json(['users' => $users]);
    } catch (Exception $e) {
        Log::error('Erreur lors de la recherche d\'utilisateurs', [
            'user_id' => auth()->id(),
            'query' => $request->query('q'),
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json(['error' => 'Erreur lors de la recherche d\'utilisateurs.'], 500);
    }
}

    // ──────────────────────────────────────────────────────────────
    // HELPER
    // ──────────────────────────────────────────────────────────────

    /**
     * Résout une room en vérifiant que l'utilisateur authentifié en est participant.
     * Lève une 404 si la room n'existe pas ou si l'accès est refusé.
     */
    protected function resolveRoomForUser(int $roomId): InternalChatRoom
    {
        try {
            Log::debug('Résolution de la conversation pour l\'utilisateur', [
                'user_id' => auth()->id(),
                'room_id' => $roomId
            ]);
            
            $room = InternalChatRoom::forUser(auth()->id())->findOrFail($roomId);
            
            Log::debug('Conversation résolue avec succès', [
                'user_id' => auth()->id(),
                'room_id' => $roomId,
                'room_type' => $room->type
            ]);
            
            return $room;
        } catch (ModelNotFoundException $e) {
            Log::warning('Conversation non trouvée ou accès refusé', [
                'user_id' => auth()->id(),
                'room_id' => $roomId
            ]);
            throw $e;
        }
    }
}


