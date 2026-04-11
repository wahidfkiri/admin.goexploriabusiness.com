<?php

namespace Vendor\Chatbot\Models;

use Illuminate\Database\Eloquent\Model;

class ChatBotFlow extends Model
{
    protected $connection = 'chatbot';
    protected $table      = 'chat_bot_flows';

    protected $fillable = [
        'etablissement_id', 'name', 'trigger_keywords',
        'response_text', 'next_flow_id', 'is_active', 'order',
    ];

    protected $casts = [
        'trigger_keywords' => 'array',
        'is_active'        => 'boolean',
        'order'            => 'integer',
    ];

    public function nextFlow()
    {
        return $this->belongsTo(ChatBotFlow::class, 'next_flow_id');
    }

    public function scopeActive($query)  { return $query->where('is_active', true); }
    public function scopeOrdered($query) { return $query->orderBy('order'); }

    /**
     * Vérifie si le message visiteur déclenche ce flow.
     */
    public function matches(string $message): bool
    {
        $message = mb_strtolower(trim($message));

        foreach ($this->trigger_keywords as $keyword) {
            if (str_contains($message, mb_strtolower(trim($keyword)))) {
                return true;
            }
        }

        return false;
    }
}