<?php

namespace Vendor\Chatbot\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'q'       => 'required|string|min:2|max:100',
            'exclude' => 'sometimes|array',
            'exclude.*' => 'integer',
        ];
    }

    public function messages(): array
    {
        return [
            'q.min' => 'La recherche doit contenir au moins 2 caractères.',
        ];
    }
}