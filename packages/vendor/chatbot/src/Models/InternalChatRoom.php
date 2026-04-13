<?php

namespace Vendor\Chatbot\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;

class InternalChatRoom extends Model
{
    protected $fillable = [
        'name',
        'type',
        'created_by',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    // ──────────────────────────────────────────────
    // RELATIONS
    // ──────────────────────────────────────────────

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(InternalChatParticipant::class, 'room_id');
    }

    public function users(): BelongsToMany
{
    return $this->belongsToMany(
        User::class, 
        'internal_chat_participants',
        'room_id',    // Clé étrangère de ce modèle dans la table pivot
        'user_id'     // Clé étrangère du modèle rejoint dans la table pivot
    )
    ->withPivot('role', 'joined_at', 'muted_until')
    ->withTimestamps();
}

    public function messages(): HasMany
    {
        return $this->hasMany(InternalChatMessage::class, 'room_id');
    }

    public function lastMessage()
    {
        return $this->hasOne(InternalChatMessage::class, 'room_id')
                    ->whereNull('deleted_at')
                    ->latestOfMany();
    }

    public function reads(): HasMany
    {
        return $this->hasMany(InternalChatRead::class, 'room_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(InternalChatFile::class, 'room_id');
    }

    // ──────────────────────────────────────────────
    // SCOPES
    // ──────────────────────────────────────────────

    /**
     * Rooms accessibles par un utilisateur donné.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->whereHas('participants', fn($q) => $q->where('user_id', $userId));
    }

    public function scopeDirect($query)
    {
        return $query->where('type', 'direct');
    }

    public function scopeGroup($query)
    {
        return $query->where('type', 'group');
    }

    // ──────────────────────────────────────────────
    // BUSINESS METHODS
    // ──────────────────────────────────────────────

    /**
     * Trouve ou crée une room directe entre deux utilisateurs.
     * Garantit l'unicité : une seule room 1:1 entre A et B.
     */
    public static function findOrCreateDirect(int $userIdA, int $userIdB): self
    {
        // Chercher une room direct où les DEUX users sont participants
        $room = self::where('type', 'direct')
            ->whereHas('participants', fn($q) => $q->where('user_id', $userIdA))
            ->whereHas('participants', fn($q) => $q->where('user_id', $userIdB))
            ->first();

        if ($room) {
            return $room;
        }

        $room = self::create([
            'type'       => 'direct',
            'created_by' => $userIdA,
        ]);

        $room->participants()->createMany([
            ['user_id' => $userIdA, 'role' => 'admin'],
            ['user_id' => $userIdB, 'role' => 'member'],
        ]);

        return $room;
    }

    /**
     * Nombre de messages non lus pour un utilisateur dans cette room.
     */
    public function unreadCount(int $userId): int
    {
        $lastReadId = InternalChatRead::where('room_id', $this->id)
            ->where('user_id', $userId)
            ->value('last_read_message_id') ?? 0;

        return $this->messages()
            ->where('id', '>', $lastReadId)
            ->where('user_id', '!=', $userId)
            ->whereNull('deleted_at')
            ->count();
    }

    /**
     * Vérifie qu'un utilisateur est bien participant de cette room.
     */
    public function hasParticipant(int $userId): bool
    {
        return $this->participants()->where('user_id', $userId)->exists();
    }

    /**
     * Retourne le nom d'affichage de la room pour un utilisateur donné.
     * Pour un chat direct, c'est le nom de l'autre participant.
     */
    public function displayName(int $forUserId): string
    {
        if ($this->type === 'group') {
            return $this->name ?? 'Groupe';
        }

        $other = $this->users->firstWhere('id', '!=', $forUserId);
        return $other?->name ?? 'Utilisateur supprimé';
    }

    /**
     * Retourne l'avatar à afficher (autre participant pour direct, null pour groupe).
     */
    public function displayAvatar(int $forUserId): ?string
    {
        if ($this->type === 'group') {
            return null;
        }

        $other = $this->users->firstWhere('id', '!=', $forUserId);
        return $other?->avatar_url;
    }

    /**
     * Met à jour le timestamp du dernier message.
     */
    public function touchLastMessage(): void
    {
        $this->update(['last_message_at' => now()]);
    }

    /**
     * Retourne les données sérialisées pour l'API (sidebar).
     */
    public function toApiArray(int $forUserId, ?int $unreadCount = null): array
    {
        $last = $this->lastMessage;

        return [
            'id'           => $this->id,
            'type'         => $this->type,
            'name'         => $this->displayName($forUserId),
            'avatar'       => $this->displayAvatar($forUserId),
            'unread_count' => $unreadCount ?? $this->unreadCount($forUserId),
            'last_message' => $last ? [
                'body'       => $last->deleted_at ? '[Message supprimé]' : ($last->body ?? ''),
                'type'       => $last->type,
                'created_at' => $last->created_at->toIso8601String(),
                'user_name'  => $last->user?->name,
            ] : null,
            'last_message_at' => $this->last_message_at?->toIso8601String(),
        ];
    }
}
