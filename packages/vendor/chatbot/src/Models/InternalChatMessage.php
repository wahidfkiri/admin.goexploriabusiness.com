<?php
// ============================================================
// app/Models/InternalChatMessage.php
// ============================================================

namespace Vendor\Chatbot\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class InternalChatMessage extends Model
{
    protected $fillable = [
        'room_id',
        'user_id',
        'body',
        'type',
        'metadata',
        'deleted_at',
    ];

    protected $casts = [
        'metadata'   => 'array',
        'deleted_at' => 'datetime',
    ];

    // ──────────────────────────────────────────────
    // RELATIONS
    // ──────────────────────────────────────────────

    public function room(): BelongsTo
    {
        return $this->belongsTo(InternalChatRoom::class, 'room_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(InternalChatFile::class, 'message_id');
    }

    // ──────────────────────────────────────────────
    // SCOPES
    // ──────────────────────────────────────────────

    public function scopeVisible($query)
    {
        return $query->whereNull('deleted_at');
    }

    public function scopeAfter($query, int $lastId)
    {
        return $query->where('id', '>', $lastId);
    }

    // ──────────────────────────────────────────────
    // ACCESSORS
    // ──────────────────────────────────────────────

    public function getBodyDisplayAttribute(): string
    {
        if ($this->deleted_at) {
            return '[Message supprimé]';
        }

        return $this->body ?? '';
    }

    // ──────────────────────────────────────────────
    // SERIALISATION API
    // ──────────────────────────────────────────────

    public function toApiArray(): array
    {
        return [
            'id'          => $this->id,
            'room_id'     => $this->room_id,
            'user_id'     => $this->user_id,
            'user_name'   => $this->user?->name,
            'user_avatar' => $this->user?->avatar_url,
            'body'        => $this->deleted_at ? '[Message supprimé]' : ($this->body ?? ''),
            'type'        => $this->type,
            'metadata'    => $this->metadata,
            'deleted'     => (bool) $this->deleted_at,
            'files'       => $this->relationLoaded('files')
                                ? $this->files->map(fn($f) => $f->toApiArray())->values()
                                : [],
            'created_at'  => $this->created_at->toIso8601String(),
        ];
    }

    // ──────────────────────────────────────────────
    // SOFT DELETE (manual, sans trait pour éviter conflit)
    // ──────────────────────────────────────────────

    public function softDelete(): void
    {
        $this->update([
            'deleted_at' => now(),
            'body'       => null,
        ]);
    }

    public function isDeleted(): bool
    {
        return $this->deleted_at !== null;
    }
}
