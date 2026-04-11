<?php

namespace Vendor\Chatbot\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAgentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status'    => 'required|in:online,away,offline',
            'max_rooms' => 'sometimes|integer|min:1|max:20',
        ];
    }
}