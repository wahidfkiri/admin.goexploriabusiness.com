<?php

namespace Vendor\Chatbot\Models\Traits;

trait HasChatSettings
{
    /**
     * Récupère une valeur de config chatbot stockée dans le cache ou la DB.
     */
    public function getChatSetting(string $key, mixed $default = null, string $group = 'chatbot'): mixed
    {
        // Les settings sont stockés dans la table cms_settings avec group='chatbot'
        // si le package CMS est présent, sinon dans un fichier de config.
        if (class_exists(\Vendor\Cms\Models\Setting::class)) {
            $setting = \Vendor\Cms\Models\Setting::where('etablissement_id', $this->id)
                ->where('group', $group)
                ->where('key', $key)
                ->first();

            return $setting ? $setting->value : $default;
        }

        return config("chatbot.{$key}", $default);
    }

    public function setChatSetting(string $key, mixed $value, string $group = 'chatbot'): void
    {
        if (class_exists(\Vendor\Cms\Models\Setting::class)) {
            \Vendor\Cms\Models\Setting::updateOrCreate(
                ['etablissement_id' => $this->id, 'group' => $group, 'key' => $key],
                ['value' => $value]
            );
        }
    }

    public function getChatWidgetConfig(): array
    {
        return [
            'primary_color'     => $this->getChatSetting('primary_color',     config('chatbot.widget.primary_color')),
            'welcome_message'   => $this->getChatSetting('welcome_message',   config('chatbot.widget.welcome_message')),
            'offline_message'   => $this->getChatSetting('offline_message',   config('chatbot.widget.offline_message')),
            'bot_enabled'       => $this->getChatSetting('bot_enabled',       config('chatbot.bot_enabled')),
            'show_pre_chat_form'=> $this->getChatSetting('show_pre_chat_form',config('chatbot.widget.show_pre_chat_form')),
            'show_rating'       => $this->getChatSetting('show_rating',       config('chatbot.widget.show_rating')),
        ];
    }
}