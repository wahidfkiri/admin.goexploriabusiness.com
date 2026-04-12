<?php

namespace Vendor\Chatbot\Traits;

use Vendor\Chatbot\Models\InternalChatRoom;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasInternalChatRooms
{
    public function internalChatRooms(): BelongsToMany
    {
        return $this->belongsToMany(
            InternalChatRoom::class,
            'internal_chat_participants',
            'user_id',
            'room_id'
        )->withPivot('role', 'joined_at', 'muted_until', 'created_at', 'updated_at')
         ->withTimestamps();
    }
}