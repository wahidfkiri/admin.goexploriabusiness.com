<?php
namespace Vendor\Chatbot\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class InternalChatParticipant extends Model
{
    protected $fillable = [
        'room_id',
        'user_id',
        'role',
        'joined_at',
        'muted_until',
    ];

    protected $casts = [
        'joined_at'   => 'datetime',
        'muted_until' => 'datetime',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(InternalChatRoom::class, 'room_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isMuted(): bool
    {
        return $this->muted_until !== null && $this->muted_until->isFuture();
    }
}
