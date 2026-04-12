<?php

namespace Vendor\Chatbot\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class InternalChatRead extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'room_id',
        'user_id',
        'last_read_message_id',
        'read_at',
    ];

    protected $casts = [
        'read_at'              => 'datetime',
        'last_read_message_id' => 'integer',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(InternalChatRoom::class, 'room_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
