<?php

namespace Vendor\Chatbot\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class InternalChatFile extends Model
{
    protected $fillable = [
        'message_id',
        'room_id',
        'uploaded_by',
        'filename',
        'original_name',
        'path',
        'mime_type',
        'extension',
        'size',
        'width',
        'height',
        'is_image',
    ];

    protected $casts = [
        'is_image' => 'boolean',
        'size'     => 'integer',
        'width'    => 'integer',
        'height'   => 'integer',
    ];

    public function message(): BelongsTo
    {
        return $this->belongsTo(InternalChatMessage::class, 'message_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk(config('internal_chat.storage_disk', 'public'))->url($this->path);
    }

    public function getSizeFormattedAttribute(): string
    {
        $kb = $this->size / 1024;
        if ($kb < 1024) return round($kb, 1) . ' Ko';
        return round($kb / 1024, 1) . ' Mo';
    }

    public function toApiArray(): array
    {
        return [
            'id'            => $this->id,
            'url'           => $this->url,
            'original_name' => $this->original_name,
            'mime_type'     => $this->mime_type,
            'extension'     => $this->extension,
            'size'          => $this->size,
            'size_formatted'=> $this->size_formatted,
            'is_image'      => $this->is_image,
            'width'         => $this->width,
            'height'        => $this->height,
        ];
    }
}