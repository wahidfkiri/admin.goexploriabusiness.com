<?php

namespace Vendor\Chatbot\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'body'    => 'required_without:file|nullable|string|max:2000',
            'type'    => 'sometimes|in:text,file,quick_reply',
            'room_id' => 'required|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'body.required_without' => 'Le message ne peut pas être vide.',
            'body.max'              => 'Le message ne peut pas dépasser 2000 caractères.',
        ];
    }
}