<?php

namespace Vendor\Chatbot\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BotFlowRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'             => 'required|string|max:100',
            'trigger_keywords' => 'required|array|min:1',
            'trigger_keywords.*' => 'required|string|max:50',
            'response_text'    => 'required|string|max:2000',
            'next_flow_id'     => 'nullable|integer|exists:chat_bot_flows,id',
            'order'            => 'nullable|integer|min:0',
            'is_active'        => 'nullable|boolean',
        ];
    }
}