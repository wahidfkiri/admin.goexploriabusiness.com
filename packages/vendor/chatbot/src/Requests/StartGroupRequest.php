<?php

namespace Vendor\Chatbot\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StartGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name'       => 'required|string|max:80',
            'user_ids'   => 'required|array|min:1|max:49',
            'user_ids.*' => [
                'integer',
                'exists:users,id',
                function ($attr, $value, $fail) {
                    if ((int) $value === auth()->id()) {
                        $fail('Vous ne pouvez pas vous ajouter vous-même comme membre (vous êtes automatiquement ajouté en tant qu\'admin).');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'Le nom du groupe est obligatoire.',
            'name.max'         => 'Le nom du groupe ne peut pas dépasser 80 caractères.',
            'user_ids.required'=> 'Ajoutez au moins un membre.',
            'user_ids.min'     => 'Un groupe doit avoir au moins 1 autre membre.',
            'user_ids.max'     => 'Un groupe ne peut pas avoir plus de 50 membres.',
            'user_ids.*.exists'=> 'Un des utilisateurs sélectionnés est introuvable.',
        ];
    }
}


