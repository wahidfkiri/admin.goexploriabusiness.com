<?php

namespace Vendor\Cms\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
{
    // public function authorize(): bool
    // {
    //     return true;
    // }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|regex:/^[a-z0-9-]+$/',
            'content' => 'nullable|string',
            'meta' => 'nullable|array',
            'status' => 'required|in:draft,published,archived',
            'visibility' => 'required|in:public,private,password',
            'password' => 'nullable|string|required_if:visibility,password',
            'is_home' => 'nullable|boolean',
            'settings' => 'nullable|array',
            'published_at' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est requis',
            'status.required' => 'Le statut est requis',
            'visibility.required' => 'La visibilité est requise',
            'slug.regex' => 'Le slug ne peut contenir que des lettres minuscules, des chiffres et des tirets',
            'password.required_if' => 'Le mot de passe est requis pour la visibilité protégée',
        ];
    }
}