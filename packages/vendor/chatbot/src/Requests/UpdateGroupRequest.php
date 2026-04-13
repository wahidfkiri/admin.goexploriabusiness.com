<?php

namespace Vendor\Chatbot\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name'     => 'sometimes|required|string|max:80',
            'user_ids' => 'sometimes|array|min:1|max:49',
            'user_ids.*' => 'integer|exists:users,id',
        ];
    }
}


