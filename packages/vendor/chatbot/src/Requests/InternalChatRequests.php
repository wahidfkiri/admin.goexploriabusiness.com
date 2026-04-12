<?php

namespace Vendor\Chatbot\Requests\InternalChat;

use Illuminate\Foundation\Http\FormRequest;

class StartDirectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
                // Ne peut pas démarrer une conversation avec soi-même
                function ($attr, $value, $fail) {
                    if ((int) $value === auth()->id()) {
                        $fail('Vous ne pouvez pas démarrer une conversation avec vous-même.');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Veuillez sélectionner un utilisateur.',
            'user_id.exists'   => 'Utilisateur introuvable.',
        ];
    }
}
